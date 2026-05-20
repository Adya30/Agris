<?php $__env->startSection('title', 'Detail Blog - AGRIS'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-4xl mx-auto pt-4 pb-12 px-4">
    <div class="mb-8 flex items-center justify-between gap-4">
        <div class="flex items-center">
            <a href="<?php echo e(route('admin.blog.index')); ?>" class="w-10 h-10 rounded-full border border-gray-200 flex items-center justify-center text-gray-400 bg-white shadow-sm transition-all">
                <i class="fa-solid fa-arrow-left text-sm"></i>
            </a>
            <span class="text-2xl pl-3 font-bold text-gray-800">Detail Blog</span>
        </div>

        <div class="flex gap-2">
            <a href="<?php echo e(route('admin.blog.edit', $blog->id)); ?>" class="bg-blue-500 text-white px-4 py-2.5 rounded-xl font-bold flex items-center gap-2 hover:bg-blue-600 transition-all text-sm">
               <i class="fa-solid fa-pen-to-square text-xs"></i> Edit
            </a>
            <button type="button" onclick="openModal('modalHapus')" class="bg-red-500 text-white px-4 py-2.5 rounded-xl font-bold flex items-center gap-2 hover:bg-red-600 transition-all text-sm">
                <i class="fa-solid fa-trash text-xs"></i> Hapus
            </button>
        </div>
    </div>

    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
        <?php if($blog->fotoBlog): ?>
            <div class="w-full aspect-video max-h-100 overflow-hidden bg-gray-50">
                <img src="<?php echo e(asset('storage/' . $blog->fotoBlog)); ?>" class="w-full h-full object-cover">
            </div>
        <?php endif; ?>

        <div class="p-6 md:p-8">
            <div class="flex items-center gap-3 mb-6">
                <div class="h-8 w-8 overflow-hidden rounded-full border-2 border-white shadow-sm bg-gray-100 shrink-0">
                    <img src="<?php echo e($blog->user && $blog->user->fotoProfil ? asset($blog->user->fotoProfil) : 'https://ui-avatars.com/api/?name='.urlencode(($blog->user->username ?? $blog->user->email) ?? 'Admin')); ?>" class="h-full w-full object-cover">
                </div>
                <div class="min-w-0">
                    <p class="text-[9px] font-black uppercase tracking-widest text-gray-400 mb-0.5">Tanggal Upload</p>
                    <p class="text-xs font-bold text-gray-700 truncate"><?php echo e($blog->tanggalBlog->format('d M Y')); ?></p>
                </div>
            </div>

            <h1 class="text-xl md:text-2xl font-bold text-gray-800 leading-snug mb-6"><?php echo e($blog->judulBlog); ?></h1>

            <div class="prose prose-sm md:prose-base max-w-none text-gray-600 leading-relaxed">
                <?php
                    $urlPattern = '/(https?:\/\/[^\s]+)/';
                    $contentWithLinks = preg_replace(
                        $urlPattern,
                        '<a href="$1" target="_blank" class="text-blue-500 hover:underline font-bold transition-all">$1</a>',
                        e($blog->isiBlog)
                    );
                ?>
                <?php echo nl2br($contentWithLinks); ?>

            </div>
        </div>
    </div>
</div>

<?php if (isset($component)) { $__componentOriginal9f64f32e90b9102968f2bc548315018c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9f64f32e90b9102968f2bc548315018c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.modal','data' => ['id' => 'modalHapus','title' => 'Hapus Blog?','message' => 'Yakin ingin menghapus blog?','confirmText' => 'Iya','cancelText' => 'Batal','confirmId' => 'btnConfirmDelete','cancelId' => 'btnCancelDelete']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('modal'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'modalHapus','title' => 'Hapus Blog?','message' => 'Yakin ingin menghapus blog?','confirmText' => 'Iya','cancelText' => 'Batal','confirmId' => 'btnConfirmDelete','cancelId' => 'btnCancelDelete']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9f64f32e90b9102968f2bc548315018c)): ?>
<?php $attributes = $__attributesOriginal9f64f32e90b9102968f2bc548315018c; ?>
<?php unset($__attributesOriginal9f64f32e90b9102968f2bc548315018c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9f64f32e90b9102968f2bc548315018c)): ?>
<?php $component = $__componentOriginal9f64f32e90b9102968f2bc548315018c; ?>
<?php unset($__componentOriginal9f64f32e90b9102968f2bc548315018c); ?>
<?php endif; ?>

<form id="delete-form" action="<?php echo e(route('admin.blog.destroy', $blog->id)); ?>" method="POST" class="hidden">
    <?php echo csrf_field(); ?>
    <?php echo method_field('DELETE'); ?>
</form>

<script>
    document.getElementById('btnConfirmDelete').addEventListener('click', function() {
        this.disabled = true;
        document.getElementById('delete-form').submit();
    });

    document.getElementById('btnCancelDelete').addEventListener('click', function() {
        closeModal('modalHapus');
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\project\Agris\resources\views\admin\blog\show.blade.php ENDPATH**/ ?>