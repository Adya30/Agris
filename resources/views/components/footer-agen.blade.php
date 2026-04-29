<footer class="bg-gray-900 text-white px-6">

    <div class="max-w-7xl mx-auto px-6 py-8">

        <div class="grid md:grid-cols-4 gap-10">

            <div class="flex flex-col items-start">
                <h2 class="text-2xl font-bold text-[#58CC02] mb-4">
                    <img src="{{ asset('images/icon.png') }}" class="w-50 mb-4">
                </h2>
                <p class="text-gray-400 text-sm">
                    Marketplace pertanian modern yang menyediakan kebutuhan
                    terbaik untuk petani dengan sistem terpercaya.
                </p>
            </div>

            <div>
                <h3 class="text-lg font-semibold mb-4">Navigasi</h3>
                <ul class="space-y-2 text-gray-400 text-sm">
                    </li>
                        <a href="#" class="hover:text-white">Blog</a>
                    <li>
                    <li>
                        <a href="{{ route('agen.produk.index') }}" class="hover:text-white  {{ Route::is('agen.produk.*') ? 'border-white' : 'border-transparent' }}">Produk</a>
                    </li>
                    <li>
                        <a href="#" class="hover:text-white ">Transaksi</a>
                    </li>
                    <li>
                        <a href="#" class="hover:text-white ">Kemitraan</a>
                    </li>
                    <li>
                        <a href="#" class="hover:text-white ">Chat</a>
                    </li>
                    <li>
                        <a href="#" class="hover:text-white ">Konsultasi</a>
                    </li>
                </ul>
            </div>

            <div>
                <h3 class="text-lg font-semibold mb-4">Kontak</h3>
                <ul class="space-y-2 text-gray-400 text-sm">
                    <li><i class="fa-solid fa-location-dot mr-2"></i> Jember, Indonesia</li>
                    <li><i class="fa-solid fa-phone mr-2"></i> +62 812 3456 7890</li>
                    <li><i class="fa-solid fa-envelope mr-2"></i> agrisagroindustri@gmail.com</li>
                </ul>

                <div class="flex gap-4 mt-8 text-xl">
                    <a href="#" class="hover:text-[#58CC02]"><i class="fa-brands fa-facebook"></i></a>
                    <a href="#" class="hover:text-[#58CC02]"><i class="fa-brands fa-instagram"></i></a>
                    <a href="#" class="hover:text-[#58CC02]"><i class="fa-brands fa-twitter"></i></a>
                </div>
            </div>

            <div>
                <h3 class="text-lg font-semibold mb-4">Lokasi</h3>
                <iframe
                    class="w-full h-48 rounded-xl border-0"
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d63192.058441133886!2d113.6171077216797!3d-8.151902600000003!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd695e2281a28c5%3A0x2c7dd8c671e2c553!2sPT.%20Surya%20Kencana%20Agrifarm%20Sejahtera!5e0!3m2!1sid!2sid!4v1777362908806!5m2!1sid!2sid"
                    loading="lazy">
                </iframe>
            </div>

        </div>

        <div class="mt-10 border-t border-gray-700 pt-6 text-center text-gray-400 text-sm">
            © {{ date('Y') }} Agris. All rights reserved.
        </div>

    </div>

</footer>
