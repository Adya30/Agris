@extends('layouts.app')

@section('title', 'AGRIS - Pusat Penyedia Suplai Benih Berkualitas')

@section('content')

<x-navbar />

<section id="home" class="min-h-screen flex items-center bg-[#58CC02] text-white">

    <div class="max-w-7xl mx-auto px-6 flex flex-col-reverse md:flex-row gap-12 items-center pt-25 justify-between lg:px-16">

        <!-- TEKS -->
        <div class="md:w-1/2 pb-20">
            <h1 class="text-5xl font-bold leading-tight mb-6">
                Pusat Penyedia <br> Suplai Benih Berkualitas
            </h1>

            <p class="text-lg opacity-90 mb-8">
                Platform digital untuk memenuhi kebutuhan pertanian Anda dengan sistem terpercaya dan berkelanjutan.
            </p>

            <div class="flex gap-4">
                <a href="#produk"
                   class="px-8 py-3 bg-white text-[#58CC02] font-semibold rounded-full shadow-md hover:shadow-xl hover:scale-105 transition duration-300">
                    Mulai
                </a>

                <a href="#kategori"
                   class="px-8 py-3 border border-white rounded-full hover:bg-white hover:text-[#58CC02] transition duration-300">
                    Lihat Produk
                </a>
            </div>
        </div>

        <!-- GAMBAR -->
        <div class="md:w-1/2 flex justify-center">
            <img src="{{ asset('images/icon.png') }}"
                 class="w-[350px] pb-3 drop-shadow-xl">
        </div>

    </div>
</section>

<section id="kategori" class="min-h-screen flex items-center bg-gray-50">

    <div class="max-w-7xl mx-auto pt-10 px-6 w-full">

        <h2 class="text-4xl font-bold text-center mb-16">
            Keunggulan Kami
        </h2>

        <div class="grid px-10 md:grid-cols-4 sm:grid-cols-2 gap-8">

            <div class="bg-white p-6 rounded-2xl shadow-md hover:shadow-xl transition duration-300 text-center">
                <div class="text-4xl mb-4 text-[#58CC02]">
                    <i class="fa-solid fa-seedling"></i>
                </div>
                <h3 class="text-xl font-semibold mb-2">Produk Berkualitas</h3>
                <p class="text-gray-600 text-sm">
                    Kami menyediakan produk pertanian terbaik langsung dari sumber terpercaya.
                </p>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-md hover:shadow-xl transition duration-300 text-center">
                <div class="text-4xl mb-4 text-[#58CC02]">
                    <i class="fa-solid fa-truck-fast"></i>
                </div>
                <h3 class="text-xl font-semibold mb-2">Pengiriman Cepat</h3>
                <p class="text-gray-600 text-sm">
                    Sistem distribusi efisien memastikan produk sampai tepat waktu.
                </p>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-md hover:shadow-xl transition duration-300 text-center">
                <div class="text-4xl mb-4 text-[#58CC02]">
                    <i class="fa-solid fa-hand-holding-dollar"></i>
                </div>
                <h3 class="text-xl font-semibold mb-2">Harga Terjangkau</h3>
                <p class="text-gray-600 text-sm">
                    Harga kompetitif dengan kualitas terbaik untuk petani modern.
                </p>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-md hover:shadow-xl transition duration-300 text-center">
                <div class="text-4xl mb-4 text-[#58CC02]">
                    <i class="fa-solid fa-leaf"></i>
                </div>
                <h3 class="text-xl font-semibold mb-2">Ramah Lingkungan</h3>
                <p class="text-gray-600 text-sm">
                    Mendukung pertanian berkelanjutan dan ramah lingkungan.
                </p>
            </div>

        </div>

    </div>
</section>

<section id="produk" class="min-h-screen flex items-center bg-[#f6f6f6] px-6">

    <div class="max-w-7xl mx-auto pt-10 px-6 pb-10 w-full">

        <h2 class="text-4xl font-bold text-center mb-16">
            Produk Kami
        </h2>

        <div class="grid md:grid-cols-3 gap-10">

            @for($i=1; $i<=3; $i++)
            <div class="rounded-3xl overflow-hidden shadow-sm hover:shadow-2xl transition duration-300 bg-white">

                <img src="{{ asset('images/product.jpg') }}"
                     class="h-60 w-full object-cover">

                <div class="p-6">
                    <h3 class="font-semibold text-lg mb-2">
                        Produk {{ $i }}
                    </h3>

                    <p class="text-gray-500 text-sm mb-4">
                        Produk pertanian berkualitas tinggi.
                    </p>

                    <div class="flex justify-between items-center">
                        <span class="text-[#58CC02] font-bold text-lg">
                            Rp 150.000
                        </span>

                        <button class="px-5 py-2 bg-[#58CC02] text-white rounded-full hover:bg-green-600 hover:scale-105 transition duration-300">
                            <i class="fa-solid fa-cart-shopping mr-1"></i>
                            Pesan Sekarang
                        </button>
                    </div>
                </div>

            </div>
            @endfor

        </div>

    </div>
</section>

<section class="bg-white py-10 px-6 md:px-10">
  <div class="max-w-3xl mx-auto">

    <h2 class="text-2xl md:text-3xl font-bold text-center mb-6">
      FAQ
    </h2>

    <div class="space-y-4">

      <div class="border rounded-lg">
        <button class="w-full text-left p-4 font-semibold flex justify-between items-center faq-btn">
          Apa itu AGRIS?
          <span>+</span>
        </button>
        <div class="px-4 pb-4 text-gray-600 hidden">
          AGRIS adalah brand yang menyediakan benih padi dan jagung berkualitas tinggi.
        </div>
      </div>

      <div class="border rounded-lg">
        <button class="w-full text-left p-4 font-semibold flex justify-between items-center faq-btn">
          Apakah benih sudah bersertifikat?
          <span>+</span>
        </button>
        <div class="px-4 pb-4 text-gray-600 hidden">
          Ya, semua produk AGRIS telah melalui proses sertifikasi dan uji kualitas.
        </div>
      </div>

      <div class="border rounded-lg">
        <button class="w-full text-left p-4 font-semibold flex justify-between items-center faq-btn">
          Bagaimana cara pemesanan?
          <span>+</span>
        </button>
        <div class="px-4 pb-4 text-gray-600 hidden">
          Anda dapat memesan melalui website kami atau menghubungi customer service.
        </div>
      </div>

    </div>
  </div>
</section>

<x-footer />

@endsection


@push('scripts')
<script>
  const buttons = document.querySelectorAll(".faq-btn");

  buttons.forEach(btn => {
    btn.addEventListener("click", () => {
      const content = btn.nextElementSibling;
      content.classList.toggle("hidden");
    });
  });
</script>
@endpush
