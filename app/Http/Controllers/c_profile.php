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
            'noTelp'       => 'required|numeric|digits_between:4,15|unique:users,noTelp,' . $user->id,
            'namaLengkap'  => 'nullable|string|max:255',
            'email'        => 'required|email|unique:users,email,' . $user->id,
            'detailAlamat' => 'nullable|string',
            'fotoProfil'   => 'nullable|image|mimes:jpeg,png,jpg|max:10048',
            'current_password' => 'required_with:password',
            'password'     => 'nullable|min:8',
            'desaId'       => $user->desaId ? 'nullable' : 'required',
        ], [
            'required'              => 'Data wajib diisi!',
            'noTelp.numeric'        => 'Nomor telepon harus berupa angka.',
            'noTelp.unique'         => 'Nomor Telpon sudah digunakan.',
            'noTelp.digits_between' => 'Nomor telepon harus antara 4 sampai 15 digit.',
            'password.min'          => 'Password baru minimal 8 karakter.',
            'current_password.required_with' => 'Konfirmasi password lama wajib diisi.',
            'fotoProfil.image'      => 'File harus berupa gambar.',
            'fotoProfil.mimes'      => 'Format gambar harus jpeg, png, atau jpg.',
            'fotoProfil.max'        => 'Ukuran gambar maksimal 10MB.'
        ]);

        if ($request->filled('password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return redirect()->back()->withErrors(['current_password' => 'Password lama salah.'])->withInput();
            }
            $user->password = Hash::make($request->password);
        }

        if ($request->hasFile('fotoProfil')) {
            $file = $request->file('fotoProfil');
            $imageData = base64_encode(file_get_contents($file->getRealPath()));
            $mimeType = $file->getClientMimeType();
            $user->fotoProfil = 'data:' . $mimeType . ';base64,' . $imageData;
        }

        if ($request->filled('desaId')) {
            $this->syncWilayah($request->desaId);
            $user->desaId = $request->desaId;
        }

        $user->email = $request->email;
        $user->detailAlamat = $request->detailAlamat;
        $user->noTelp = $request->noTelp;

        if (!$user->isAdmin) {
            $user->namaLengkap = $request->namaLengkap;
        }

        $user->save();

        return redirect()->back()->with('success', 'Profil berhasil diperbarui!');
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
