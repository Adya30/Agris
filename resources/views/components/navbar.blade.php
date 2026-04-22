<nav class="fixed w-full z-50 bg-white/90 backdrop-blur shadow-sm px-6">
    <div class="max-w-7xl mx-auto">
        <div class="flex justify-between items-center h-16">

            <!-- Logo -->
            <a href="#" class="flex items-center gap-2 text-2xl font-bold text-[#58CC02]">
                <img src="{{ asset('images/icon.png') }}" class="w-10">
                AGRIS
            </a>

            <!-- Menu -->
            <div class="hidden md:flex items-center gap-8 font-medium">
                <a href="#home" class="hover:text-[#58CC02] transition">Beranda</a>
                <a href="#kategori" class="hover:text-[#58CC02] transition">Layanan</a>
                <a href="#produk" class="hover:text-[#58CC02] transition">Tentang Kami</a>
                <a href="#kontak" class="hover:text-[#58CC02] transition">Kontak</a>
            </div>

            <!-- Buttons -->
            <div class="hidden md:flex gap-4">
                <a href="{{ route('login') }}"
                   class="px-6 py-2 rounded-full bg-[#58CC02] text-white shadow-md hover:shadow-lg hover:scale-105 transition duration-300">
                    Login
                </a>
            </div>

        </div>
    </div>
</nav>