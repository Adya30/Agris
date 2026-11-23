<?php

namespace Database\Seeders;

use App\Models\KategoriProduk;
use App\Models\Produk;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class ProdukSeeder extends Seeder
{
    public function run(): void
    {
        $existingStorageImages = [];
        $storageDir = storage_path('app/public/produk');
        if (File::isDirectory($storageDir)) {
            $files = File::files($storageDir);
            foreach ($files as $file) {
                $existingStorageImages[] = 'produk/'.$file->getFilename();
            }
        }

        $pickImage = function (string $preferredImage, int $index) use ($existingStorageImages) {
            if (File::exists(storage_path('app/public/'.$preferredImage))) {
                return $preferredImage;
            }
            if (! empty($existingStorageImages)) {
                return $existingStorageImages[$index % count($existingStorageImages)];
            }

            return $preferredImage;
        };

        $products = [
            [
                'namaProduk' => 'Beras Pandan Wangi Super Premium (5 Kg)',
                'jenisKategori' => 'padi',
                'mutu' => 'pandan wangi A',
                'karung' => 5.00,
                'stok' => 150,
                'harga' => 82000.00,
                'deskripsi' => 'Beras Pandan Wangi murni dengan aroma alami harum khas pandan. Butiran beras utuh, bersih, dan pulen saat dimasak. Diproses tanpa pemutih, pewangi sintetis, atau pengawet kimia.',
                'preferredFoto' => 'produk/xrm97uLdQUCqBWPH8o3a8ZO1yv7oYjh0YYUMgNel.png',
            ],
            [
                'namaProduk' => 'Beras Pandan Wangi Super Premium (10 Kg)',
                'jenisKategori' => 'padi',
                'mutu' => 'pandan wangi A',
                'karung' => 10.00,
                'stok' => 120,
                'harga' => 160000.00,
                'deskripsi' => 'Kemasan 10 kg beras Pandan Wangi kualitas nomor satu. Sangat diminati rumah makan, restoran, dan keluarga yang mengutamakan cita rasa nasi pulen beraroma wangi istimewa.',
                'preferredFoto' => 'produk/hdqsO8C2V4WgnNr9gVN2zkrUjtfAyKSgSdI226ox.png',
            ],
            [
                'namaProduk' => 'Beras Pandan Wangi Super Premium (25 Kg)',
                'jenisKategori' => 'padi',
                'mutu' => 'pandan wangi A',
                'karung' => 25.00,
                'stok' => 85,
                'harga' => 395000.00,
                'deskripsi' => 'Beras Pandan Wangi mutu A dalam kemasan karung 25 kg. Diproduksi langsung dari sentra pertanian mitra Agris dengan penggilingan berteknologi modern.',
                'preferredFoto' => 'produk/5jxOqccBvuCEMs7qObtkCodvxikKtrbmCbpGaXxe.png',
            ],
            [
                'namaProduk' => 'Beras Rojolele Delanggu (5 Kg)',
                'jenisKategori' => 'padi',
                'mutu' => 'rojolele A',
                'karung' => 5.00,
                'stok' => 180,
                'harga' => 78000.00,
                'deskripsi' => 'Beras Rojolele legendaris dengan tekstur pulen gurih dan warna putih susu alami. Pilihan favorit hidangan tumpeng, hajatan, maupun konsumsi sehari-hari.',
                'preferredFoto' => 'produk/8Lb3hbgCTqPRS2zNxD7h2FqZ8I3MlT3jwJBoprPT.png',
            ],
            [
                'namaProduk' => 'Beras Rojolele Delanggu (10 Kg)',
                'jenisKategori' => 'padi',
                'mutu' => 'rojolele A',
                'karung' => 10.00,
                'stok' => 110,
                'harga' => 152000.00,
                'deskripsi' => 'Beras Rojolele kemasan 10 kg siap kirim. Ciri khas tekstur nasi lembut, sedikit lengket dan pulen, sangat lezat dinikmati dengan berbagai lauk tradisional nusantara.',
                'preferredFoto' => 'produk/9rnkXrnFq5kFjnXFy6944LTadM5aLPDe2f6d4O4X.png',
            ],
            [
                'namaProduk' => 'Beras Rojolele Delanggu (50 Kg)',
                'jenisKategori' => 'padi',
                'mutu' => 'rojolele A',
                'karung' => 50.00,
                'stok' => 50,
                'harga' => 740000.00,
                'deskripsi' => 'Pasokan karung besar 50 kg untuk toko kelontong, agen beras, dan katering. Kadar air terukur sesuai standar SNI untuk daya simpan lebih tahan lama.',
                'preferredFoto' => 'produk/Dsm4ijHwXoSpVuV8ZPXHmvTa9QloGhDpfZMaki1u.png',
            ],
            [
                'namaProduk' => 'Beras Ciherang Kristal (20 Kg)',
                'jenisKategori' => 'padi',
                'mutu' => 'ciherang B',
                'karung' => 20.00,
                'stok' => 95,
                'harga' => 285000.00,
                'deskripsi' => 'Beras Ciherang berpenampilan bening menyerupai kristal. Tekstur nasi pulen sedang, tidak mudah lembek, dan tahan basi hingga seharian penuh.',
                'preferredFoto' => 'produk/eRXcPpXWuZDF5y14Pb8u4GE4cwE5JbXZ7vqNUnP4.png',
            ],
            [
                'namaProduk' => 'Beras Ciherang Kristal (25 Kg)',
                'jenisKategori' => 'padi',
                'mutu' => 'ciherang B',
                'karung' => 25.00,
                'stok' => 130,
                'harga' => 350000.00,
                'deskripsi' => 'Kemasan 25 kg beras Ciherang mutu B. Cocok untuk warung makan, kantin, dan konsumsi harian keluarga dengan harga yang ramah di kantong.',
                'preferredFoto' => 'produk/fpuFLFD4iEspuEfe6cdrvagsVGq5eK7ECS6NcPO4.png',
            ],
            [
                'namaProduk' => 'Beras IR64 Sawah Subur (25 Kg)',
                'jenisKategori' => 'padi',
                'mutu' => 'IR64 B',
                'karung' => 25.00,
                'stok' => 140,
                'harga' => 330000.00,
                'deskripsi' => 'Beras varietas IR64 berbutir panjang (long grain) yang sangat populer di Indonesia. Menghasilkan nasi yang mekar sempurna dan pulen pas.',
                'preferredFoto' => 'produk/Frtt6fPbEfxB43waAAJoH1nPkltLSeAHZU4GY0Kx.png',
            ],
            [
                'namaProduk' => 'Beras IR64 Sawah Subur (50 Kg)',
                'jenisKategori' => 'padi',
                'mutu' => 'IR64 B',
                'karung' => 50.00,
                'stok' => 60,
                'harga' => 645000.00,
                'deskripsi' => 'Karung grosir 50 kg beras IR64. Kualitas terjamin dengan butir patah rendah, bebas kutu, dan dikemas rapat dalam karung berlaminasi kuat.',
                'preferredFoto' => 'produk/HQL1wIi5iIqLtxxcdTeQKTpyKmr5WHWMLm8SMeiX.png',
            ],
            [
                'namaProduk' => 'Benih Jagung Hibrida Bonanza F1 (1 Kg)',
                'jenisKategori' => 'jagung',
                'mutu' => 'bonanza F1',
                'karung' => 1.00,
                'stok' => 200,
                'harga' => 125000.00,
                'deskripsi' => 'Benih jagung manis hibrida unggul varietas Bonanza F1. Menghasilkan tongkol seragam, rasa sangat manis (13-15 Brix), dan tahan terhadap penyakit bulai.',
                'preferredFoto' => 'produk/hi3oUvVlEgY47HnT3bnBXqZ60s64rGjECYER71Aq.png',
            ],
            [
                'namaProduk' => 'Benih Jagung Hibrida Bonanza F1 (5 Kg)',
                'jenisKategori' => 'jagung',
                'mutu' => 'bonanza F1',
                'karung' => 5.00,
                'stok' => 70,
                'harga' => 590000.00,
                'deskripsi' => 'Kemasan 5 kg benih jagung manis bersertifikat resmi. Daya tumbuh di atas 85% dengan potensi hasil panen melimpah hingga 15-18 ton per hektar.',
                'preferredFoto' => 'produk/AkL1PztxRvb22WkEq0bcqAYK7IiUARFR1NHESTHz.jpg',
            ],
            [
                'namaProduk' => 'Jagung Pipil Kering Pakan Ternak (10 Kg)',
                'jenisKategori' => 'jagung',
                'mutu' => 'bonanza F1',
                'karung' => 10.00,
                'stok' => 160,
                'harga' => 95000.00,
                'deskripsi' => 'Jagung pipil kering bersih kadar air rendah di bawah 14%. Sumber nutrisi dan energi terbaik untuk pakan ternak unggas, ayam petelur, dan burung.',
                'preferredFoto' => 'produk/J343Gz5FmAChiOsMH8uY0qabymGny3gEodVGInoC.png',
            ],
        ];

        Produk::withoutEvents(function () use ($products, $pickImage) {
            foreach ($products as $index => $item) {
                $kategori = KategoriProduk::where('jenisKategori', $item['jenisKategori'])
                    ->where('mutu', $item['mutu'])
                    ->where('karung', $item['karung'])
                    ->first();

                if (! $kategori) {
                    $kategori = KategoriProduk::firstOrCreate(
                        [
                            'jenisKategori' => $item['jenisKategori'],
                            'mutu' => $item['mutu'],
                            'karung' => $item['karung'],
                        ]
                    );
                }

                $foto = $pickImage($item['preferredFoto'], $index);

                Produk::updateOrCreate(
                    ['namaProduk' => $item['namaProduk']],
                    [
                        'kategoriId' => $kategori->id,
                        'fotoProduk' => $foto,
                        'stok' => $item['stok'],
                        'harga' => $item['harga'],
                        'deskripsi' => $item['deskripsi'],
                    ]
                );
            }
        });
    }
}
