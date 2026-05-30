<?php $__env->startSection('title', 'Kontak Kami - PT Surya Kencana Agrifarm'); ?>

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
            Kontak
        </span>
        <h1 class="text-4xl md:text-5xl font-extrabold text-white mb-6 leading-tight">
            Kontak Kami
        </h1>
        <p class="text-gray-200 max-w-2xl mx-auto text-lg leading-relaxed">
           Informasi melalui kontak kami
        </p>
    </div>
</section>

<section class="pt-20 pb-20 bg-gray-100 px-6">
    <div class="max-w-7xl mx-auto">
        <div class="grid md:grid-cols-1 gap-16 items-center">
            <div>
                <h1 class="text-4xl font-bold text-gray-800 mb-6">Hubungi Kami</h1>
                <p class="text-gray-500 mb-10 text-lg">Ada pertanyaan atau ingin bekerja sama sebagai mitra? Tim kami siap membantu Anda dengan sepenuh hati.</p>

                <div class="space-y-6">
                    <div class="flex items-start gap-6 p-6 rounded-3xl border-2 border-gray-300 hover:border-[#58CC02]/30 hover:bg-[#58CC02]/5 transition-all duration-300">
                        <div class="w-14 h-14 bg-[#58CC02]/10 rounded-2xl flex items-center justify-center text-[#58CC02] shrink-0">
                            <i class="fa-solid fa-location-dot text-2xl"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-800 text-lg">Kantor Pusat</h4>
                            <p class="text-gray-500 capitalize">
                                <?php if($admin && (!empty($admin->detailAlamat) || !empty($admin->desa->namaDesa))): ?>
                                    <?php echo e(!empty($admin->detailAlamat) ? strtolower($admin->detailAlamat) . ',' : ''); ?>

                                    <?php echo e(!empty($admin->desa->namaDesa) ? strtolower($admin->desa->namaDesa) . ',' : ''); ?>

                                    <?php echo e(!empty($admin->desa->kecamatan->namaKecamatan) ? strtolower($admin->desa->kecamatan->namaKecamatan) . ',' : ''); ?>

                                    <?php echo e(!empty($admin->desa->kecamatan->kabupaten->namaKabupaten) ? strtolower($admin->desa->kecamatan->kabupaten->namaKabupaten) : ''); ?>

                                <?php else: ?>
                                    Jl. Raya Pertanian No. 123, Kabupaten Jember, Jawa Timur, Indonesia.
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>

                    <div class="flex items-start gap-6 p-6 rounded-3xl border-2 border-gray-300 hover:border-[#58CC02]/30 hover:bg-[#58CC02]/5 transition-all duration-300">
                        <div class="w-14 h-14 bg-[#58CC02]/10 rounded-2xl flex items-center justify-center text-[#58CC02] shrink-0">
                            <i class="fa-solid fa-phone text-2xl"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-800 text-lg">Telepon & WhatsApp</h4>
                            <p class="text-gray-500"><?php echo e($admin->noTelp ?? '+62 812-3456-7890'); ?></p>
                        </div>
                    </div>

                    <div class="flex items-start gap-6 p-6 rounded-3xl border-2 border-gray-300 hover:border-[#58CC02]/30 hover:bg-[#58CC02]/5 transition-all duration-300">
                        <div class="w-14 h-14 bg-[#58CC02]/10 rounded-2xl flex items-center justify-center text-[#58CC02] shrink-0">
                            <i class="fa-solid fa-envelope text-2xl"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-800 text-lg">Email Resmi</h4>
                            <p class="text-gray-500"><?php echo e($admin->email ?? 'agrisagroindustri@agris.co.id'); ?></p>
                        </div>
                    </div>
                </div>
            </div>
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

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\project\Agris\resources\views\guest\contact.blade.php ENDPATH**/ ?>