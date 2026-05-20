<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Desa;
use App\Models\Kecamatan;
use App\Models\Kabupaten;
use App\Models\Provinsi;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $provinsiId = '35';
        $kabupatenId = '3509';
        $kecamatanId = '3509200';
        $desaId = '3509200004';

        $provinsi = Provinsi::find($provinsiId);
        if (!$provinsi) {
            $provinsi = Provinsi::create([
                'id' => $provinsiId,
                'namaProvinsi' => 'JAWA TIMUR',
            ]);
        }

        $kabupaten = Kabupaten::find($kabupatenId);
        if (!$kabupaten) {
            $kabupaten = Kabupaten::create([
                'id' => $kabupatenId,
                'provinsiId' => $provinsi->id,
                'namaKabupaten' => 'JEMBER',
            ]);
        }

        $kecamatan = Kecamatan::find($kecamatanId);
        if (!$kecamatan) {
            $kecamatan = Kecamatan::create([
                'id' => $kecamatanId,
                'kabupatenId' => $kabupaten->id,
                'namaKecamatan' => 'PATRANG',
            ]);
        }

        $desa = Desa::find($desaId);
        if (!$desa) {
            $desa = Desa::create([
                'id' => $desaId,
                'kecamatanId' => $kecamatan->id,
                'namaDesa' => 'SLAWU',
            ]);
        }

        User::create([
            'namaLengkap' => 'Admin Utama',
            'email'       => 'agrisagroindustri@gmail.com',
            'password'    => Hash::make('admin123'),
            'noTelp'      => '08123456789',
            'isActive'    => true,
            'isAdmin'     => true,
            'detailAlamat'=> 'Jl. Manyar Gg. Kelapa, Puring',
            'desaId'      => $desa->id,
        ]);
    }
}
