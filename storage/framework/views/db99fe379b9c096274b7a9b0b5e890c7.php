<?php $__env->startSection('title', 'Manajemen Produk - AGRIS'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto pt-2 pb-10 px-4 md:px-0">
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Daftar Produk</h1>
            <p class="text-gray-500 text-sm">Kelola stok berdasarkan inputan kategori admin</p>
        </div>
        <div class="flex gap-2 sm:gap-3">
            <a href="<?php echo e(route('admin.produk.trash')); ?>" class="flex-1 md:flex-none justify-center bg-gray-100 hover:bg-gray-200 text-gray-600 px-4 py-2.5 rounded-xl transition font-bold text-sm flex items-center">
                Stok Habis
            </a>
            <a href="<?php echo e(route('admin.produk.create')); ?>" class="flex-1 md:flex-none justify-center bg-[#58CC02] hover:bg-[#46a302] text-white px-5 py-2.5 rounded-xl transition shadow-md font-bold text-sm flex items-center">
                <i class="fa-solid fa-plus mr-2"></i> Tambah
            </a>
        </div>
    </div>

    <div class="bg-white p-4 md:p-5 rounded-2xl shadow-sm border border-gray-100 mb-8">
        <form action="<?php echo e(route('admin.produk.index')); ?>" method="GET" class="flex flex-col md:flex-row items-end gap-4">
            <div class="w-full md:flex-1">
                <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1 ml-1">Jenis</label>
                <select name="jenis" class="w-full px-4 py-2.5 rounded-xl border border-gray-100 bg-gray-50 outline-none focus:ring-2 focus:ring-[#58CC02] text-sm cursor-pointer appearance-none">
                    <option value="">Semua Jenis</option>
                    <?php $__currentLoopData = $daftarJenis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $j): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($j); ?>" <?php echo e(request('jenis') == $j ? 'selected' : ''); ?>><?php echo e(strtoupper($j)); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            <div class="w-full md:flex-1">
                <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1 ml-1">Mutu</label>
                <select name="mutu" class="w-full px-4 py-2.5 rounded-xl border border-gray-100 bg-gray-50 outline-none focus:ring-2 focus:ring-[#58CC02] text-sm cursor-pointer appearance-none">
                    <option value="">Semua Mutu</option>
                    <?php $__currentLoopData = $daftarMutu; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($m); ?>" <?php echo e(request('mutu') == $m ? 'selected' : ''); ?>>MUTU <?php echo e(strtoupper($m)); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            <div class="w-full md:flex-1">
                <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1 ml-1">Isi Karung</label>
                <select name="karung" class="w-full px-4 py-2.5 rounded-xl border border-gray-100 bg-gray-50 outline-none focus:ring-2 focus:ring-[#58CC02] text-sm cursor-pointer appearance-none">
                    <option value="">Semua Ukuran</option>
                    <?php $__currentLoopData = $daftarKarung; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($k); ?>" <?php echo e(request('karung') == $k ? 'selected' : ''); ?>><?php echo e($k); ?> Kg</option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            <div class="w-full md:w-auto">
                <button type="submit" class="w-full md:w-auto bg-gray-800 hover:bg-black text-white px-8 py-2.5 rounded-xl transition font-bold text-sm flex items-center justify-center shadow-sm">
                    <i class="fa-solid fa-filter mr-2"></i> Filter
                </button>
            </div>
        </form>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6">
        <?php $__empty_1 = true; $__currentLoopData = $produks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <a href="<?php echo e(route('admin.produk.show', $item->id)); ?>" class="group">
            <div class="bg-white rounded-3xl border border-gray-100 p-3 md:p-5 shadow-sm hover:shadow-md transition flex flex-col h-full relative">
                <div class="relative aspect-3/4 rounded-2xl overflow-hidden bg-gray-50 mb-4 flex items-center justify-center">
                    <?php if($item->fotoProduk): ?>
                        <img src="<?php echo e(asset('storage/' . $item->fotoProduk)); ?>" class="w-full h-full object-contain p-2 group-hover:scale-105 transition duration-500" alt="<?php echo e($item->namaProduk); ?>">
                    <?php else: ?>
                        <div class="flex items-center justify-center h-full text-gray-200">
                            <i class="fa-solid fa-image text-4xl"></i>
                        </div>
                    <?php endif; ?>

                    <?php if($item->stok <= 0): ?>
                        <div class="absolute inset-0 bg-black/40 flex items-center justify-center">
                            <span class="bg-red-500 text-white text-[10px] font-bold px-3 py-1 rounded-full uppercase">Stok Habis</span>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="flex flex-wrap gap-1.5 mb-2">
                    <span class="text-[10px] font-bold uppercase text-[#58CC02] bg-[#58CC02]/10 px-2 py-0.5 rounded-md">
                        <?php echo e($item->kategori->jenisKategori); ?>

                    </span>
                    <span class="text-[10px] font-bold uppercase text-blue-500 bg-blue-50 px-2 py-0.5 rounded-md">
                        <?php echo e($item->kategori->karung); ?> Kg
                    </span>
                    <span class="text-[10px] font-bold uppercase text-orange-400 bg-orange-50 px-2 py-0.5 rounded-md">
                        <?php echo e($item->kategori->mutu); ?>

                    </span>
                </div>

                <h3 class="font-bold text-gray-800 text-sm mb-3 line-clamp-2 leading-snug group-hover:text-[#58CC02] transition-colors"><?php echo e($item->namaProduk); ?></h3>

                <div class="mt-auto">
                    <p class="text-[#58CC02] font-bold text-xl mb-3">
                        Rp <?php echo e(number_format($item->harga, 0, ',', '.')); ?>

                    </p>
                    <div class="flex items-center justify-between pt-3 border-t border-gray-50">
                        <span class="text-[10px] font-bold <?php echo e($item->stok > 5 ? 'text-gray-500' : 'text-orange-500'); ?> uppercase tracking-tight">
                            Stok : <?php echo e($item->stok); ?>

                        </span>
                    </div>
                </div>
            </div>
        </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="col-span-full py-20 text-center bg-gray-50 rounded-3xl border-2 border-dashed border-gray-200">
                <i class="fa-solid fa-box-open text-5xl text-gray-200 mb-4"></i>
                <p class="text-gray-400 font-bold uppercase text-sm tracking-widest">Data tidak ditemukan.</p>
            </div>
        <?php endif; ?>
    </div>

    <div class="mt-10">
        <?php echo e($produks->links()); ?>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\project\Agris\resources\views\admin\produk\index.blade.php ENDPATH**/ ?>