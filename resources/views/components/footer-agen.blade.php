<footer class="bg-gray-900 text-white px-6">
    <div class="max-w-7xl mx-auto px-6 py-8">
        <div class="grid md:grid-cols-4 gap-10">

            <div class="flex flex-col items-start">
                <div class="mb-4">
                    <img src="{{ asset('images/icon.png') }}" class="w-40" alt="Logo AGRIS">
                </div>
                <p class="text-gray-400 text-sm leading-relaxed">
                    Marketplace pertanian modern yang menyediakan kebutuhan
                    terbaik untuk petani dengan sistem terpercaya.
                </p>
            </div>

            <div>
                <h3 class="text-lg font-semibold mb-4">Navigasi</h3>
                <ul class="space-y-2 text-gray-400 text-sm">
                    <li><a href="#" class="hover:text-white transition">Blog</a></li>
                    <li>
                        <a href="{{ route('agen.produk.index') }}" class="hover:text-white transition {{ Route::is('agen.produk.*') ? 'text-white font-bold' : '' }}">
                            Produk
                        </a>
                    </li>
                    <li><a href="#" class="hover:text-white transition">Transaksi</a></li>
                    <li><a href="#" class="hover:text-white transition">Kemitraan</a></li>
                    <li><a href="#" class="hover:text-white transition">Chat</a></li>
                    <li><a href="#" class="hover:text-white transition">Konsultasi</a></li>
                </ul>
            </div>

            <div>
                <h3 class="text-lg font-semibold mb-4">Kontak</h3>
                <ul class="space-y-3 text-gray-400 text-sm">
                    <li class="flex items-center gap-2">
                        <i class="fa-solid fa-location-dot text-[#58CC02]"></i> Jember, Indonesia
                    </li>
                    <li class="flex items-center gap-2">
                        <i class="fa-solid fa-phone text-[#58CC02]"></i> +62 812 3456 7890
                    </li>
                    <li class="flex items-center gap-2">
                        <i class="fa-solid fa-envelope text-[#58CC02]"></i> agrisagroindustri@gmail.com
                    </li>
                </ul>

                <div class="flex gap-4 mt-8 text-xl">
                    <a href="#" class="text-gray-400 hover:text-[#58CC02] transition"><i class="fa-brands fa-facebook"></i></a>
                    <a href="#" class="text-gray-400 hover:text-[#58CC02] transition"><i class="fa-brands fa-instagram"></i></a>
                    <a href="#" class="text-gray-400 hover:text-[#58CC02] transition"><i class="fa-brands fa-twitter"></i></a>
                </div>
            </div>

            <div>
                <h3 class="text-lg font-semibold mb-4">Lokasi</h3>
                <iframe
                    title="Lokasi AGRIS"
                    class="w-full h-48 rounded-xl border-0"
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d63192.058441133886!2d113.6171077216797!3d-8.151902600000003!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd695e2281a28c5%3A0x2c7dd8c671e2c553!2sPT.%20Surya%20Kencana%20Agrifarm%20Sejahtera!5e0!3m2!1sid!2sid!4v1777362908806!5m2!1sid!2sid"
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>

        </div>

        <div class="mt-10 border-t border-gray-800 pt-6 text-center text-gray-500 text-sm">
            © {{ date('Y') }} <span class="font-bold text-gray-400">Agris</span>. All rights reserved.
        </div>
    </div>
</footer>
