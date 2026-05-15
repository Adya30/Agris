<footer class="bg-gray-900 text-white px-6">
    <div class="max-w-7xl mx-auto px-6 py-8">
        <div class="grid md:grid-cols-4 gap-10">

            <div class="flex flex-col items-start">
                <div class="mb-4">
                    <img src="<?php echo e(asset('images/icon.png')); ?>" class="w-40" alt="Logo AGRIS">
                </div>
                <p class="text-gray-400 text-sm leading-relaxed">
                    Marketplace pertanian modern yang menyediakan kebutuhan
                    terbaik untuk petani dengan sistem terpercaya.
                </p>
            </div>

            <div>
                <h3 class="text-lg font-semibold mb-4">Navigasi</h3>
                <ul class="space-y-2 text-gray-400 text-sm">
                    <li>
                        <a href="<?php echo e(route('agen.blog.index')); ?>" class="hover:text-white transition <?php echo e(Route::is('agen.blog.*') ? 'text-white font-bold' : ''); ?>">
                            Blog
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo e(route('agen.produk.index')); ?>" class="hover:text-white transition <?php echo e(Route::is('agen.produk.*') ? 'text-white font-bold' : ''); ?>">
                            Produk
                        </a>
                    </li>
                    <li>
                        <a href="#" class="hover:text-white transition">Transaksi</a>
                    </li>
                    <li>
                        <a href="<?php echo e(route('kemitraan.index')); ?>" class="hover:text-white transition <?php echo e(Route::is('kemitraan.*') ? 'text-white font-bold' : ''); ?>">
                            Kemitraan
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo e(route('agen.chat.index')); ?>" class="hover:text-white transition <?php echo e(Route::is('agen.chat.*') ? 'text-white font-bold' : ''); ?>">
                            Chat
                        </a>
                    </li>
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
                    <a href="https://www.tiktok.com/@skas.official?_t=ZS-8wWtyVtbfV7&_r=1" target="_blank" class="text-gray-400 hover:text-[#58CC02] transition"><i class="fa-brands fa-tiktok"></i></a>
                    <a href="https://www.instagram.com/skas.official/" target="_blank" class="text-gray-400 hover:text-[#58CC02] transition"><i class="fa-brands fa-instagram"></i></a>
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
            © <?php echo e(date('Y')); ?> <span class="font-bold text-gray-400">Agris</span>. All rights reserved.
        </div>
    </div>
</footer>
<?php /**PATH D:\project\Agris\resources\views/components/footer-agen.blade.php ENDPATH**/ ?>