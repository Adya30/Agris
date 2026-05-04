<?php

namespace App\Http\Controllers;

use App\Models\Kemitraan;
use App\Models\User;
use App\Models\Desa;
use App\Models\Kecamatan;
use App\Models\Kabupaten;
use App\Models\Provinsi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class c_kemitraan extends Controller
{
    protected $baseUrl = 'https://www.emsifa.com/api-wilayah-indonesia/api';

    public function index()
    {
        $user = Auth::user();

        if ($user->isAdmin) {
            $kemitraans = Kemitraan::with('user')->latest()->get();
            return view('admin.kemitraan.index', compact('kemitraans'));
        }

        $kemitraan = Kemitraan::where('userId', $user->id)->first();
        return view('agen.kemitraan.index', compact('kemitraan'));
    }

    public function create()
    {
        $user = Auth::user();
        $kemitraan = Kemitraan::where('userId', $user->id)->first();

        if ($kemitraan && $kemitraan->statusPengajuan !== 'Ditolak') {
            return redirect()->route('kemitraan.index');
        }

        return view('agen.kemitraan.create', compact('user', 'kemitraan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'namaLengkap' => 'required|string|max:255',
            'noTelp' => 'required|string|max:15',
            'detailAlamat' => 'required|string',
            'desaId' => 'required'
        ]);

        $user = Auth::user();

        $this->syncWilayah($request->desaId);

        $user->update([
            'namaLengkap' => $request->namaLengkap,
            'noTelp' => $request->noTelp,
            'detailAlamat' => $request->detailAlamat,
            'desaId' => $request->desaId
        ]);

        Kemitraan::updateOrCreate(
            ['userId' => $user->id],
            [
                'tanggalPengajuan' => now(),
                'statusPengajuan' => 'diproses',
                'fileKemitraan' => null
            ]
        );

        return redirect()->route('kemitraan.index')->with('success', 'Pengajuan berhasil dikirim.');
    }

    public function adminAction(Request $request, string $id)
    {
        $request->validate([
            'action' => 'required|in:setujui,tolak,hentikan',
        ]);

        $kemitraan = Kemitraan::findOrFail($id);

        if ($request->action === 'setujui') {
            $kemitraan->update(['statusPengajuan' => 'Menunggu Upload MOU']);
        } elseif ($request->action === 'tolak') {
            $kemitraan->update(['statusPengajuan' => 'Ditolak']);
        } elseif ($request->action === 'hentikan') {
            $kemitraan->update(['statusPengajuan' => 'Ditolak']);
            User::where('id', $kemitraan->userId)->update(['isActive' => 0]);
        }

        return redirect()->route('admin.kemitraan.show', $id)->with('success', 'Aksi berhasil dilakukan.');
    }

    public function uploadMou(Request $request, string $id)
    {
        $request->validate([
            'fileKemitraan' => 'required|mimes:pdf|max:10240',
        ]);

        $kemitraan = Kemitraan::where('id', $id)->where('userId', Auth::id())->firstOrFail();

        if ($request->hasFile('fileKemitraan')) {
            $file = $request->file('fileKemitraan');
            $fileContents = file_get_contents($file->getRealPath());
            $base64 = base64_encode($fileContents);

            $kemitraan->update([
                'fileKemitraan' => $base64,
                'statusPengajuan' => 'Menunggu Verifikasi MOU'
            ]);
        }

        return redirect()->back()->with('success', 'File MOU berhasil diunggah.');
    }

    public function verifyMou(Request $request, string $id)
    {
        $request->validate([
            'status' => 'required|in:Aktif,Menunggu Upload MOU',
        ]);

        $kemitraan = Kemitraan::findOrFail($id);

        $updateData = ['statusPengajuan' => $request->status];

        if ($request->status === 'Menunggu Upload MOU') {
            $updateData['fileKemitraan'] = null;
        }

        $kemitraan->update($updateData);

        if ($request->status === 'Aktif') {
            User::where('id', $kemitraan->userId)->update(['isActive' => 1]);
        }

        return redirect()->route('admin.kemitraan.index')->with('success', 'Verifikasi dokumen selesai.');
    }

    public function show(string $id)
    {
        $kemitraan = Kemitraan::with(['user.desa.kecamatan.kabupaten.provinsi'])->findOrFail($id);
        return view('admin.kemitraan.show', compact('kemitraan'));
    }

    private function syncWilayah(string $desaId)
    {
        try {
            $resDesa = Http::get("{$this->baseUrl}/village/{$desaId}.json")->json();
            if (!$resDesa) return;

            $kecId = substr($desaId, 0, 7);
            $kabId = substr($desaId, 0, 4);
            $provId = substr($desaId, 0, 2);

            $resProv = Http::get("{$this->baseUrl}/province/{$provId}.json")->json();
            $resKab = Http::get("{$this->baseUrl}/regency/{$kabId}.json")->json();
            $resKec = Http::get("{$this->baseUrl}/district/{$kecId}.json")->json();

            Provinsi::firstOrCreate(['id' => $provId], ['namaProvinsi' => $resProv['name']]);
            Kabupaten::firstOrCreate(['id' => $kabId], ['provinsiId' => $provId, 'namaKabupaten' => $resKab['name']]);
            Kecamatan::firstOrCreate(['id' => $kecId], ['kabupatenId' => $kabId, 'namaKecamatan' => $resKec['name']]);
            Desa::firstOrCreate(['id' => $desaId], ['kecamatanId' => $kecId, 'namaDesa' => $resDesa['name']]);

        } catch (\Exception $e) {
            Log::error("Sync Wilayah Gagal: " . $e->getMessage());
        }
    }
}
