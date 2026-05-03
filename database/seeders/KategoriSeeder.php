<?php

namespace Database\Seeders;

use App\Models\KategoriProduk;
use Illuminate\Database\Seeder;

class KategoriSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['jenisKategori' => 'padi', 'mutu' => 'pandan wangi A', 'karung' => 5.00],
            ['jenisKategori' => 'padi', 'mutu' => 'pandan wangi A', 'karung' => 10.00],
            ['jenisKategori' => 'padi', 'mutu' => 'pandan wangi A', 'karung' => 25.00],

            ['jenisKategori' => 'padi', 'mutu' => 'rojolele A', 'karung' => 5.00],
            ['jenisKategori' => 'padi', 'mutu' => 'rojolele A', 'karung' => 10.00],
            ['jenisKategori' => 'padi', 'mutu' => 'rojolele A', 'karung' => 50.00],

            ['jenisKategori' => 'padi', 'mutu' => 'ciherang B', 'karung' => 20.00],
            ['jenisKategori' => 'padi', 'mutu' => 'ciherang B', 'karung' => 25.00],
            ['jenisKategori' => 'padi', 'mutu' => 'ciherang B', 'karung' => 50.00],

            ['jenisKategori' => 'jagung', 'mutu' => 'bonanza F1', 'karung' => 1.00],
            ['jenisKategori' => 'jagung', 'mutu' => 'bonanza F1', 'karung' => 5.00],
            ['jenisKategori' => 'jagung', 'mutu' => 'bonanza F1', 'karung' => 10.00],

            ['jenisKategori' => 'padi', 'mutu' => 'IR64 B', 'karung' => 25.00],
            ['jenisKategori' => 'padi', 'mutu' => 'IR64 B', 'karung' => 50.00],
        ];

        foreach ($data as $item) {
            KategoriProduk::updateOrCreate(
                [
                    'jenisKategori' => $item['jenisKategori'],
                    'mutu' => $item['mutu'],
                    'karung' => $item['karung']
                ],
                $item
            );
        }
    }
}
