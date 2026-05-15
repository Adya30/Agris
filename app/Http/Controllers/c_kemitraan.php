<?php

namespace App\Http\Controllers;

use App\Models\Kemitraan;
use App\Models\User;
use App\Events\KemitraanUpdated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class c_kemitraan extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if ($user->isAdmin) {
            $kemitraans = Kemitraan::with(['user.desa.kecamatan.kabupaten.provinsi'])->latest()->get();
            return view('admin.kemitraan.index', compact('kemitraans'));
        }
        $kemitraan = Kemitraan::where('userId', $user->id)->first();
        return view('agen.kemitraan.index', compact('kemitraan'));
    }

    public function create()
    {
        $user = Auth::user();
        if (empty($user->namaLengkap) || empty($user->noTelp) || empty($user->desaId) || empty($user->detailAlamat)) {
            return redirect()->route('admin.profile')->with('modalIncomplete', true);
        }
        $kemitraan = Kemitraan::where('userId', $user->id)->first();
        if ($kemitraan && !in_array($kemitraan->statusPengajuan, ['Ditolak', 'Menunggu Upload MOU'])) {
            return redirect()->route('kemitraan.index');
        }
        return view('agen.kemitraan.create', compact('user', 'kemitraan'));
    }

    public function store(Request $request)
    {
        $request->validate(['fileKemitraan' => 'required|mimes:pdf|max:10240']);
        $user = Auth::user();
        $file = $request->file('fileKemitraan');
        $base64 = base64_encode(file_get_contents($file->getRealPath()));

        $kemitraan = Kemitraan::updateOrCreate(
            ['userId' => $user->id],
            ['tanggalPengajuan' => now(), 'statusPengajuan' => 'Menunggu Verifikasi MOU', 'fileKemitraan' => $base64]
        );

        broadcast(new KemitraanUpdated($kemitraan->id, 'Menunggu Verifikasi MOU'));

        return redirect()->route('kemitraan.index')->with('success', 'File MOU berhasil diunggah.');
    }

    public function adminAction(Request $request, string $id)
    {
        $request->validate(['action' => 'required|in:setujui,tolak,hentikan']);
        $kemitraan = Kemitraan::findOrFail($id);
        $status = '';

        if ($request->action === 'setujui') {
            $status = 'Menunggu Upload MOU';
        } elseif ($request->action === 'tolak' || $request->action === 'hentikan') {
            $status = 'Ditolak';
            if ($request->action === 'hentikan') User::where('id', $kemitraan->userId)->update(['isActive' => 0]);
        }

        $kemitraan->update(['statusPengajuan' => $status]);
        broadcast(new KemitraanUpdated($id, $status));

        return redirect()->route('admin.kemitraan.show', $id)->with('success', 'Perubahan berhasil disimpan');
    }

    public function uploadMou(Request $request, string $id)
    {
        $request->validate(['fileKemitraan' => 'required|mimes:pdf|max:10240']);
        $kemitraan = Kemitraan::where('id', $id)->where('userId', Auth::id())->firstOrFail();
        $file = $request->file('fileKemitraan');
        $base64 = base64_encode(file_get_contents($file->getRealPath()));

        $kemitraan->update(['fileKemitraan' => $base64, 'statusPengajuan' => 'Menunggu Verifikasi MOU']);

        broadcast(new KemitraanUpdated($id, 'Menunggu Verifikasi MOU'));

        return redirect()->back()->with('success', 'File MOU berhasil di upload.');
    }

    public function verifyMou(Request $request, string $id)
    {
        $request->validate(['status' => 'required|in:Aktif,Ditolak']);
        $kemitraan = Kemitraan::findOrFail($id);

        if ($request->status === 'Ditolak') {
            $kemitraan->update(['statusPengajuan' => 'Ditolak', 'fileKemitraan' => null]);
        } else {
            $kemitraan->update(['statusPengajuan' => 'Aktif']);
            User::where('id', $kemitraan->userId)->update(['isActive' => 1]);
        }

        broadcast(new KemitraanUpdated($id, $request->status));

        return redirect()->route('admin.kemitraan.show', $id);
    }

    public function show(string $id)
    {
        $kemitraan = Kemitraan::with(['user.desa.kecamatan.kabupaten.provinsi'])->findOrFail($id);
        return view('admin.kemitraan.show', compact('kemitraan'));
    }
}
