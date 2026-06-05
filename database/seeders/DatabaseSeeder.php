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
        $this->call([
            AdminSeeder::class,
            KategoriSeeder::class,
        ]);

        $kategori = KategoriProduk::first();
        if ($kategori) {
            Produk::updateOrCreate(
                ['namaProduk' => 'Beras Pandan Wangi Premium'],
                [
                    'kategoriId' => $kategori->id,
                    'stok' => 1000,
                    'harga' => 15000.00,
                    'deskripsi' => 'Beras Pandan Wangi kualitas terbaik langsung dari petani.',
                ]
            );
        }
    }
}
