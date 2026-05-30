<?php $__env->startSection('title', $item->namaProduk . ' - Detail Produk'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto pt-5 pb-12 px-6">
    <div class="flex items-center gap-3 pb-5">
        <a href="<?php echo e(route('agen.produk.index')); ?>" class="w-10 h-10 flex items-center justify-center rounded-full bg-white border border-gray-200 text-gray-600 hover:bg-gray-50 transition shadow-sm">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <h1 class="text-xl font-bold text-gray-800">Detail Produk</h1>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="grid grid-cols-1 md:grid-cols-2">
            <div class="p-6 md:p-10 bg-gray-50 flex items-center justify-center">
                <div class="relative w-full rounded-2xl overflow-hidden shadow-lg bg-white">
                    <?php if($item->fotoProduk): ?>
                        <img src="<?php echo e(asset('storage/' . $item->fotoProduk)); ?>" class="w-full h-full object-cover group-hover:scale-105 transition duration-500" alt="<?php echo e($item->namaProduk); ?>">
                    <?php else: ?>
                        <div class="flex flex-col items-center justify-center aspect-square text-gray-300">
                            <i class="fa-solid fa-image text-8xl mb-4"></i>
                            <p class="font-bold">Foto tidak tersedia</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="p-8 md:p-12 flex flex-col">
                <div class="mb-6">
                    <span class="text-xs font-black uppercase tracking-widest text-[#58CC02] bg-[#58CC02]/10 px-3 py-1 rounded-full mb-4 inline-block">
                        <?php echo e($item->kategori->jenisKategori); ?>

                    </span>
                    <h1 class="text-3xl md:text-4xl font-black text-gray-900 leading-tight mb-2"><?php echo e($item->namaProduk); ?></h1>
                    <p class="text-2xl font-bold text-[#58CC02]">Rp <?php echo e(number_format($item->harga, 0, ',', '.')); ?> <span class="text-sm text-gray-400 font-medium">/ Karung</span></p>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-8">
                    <div class="bg-gray-50 p-4 rounded-2xl border border-gray-100">
                        <p class="text-[10px] font-bold text-gray-400 uppercase mb-1">Mutu Produk</p>
                        <p class="font-bold text-gray-800"><?php echo e(strtoupper($item->kategori->mutu)); ?></p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-2xl border border-gray-100">
                        <p class="text-[10px] font-bold text-gray-400 uppercase mb-1">Ukuran Karung</p>
                        <p class="font-bold text-gray-800"><?php echo e($item->kategori->karung); ?> Kg</p>
                    </div>
                </div>

                <div class="mb-8">
                    <h4 class="font-black text-gray-800 uppercase text-xs tracking-wider mb-3">Deskripsi Produk</h4>
                    <div class="text-gray-600 leading-relaxed text-sm space-y-4">
                        <?php echo nl2br(e($item->deskripsi ?? 'Belum ada deskripsi untuk produk ini.')); ?>

                    </div>
                </div>

                <div class="mt-auto pt-8 border-t border-gray-100 flex items-center justify-between gap-6">
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase">Stok Tersedia</p>
                        <p class="text-lg font-black <?php echo e($item->stok > 0 ? 'text-gray-800' : 'text-red-500'); ?>">
                            <?php echo e($item->stok); ?> <span class="text-xs font-bold text-gray-400">Karung</span>
                        </p>
                    </div>

                    <button class="flex-1 bg-[#58CC02] hover:bg-[#46a302] text-white py-4 rounded-2xl transition-all shadow-lg shadow-[#58CC02]/20 font-black flex items-center justify-center gap-3">
                        <i class="fa-solid fa-cart-plus"></i>
                        Tambah Pesanan
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.agen', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\project\Agris\resources\views\agen\produk\show.blade.php ENDPATH**/ ?>