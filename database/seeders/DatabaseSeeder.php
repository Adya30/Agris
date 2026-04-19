<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Provinsi;
use App\Models\Kabupaten;
use App\Models\Kecamatan;
use App\Models\Desa;
use App\Models\User;
use App\Models\Produk;
use App\Models\KategoriProduk;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat Data Lokasi Berjenjang
        $provinsi = Provinsi::factory()->create(['namaProvinsi' => 'Jawa Barat']);
        $kabupaten = Kabupaten::factory()->create(['provinsiId' => $provinsi->id, 'namaKabupaten' => 'Bandung']);
        $kecamatan = Kecamatan::factory()->create(['kabupatenId' => $kabupaten->id, 'namaKecamatan' => 'Cicendo']);
        $desa = Desa::factory()->create(['kecamatanId' => $kecamatan->id, 'namaDesa' => 'Arjuna']);

        // 2. Buat User yang terhubung ke Desa tersebut
        User::factory()->count(5)->create(['desaId' => $desa->id]);

        // 3. Buat Kategori & Produk
        $kategori = KategoriProduk::factory()->create(['jenisKategori' => 'Makanan', 'mutu' => 'A']);
        Produk::factory()->count(10)->create(['kategoriId' => $kategori->id]);
    }
}