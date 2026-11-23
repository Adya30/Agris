<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class BlogSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('isAdmin', true)->first() ?? User::first();
        if (! $admin) {
            return;
        }

        $riceImages = [
            'blog/boxWIP1KQTx1ADZgHBAJ39EMS6UplMOwt4JOai53.png',
            'blog/4PgMbaPRi6WbYHViIIizaWyQM69t7aJ4V0uogbXR.png',
            'blog/l0EKs5gmxnh7vy4phNdxlXcGXbzBD1ZWdG6cINW8.png',
            'blog/ScsVbny41dRuk6IVRArGb5RqPvr2KeLVglFaIsrO.png',
            'blog/Y1bljyf8I3zlhHAbvxMzRpSS3xR1enpkUJGc5vae.png',
        ];

        $validRiceImages = array_values(array_filter($riceImages, function ($path) {
            return File::exists(storage_path('app/public/'.$path));
        }));

        $getRiceImage = function (string $preferred, int $index) use ($validRiceImages) {
            if (File::exists(storage_path('app/public/'.$preferred))) {
                return $preferred;
            }
            if (! empty($validRiceImages)) {
                return $validRiceImages[$index % count($validRiceImages)];
            }

            return 'blog/boxWIP1KQTx1ADZgHBAJ39EMS6UplMOwt4JOai53.png';
        };

        $articles = [
            [
                'judulBlog' => 'Panduan Lengkap Budidaya Padi Organik untuk Hasil Panen Maksimal',
                'tanggalBlog' => Carbon::now()->subDays(2)->toDateString(),
                'preferredFoto' => 'blog/boxWIP1KQTx1ADZgHBAJ39EMS6UplMOwt4JOai53.png',
                'isiBlog' => "Pertanian padi organik kini menjadi tren sekaligus kebutuhan pokok dalam mewujudkan ketahanan pangan yang sehat dan ramah lingkungan. Berbeda dari metode konvensional yang bergantung pada pestisida dan pupuk kimia sintetis, budidaya organik berfokus pada kesuburan tanah alami dan keseimbangan ekosistem sawah.\n\nLangkah pertama yang sangat menentukan keberhasilan adalah pengolahan tanah secara matang. Tanah perlu dibajak dan digemburkan bersama aplikasi pupuk kandang atau kompos terfermentasi minimal 2-3 minggu sebelum tanam. Hal ini bertujuan agar mikroorganisme pengurai memiliki waktu cukup untuk menyuburkan struktur hara tanah.\n\nPemilihan benih padi bersertifikat dengan daya kecambah tinggi juga menjadi pilar utama. Sebelum disemai, lakukan seleksi benih menggunakan larutan air garam untuk memisahkan bulir bernas dari yang hampa. Selama masa pertumbuhan, gunakan pestisida nabati berbahan daun nimba, serai wangi, dan bawang putih untuk menangkal serangan wereng dan walang sangit tanpa membunuh predator alami sawah seperti laba-laba dan capung.\n\nDengan konsistensi pemeliharaan dan pengairan berselang (intermittent irrigation), produktivitas panen padi organik terbukti mampu menyamai bahkan melampaui hasil konvensional, sembari meningkatkan nilai jual gabah secara signifikan di pasaran.",
            ],
            [
                'judulBlog' => 'Teknik Pemilihan Benih Jagung Unggul dan Pengendalian Hama Terpadu',
                'tanggalBlog' => Carbon::now()->subDays(5)->toDateString(),
                'preferredFoto' => 'blog/4PgMbaPRi6WbYHViIIizaWyQM69t7aJ4V0uogbXR.png',
                'isiBlog' => "Komoditas jagung merupakan salah satu pilar agribisnis strategis nasional, baik untuk kebutuhan pangan olahan maupun bahan baku utama industri pakan ternak. Kunci utama untuk mencapai panen jagung yang melimpah dan bermutu tinggi berakar pada pemilihan varietas benih yang tepat serta penerapan Pengendalian Hama Terpadu (PHT).\n\nVarietas hibrida seperti Bonanza F1 menawarkan keunggulan berupa vigor tanaman yang kokoh, ketahanan terhadap penyakit bulai (Peronosclerospora maydis), serta potensi ukuran tongkol yang besar dan seragam. Pastikan benih yang dibeli memiliki label sertifikasi resmi dengan informasi tanggal kedaluwarsa dan daya tumbuh di atas 85 persen.\n\nDalam budidaya jagung, ancaman ulat grayak frugiperda (Fall Armyworm) menjadi salah satu tantangan paling serius bagi petani di berbagai daerah. Strategi pencegahan yang efektif mencakup pergiliran tanaman, pemasangan perangkap feromon, serta pelepasan musuh alami seperti Trichogramma sp. Pengamatan rutin pada pucuk daun muda saat tanaman berumur 15 hingga 35 hari setelah tanam memungkinkan tindakan kuratif cepat sebelum populasi hama merusak titik tumbuh tanaman.",
            ],
            [
                'judulBlog' => 'Pentingnya Manajemen Pasca Panen dalam Menjaga Kualitas Beras Premium',
                'tanggalBlog' => Carbon::now()->subDays(9)->toDateString(),
                'preferredFoto' => 'blog/l0EKs5gmxnh7vy4phNdxlXcGXbzBD1ZWdG6cINW8.png',
                'isiBlog' => "Kualitas beras premium di meja konsumen tidak hanya ditentukan oleh cara bertani di sawah, tetapi sangat dipengaruhi oleh tahapan pasca panen. Menurut data penelitian pertanian, kehilangan hasil (losses) dan penurunan mutu beras terbesar sering kali terjadi akibat penanganan pasca panen yang kurang tepat.\n\nTahap krusial pertama adalah penentuan waktu panen yang presisi. Padi sebaiknya dipanen saat 90-95% malai telah menguning dan kadar air gabah berkisar antara 21-24%. Pemanenan terlalu dini menghasilkan banyak butir hijau dan kapur, sementara pemanenan yang terlambat menyebabkan butir gabah mudah rontok dan retak saat digiling.\n\nSetelah dipanen, perontokan harus segera dilakukan dalam waktu maksimal 24 jam untuk mencegah terjadinya fermentasi gabah yang menyebabkan beras menguning. Pengeringan gabah harus diturunkan secara bertahap hingga mencapai kadar air aman 13-14%. Penggunaan mesin pengering modern (bed dryer) atau penjemuran beralaskan terpal bersih sangat dianjurkan. Pada tahap penggilingan, mesin pembersih kotoran, pemecah kulit (husker), dan penyosoh bertingkat (polisher) akan memastikan butir patah minimal, warna mengkilap alami, dan beras bebas dari bekatul berlebih.",
            ],
            [
                'judulBlog' => 'Peluang Bisnis Distribusi Hasil Pertanian dan Kemitraan Agen Agris',
                'tanggalBlog' => Carbon::now()->subDays(14)->toDateString(),
                'preferredFoto' => 'blog/ScsVbny41dRuk6IVRArGb5RqPvr2KeLVglFaIsrO.png',
                'isiBlog' => "Bisnis komoditas pangan pokok, khususnya beras dan jagung, memiliki karakteristik permintaan yang senantiasa stabil dan tahan terhadap gejolak ekonomi. Sebagai kebutuhan primer harian, perputaran modal di sektor perdagangan pangan sangat dinamis dan menawarkan margin keuntungan yang menjanjikan bagi para pelaku usaha distribusi.\n\nPlatform Agris hadir menjembatani produsen agroindustri langsung dengan para agen mitra di berbagai wilayah. Melalui sistem kemitraan digital terintegrasi, agen mendapatkan kepastian pasokan beras langsung dari sumber penggilingan terpercaya tanpa harus melalui rantai tengkulak yang panjang. Hal ini memberikan keunggulan kompetitif dari sisi harga beli yang lebih bersaing dan jaminan kualitas produk yang konsisten.\n\nSelain itu, Agris melengkapi para agen dengan fasilitas tracking logistik terintegrasi (Biteship), sistem pembayaran digital otomatis, serta dukungan nota kesepahaman (MOU) kemitraan resmi yang saling menguntungkan. Menjadi agen Agris bukan hanya sekadar berniaga, melainkan juga turut menggerakkan perekonomian desa dan menghubungkan keringat petani dengan kepuasan konsumen akhir.",
            ],
            [
                'judulBlog' => 'Inovasi Pertanian Berkelanjutan: Menjawab Tantangan Ketahanan Pangan Nasional',
                'tanggalBlog' => Carbon::now()->subDays(20)->toDateString(),
                'preferredFoto' => 'blog/Y1bljyf8I3zlhHAbvxMzRpSS3xR1enpkUJGc5vae.png',
                'isiBlog' => "Tantangan ketahanan pangan global menuntut perubahan paradigma dalam sektor agrikultur. Perubahan iklim ekstrem, penyusutan luas lahan produktif, dan berkurangnya tenaga kerja muda di pedesaan mendorong perlunya adopsi inovasi pertanian cerdas (smart agriculture) yang berkelanjutan.\n\nPertanian berkelanjutan mengedepankan efisiensi pemanfaatan sumber daya alam tanpa merusak daya dukung lingkungan. Penggunaan varietas benih unggul tahan kekeringan, pengelolaan hara terpadu berbasis uji tanah, serta mekanisasi panen modern terbukti mampu meningkatkan produktivitas lahan per musim secara signifikan.\n\nTransformasi digital dalam rantai pasok agribisnis juga memainkan peran sentral. Melalui transparansi data stok, prediksi kebutuhan pasar, dan otomatisasi logistik terdistribusi, limbah pangan akibat ketidaksesuaian distribusi dapat ditekan seminimal mungkin. Sinergi antara teknologi, sains pertanian, dan kearifan lokal petani menjadi fondasi terkuat untuk mengantarkan Indonesia menuju kedaulatan pangan yang mandiri dan berdaya saing.",
            ],
            [
                'judulBlog' => 'Mengenal Perbedaan Karakteristik Beras Pandan Wangi, Rojolele, dan Mentik',
                'tanggalBlog' => Carbon::now()->subDays(26)->toDateString(),
                'preferredFoto' => 'blog/boxWIP1KQTx1ADZgHBAJ39EMS6UplMOwt4JOai53.png',
                'isiBlog' => "Bagi masyarakat Indonesia, beras bukan sekadar makanan pokok, melainkan bagian dari kekayaan budaya kuliner dengan ragam varietas yang kaya cita rasa. Di antara sekian banyak varietas lokal, Beras Pandan Wangi, Rojolele, dan Mentik Wangi adalah tiga primadona yang paling banyak dicari.\n\nBeras Pandan Wangi memiliki keistimewaan pada aroma harum pandan alami yang menguar saat dimasak. Bulir berasnya cenderung agak bulat, bening dengan sedikit bercak putih di tengah. Nasinya pulen lembut dengan rasa manis alami yang khas, sangat cocok disajikan untuk jamuan makan istimewa.\n\nSementara itu, Beras Rojolele dikenal dengan bulir yang agak bulat dan warna putih susu yang lebih pekat. Nasi yang dihasilkan memiliki kepulenan tinggi dengan rasa gurih yang kaya. Adapun Beras Mentik Wangi sering dijuluki sebagai 'beras sushi lokal' karena butirannya yang kenyal, sangat pulen, dan memiliki kadar amilosa rendah. Mengenali karakteristik masing-masing varietas membantu agen dan konsumen memilih beras yang paling sesuai dengan selera dan menu masakan harian keluarga.",
            ],
        ];

        foreach ($articles as $index => $item) {
            $foto = $getRiceImage($item['preferredFoto'], $index);

            Blog::updateOrCreate(
                ['judulBlog' => $item['judulBlog']],
                [
                    'userId' => $admin->id,
                    'isiBlog' => $item['isiBlog'],
                    'fotoBlog' => $foto,
                    'tanggalBlog' => $item['tanggalBlog'],
                ]
            );
        }
    }
}
