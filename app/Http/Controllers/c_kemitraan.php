<?php

namespace App\Http\Controllers;

use App\Events\KemitraanUpdated;
use App\Models\Kemitraan;
use App\Models\User;
use App\Rules\SecurePdfFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class c_kemitraan extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if ($user->isAdmin) {
            $kemitraans = Kemitraan::with(['user.desa.kecamatan.kabupaten.provinsi'])->latest()->get();

            return view('admin.kemitraan.index', compact('kemitraans'));
        }
        $kemitraan = Kemitraan::userId($user->id)->first();

        return view('agen.kemitraan.index', compact('kemitraan'));
    }

    public function create()
    {
        $user = Auth::user();
        $kemitraan = Kemitraan::userId($user->id)->first();
        if ($kemitraan && ! in_array($kemitraan->statusPengajuan, ['Ditolak', 'Menunggu Upload MOU'])) {
            return redirect()->route('kemitraan.index');
        }

        return view('agen.kemitraan.create', compact('user', 'kemitraan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'fileKemitraan' => ['required', new SecurePdfFile(1024)],
        ], [
            'fileKemitraan.required' => 'Dokumen MOU wajib diunggah.',
        ]);
        $user = Auth::user();
        $file = $request->file('fileKemitraan');
        $disk = config('filesystems.default', 'public');
        $path = $file->store('mou', $disk);

        $kemitraan = Kemitraan::updateOrCreate(
            ['userId' => $user->id],
            ['tanggalPengajuan' => now(), 'statusPengajuan' => 'Menunggu Verifikasi MOU', 'fileKemitraan' => $path]
        );

        try {
            broadcast(new KemitraanUpdated($kemitraan->id, 'Menunggu Verifikasi MOU', $user->id));
        } catch (\Throwable $e) {
            Log::info('Broadcast KemitraanUpdated skipped: '.$e->getMessage());
        }

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
            if ($request->action === 'hentikan') {
                User::whereId($kemitraan->userId)->update(['isActive' => 0]);
            }
        }

        $kemitraan->update(['statusPengajuan' => $status]);
        try {
            broadcast(new KemitraanUpdated($id, $status, $kemitraan->userId));
        } catch (\Throwable $e) {
            Log::info('Broadcast KemitraanUpdated skipped: '.$e->getMessage());
        }

        return redirect()->route('admin.kemitraan.show', $id)->with('success', 'Perubahan berhasil disimpan');
    }

    public function uploadMou(Request $request, string $id)
    {
        $request->validate([
            'fileKemitraan' => ['required', new SecurePdfFile(1024)],
        ], [
            'fileKemitraan.required' => 'Dokumen MOU wajib diunggah.',
        ]);
        $kemitraan = Kemitraan::whereId($id)->userId(Auth::id())->firstOrFail();
        $disk = config('filesystems.default', 'public');
        if ($kemitraan->fileKemitraan && Storage::disk($disk)->exists($kemitraan->fileKemitraan)) {
            Storage::disk($disk)->delete($kemitraan->fileKemitraan);
        }
        $file = $request->file('fileKemitraan');
        $path = $file->store('mou', $disk);

        $kemitraan->fill(['fileKemitraan' => $path, 'statusPengajuan' => 'Menunggu Verifikasi MOU']);
        $kemitraan->save();

        try {
            broadcast(new KemitraanUpdated($id, 'Menunggu Verifikasi MOU', Auth::id()));
        } catch (\Throwable $e) {
            Log::info('Broadcast KemitraanUpdated skipped: '.$e->getMessage());
        }

        return redirect()->back()->with('success', 'File MOU berhasil di upload.');
    }

    public function verifyMou(Request $request, string $id)
    {
        $request->validate(['status' => 'required|in:Aktif,Ditolak']);
        $kemitraan = Kemitraan::findOrFail($id);

        if ($request->status === 'Ditolak') {
            $disk = config('filesystems.default', 'public');
            if ($kemitraan->fileKemitraan && Storage::disk($disk)->exists($kemitraan->fileKemitraan)) {
                Storage::disk($disk)->delete($kemitraan->fileKemitraan);
            }
            $kemitraan->update(['statusPengajuan' => 'Ditolak', 'fileKemitraan' => null]);
        } else {
            $kemitraan->update(['statusPengajuan' => 'Aktif']);
            User::whereId($kemitraan->userId)->update(['isActive' => 1]);
        }

        try {
            broadcast(new KemitraanUpdated($id, $request->status, $kemitraan->userId));
        } catch (\Throwable $e) {
            Log::info('Broadcast KemitraanUpdated skipped: '.$e->getMessage());
        }

        return redirect()->route('admin.kemitraan.show', $id);
    }

    public function show(string $id)
    {
        $kemitraan = Kemitraan::with(['user.desa.kecamatan.kabupaten.provinsi'])->findOrFail($id);

        return view('admin.kemitraan.show', compact('kemitraan'));
    }

    public function previewMou(string $id)
    {
        $kemitraan = Kemitraan::findOrFail($id);
        if (! $kemitraan->fileKemitraan) {
            abort(404, 'File MOU tidak ditemukan.');
        }

        $disk = config('filesystems.default', 'public');
        if (Storage::disk($disk)->exists($kemitraan->fileKemitraan)) {
            return Storage::disk($disk)->response($kemitraan->fileKemitraan, 'MOU-Kemitraan-'.$kemitraan->id.'.pdf', [
                'Content-Type' => 'application/pdf',
                'Cache-Control' => 'private, no-cache, no-store, must-revalidate',
            ]);
        }

        $pdfBinary = base64_decode($kemitraan->fileKemitraan);

        return response($pdfBinary, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="MOU-Kemitraan-'.$kemitraan->id.'.pdf"',
            'Cache-Control' => 'private, no-cache, no-store, must-revalidate',
        ]);
    }
}
