<?php $__env->startSection('title', 'Manajemen Transaksi - Admin AGRIS'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto pt-5 pb-12">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900 tracking-tight">Manajemen Transaksi</h1>
            <p class="text-gray-500 text-xs md:text-sm mt-1">Kelola pesanan, pengiriman, dan status pembayaran pelanggan</p>
        </div>
    </div>

    <!-- Filter & Search Board -->
    <div class="bg-white rounded-3xl border border-gray-200 shadow-sm p-5 md:p-6 mb-8">
        <form action="<?php echo e(route('admin.pesanan.index')); ?>" method="GET" class="flex flex-col md:flex-row gap-4 items-center">
            <!-- Search field -->
            <div class="relative flex-1 w-full">
                <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </span>
                <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Cari ID Pesanan, Nama Pelanggan..." 
                    class="w-full pl-11 pr-4 py-3 rounded-2xl border-gray-200 text-xs md:text-sm font-bold text-gray-700 focus:border-[#0f8629] focus:ring-0 shadow-sm transition">
            </div>

            <!-- Status dropdown filter -->
            <div class="w-full md:w-52">
                <select name="status" onchange="this.form.submit()" 
                    class="w-full rounded-2xl border-gray-200 py-3 px-4 text-xs md:text-sm font-bold text-gray-700 focus:border-[#0f8629] focus:ring-0 shadow-sm transition">
                    <option value="all" <?php echo e(request('status') === 'all' ? 'selected' : ''); ?>>Semua Status</option>
                    <option value="pending" <?php echo e(request('status') === 'pending' ? 'selected' : ''); ?>>Belum Bayar (Pending)</option>
                    <option value="diproses" <?php echo e(request('status') === 'diproses' ? 'selected' : ''); ?>>Dikemas (Diproses)</option>
                    <option value="dikirim" <?php echo e(request('status') === 'dikirim' ? 'selected' : ''); ?>>Dikirim</option>
                    <option value="selesai" <?php echo e(request('status') === 'selesai' ? 'selected' : ''); ?>>Selesai</option>
                    <option value="dibatalkan" <?php echo e(request('status') === 'dibatalkan' ? 'selected' : ''); ?>>Dibatalkan</option>
                </select>
            </div>

            <div class="flex gap-2 w-full md:w-auto">
                <button type="submit" class="flex-1 md:flex-none bg-[#0f8629] hover:bg-[#0c6b20] text-white px-6 py-3 rounded-2xl font-black text-xs md:text-sm transition shadow-sm">
                    Filter
                </button>
                
                <?php if(request()->anyFilled(['search', 'status'])): ?>
                    <a href="<?php echo e(route('admin.pesanan.index')); ?>" class="flex-1 md:flex-none text-center border border-gray-200 hover:bg-gray-50 text-gray-600 px-6 py-3 rounded-2xl font-bold text-[10px] md:text-xs transition flex items-center justify-center">
                        Reset
                    </a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <!-- Data Table (Desktop) -->
    <div class="bg-white rounded-3xl border border-gray-200 shadow-sm overflow-hidden hidden md:block">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="py-4 px-6 text-xs font-black text-gray-400 uppercase tracking-wider">ID Pesanan</th>
                        <th class="py-4 px-6 text-xs font-black text-gray-400 uppercase tracking-wider">Pelanggan</th>
                        <th class="py-4 px-6 text-xs font-black text-gray-400 uppercase tracking-wider">Tanggal</th>
                        <th class="py-4 px-6 text-xs font-black text-gray-400 uppercase tracking-wider">Total Tagihan</th>
                        <th class="py-4 px-6 text-xs font-black text-gray-400 uppercase tracking-wider">Opsi Pengiriman</th>
                        <th class="py-4 px-6 text-xs font-black text-gray-400 uppercase tracking-wider">Status Order</th>
                        <th class="py-4 px-6 text-xs font-black text-gray-400 uppercase tracking-wider">Status Bayar</th>
                        <th class="py-4 px-6 text-xs font-black text-gray-400 uppercase tracking-wider text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm font-bold text-gray-700">
                    <?php $__empty_1 = true; $__currentLoopData = $pesanans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pesanan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="py-4 px-6 font-mono text-xs font-black text-gray-500">
                                <?php echo e($pesanan->id); ?>

                            </td>
                            <td class="py-4 px-6">
                                <div class="flex flex-col">
                                    <span><?php echo e($pesanan->user->namaLengkap ?? 'Dihapus'); ?></span>
                                    <span class="text-xs text-gray-400 font-bold mt-0.5"><?php echo e('@' . ($pesanan->user->username ?? 'user')); ?></span>
                                </div>
                            </td>
                            <td class="py-4 px-6 text-xs text-gray-500">
                                <?php echo e(\Carbon\Carbon::parse($pesanan->created_at)->timezone('Asia/Jakarta')->locale('id')->translatedFormat('d M Y H:i')); ?> WIB
                            </td>
                            <td class="py-4 px-6 text-gray-900">
                                Rp <?php echo e(number_format($pesanan->total_harga, 0, ',', '.')); ?>

                            </td>
                            <td class="py-4 px-6 text-xs text-gray-500 max-w-xs truncate">
                                <?php echo e($pesanan->deskripsi); ?>

                            </td>
                            <td class="py-4 px-6">
                                <?php if($pesanan->status_pesanan === 'pending'): ?>
                                    <span class="bg-amber-50 text-amber-600 border border-amber-100 px-2.5 py-0.5 rounded-full text-[10px] uppercase font-black tracking-wide">Menunggu</span>
                                <?php elseif($pesanan->status_pesanan === 'diproses'): ?>
                                    <span class="bg-blue-50 text-blue-600 border border-blue-100 px-2.5 py-0.5 rounded-full text-[10px] uppercase font-black tracking-wide">Dikemas</span>
                                <?php elseif($pesanan->status_pesanan === 'dikirim'): ?>
                                    <span class="bg-purple-50 text-purple-600 border border-purple-100 px-2.5 py-0.5 rounded-full text-[10px] uppercase font-black tracking-wide">Dikirim</span>
                                <?php elseif($pesanan->status_pesanan === 'selesai'): ?>
                                    <span class="bg-green-50 text-green-600 border border-green-100 px-2.5 py-0.5 rounded-full text-[10px] uppercase font-black tracking-wide">Selesai</span>
                                <?php else: ?>
                                    <span class="bg-red-50 text-red-600 border border-red-100 px-2.5 py-0.5 rounded-full text-[10px] uppercase font-black tracking-wide">Batal</span>
                                <?php endif; ?>
                            </td>
                            <td class="py-4 px-6">
                                <?php if($pesanan->pembayaran): ?>
                                    <?php if($pesanan->pembayaran->statusPembayaran === 'berhasil'): ?>
                                        <span class="text-green-600 text-xs flex items-center gap-1">
                                            <i class="fa-solid fa-circle-check"></i> Lunas
                                        </span>
                                    <?php elseif($pesanan->pembayaran->statusPembayaran === 'pending'): ?>
                                        <span class="text-amber-600 text-xs flex items-center gap-1">
                                            <i class="fa-solid fa-clock"></i> Pending
                                        </span>
                                    <?php else: ?>
                                        <span class="text-red-600 text-xs flex items-center gap-1">
                                            <i class="fa-solid fa-circle-xmark"></i> Gagal
                                        </span>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span class="text-gray-400 text-xs">-</span>
                                <?php endif; ?>
                            </td>
                            <td class="py-4 px-6 text-center">
                                <a href="<?php echo e(route('admin.pesanan.show', $pesanan->id)); ?>" class="inline-block bg-[#0f8629] hover:bg-[#0c6b20] text-white px-4 py-2 rounded-xl text-xs font-black transition shadow-sm">
                                    Respon
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="8" class="py-16 text-center text-gray-400">
                                <i class="fa-solid fa-box-open text-4xl mb-3 block"></i>
                                Tidak ditemukan data transaksi.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if($pesanans->hasPages()): ?>
            <div class="bg-gray-50 px-6 py-4 border-t border-gray-100">
                <?php echo e($pesanans->links()); ?>

            </div>
        <?php endif; ?>
    </div>

    <!-- Mobile Card View -->
    <div class="space-y-4 md:hidden">
        <?php $__empty_1 = true; $__currentLoopData = $pesanans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pesanan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="bg-white rounded-3xl border border-gray-200 shadow-sm p-5 space-y-4">
                <!-- ID & Date -->
                <div class="flex justify-between items-center pb-3 border-b border-gray-100">
                    <span class="font-mono text-xs font-black text-gray-500">#<?php echo e($pesanan->id); ?></span>
                    <span class="text-[10px] text-gray-400 font-bold">
                        <?php echo e(\Carbon\Carbon::parse($pesanan->created_at)->timezone('Asia/Jakarta')->locale('id')->translatedFormat('d M Y H:i')); ?> WIB
                    </span>
                </div>

                <!-- Customer and Shipping -->
                <div class="grid grid-cols-2 gap-3 text-xs">
                    <div>
                        <span class="text-[9px] text-gray-400 font-black uppercase tracking-wider block mb-0.5">Pelanggan</span>
                        <div class="flex flex-col">
                            <span class="font-bold text-gray-800 text-xs"><?php echo e($pesanan->user->namaLengkap ?? 'Dihapus'); ?></span>
                            <span class="text-[10px] text-gray-400">@​<?php echo e($pesanan->user->username ?? 'user'); ?></span>
                        </div>
                    </div>
                    <div>
                        <span class="text-[9px] text-gray-400 font-black uppercase tracking-wider block mb-0.5">Pengiriman</span>
                        <span class="font-medium text-gray-700 text-[11px] truncate block"><?php echo e($pesanan->deskripsi); ?></span>
                    </div>
                </div>

                <!-- Bill and Badges -->
                <div class="grid grid-cols-2 gap-3 pt-2">
                    <div>
                        <span class="text-[9px] text-gray-400 font-black uppercase tracking-wider block mb-0.5">Total Tagihan</span>
                        <span class="font-black text-gray-900 text-xs">Rp <?php echo e(number_format($pesanan->total_harga, 0, ',', '.')); ?></span>
                    </div>
                    <div class="space-y-1">
                        <span class="text-[9px] text-gray-400 font-black uppercase tracking-wider block mb-0.5">Status</span>
                        <div class="flex flex-wrap gap-1">
                            <!-- Order Status Badge -->
                            <?php if($pesanan->status_pesanan === 'pending'): ?>
                                <span class="bg-amber-50 text-amber-600 border border-amber-100 px-2 py-0.5 rounded-full text-[9px] uppercase font-black tracking-wide">Menunggu</span>
                            <?php elseif($pesanan->status_pesanan === 'diproses'): ?>
                                <span class="bg-blue-50 text-blue-600 border border-blue-100 px-2 py-0.5 rounded-full text-[9px] uppercase font-black tracking-wide">Dikemas</span>
                            <?php elseif($pesanan->status_pesanan === 'dikirim'): ?>
                                <span class="bg-purple-50 text-purple-600 border border-purple-100 px-2 py-0.5 rounded-full text-[9px] uppercase font-black tracking-wide">Dikirim</span>
                            <?php elseif($pesanan->status_pesanan === 'selesai'): ?>
                                <span class="bg-green-50 text-green-600 border border-green-100 px-2 py-0.5 rounded-full text-[9px] uppercase font-black tracking-wide">Selesai</span>
                            <?php else: ?>
                                <span class="bg-red-50 text-red-600 border border-red-100 px-2 py-0.5 rounded-full text-[9px] uppercase font-black tracking-wide">Batal</span>
                            <?php endif; ?>

                            <!-- Payment Status Badge -->
                            <?php if($pesanan->pembayaran): ?>
                                <?php if($pesanan->pembayaran->statusPembayaran === 'berhasil'): ?>
                                    <span class="bg-green-50 text-green-700 border border-green-100 px-2 py-0.5 rounded-full text-[9px] uppercase font-black tracking-wide">Lunas</span>
                                <?php elseif($pesanan->pembayaran->statusPembayaran === 'pending'): ?>
                                    <span class="bg-amber-50 text-amber-700 border border-amber-100 px-2 py-0.5 rounded-full text-[9px] uppercase font-black tracking-wide">Pending</span>
                                <?php else: ?>
                                    <span class="bg-red-50 text-red-700 border border-red-100 px-2 py-0.5 rounded-full text-[9px] uppercase font-black tracking-wide">Gagal</span>
                                <?php endif; ?>
                            <?php else: ?>
                                <span class="bg-gray-50 text-gray-500 border border-gray-200 px-2 py-0.5 rounded-full text-[9px] uppercase font-black tracking-wide">-</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Action Button -->
                <div class="pt-3 border-t border-gray-100 flex justify-end">
                    <a href="<?php echo e(route('admin.pesanan.show', $pesanan->id)); ?>" class="w-full text-center bg-[#0f8629] hover:bg-[#0c6b20] text-white py-2.5 rounded-2xl text-xs font-black transition shadow-sm">
                        Respon
                    </a>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="bg-white rounded-3xl border border-gray-200 shadow-sm p-10 text-center text-gray-400">
                <i class="fa-solid fa-box-open text-4xl mb-3 block"></i>
                Tidak ditemukan data transaksi.
            </div>
        <?php endif; ?>

        <?php if($pesanans->hasPages()): ?>
            <div class="bg-white p-4 rounded-3xl border border-gray-200 shadow-sm mt-4">
                <?php echo e($pesanans->links()); ?>

            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\project\Agris\resources\views\admin\pesanan\index.blade.php ENDPATH**/ ?>