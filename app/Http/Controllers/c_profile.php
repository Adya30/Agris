<?php

namespace App\Http\Controllers;

use App\Models\Desa;
use App\Models\Kecamatan;
use App\Models\Kabupaten;
use App\Models\Provinsi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;

class c_profile extends Controller
{
    protected $baseUrl = 'https://www.emsifa.com/api-wilayah-indonesia/api';

    public function show()
    {
        $user = Auth::user();
        $view = $user->isAdmin ? 'admin.profile' : 'agen.profile';
        return view($view, compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'username'     => 'required|string|min:4|max:30|unique:users,username,' . $user->id,
            'email'        => 'required|email|unique:users,email,' . $user->id,
            'desaId'       => 'required',
            'detailAlamat' => 'required|string',
            'fotoProfil'   => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'current_password' => 'required_with:password',
            'password'     => 'nullable|min:8',
        ], [
            'username.required' => 'Username wajib diisi.',
            'username.min' => 'Username minimal 4 karakter.',
            'username.unique' => 'Username sudah digunakan.',
            'email.unique'    => 'Email sudah digunakan.',
            'email.required'  => 'Email wajib diisi.',
            'password.min'    => 'Password baru minimal 8 karakter.',
            'current_password.required_with' => 'Konfirmasi password wajib diisi.',
            'fotoProfil.image' => 'File harus berupa gambar.',
            'fotoProfil.mimes' => 'Format gambar harus jpeg, png, atau jpg.',
            'fotoProfil.max' => 'Ukuran gambar maksimal 2MB.'
        ]);

        if ($request->filled('password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return redirect()->back()->withErrors(['current_password' => 'Password lama salah.']);
            }
            $user->password = Hash::make($request->password);
        }

        if ($request->hasFile('fotoProfil')) {
            $file = $request->file('fotoProfil');

            $imageData = base64_encode(file_get_contents($file->getRealPath()));
            $mimeType = $file->getClientMimeType();

            $user->fotoProfil = 'data:' . $mimeType . ';base64,' . $imageData;
        }

        if ($request->filled('desaId') && $request->desaId != $user->desaId) {
            $this->syncWilayah($request->desaId);
            $user->desaId = $request->desaId;
        }

        $user->email = $request->email;
        $user->detailAlamat = $request->detailAlamat;

        if (!$user->isAdmin) {
            $user->namaLengkap = $request->namaLengkap;
            $user->noTelp = $request->noTelp;
            $user->jenisKelamin = $request->jenisKelamin;
            $user->tanggalLahir = $request->tanggalLahir;
        }

        $user->save();

        return redirect()->back()->with('success', 'Profil berhasil diperbarui langsung ke database!');
    }

    private function syncWilayah($desaId)
    {
        try {
            $resDesa = Http::get("{$this->baseUrl}/village/{$desaId}.json")->json();
            if (!$resDesa) return;

            $kecId = $resDesa['district_id'];
            $resKec = Http::get("{$this->baseUrl}/district/{$kecId}.json")->json();

            $kabId = $resKec['regency_id'];
            $resKab = Http::get("{$this->baseUrl}/regency/{$kabId}.json")->json();

            $provId = $resKab['province_id'];
            $resProv = Http::get("{$this->baseUrl}/province/{$provId}.json")->json();

            Provinsi::firstOrCreate(['id' => $provId], ['namaProvinsi' => $resProv['name']]);
            Kabupaten::firstOrCreate(['id' => $kabId], ['provinsiId' => $provId, 'namaKabupaten' => $resKab['name']]);
            Kecamatan::firstOrCreate(['id' => $kecId], ['kabupatenId' => $kabId, 'namaKecamatan' => $resKec['name']]);
            Desa::firstOrCreate(['id' => $desaId], ['kecamatanId' => $kecId, 'namaDesa' => $resDesa['name']]);

        } catch (\Exception $e) {
            Log::error("Sync Wilayah Gagal: " . $e->getMessage());
        }
    }
}
