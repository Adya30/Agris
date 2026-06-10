<?php $__env->startSection('title', 'Manajemen Transaksi - Admin AGRIS'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto pt-2 pb-10 px-4 md:px-0">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Daftar Transaksi</h1>
            <p class="text-gray-500 text-sm">Kelola pesanan, pengiriman, dan status pembayaran pelanggan</p>
        </div>
    </div>

    <!-- Tabs -->
    <div class="flex border-b border-gray-200 mb-6 gap-2 text-xs md:text-sm font-bold">
        <a href="<?php echo e(route('admin.pesanan.index', ['tab' => 'aktif'])); ?>" 
            class="pb-2.5 px-4 transition-all relative <?php echo e($activeTab === 'aktif' ? 'text-gray-950 after:absolute after:bottom-0 after:left-0 after:w-full after:h-0.5 after:bg-[#58CC02]' : 'text-gray-400 hover:text-gray-600'); ?>">
            Transaksi Aktif
        </a>
        <a href="<?php echo e(route('admin.pesanan.index', ['tab' => 'riwayat'])); ?>" 
            class="pb-2.5 px-4 transition-all relative <?php echo e($activeTab === 'riwayat' ? 'text-gray-950 after:absolute after:bottom-0 after:left-0 after:w-full after:h-0.5 after:bg-[#58CC02]' : 'text-gray-400 hover:text-gray-600'); ?>">
            Riwayat Transaksi
        </a>
        <a href="<?php echo e(route('admin.pesanan.index', ['tab' => 'refund'])); ?>" 
            class="pb-2.5 px-4 transition-all relative <?php echo e($activeTab === 'refund' ? 'text-gray-950 after:absolute after:bottom-0 after:left-0 after:w-full after:h-0.5 after:bg-[#58CC02]' : 'text-gray-400 hover:text-gray-600'); ?>">
            Refund
        </a>
    </div>

    <!-- Filter & Search Board (Matched to admin.produk.index style) -->
    <div class="bg-white p-4 md:p-5 rounded-xl shadow-sm border border-gray-100 mb-8">
        <form action="<?php echo e(route('admin.pesanan.index')); ?>" method="GET" class="flex flex-col md:flex-row items-end gap-4">
            <input type="hidden" name="tab" value="<?php echo e($activeTab); ?>">
            
            <div class="w-full md:flex-1">
                <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1 ml-1">Cari</label>
                <div class="relative w-full">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </span>
                    <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Cari nomor, nama pelanggan..." 
                        class="w-full pl-11 pr-4 py-2.5 rounded-xl border border-gray-100 bg-gray-50 outline-none focus:ring-2 focus:ring-[#58CC02] text-sm transition">
                </div>
            </div>

            <?php if($activeTab !== 'refund'): ?>
            <div class="w-full md:w-52">
                <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1 ml-1">Status</label>
                <select name="status" onchange="this.form.submit()" 
                    class="w-full px-4 py-2.5 rounded-xl border border-gray-100 bg-gray-50 outline-none focus:ring-2 focus:ring-[#58CC02] text-sm cursor-pointer appearance-none">
                    <option value="all" <?php echo e(request('status') === 'all' ? 'selected' : ''); ?>>Semua Status</option>
                    <?php if($activeTab === 'aktif'): ?>
                        <option value="diproses" <?php echo e(request('status') === 'diproses' ? 'selected' : ''); ?>>Dikemas (Diproses)</option>
                        <option value="dikirim" <?php echo e(request('status') === 'dikirim' ? 'selected' : ''); ?>>Dikirim</option>
                    <?php else: ?>
                        <option value="selesai" <?php echo e(request('status') === 'selesai' ? 'selected' : ''); ?>>Selesai</option>
                        <option value="dibatalkan" <?php echo e(request('status') === 'dibatalkan' ? 'selected' : ''); ?>>Dibatalkan</option>
                    <?php endif; ?>
                </select>
            </div>
            <?php else: ?>
            <div class="w-full md:w-52">
                <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1 ml-1">Status Refund</label>
                <select name="refund_status" onchange="this.form.submit()" 
                    class="w-full px-4 py-2.5 rounded-xl border border-gray-100 bg-gray-50 outline-none focus:ring-2 focus:ring-[#58CC02] text-sm cursor-pointer appearance-none">
                    <option value="all" <?php echo e(($refundStatus ?? 'all') === 'all' ? 'selected' : ''); ?>>Semua Status</option>
                    <option value="pending" <?php echo e(($refundStatus ?? '') === 'pending' ? 'selected' : ''); ?>>Pending</option>
                    <option value="disetujui" <?php echo e(($refundStatus ?? '') === 'disetujui' ? 'selected' : ''); ?>>Disetujui</option>
                    <option value="ditolak" <?php echo e(($refundStatus ?? '') === 'ditolak' ? 'selected' : ''); ?>>Ditolak</option>
                </select>
            </div>
            <?php endif; ?>

            <div class="w-full md:w-auto flex gap-2">
                <button type="submit" class="w-full md:w-auto bg-gray-800 hover:bg-black text-white px-8 py-2.5 rounded-xl transition font-bold text-sm flex items-center justify-center shadow-sm">
                    <i class="fa-solid fa-filter mr-2"></i> Filter
                </button>
                
                <?php if(request()->anyFilled(['search', 'status', 'refund_status'])): ?>
                    <a href="<?php echo e(route('admin.pesanan.index', ['tab' => $activeTab])); ?>" class="text-center border border-gray-200 hover:bg-gray-50 text-gray-600 px-6 py-2.5 rounded-xl font-bold text-xs transition flex items-center justify-center">
                        Reset
                    </a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <?php if($activeTab !== 'refund'): ?>
        <!-- Data Table untuk Pesanan (Desktop) -->
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden hidden md:block">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100">
                            <th class="py-4 px-6 text-xs font-black text-gray-400 uppercase tracking-wider w-16">No.</th>
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
                                <td class="py-4 px-6 text-xs font-black text-gray-500">
                                    <?php echo e(($pesanans->firstItem() ?? 1) + $loop->index); ?>

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
                                    <a href="<?php echo e(route('admin.pesanan.show', $pesanan->id)); ?>" class="inline-block bg-[#58CC02] hover:bg-[#46a302] text-white px-4 py-2 rounded-xl text-xs font-black transition shadow-sm">
                                        Detail
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

        <!-- Mobile Card View untuk Pesanan -->
        <div class="space-y-4 md:hidden">
            <?php $__empty_1 = true; $__currentLoopData = $pesanans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pesanan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 space-y-4">
                    <!-- Row number & Date -->
                    <div class="flex justify-between items-center pb-3 border-b border-gray-100">
                        <span class="font-bold text-xs text-gray-500">No: <?php echo e(($pesanans->firstItem() ?? 1) + $loop->index); ?></span>
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
                                <span class="text-[10px] text-gray-400">{{ $pesanan->user->username ?? 'user' }}</span>
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
                        <a href="<?php echo e(route('admin.pesanan.show', $pesanan->id)); ?>" class="w-full text-center bg-[#58CC02] hover:bg-[#46a302] text-white py-2.5 rounded-xl text-xs font-black transition shadow-sm">
                            Respon
                        </a>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-10 text-center text-gray-400">
                    <i class="fa-solid fa-box-open text-4xl mb-3 block"></i>
                    Tidak ditemukan data transaksi.
                </div>
            <?php endif; ?>

            <?php if($pesanans->hasPages()): ?>
                <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm mt-4">
                    <?php echo e($pesanans->links()); ?>

                </div>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <!-- Data Table untuk Refund (Desktop) -->
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden hidden md:block">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100">
                            <th class="py-4 px-6 text-xs font-black text-gray-400 uppercase tracking-wider w-16">No.</th>
                            <th class="py-4 px-6 text-xs font-black text-gray-400 uppercase tracking-wider">Tanggal</th>
                            <th class="py-4 px-6 text-xs font-black text-gray-400 uppercase tracking-wider">Agen</th>
                            <th class="py-4 px-6 text-xs font-black text-gray-400 uppercase tracking-wider">Produk</th>
                            <th class="py-4 px-6 text-xs font-black text-gray-400 uppercase tracking-wider">Nominal</th>
                            <th class="py-4 px-6 text-xs font-black text-gray-400 uppercase tracking-wider">Status</th>
                            <th class="py-4 px-6 text-xs font-black text-gray-400 uppercase tracking-wider text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm font-bold text-gray-700">
                        <?php $__empty_1 = true; $__currentLoopData = $refunds; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $refund): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="py-4 px-6 text-xs font-black text-gray-500">
                                    <?php echo e(($refunds->firstItem() ?? 1) + $loop->index); ?>

                                </td>
                                <td class="py-4 px-6 text-xs text-gray-500">
                                    <?php echo e(\Carbon\Carbon::parse($refund->created_at)->timezone('Asia/Jakarta')->locale('id')->translatedFormat('d M Y H:i')); ?> WIB
                                </td>
                                <td class="py-4 px-6">
                                    <div class="flex flex-col">
                                        <span class="text-gray-800"><?php echo e($refund->pesanan->user->namaLengkap ?? 'Dihapus'); ?></span>
                                        <span class="text-[10px] text-gray-400 font-normal">@&#8203;<?php echo e($refund->pesanan->user->username ?? 'user'); ?></span>
                                    </div>
                                </td>
                                <td class="py-4 px-6">
                                    <div class="min-w-0">
                                        <p class="text-xs text-gray-800"><?php echo e($refund->detailPesanan->produk->namaProduk ?? 'Produk Telah Dihapus'); ?></p>
                                        <p class="text-[10px] text-gray-400 font-semibold mt-0.5"><?php echo e($refund->jumlah); ?> unit x Rp <?php echo e(number_format($refund->detailPesanan->harga_satuan ?? 0, 0, ',', '.')); ?></p>
                                    </div>
                                </td>
                                <td class="py-4 px-6 text-gray-950 font-black">
                                    Rp <?php echo e(number_format($refund->nominal, 0, ',', '.')); ?>

                                </td>
                                <td class="py-4 px-6">
                                    <?php if($refund->status === 'pending'): ?>
                                        <span class="bg-amber-50 text-amber-600 border border-amber-200 px-2.5 py-0.5 rounded-full text-[10px] uppercase font-black tracking-wide">Pending</span>
                                    <?php elseif($refund->status === 'disetujui'): ?>
                                        <span class="bg-green-50 text-green-600 border border-green-200 px-2.5 py-0.5 rounded-full text-[10px] uppercase font-black tracking-wide">Disetujui</span>
                                    <?php else: ?>
                                        <span class="bg-red-50 text-red-600 border border-red-200 px-2.5 py-0.5 rounded-full text-[10px] uppercase font-black tracking-wide">Ditolak</span>
                                    <?php endif; ?>
                                </td>
                                <td class="py-4 px-6 text-right">
                                    <a href="<?php echo e(route('admin.refund.show', $refund->id)); ?>" class="inline-block border border-gray-200 hover:border-gray-300 text-gray-700 bg-white px-3.5 py-2 rounded-xl text-xs font-black transition">
                                        Detail & Verifikasi
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="7" class="py-16 text-center text-gray-400">
                                    <i class="fa-solid fa-rotate-left text-4xl mb-3 block"></i>
                                    Tidak ditemukan data pengajuan refund.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <?php if($refunds->hasPages()): ?>
                <div class="bg-gray-50 px-6 py-4 border-t border-gray-100">
                    <?php echo e($refunds->links()); ?>

                </div>
            <?php endif; ?>
        </div>

        <!-- Mobile Card View untuk Refund -->
        <div class="space-y-4 md:hidden">
            <?php $__empty_1 = true; $__currentLoopData = $refunds; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $refund): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 space-y-4">
                    <div class="flex justify-between items-center pb-2 border-b border-gray-100">
                        <div class="text-left">
                            <span class="text-[9px] text-gray-400 block uppercase font-bold">Agen</span>
                            <span class="font-bold text-gray-800 text-xs"><?php echo e($refund->pesanan->user->namaLengkap ?? 'Dihapus'); ?></span>
                        </div>
                        <?php if($refund->status === 'pending'): ?>
                            <span class="bg-amber-50 text-amber-600 border border-amber-200 px-2.5 py-0.5 rounded-full text-[9px] uppercase font-black tracking-wide">Pending</span>
                        <?php elseif($refund->status === 'disetujui'): ?>
                            <span class="bg-green-50 text-green-600 border border-green-200 px-2.5 py-0.5 rounded-full text-[9px] uppercase font-black tracking-wide">Disetujui</span>
                        <?php else: ?>
                            <span class="bg-red-50 text-red-600 border border-red-200 px-2.5 py-0.5 rounded-full text-[9px] uppercase font-black tracking-wide">Ditolak</span>
                        <?php endif; ?>
                    </div>

                    <div class="flex items-center gap-3">
                        <?php if($refund->foto_bukti): ?>
                            <div class="w-12 h-12 rounded-xl overflow-hidden border border-gray-150 shrink-0">
                                <img src="<?php echo e(asset('storage/' . $refund->foto_bukti)); ?>" class="w-full h-full object-cover">
                            </div>
                        <?php endif; ?>
                        <div class="min-w-0">
                            <h4 class="font-extrabold text-gray-800 text-xs truncate"><?php echo e($refund->detailPesanan->produk->namaProduk ?? 'Produk Telah Dihapus'); ?></h4>
                            <p class="text-[10px] text-gray-400 font-bold mt-0.5">
                                <?php echo e($refund->jumlah); ?> unit x Rp <?php echo e(number_format($refund->detailPesanan->harga_satuan ?? 0, 0, ',', '.')); ?>

                            </p>
                            <p class="text-[10px] text-gray-500 font-mono mt-0.5">No: <?php echo e(($refunds->firstItem() ?? 1) + $loop->index); ?></p>
                        </div>
                    </div>

                    <div class="flex justify-between items-center pt-2 border-t border-gray-100 text-xs">
                        <div>
                            <span class="text-[9px] text-gray-400 block uppercase font-bold">Tanggal Pengajuan</span>
                            <span class="text-[10px] text-gray-500 font-semibold"><?php echo e(\Carbon\Carbon::parse($refund->created_at)->timezone('Asia/Jakarta')->locale('id')->translatedFormat('d M Y H:i')); ?> WIB</span>
                        </div>
                        <div class="text-right shrink-0">
                            <span class="text-[9px] text-gray-400 block uppercase font-bold">Nominal Refund</span>
                            <span class="font-black text-gray-900 text-sm">Rp <?php echo e(number_format($refund->nominal, 0, ',', '.')); ?></span>
                        </div>
                    </div>

                    <div class="pt-2">
                        <a href="<?php echo e(route('admin.refund.show', $refund->id)); ?>" class="block text-center w-full bg-gray-50 hover:bg-gray-100 border border-gray-200 text-gray-700 py-2.5 rounded-xl text-xs font-black transition">
                            Detail & Verifikasi
                        </a>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-10 text-center text-gray-400">
                    <i class="fa-solid fa-rotate-left text-4xl mb-3 block"></i>
                    Tidak ditemukan data pengajuan refund.
                </div>
            <?php endif; ?>

            <?php if($refunds->hasPages()): ?>
                <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm mt-4">
                    <?php echo e($refunds->links()); ?>

                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\project\Agris\resources\views/admin/pesanan/index.blade.php ENDPATH**/ ?>