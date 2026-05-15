<?php $__env->startSection('title', 'Blog Admin - AGRIS'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto pt-4 pb-12 px-4 md:px-0">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-12 gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Blog</h1>
            <p class="text-gray-500 font-medium pt-1">Buat dan bagikan cerita Anda</p>
        </div>
        <a href="<?php echo e(route('admin.blog.create')); ?>" class="w-full md:w-auto bg-[#58CC02] hover:bg-[#46A302] text-white px-8 py-4 rounded-2xl font-bold transition-all flex items-center justify-center gap-3 shadow-sm">
            <i class="fa-solid fa-plus"></i> Buat Blog
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <?php $__empty_1 = true; $__currentLoopData = $blogs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $blog): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="group bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100 flex flex-col h-full">
            <div class="relative aspect-video w-full overflow-hidden bg-gray-50 flex items-center justify-center">
                <?php if($blog->fotoBlog): ?>
                    <img src="<?php echo e($blog->fotoBlog); ?>" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" alt="<?php echo e($blog->judulBlog); ?>">
                <?php else: ?>
                    <div class="flex items-center justify-center h-full text-gray-200">
                        <i class="fa-solid fa-image text-5xl"></i>
                    </div>
                <?php endif; ?>
                <a href="<?php echo e(route('admin.blog.show', $blog->id)); ?>" class="absolute inset-0 z-10"></a>
            </div>

            <div class="p-6 md:p-8 flex flex-col grow">
                <a href="<?php echo e(route('admin.blog.show', $blog->id)); ?>">
                    <h3 class="text-xl md:text-2xl font-bold text-gray-900 mb-4 line-clamp-2 leading-tight hover:text-[#58CC02] transition-colors">
                        <?php echo e($blog->judulBlog); ?>

                    </h3>
                </a>

                <div class="text-gray-500 text-sm md:text-base leading-relaxed line-clamp-3 mb-8 grow">
                    <?php echo e(strip_tags($blog->isiBlog)); ?>

                </div>

                <div class="pt-6 flex items-center justify-between border-t border-gray-50 mt-auto">
                    <div class="flex items-center gap-3">
                        <div class="h-10 w-10 overflow-hidden rounded-full border-2 border-white shadow-sm bg-gray-100 shrink-0">
                            <img src="<?php echo e($blog->user->fotoProfil ?? 'https://ui-avatars.com/api/?name='.urlencode($blog->user->username ?? 'Admin')); ?>"
                                 class="h-full w-full object-cover">
                        </div>
                        <div class="min-w-0">
                            <p class="text-xs font-bold text-gray-400 tracking-wider mb-0.5">Tanggal</p>
                            <p class="text-xs font-bold text-gray-900 truncate"><?php echo e($blog->tanggalBlog->format('d F Y')); ?></p>
                        </div>
                    </div>
                    <a href="<?php echo e(route('admin.blog.show', $blog->id)); ?>" class="w-10 h-10 rounded-xl bg-gray-50 flex items-center justify-center text-gray-400 group-hover:bg-[#58CC02] group-hover:text-white transition-all duration-300">
                        <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="col-span-full py-24 text-center bg-gray-50 rounded-[40px] border-2 border-dashed border-gray-200">
            <div class="mb-4">
                <i class="fa-solid fa-box-open text-5xl text-gray-200"></i>
            </div>
            <p class="text-gray-400 text-lg font-bold">Belum ada Blog yang dibuat.</p>
        </div>
        <?php endif; ?>
    </div>

    <div class="mt-16">
        <?php echo e($blogs->links()); ?>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\project\Agris\resources\views\admin\blog\index.blade.php ENDPATH**/ ?>