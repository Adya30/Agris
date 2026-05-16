<?php $__env->startSection('title', 'Tentang Kami - PT Surya Kencana Agrifarm'); ?>

<?php $__env->startSection('content'); ?>
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

<section class="relative pt-32 pb-20 px-6 overflow-hidden">
    <div class="absolute inset-0 z-0">
        <img src="<?php echo e(asset('images/hero.jpg')); ?>" class="w-full h-full object-cover" alt="Background">
        <div class="absolute inset-0 bg-black/60 backdrop-blur-[2px]"></div>
    </div>

    <div class="relative z-10 max-w-7xl mx-auto text-center">
        <span class="text-[#58CC02] font-bold tracking-widest uppercase text-sm mb-4 block">
            Mengenal Lebih Dekat
        </span>
        <h1 class="text-4xl md:text-5xl font-extrabold text-white mb-6 leading-tight">
            Membangun Masa Depan <br> Pertanian Indonesia
        </h1>
        <p class="text-gray-200 max-w-2xl mx-auto text-lg leading-relaxed">
            PT Surya Kencana Agrifarm berkomitmen menyediakan solusi hulu pertanian terbaik melalui benih unggul <span class="text-[#58CC02] font-bold">"Janger"</span> untuk kesejahteraan petani.
        </p>
    </div>
</section>

<section class="py-20 bg-white px-6">
    <div class="max-w-7xl mx-auto grid md:grid-cols-2 gap-16 items-center">
        <div>
            <img src="<?php echo e(asset('images/about.jpg')); ?>" class="rounded-3xl shadow-xl w-full h-112 object-cover">
        </div>
        <div class="space-y-8">
            <div>
                <h2 class="text-3xl font-bold text-gray-800 mb-4">Visi Kami</h2>
                <p class="text-gray-600 leading-relaxed">
                    Menjadi pemimpin pasar dalam penyediaan benih tanaman pangan berkualitas tinggi yang mampu beradaptasi dengan perubahan iklim dan memberikan hasil maksimal bagi petani di seluruh Nusantara.
                </p>
            </div>
            <div>
                <h2 class="text-3xl font-bold text-gray-800 mb-4">Misi Kami</h2>
                <ul class="space-y-4">
                    <li class="flex gap-4">
                        <div class="shrink-0 w-6 h-6 bg-[#58CC02] text-white rounded-full flex items-center justify-center text-xs">1</div>
                        <p class="text-gray-600">Mengembangkan riset benih yang tahan terhadap hama dan penyakit lokal.</p>
                    </li>
                    <li class="flex gap-4">
                        <div class="shrink-0 w-6 h-6 bg-[#58CC02] text-white rounded-full flex items-center justify-center text-xs">2</div>
                        <p class="text-gray-600">Membangun rantai distribusi yang efisien hingga ke pelosok desa.</p>
                    </li>
                    <li class="flex gap-4">
                        <div class="shrink-0 w-6 h-6 bg-[#58CC02] text-white rounded-full flex items-center justify-center text-xs">3</div>
                        <p class="text-gray-600">Memberikan edukasi dan pendampingan teknis kepada mitra tani kami.</p>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>

<section class="py-20 bg-gray-50 px-6">
    <div class="max-w-7xl mx-auto text-center mb-16">
        <h2 class="text-3xl font-bold text-gray-800">Nilai Inti Kami</h2>
    </div>
    <div class="grid md:grid-cols-3 gap-8 max-w-5xl mx-auto">
        <div class="text-center p-8 bg-white rounded-2xl shadow-sm border border-gray-100">
            <h3 class="font-bold text-xl mb-2">Inovasi</h3>
            <p class="text-gray-500 text-sm">Terus mengembangkan teknologi pembenihan terbaru.</p>
        </div>
        <div class="text-center p-8 bg-white rounded-2xl shadow-sm border border-gray-100">
            <h3 class="font-bold text-xl mb-2">Integritas</h3>
            <p class="text-gray-500 text-sm">Kejujuran dalam kualitas dan sertifikasi setiap benih.</p>
        </div>
        <div class="text-center p-8 bg-white rounded-2xl shadow-sm border border-gray-100">
            <h3 class="font-bold text-xl mb-2">Kualitas</h3>
            <p class="text-gray-500 text-sm">Hanya memberikan hasil seleksi benih terbaik bagi pasar.</p>
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

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\project\Agris\resources\views/guest/about.blade.php ENDPATH**/ ?>