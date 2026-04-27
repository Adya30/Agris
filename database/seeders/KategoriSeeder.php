<?php

namespace Database\Seeders;

use App\Models\KategoriProduk; // Pastikan nama Model sesuai
use Illuminate\Database\Seeder;

class KategoriSeeder extends Seeder
{
    public function run(): void
    {
        $jenisKategori = ['padi', 'jagung'];
        $daftarMutu = ['pandan wangi A', 'rojolele A', 'mentik wangi A', 'ciherang B', 'IR64 B', 'mekongga B', 'bonanza F1'];

        foreach ($jenisKategori as $jenis) {
            foreach ($daftarMutu as $mutu) {
                KategoriProduk::create([
                    'jenisKategori' => $jenis,
                    'mutu'          => $mutu,
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ]);
            }
        }
    }
}
