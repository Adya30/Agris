<?php $__env->startSection('title', 'Blog - AGRIS'); ?>

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
            Blog
        </span>
        <h1 class="text-4xl md:text-5xl font-extrabold text-white mb-6 leading-tight">
            Informasi <br> Seputar Pertanian
        </h1>
        <p class="text-gray-200 max-w-2xl mx-auto text-lg leading-relaxed">
           Informasi seputar pertanian PT Surya Kencana Agrifarm Sejahtera
        </p>
    </div>
</section>

<section class="relative pt-10 pb-20 bg-gray-50 px-6">
    <div class="max-w-7xl mx-auto">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php $__empty_1 = true; $__currentLoopData = $blogs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $blog): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <a href="<?php echo e(route('guest.blog.show', $blog->id)); ?>" class="group bg-white rounded-4xl overflow-hidden shadow-sm border border-gray-100 flex flex-col">
                <div class="relative h-64 overflow-hidden">
                    <?php if($blog->fotoBlog): ?>
                        <img src="<?php echo e(asset('storage/' . $blog->fotoBlog)); ?>" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                    <?php else: ?>
                        <div class="w-full h-full bg-green-50 flex items-center justify-center text-green-200">
                            <i class="fa-solid fa-image text-5xl"></i>
                        </div>
                    <?php endif; ?>
                    <div class="absolute top-6 left-6">
                        <span class="px-4 py-2 bg-white/90 backdrop-blur-md text-slate-900 text-xs font-bold rounded-xl shadow-sm">
                            <?php echo e($blog->tanggalBlog->format('d M Y')); ?>

                        </span>
                    </div>
                </div>

                <div class="p-8 flex flex-col flex-1">
                    <h3 class="text-xl font-bold text-slate-900 mb-4 leading-tight group-hover:text-[#58CC02] transition-colors">
                        <?php echo e($blog->judulBlog); ?>

                    </h3>

                    <div class="text-gray-500 text-base leading-relaxed line-clamp-3 mb-10">
                        <?php echo e(Str::limit(strip_tags($blog->isiBlog), 90, '...')); ?>

                    </div>

                    <div class="mt-auto pt-6 flex items-center justify-between border-t border-gray-50">
                        <div class="flex items-center gap-3">
                            <div class="h-10 w-10 overflow-hidden rounded-full border border-gray-100 bg-gray-50 shadow-sm shrink-0">
                                <img src="<?php echo e($blog->user && $blog->user->fotoProfil ? asset($blog->user->fotoProfil) : 'https://ui-avatars.com/api/?name='.urlencode(($blog->user->username ?? $blog->user->email) ?? 'Admin').'&background=f0fdf4&color=166534'); ?>" class="h-full w-full object-cover">
                            </div>
                            <span class="text-sm font-bold text-slate-700"><?php echo e($blog->user->username ?? 'Admin'); ?></span>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-gray-50 flex items-center justify-center text-slate-400 group-hover:bg-[#58CC02] group-hover:text-white transition-all">
                            <i class="fa-solid fa-arrow-right text-xs"></i>
                        </div>
                    </div>
                </div>
            </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="col-span-full py-24 text-center bg-white rounded-[40px] border-2 border-dashed border-gray-200">
                <i class="fa-solid fa-box-open text-6xl text-gray-200 mb-6"></i>
                <p class="text-gray-400 text-lg font-bold">Belum ada blog yang diterbitkan.</p>
            </div>
            <?php endif; ?>
        </div>

        <div class="mt-16">
            <?php echo e($blogs->links()); ?>

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

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\project\Agris\resources\views/guest/blog/index.blade.php ENDPATH**/ ?>