<?php $__env->startSection('title', 'Detail Pesanan - AGRIS'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-4xl mx-auto pb-10 px-4">
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <a href="<?php echo e(route('agen.pesanan.index')); ?>" class="text-xs font-bold text-gray-400 hover:text-gray-600 flex items-center gap-1.5 mb-2 uppercase">
                <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar
            </a>
            <h1 class="text-2xl font-bold text-gray-800">Detail Pesanan</h1>
            <p class="text-gray-500 text-sm">ID Pesanan: <span class="font-mono text-gray-700 font-bold"><?php echo e($pesanan->id); ?></span></p>
        </div>

        <div>
            <?php if($pesanan->status === 'pending'): ?>
                <span class="bg-amber-50 text-amber-600 border border-amber-100 px-4 py-1.5 rounded-full text-xs font-bold uppercase">Menunggu Konfirmasi</span>
            <?php elseif($pesanan->status === 'diproses'): ?>
                <span class="bg-blue-50 text-blue-600 border border-blue-100 px-4 py-1.5 rounded-full text-xs font-bold uppercase">Sedang Diproses</span>
            <?php elseif($pesanan->status === 'selesai'): ?>
                <span class="bg-green-50 text-green-600 border border-green-100 px-4 py-1.5 rounded-full text-xs font-bold uppercase">Selesai</span>
            <?php else: ?>
                <span class="bg-red-50 text-red-600 border border-red-100 px-4 py-1.5 rounded-full text-xs font-bold uppercase">Dibatalkan</span>
            <?php endif; ?>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div class="md:col-span-2 space-y-4">
            <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
                <h2 class="font-bold text-gray-800 text-sm mb-4 uppercase tracking-wider text-gray-400">Daftar Produk</h2>
                <div class="divide-y divide-gray-50">
                    <?php $__currentLoopData = $pesanan->detailPesanan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="flex items-center gap-4 py-4 first:pt-0 last:pb-0">
                            <div class="w-14 h-14 bg-gray-50 rounded-xl overflow-hidden flex items-center justify-center p-1.5 shrink-0">
                                <?php if($detail->produk && $detail->produk->fotoProduk): ?>
                                    <img src="<?php echo e(asset('storage/' . $detail->produk->fotoProduk)); ?>" class="w-full h-full object-contain">
                                <?php else: ?>
                                    <i class="fa-solid fa-image text-xl text-gray-300"></i>
                                <?php endif; ?>
                            </div>

                            <div class="grow">
                                <h4 class="font-bold text-gray-800 text-sm line-clamp-1">
                                    <?php echo e($detail->produk ? $detail->produk->namaProduk : 'Produk Telah Dihapus'); ?>

                                </h4>
                                <p class="text-xs text-gray-400 font-medium mt-0.5">
                                    <?php echo e($detail->jumlahPesanan); ?> barang x Rp <?php echo e(number_format($detail->harga_satuan, 0, ',', '.')); ?>

                                </p>
                            </div>

                            <div class="text-right shrink-0">
                                <span class="font-bold text-gray-800 text-sm">
                                    Rp <?php echo e(number_format($detail->subtotal, 0, ',', '.')); ?>

                                </span>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>

        <div class="space-y-4">
            <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
                <h2 class="font-bold text-gray-800 text-sm mb-4 uppercase tracking-wider text-gray-400">Informasi Pembayaran</h2>

                <div class="space-y-3 pb-3 border-b border-gray-50">
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-gray-400 font-medium">Tanggal Selesai</span>
                        <span class="text-gray-700 font-bold text-xs"><?php echo e($pesanan->created_at->translatedFormat('d F Y H:i')); ?></span>
                    </div>
                </div>

                <div class="flex justify-between items-center pt-3">
                    <span class="text-gray-800 font-bold text-sm">Total Bayar</span>
                    <span class="text-lg font-bold text-[#58CC02]">
                        Rp <?php echo e(number_format($pesanan->total_harga, 0, ',', '.')); ?>

                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.agen', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\project\Agris\resources\views\agen\pesanan\show.blade.php ENDPATH**/ ?>