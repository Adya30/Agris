<?php

namespace Database\Seeders;

use App\Models\KategoriProduk;
use App\Models\Produk;
use Illuminate\Database\Seeder;

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
