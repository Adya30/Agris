@extends('layouts.app')

@section('title', 'AGRIS - PT Surya Kencana Agrifarm')

@section('content')

<x-navbar />

<section id="home" class="min-h-screen flex items-center bg-[#58CC02] text-white">
    <div class="max-w-7xl mx-auto px-6 flex flex-col-reverse md:flex-row gap-12 items-center pt-20 justify-between lg:px-16">
        <div class="md:w-1/2 pb-20">
            <h1 class="text-5xl font-bold leading-tight mb-6">
                Pusat Penyedia <br> Suplai Benih Berkualitas
            </h1>
            <p class="text-lg opacity-90 mb-8">
                Platform digital untuk memenuhi kebutuhan pertanian Anda dengan sistem terpercaya, berkelanjutan, dan hasil panen maksimal.
            </p>
            <div class="flex gap-4">
                <a href="{{ route('login') }}" class="px-8 py-3 bg-white text-[#58CC02] font-bold rounded-full shadow-md hover:shadow-xl hover:scale-105 transition duration-300">
                    Mulai Sekarang
                </a>
                <a href="{{ route('about') }}" class="px-8 py-3 border border-white rounded-full font-medium hover:bg-white hover:text-[#58CC02] transition duration-300">
                    Tentang Kami
                </a>
            </div>
        </div>
        <div class="md:w-1/2 flex justify-center">
            <img src="{{ asset('images/icon.png') }}" class="w-[350px] md:w-[450px] drop-shadow-2xl animate-pulse-slow">
        </div>
    </div>
</section>

<section id="about" class="py-24 bg-white px-6">
    <div class="max-w-7xl mx-auto flex flex-col md:flex-row items-center gap-16">
        <div class="md:w-1/2">
            <div class="relative">
                <div class="absolute -top-4 -left-4 w-24 h-24 bg-[#58CC02]/10 rounded-full -z-10"></div>
                <img src="{{ asset('images/about.jpg') }}" class="rounded-3xl shadow-2xl w-full object-cover h-[400px]">
                <div class="absolute -bottom-6 -right-6 bg-white p-6 rounded-2xl shadow-xl border border-gray-50 hidden md:block">
                    <p class="text-[#58CC02] font-bold text-4xl">10 Tahun</p>
                    <p class="text-gray-500 text-sm font-bold uppercase tracking-widest">Telah berdiri</p>
                </div>
            </div>
        </div>
        <div class="md:w-1/2">
            <span class="text-[#58CC02] font-bold tracking-widest uppercase text-sm mb-4 block">Tentang AGRIS</span>
            <h2 class="text-4xl font-bold text-gray-800 mb-6 leading-tight">Dedikasi Kami untuk Kemajuan Petani Indonesia</h2>
            <p class="text-gray-600 leading-relaxed mb-6">
                AGRIS lahir dari semangat untuk memberikan solusi hulu pertanian. Kami memahami bahwa benih berkualitas adalah fondasi utama keberhasilan panen. Dengan teknologi seleksi terkini, kami memastikan setiap benih yang sampai ke tangan Anda memiliki daya tumbuh tinggi.
            </p>
            <ul class="space-y-4 mb-8">
                <li class="flex items-center gap-3 text-gray-700 font-medium">
                    <i class="fa-solid fa-circle-check text-[#58CC02]"></i> Seleksi Benih Unggul Bersertifikat
                </li>
                <li class="flex items-center gap-3 text-gray-700 font-medium">
                    <i class="fa-solid fa-circle-check text-[#58CC02]"></i> Penjualan benih berkualitas
                </li>
            </ul>
        </div>
    </div>
</section>

<section class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-6 text-center">
        <h2 class="text-3xl font-bold mb-12">Layanan Utama Kami</h2>
        <div class="grid md:grid-cols-3 gap-8">
            <div class="bg-white p-8 rounded-3xl border border-gray-100 hover:border-[#58CC02] transition">
                <div class="w-16 h-16 bg-[#58CC02]/10 rounded-2xl flex items-center justify-center text-[#58CC02] text-2xl mb-6 mx-auto">
                    <i class="fa-solid fa-seedling"></i>
                </div>
                <h3 class="font-bold text-xl mb-3">Distribusi Benih</h3>
                <p class="text-gray-500 text-sm">Penyaluran benih padi dan jagung unggul ke seluruh penjuru daerah.</p>
            </div>
            <div class="bg-white p-8 rounded-3xl border border-gray-100 hover:border-[#58CC02] transition">
                <div class="w-16 h-16 bg-[#58CC02]/10 rounded-2xl flex items-center justify-center text-[#58CC02] text-2xl mb-6 mx-auto">
                    <i class="fa-solid fa-microscope"></i>
                </div>
                <h3 class="font-bold text-xl mb-3">Uji Laboratorium</h3>
                <p class="text-gray-500 text-sm">Setiap batch produk melalui uji kelayakan dan daya tumbuh yang ketat.</p>
            </div>
            <div class="bg-white p-8 rounded-3xl border border-gray-100 hover:border-[#58CC02] transition">
                <div class="w-16 h-16 bg-[#58CC02]/10 rounded-2xl flex items-center justify-center text-[#58CC02] text-2xl mb-6 mx-auto">
                    <i class="fa-solid fa-handshake"></i>
                </div>
                <h3 class="font-bold text-xl mb-3">Kemitraan Tani</h3>
                <p class="text-gray-500 text-sm">Program kerjasama saling menguntungkan untuk meningkatkan kesejahteraan.</p>
            </div>
        </div>
    </div>
</section>

<section id="produk" class="py-24 bg-white px-6">
    <div class="max-w-7xl mx-auto">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold mb-4">Komoditas Unggulan</h2>
            <p class="text-gray-500">Pilihan benih terbaik untuk hasil panen yang melimpah.</p>
        </div>

        <div class="grid md:grid-cols-2 gap-12 max-w-4xl mx-auto">
            <div class="group relative rounded-3xl overflow-hidden bg-white shadow-lg border border-gray-100">
                <img src="{{ asset('images/padi.png') }}" class="h-100 w-full object-cover">
                <div class="p-8">
                    <span class="text-[10px] font-bold uppercase text-[#58CC02] bg-[#58CC02]/10 px-3 py-1 rounded-full mb-4 inline-block">Sertifikat Resmi</span>
                    <h3 class="text-2xl font-bold text-gray-800 mb-2">Benih Padi Unggul</h3>
                    <p class="text-gray-500 text-sm mb-6">Varietas pilihan dengan ketahanan hama yang kuat dan tekstur nasi yang pulen.</p>
                    <a href="#" class="inline-flex items-center font-bold text-[#58CC02] hover:gap-3 transition-all">
                        Lihat Produk <i class="fa-solid fa-arrow-right ml-2 text-sm"></i>
                    </a>
                </div>
            </div>

            <div class="group relative rounded-3xl overflow-hidden bg-white shadow-lg border border-gray-100">
                <img src="{{ asset('images/jagung.png') }}" class="h-100 w-full object-cover">
                <div class="p-8">
                    <span class="text-[10px] font-bold uppercase text-orange-500 bg-orange-50 px-3 py-1 rounded-full mb-4 inline-block">Produktivitas Tinggi</span>
                    <h3 class="text-2xl font-bold text-gray-800 mb-2">Benih Jagung Hibrida</h3>
                    <p class="text-gray-500 text-sm mb-6">Pertumbuhan cepat dan hasil tongkol yang besar, sangat cocok untuk pakan ternak.</p>
                    <a href="#" class="inline-flex items-center font-bold text-[#58CC02] hover:gap-3 transition-all">
                        Lihat Produk <i class="fa-solid fa-arrow-right ml-2 text-sm"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-20 bg-[#58CC02] text-white overflow-hidden relative">
    <div class="absolute top-0 right-0 opacity-10">
        <i class="fa-solid fa-leaf text-[200px]"></i>
    </div>
    <div class="max-w-4xl mx-auto px-6 text-center relative z-10">
        <h2 class="text-4xl font-bold mb-6">Tumbuh Bersama AGRIS</h2>
        <p class="text-xl opacity-90 mb-10 font-medium">Ayo bermitra dengan kami! Bersama kita bangun ketahanan pangan Indonesia yang lebih kuat.</p>
        <a href="https://wa.me/yournumber" class="px-10 py-4 bg-white text-[#58CC02] font-bold rounded-2xl hover:shadow-2xl hover:-translate-y-1 transition duration-300">
            Mulai Sekarang
        </a>
    </div>
</section>

<section class="py-24 bg-gray-50 px-6">
    <div class="max-w-7xl mx-auto flex flex-col md:flex-row gap-16">
        <div class="md:w-1/3">
            <h2 class="text-4xl font-bold text-gray-800 mb-6">Pertanyaan Populer</h2>
            <p class="text-gray-500 mb-8">Masih bingung? Kami merangkum beberapa hal yang sering ditanyakan oleh rekan petani dan mitra kami.</p>
        </div>

        <div class="md:w-2/3 space-y-4">
            <div class="faq-item group">
                <button class="faq-btn w-full flex items-center justify-between p-6 bg-white rounded-2xl border border-gray-100 hover:border-[#58CC02] transition duration-300">
                    <span class="font-bold text-gray-700 text-lg">Apa itu AGRIS?</span>
                    <div class="w-8 h-8 rounded-lg bg-gray-50 flex items-center justify-center text-gray-400 group-hover:bg-[#58CC02] group-hover:text-white transition">
                        <i class="fa-solid fa-chevron-down text-xs transition-transform duration-300"></i>
                    </div>
                </button>
                <div class="faq-content hidden px-6 py-4 text-gray-500 leading-relaxed">
                    AGRIS adalah platform penyedia benih padi dan jagung tersertifikasi yang berfokus pada distribusi bibit unggul untuk meningkatkan produktivitas pertanian di Indonesia.
                </div>
            </div>

            <div class="faq-item group">
                <button class="faq-btn w-full flex items-center justify-between p-6 bg-white rounded-2xl border border-gray-100 hover:border-[#58CC02] transition duration-300">
                    <span class="font-bold text-gray-700 text-lg">Apakah benih sudah bersertifikat?</span>
                    <div class="w-8 h-8 rounded-lg bg-gray-50 flex items-center justify-center text-gray-400 group-hover:bg-[#58CC02] group-hover:text-white transition">
                        <i class="fa-solid fa-chevron-down text-xs transition-transform duration-300"></i>
                    </div>
                </button>
                <div class="faq-content hidden px-6 py-4 text-gray-500 leading-relaxed">
                    Ya, seluruh benih yang kami distribusikan telah lolos uji dari Balai Pengawasan dan Sertifikasi Benih (BPSB) dan memiliki label resmi sesuai standar pemerintah.
                </div>
            </div>

            <div class="faq-item group">
                <button class="faq-btn w-full flex items-center justify-between p-6 bg-white rounded-2xl border border-gray-100 hover:border-[#58CC02] transition duration-300">
                    <span class="font-bold text-gray-700 text-lg">Bagaimana cara menjadi mitra?</span>
                    <div class="w-8 h-8 rounded-lg bg-gray-50 flex items-center justify-center text-gray-400 group-hover:bg-[#58CC02] group-hover:text-white transition">
                        <i class="fa-solid fa-chevron-down text-xs transition-transform duration-300"></i>
                    </div>
                </button>
                <div class="faq-content hidden px-6 py-4 text-gray-500 leading-relaxed">
                    Anda dapat menekan tombol "Hubungi Tim Kemitraan" di atas. Tim kami akan mengirimkan proposal kerjasama dan melakukan survei kelayakan lokasi/usaha tani Anda.
                </div>
            </div>
        </div>
    </div>
</section>

<x-footer />

@endsection

@push('scripts')
<script>
    const faqBtns = document.querySelectorAll(".faq-btn");

    faqBtns.forEach(btn => {
        btn.addEventListener("click", () => {
            const content = btn.nextElementSibling;
            const icon = btn.querySelector('.fa-chevron-down');

            // Toggle Hidden
            content.classList.toggle("hidden");

            // Rotate Icon
            if(!content.classList.contains('hidden')){
                icon.style.transform = 'rotate(180deg)';
                btn.classList.add('border-[#58CC02]');
            } else {
                icon.style.transform = 'rotate(0deg)';
                btn.classList.remove('border-[#58CC02]');
            }
        });
    });
</script>
@endpush
