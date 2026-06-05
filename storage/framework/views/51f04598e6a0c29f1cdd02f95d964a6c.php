<?php $__env->startSection('title', 'Riwayat Transaksi - AGRIS'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-5xl mx-auto pt-5 pb-12 px-6">
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-800">Daftar Transaksi</h1>
        <p class="text-gray-500 text-sm">Pantau status pesanan dan riwayat belanja Anda</p>
    </div>

    <?php if($pesanans->isEmpty()): ?>
        <div class="py-24 text-center bg-white rounded-3xl border border-gray-150 shadow-sm">
            <i class="fa-solid fa-receipt text-5xl text-gray-200 mb-4"></i>
            <p class="text-gray-400 font-bold uppercase text-sm tracking-widest mb-4">Belum ada transaksi.</p>
            <a href="<?php echo e(route('agen.produk.index')); ?>" class="inline-block bg-[#58CC02] text-white px-6 py-2.5 rounded-xl font-bold text-sm hover:bg-[#46A302] transition">
                Mulai Belanja
            </a>
        </div>
    <?php else: ?>
        <div class="space-y-4">
            <?php $__currentLoopData = $pesanans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pesanan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 hover:shadow-md transition-shadow">
                    <!-- Head of Card -->
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 border-b border-gray-100 mb-4">
                        <div class="flex items-center gap-3">
                            <span class="text-xs font-bold text-gray-400 font-mono">ID: <?php echo e($pesanan->id); ?></span>
                            <span class="text-xs font-medium text-gray-400">•</span>
                            <span class="text-xs text-gray-500 font-bold"><?php echo e($pesanan->created_at->translatedFormat('d F Y H:i')); ?></span>
                        </div>
                        <div>
                            <?php if($pesanan->status === 'pending'): ?>
                                <span class="bg-amber-50 text-amber-600 border border-amber-100 px-3 py-1 rounded-full text-xs font-bold uppercase">Menunggu Pembayaran</span>
                            <?php elseif($pesanan->status === 'diproses'): ?>
                                <span class="bg-blue-50 text-blue-600 border border-blue-100 px-3 py-1 rounded-full text-xs font-bold uppercase">Diproses</span>
                            <?php elseif($pesanan->status === 'selesai'): ?>
                                <span class="bg-green-50 text-green-600 border border-green-100 px-3 py-1 rounded-full text-xs font-bold uppercase">Selesai</span>
                            <?php else: ?>
                                <span class="bg-red-50 text-red-600 border border-red-100 px-3 py-1 rounded-full text-xs font-bold uppercase">Dibatalkan</span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Body of Card (Products Summary) -->
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div class="grow min-w-0">
                            <?php $firstDetail = $pesanan->detailPesanans->first(); ?>
                            <?php if($firstDetail && $firstDetail->produk): ?>
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 bg-gray-50 rounded-xl overflow-hidden border border-gray-100 flex items-center justify-center p-1.5 shrink-0">
                                        <?php if($firstDetail->produk->fotoProduk): ?>
                                            <img src="<?php echo e(asset('storage/' . $firstDetail->produk->fotoProduk)); ?>" class="w-full h-full object-cover rounded-lg">
                                        <?php else: ?>
                                            <i class="fa-solid fa-image text-lg text-gray-300"></i>
                                        <?php endif; ?>
                                    </div>
                                    <div class="min-w-0">
                                        <h4 class="font-bold text-gray-800 text-sm truncate"><?php echo e($firstDetail->produk->namaProduk); ?></h4>
                                        <p class="text-xs text-gray-500 font-medium mt-0.5">
                                            <?php echo e($firstDetail->jumlahPesanan); ?> barang x Rp <?php echo e(number_format($firstDetail->harga_satuan, 0, ',', '.')); ?>

                                        </p>
                                        <?php if($pesanan->detailPesanans->count() > 1): ?>
                                            <p class="text-xs text-[#58CC02] font-bold mt-1">
                                                +<?php echo e($pesanan->detailPesanans->count() - 1); ?> produk lainnya
                                            </p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php else: ?>
                                <p class="text-gray-400 text-sm font-medium">Produk telah dihapus</p>
                            <?php endif; ?>
                        </div>

                        <!-- Divider on Mobile -->
                        <div class="border-t border-gray-100 md:hidden my-2"></div>

                        <!-- Price Summary & Action -->
                        <div class="flex items-center justify-between md:justify-end md:gap-8 shrink-0">
                            <div class="text-left md:text-right">
                                <span class="text-[10px] font-bold text-gray-400 block uppercase">Total Belanja</span>
                                <span class="font-bold text-gray-900 text-base">
                                    Rp <?php echo e(number_format($pesanan->total_harga, 0, ',', '.')); ?>

                                </span>
                            </div>
                            <div>
                                <a href="<?php echo e(route('agen.pesanan.show', $pesanan->id)); ?>" class="inline-block border border-gray-200 hover:border-[#58CC02] hover:text-[#58CC02] text-gray-700 bg-white px-5 py-2 rounded-xl text-xs font-bold transition">
                                    Detail Transaksi
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.agen', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\project\Agris\resources\views\agen\pesanan\index.blade.php ENDPATH**/ ?>