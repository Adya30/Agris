<footer class="bg-gray-900 text-white px-6">

    <div class="max-w-7xl mx-auto px-6 py-8">

        <div class="grid md:grid-cols-4 gap-10">

            <!-- 1. LOGO + DESKRIPSI -->
            <div>
                <h2 class="text-2xl font-bold text-[#58CC02] mb-4">
                    <img src="{{ asset('images/icon.png') }}" class="w-40 mb-4">
                </h2>
                <p class="text-gray-400 text-sm">
                    Marketplace pertanian modern yang menyediakan kebutuhan 
                    terbaik untuk petani dengan sistem terpercaya.
                </p>
            </div>

            <!-- 2. NAVIGASI -->
            <div>
                <h3 class="text-lg font-semibold mb-4">Navigasi</h3>
                <ul class="space-y-2 text-gray-400 text-sm">
                    <li><a href="#home" class="hover:text-white">Home</a></li>
                    <li><a href="#produk" class="hover:text-white">Produk</a></li>
                    <li><a href="#kategori" class="hover:text-white">Kategori</a></li>
                    <li><a href="#kontak" class="hover:text-white">Kontak</a></li>
                </ul>
            </div>

            <!-- 3. KONTAK -->
            <div>
                <h3 class="text-lg font-semibold mb-4">Kontak</h3>
                <ul class="space-y-2 text-gray-400 text-sm">
                    <li><i class="fa-solid fa-location-dot mr-2"></i> Jember, Indonesia</li>
                    <li><i class="fa-solid fa-phone mr-2"></i> +62 812 3456 7890</li>
                    <li><i class="fa-solid fa-envelope mr-2"></i> info@mylogo.com</li>
                </ul>

                <!-- SOSIAL MEDIA -->
                <div class="flex gap-4 mt-4 text-xl">
                    <a href="#" class="hover:text-[#58CC02]"><i class="fa-brands fa-facebook"></i></a>
                    <a href="#" class="hover:text-[#58CC02]"><i class="fa-brands fa-instagram"></i></a>
                    <a href="#" class="hover:text-[#58CC02]"><i class="fa-brands fa-twitter"></i></a>
                </div>
            </div>

            <!-- 4. MAPS -->
            <div>
                <h3 class="text-lg font-semibold mb-4">Lokasi</h3>
                <iframe 
                    class="w-full h-48 rounded-xl border-0"
                    src="https://www.google.com/maps?q=Jember&output=embed"
                    loading="lazy">
                </iframe>
            </div>

        </div>

        <!-- BOTTOM -->
        <div class="mt-10 border-t border-gray-700 pt-6 text-center text-gray-400 text-sm">
            © {{ date('Y') }} MyLogo. All rights reserved.
        </div>

    </div>

</footer>