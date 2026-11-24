<?php $__env->startSection('title', 'AGRIS - PT Surya Kencana Agrifarm'); ?>

<?php $__env->startSection('content'); ?>

<?php
    $services = [
        [
            'icon' => 'fa-seedling',
            'title' => 'Distribusi Benih',
            'desc' => 'Penyaluran benih padi dan jagung unggul dengan logistik terintegrasi.'
        ],
        [
            'icon' => 'fa-microscope',
            'title' => 'Uji Laboratorium',
            'desc' => 'Produk melalui uji kelayakan ketat di laboratorium bersertifikasi.'
        ],
        [
            'icon' => 'fa-handshake',
            'title' => 'Kemitraan Tani',
            'desc' => 'Kerjasama strategis untuk meningkatkan kualitas hasil panen.'
        ]
    ];

    $faqs = [
        [
            'q' => 'Apa itu AGRIS?',
            'a' => 'AGRIS adalah platform penyedia benih padi dan jagung tersertifikasi dari PT Surya Kencana Agrifarm Sejahtera.'
        ],
        [
            'q' => 'Apakah benih sudah bersertifikat?',
            'a' => 'Ya, seluruh benih yang kami distribusikan telah lolos uji BPSB dan memiliki daya tumbuh tinggi.'
        ],
        [
            'q' => 'Bagaimana cara menjadi mitra AGRIS?',
            'a' => 'Anda dapat mendaftar melalui tombol "Mulai Sekarang" atau menghubungi admin kami.'
        ],
        [
            'q' => 'Di mana lokasi gudang distribusi?',
            'a' => 'Gudang utama kami terletak di Jawa Timur, melayani pengiriman ke seluruh Indonesia.'
        ]
    ];
?>

<?php if (isset($component)) { $__componentOriginala591787d01fe92c5706972626cdf7231 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala591787d01fe92c5706972626cdf7231 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.navbar','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('navbar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala591787d01fe92c5706972626cdf7231)): ?>
<?php $attributes = $__attributesOriginala591787d01fe92c5706972626cdf7231; ?>
<?php unset($__attributesOriginala591787d01fe92c5706972626cdf7231); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala591787d01fe92c5706972626cdf7231)): ?>
<?php $component = $__componentOriginala591787d01fe92c5706972626cdf7231; ?>
<?php unset($__componentOriginala591787d01fe92c5706972626cdf7231); ?>
<?php endif; ?>

<section id="home" class="relative min-h-screen shrink-0 flex items-center pt-24 pb-12 sm:pt-28 sm:pb-16 md:py-32 overflow-hidden">
    <div class="absolute inset-0 z-0">
        <img src="<?php echo e(asset('images/about.svg')); ?>" class="w-full h-full object-cover" alt="Background">
        <div class="absolute inset-0 bg-black/60 bg-opacity-50 backdrop-blur-xs"></div>
    </div>

    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-16 w-full">
        <div class="flex flex-col md:flex-row items-center justify-between gap-6 sm:gap-8 md:gap-12">
            <div class="max-w-2xl text-white order-2 md:order-1 text-center md:text-left" data-aos="fade-up" data-aos-delay="200">
                <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold leading-tight mb-4 sm:mb-6">
                    Pusat Penyedia <br class="hidden md:block">
                    <span class="text-green-500">Suplai Benih</span> Unggul
                </h1>
                <p class="text-sm sm:text-base md:text-lg opacity-90 mb-6 sm:mb-8 md:mb-10 leading-relaxed max-w-xl mx-auto md:mx-0">
                    Platform digital PT Surya Kencana Agrifarm Sejahtera untuk memenuhi kebutuhan pertanian Anda dengan sistem terpercaya dan hasil panen maksimal.
                </p>
                <div class="flex flex-col sm:flex-row justify-center md:justify-start gap-3 sm:gap-4">
                    <a href="<?php echo e(route('login')); ?>" class="w-full sm:w-auto px-6 sm:px-8 py-3.5 sm:py-4 bg-green-700 text-white font-bold rounded-xl sm:rounded-2xl shadow-xl hover:bg-green-600 transition duration-300 text-center text-sm sm:text-base">
                        Mulai Sekarang
                    </a>
                    <a href="<?php echo e(route('about')); ?>" class="w-full sm:w-auto px-6 sm:px-8 py-3.5 sm:py-4 border-2 border-white/30 backdrop-blur-sm text-white rounded-xl sm:rounded-2xl font-bold hover:bg-white/20 transition duration-300 text-center text-sm sm:text-base">
                        Tentang Kami
                    </a>
                </div>
            </div>
            <div class="flex justify-center order-1 md:order-2" data-aos="flip-left" data-aos-delay="200">
                <img src="<?php echo e(asset('images/icon.svg')); ?>" class="w-32 sm:w-44 md:w-72 lg:w-80 h-auto drop-shadow-2xl" alt="Icon AGRIS">
            </div>
        </div>
    </div>
</section>

<section id="about" class="relative py-16 md:py-24 bg-gray-100 px-4 sm:px-6">
    <div class="max-w-7xl mx-auto flex flex-col md:flex-row items-center gap-10 md:gap-20">
        <div class="w-full md:w-1/2 relative">
            <div class="relative z-10">
                <img src="<?php echo e(asset('images/about.svg')); ?>" class="rounded-3xl shadow-2xl w-full object-cover h-64 sm:h-80 md:h-125" data-aos="fade-up" >
                <div class="absolute -bottom-6 -right-6 bg-gray-100 p-6 rounded-3xl shadow-xl border-2 border-gray-200 hidden lg:block" data-aos="flip-down">
                    <p class="text-slate-900 font-extrabold text-4xl">10+</p>
                    <p class="text-gray-400 text-xs font-black uppercase tracking-widest">Terpercaya</p>
                </div>
            </div>
        </div>

        <div class="w-full md:w-1/2" data-aos="fade-up">
            <span class="text-green-600 font-bold tracking-widest uppercase text-xs mb-3 sm:mb-4 block">Tentang AGRIS</span>
            <h2 class="text-2xl sm:text-3xl md:text-5xl font-bold text-slate-900 mb-4 sm:mb-6 leading-tight">Dedikasi Kami untuk Kemajuan Petani Indonesia</h2>
            <p class="text-gray-600 leading-relaxed text-sm sm:text-base md:text-lg mb-6 sm:mb-8">
                AGRIS lahir dari semangat untuk memberikan solusi hulu pertanian. Kami memahami bahwa benih berkualitas adalah fondasi utama keberhasilan panen.
            </p>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                <div class="flex items-start gap-3 border-2 border-gray-200 p-4 rounded-2xl bg-gray-50" data-aos="fade-up" data-aos-delay="100">
                    <div class="mt-1 shrink-0 w-5 h-5 rounded-full bg-green-500 flex items-center justify-center">
                        <i class="fa-solid fa-check text-[8px] text-white"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-slate-900 text-sm md:text-base">Seleksi Benih Unggul</h4>
                        <p class="text-xs text-gray-500">Daya tumbuh di atas 95%.</p>
                    </div>
                </div>
                <div class="flex items-start gap-3 border-2 border-gray-200 p-4 rounded-2xl bg-gray-50" data-aos="fade-up" data-aos-delay="200">
                    <div class="mt-1 shrink-0 w-5 h-5 rounded-full bg-green-500 flex items-center justify-center">
                        <i class="fa-solid fa-check text-[8px] text-white"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-slate-900 text-sm md:text-base">Distribusi Nasional</h4>
                        <p class="text-xs text-gray-500">Logistik menjangkau pelosok.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="services" class="relative py-16 md:py-24 bg-gray-200 px-4 sm:px-6">
    <div class="max-w-7xl mx-auto w-full">
        <div class="text-center max-w-2xl mx-auto mb-10 sm:mb-16" data-aos="fade-up">
            <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-slate-900 mb-3 sm:mb-4">Layanan Utama Kami</h2>
            <p class="text-gray-500 text-sm md:text-base">Mendukung ekosistem pertanian secara profesional.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 sm:gap-8">
            <?php $__currentLoopData = $services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="group bg-white p-6 sm:p-8 md:p-10 rounded-2xl sm:rounded-3xl shadow-sm hover:shadow-xl transition-all duration-300 border-2 border-gray-200" data-aos="zoom-in" data-aos-delay="<?php echo e($loop->index * 100); ?>">
                <div class="w-16 h-16 bg-green-50 rounded-2xl flex items-center justify-center text-green-600 text-2xl mb-8 group-hover:bg-green-600 group-hover:text-white transition-all duration-300">
                    <i class="fa-solid <?php echo e($service['icon']); ?>"></i>
                </div>
                <h3 class="font-bold text-xl text-slate-900 mb-4"><?php echo e($service['title']); ?></h3>
                <p class="text-gray-500 text-sm leading-relaxed"><?php echo e($service['desc']); ?></p>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</section>

<section id="faq" class="relative py-16 md:py-24 bg-gray-100 px-4 sm:px-6">
    <div class="max-w-7xl mx-auto flex flex-col lg:flex-row gap-10 lg:gap-20 w-full">
        <div class="w-full lg:w-1/3" data-aos="fade-up">
            <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-slate-900 mb-4 sm:mb-6">FAQ</h2>
            <p class="text-gray-500 mb-6 sm:mb-8 text-sm md:text-base">Kami merangkum hal yang sering ditanyakan oleh mitra kami.</p>
            <?php
                $adminUser = \App\Models\User::isAdmin(true)->first();
                $rawPhone = $adminUser?->noTelp ?: '08123456789';
                $cleanPhone = preg_replace('/[^0-9]/', '', $rawPhone);
                $waPhone = str_starts_with($cleanPhone, '0') ? '62' . substr($cleanPhone, 1) : $cleanPhone;
            ?>
            <div class="p-6 sm:p-8 bg-slate-900 rounded-2xl sm:rounded-3xl text-white shadow-xl">
                <p class="font-bold mb-2 text-green-400">Contact Us</p>
                <p class="text-xs opacity-70 mb-5">Tim support kami siap membantu Anda menjawab pertanyaan atau informasi lebih lanjut.</p>
                <div>
                    <a href="https://wa.me/<?php echo e($waPhone); ?>?text=Halo%20Admin%20Agris,%20saya%20butuh%20bantuan" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2.5 text-green-400 font-bold hover:text-green-300 transition-all text-sm">
                        <i class="fa-brands fa-whatsapp text-lg"></i> <?php echo e($rawPhone); ?>

                    </a>
                </div>
            </div>
        </div>

        <div class="w-full lg:w-2/3 space-y-4" data-aos="fade-up">
            <?php $__currentLoopData = $faqs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $faq): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="faq-item bg-white rounded-2xl border border-gray-200/90 shadow-xs hover:border-gray-300 transition-all duration-200 overflow-hidden">
                <button type="button" class="faq-btn w-full flex items-center justify-between p-5 md:p-6 text-left cursor-pointer transition-colors duration-200 select-none group focus:outline-none">
                    <span class="faq-question font-bold text-slate-800 text-sm md:text-base group-hover:text-slate-900 transition-colors duration-200 pr-4 leading-snug"><?php echo e($faq['q']); ?></span>
                    <div class="faq-icon-wrapper shrink-0 w-9 h-9 rounded-xl bg-gray-100 flex items-center justify-center text-slate-600 group-hover:bg-gray-200 transition-all duration-200">
                        <i class="fa-solid fa-chevron-down text-xs transition-transform duration-300"></i>
                    </div>
                </button>
                <div class="faq-content hidden px-5 md:px-6 pb-6 pt-2 text-slate-600 text-sm md:text-base leading-relaxed border-t border-gray-100/90 bg-slate-50/50">
                    <?php echo e($faq['a']); ?>

                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</section>

<?php if (isset($component)) { $__componentOriginal8a8716efb3c62a45938aca52e78e0322 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8a8716efb3c62a45938aca52e78e0322 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.footer','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('footer'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8a8716efb3c62a45938aca52e78e0322)): ?>
<?php $attributes = $__attributesOriginal8a8716efb3c62a45938aca52e78e0322; ?>
<?php unset($__attributesOriginal8a8716efb3c62a45938aca52e78e0322); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8a8716efb3c62a45938aca52e78e0322)): ?>
<?php $component = $__componentOriginal8a8716efb3c62a45938aca52e78e0322; ?>
<?php unset($__componentOriginal8a8716efb3c62a45938aca52e78e0322); ?>
<?php endif; ?>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    document.querySelectorAll(".faq-btn").forEach(btn => {
        btn.addEventListener("click", () => {
            const item = btn.closest('.faq-item');
            const content = item.querySelector('.faq-content');
            const icon = btn.querySelector('.fa-chevron-down');
            const iconWrapper = btn.querySelector('.faq-icon-wrapper');
            const question = btn.querySelector('.faq-question');

            const isCurrentlyHidden = content.classList.contains("hidden");

            // Close all other FAQ items for a clean accordion experience
            document.querySelectorAll(".faq-item").forEach(otherItem => {
                if (otherItem !== item) {
                    const otherContent = otherItem.querySelector('.faq-content');
                    const otherIcon = otherItem.querySelector('.fa-chevron-down');
                    const otherWrapper = otherItem.querySelector('.faq-icon-wrapper');

                    otherContent.classList.add("hidden");
                    if (otherIcon) otherIcon.style.transform = "rotate(0deg)";
                    if (otherWrapper) {
                        otherWrapper.classList.remove('bg-gray-200', 'text-slate-800');
                        otherWrapper.classList.add('bg-gray-100', 'text-slate-600');
                    }
                    otherItem.classList.remove('border-gray-300', 'bg-slate-50/30');
                }
            });

            if (isCurrentlyHidden) {
                content.classList.remove("hidden");
                icon.style.transform = "rotate(180deg)";
                iconWrapper.classList.remove('bg-gray-100', 'text-slate-600');
                iconWrapper.classList.add('bg-gray-200', 'text-slate-800');
                item.classList.add('border-gray-300', 'bg-slate-50/30');
            } else {
                content.classList.add("hidden");
                icon.style.transform = "rotate(0deg)";
                iconWrapper.classList.remove('bg-gray-200', 'text-slate-800');
                iconWrapper.classList.add('bg-gray-100', 'text-slate-600');
                item.classList.remove('border-gray-300', 'bg-slate-50/30');
            }
        });
    });
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\project\Agris\resources\views/guest/landing.blade.php ENDPATH**/ ?>