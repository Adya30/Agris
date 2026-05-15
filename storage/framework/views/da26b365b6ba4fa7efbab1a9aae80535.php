<?php $__env->startSection('title', $blog->judulBlog . ' - AGRIS'); ?>

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

<section class="relative pt-32 pb-24 bg-white px-6">
    <div class="max-w-4xl mx-auto">
        <div class="flex items-center gap-2 mb-5">
            <div class="flex justify-between">
                <a href="<?php echo e(route('guest.blog.index')); ?>" class="w-12 h-12 rounded-2xl border border-gray-100 flex items-center justify-center text-gray-400 hover:text-[#58CC02] bg-white shadow-sm transition-all">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
            </div>
            <span class="font-bold text-2xl items-center">Detail Blog</span>
        </div>

        <div class="bg-white rounded-[48px] border border-gray-100 shadow-sm overflow-hidden">
            <?php if($blog->fotoBlog): ?>
                <div class="w-full h-100 overflow-hidden">
                    <img src="<?php echo e($blog->fotoBlog); ?>" class="w-full h-full object-cover">
                </div>
            <?php endif; ?>

            <div class="p-12">
                <div class="flex items-center gap-4 mb-8">
                    <div class="w-12 h-12 rounded-full bg-[#0f8629] flex items-center justify-center text-white font-bold">
                        <img src="<?php echo e($blog->user->fotoProfil ?? 'https://ui-avatars.com/api/?name='.urlencode($blog->user->username ?? 'Admin')); ?>" class="h-full w-full object-cover rounded-full">
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-900"><?php echo e($blog->user->name ?? 'Admin'); ?></h4>
                        <p class="text-xs text-gray-400 font-bold uppercase tracking-widest"><?php echo e($blog->tanggalBlog->format('d F Y')); ?></p>
                    </div>
                </div>

                <h1 class="text-4xl font-bold text-gray-900 leading-tight mb-8"><?php echo e($blog->judulBlog); ?></h1>

                <div class="prose prose-lg prose-green max-w-none text-gray-600 leading-relaxed">
                    <?php
                        $urlPattern = '/(https?:\/\/[^\s]+)/';
                        $contentWithLinks = preg_replace(
                            $urlPattern,
                            '<a href="$1" target="_blank" class="text-[#58CC02] hover:underline font-bold transition-all">$1</a>',
                            e($blog->isiBlog)
                        );
                    ?>
                    <?php echo nl2br($contentWithLinks); ?>

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

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\project\Agris\resources\views\guest\blog\show.blade.php ENDPATH**/ ?>