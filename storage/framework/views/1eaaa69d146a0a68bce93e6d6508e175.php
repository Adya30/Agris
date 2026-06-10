<?php $__env->startSection('title', 'Laporan Keuangan - Admin AGRIS'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto pt-5 pb-12 px-4 sm:px-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900 tracking-tight">Laporan Keuangan</h1>
            <p class="text-gray-500 text-xs md:text-sm mt-1">Pantau ringkasan pemasukan dan pengeluaran transaksi secara real-time</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-3xl border border-gray-200 shadow-sm p-6 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-16 h-16 bg-gradient-to-bl from-green-50 to-transparent rounded-bl-full pointer-events-none"></div>
            <span class="text-[10px] font-black text-gray-400 uppercase tracking-wider block">Total Pemasukan</span>
            <span class="text-2xl font-black text-green-600 mt-2 block">Rp <?php echo e(number_format($totalPemasukan, 0, ',', '.')); ?></span>
        </div>
        <div class="bg-white rounded-3xl border border-gray-200 shadow-sm p-6 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-16 h-16 bg-gradient-to-bl from-red-50 to-transparent rounded-bl-full pointer-events-none"></div>
            <span class="text-[10px] font-black text-gray-400 uppercase tracking-wider block">Total Pengeluaran</span>
            <span class="text-2xl font-black text-red-600 mt-2 block">Rp <?php echo e(number_format($totalPengeluaran, 0, ',', '.')); ?></span>
        </div>
        <div class="bg-white rounded-3xl border border-gray-200 shadow-sm p-6 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-16 h-16 bg-gradient-to-bl from-blue-50 to-transparent rounded-bl-full pointer-events-none"></div>
            <span class="text-[10px] font-black text-gray-400 uppercase tracking-wider block">Saldo Neto</span>
            <span class="text-2xl font-black text-gray-900 mt-2 block">Rp <?php echo e(number_format($saldoNeto, 0, ',', '.')); ?></span>
        </div>
    </div>

    <div class="bg-white rounded-3xl border border-gray-200 shadow-sm overflow-hidden hidden md:block">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="py-4 px-6 text-xs font-black text-gray-400 uppercase tracking-wider">Tanggal</th>
                        <th class="py-4 px-6 text-xs font-black text-gray-400 uppercase tracking-wider">Tipe</th>
                        <th class="py-4 px-6 text-xs font-black text-gray-400 uppercase tracking-wider">Keterangan</th>
                        <th class="py-4 px-6 text-xs font-black text-gray-400 uppercase tracking-wider text-right">Nominal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm font-bold text-gray-700">
                    <?php $__empty_1 = true; $__currentLoopData = $riwayat; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="py-4 px-6 text-xs text-gray-500">
                                <?php echo e(\Carbon\Carbon::parse($item['tanggal'])->timezone('Asia/Jakarta')->locale('id')->translatedFormat('d M Y H:i')); ?> WIB
                            </td>
                            <td class="py-4 px-6">
                                <?php if($item['tipe'] === 'pemasukan'): ?>
                                    <span class="bg-green-50 text-green-600 border border-green-100 px-2.5 py-0.5 rounded-full text-[10px] uppercase font-black tracking-wide">Pemasukan</span>
                                <?php else: ?>
                                    <span class="bg-red-50 text-red-600 border border-red-100 px-2.5 py-0.5 rounded-full text-[10px] uppercase font-black tracking-wide">Pengeluaran</span>
                                <?php endif; ?>
                            </td>
                            <td class="py-4 px-6 text-xs text-gray-600">
                                <?php echo e($item['deskripsi']); ?>

                            </td>
                            <td class="py-4 px-6 text-right font-black <?php echo e($item['tipe'] === 'pemasukan' ? 'text-green-600' : 'text-red-600'); ?>">
                                <?php echo e($item['tipe'] === 'pemasukan' ? '+' : '-'); ?> Rp <?php echo e(number_format($item['nominal'], 0, ',', '.')); ?>

                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="4" class="py-16 text-center text-gray-400">
                                <i class="fa-solid fa-receipt text-4xl mb-3 block"></i>
                                Tidak ditemukan data riwayat keuangan.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="space-y-4 md:hidden">
        <?php $__empty_1 = true; $__currentLoopData = $riwayat; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="bg-white rounded-3xl border border-gray-200 shadow-sm p-5 space-y-3">
                <div class="flex justify-between items-center pb-2 border-b border-gray-100">
                    <span class="text-[10px] text-gray-400 font-bold">
                        <?php echo e(\Carbon\Carbon::parse($item['tanggal'])->timezone('Asia/Jakarta')->locale('id')->translatedFormat('d M Y H:i')); ?> WIB
                    </span>
                    <?php if($item['tipe'] === 'pemasukan'): ?>
                        <span class="bg-green-50 text-green-600 border border-green-100 px-2.5 py-0.5 rounded-full text-[9px] uppercase font-black tracking-wide">Pemasukan</span>
                    <?php else: ?>
                        <span class="bg-red-50 text-red-600 border border-red-100 px-2.5 py-0.5 rounded-full text-[9px] uppercase font-black tracking-wide">Pengeluaran</span>
                    <?php endif; ?>
                </div>
                <div class="text-xs text-gray-700">
                    <?php echo e($item['deskripsi']); ?>

                </div>
                <div class="text-right font-black text-sm <?php echo e($item['tipe'] === 'pemasukan' ? 'text-green-600' : 'text-red-600'); ?>">
                    <?php echo e($item['tipe'] === 'pemasukan' ? '+' : '-'); ?> Rp <?php echo e(number_format($item['nominal'], 0, ',', '.')); ?>

                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="bg-white rounded-3xl border border-gray-200 shadow-sm p-10 text-center text-gray-400">
                <i class="fa-solid fa-receipt text-4xl mb-3 block"></i>
                Tidak ditemukan data riwayat keuangan.
            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\project\Agris\resources\views\admin\laporan\index.blade.php ENDPATH**/ ?>