<?php $__env->startSection('title', 'Detail Pesanan - AGRIS'); ?>

<?php $__env->startSection('content'); ?>
<script src="<?php echo e(config('services.midtrans.is_production', false) ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js'); ?>" data-client-key="<?php echo e(config('services.midtrans.client_key')); ?>"></script>

<?php
    $pembayaran = $pesanan->pembayaran;
    $paymentInfo = null;
    if ($pembayaran && $pembayaran->payment_info) {
        $paymentInfo = json_decode($pembayaran->payment_info, true);
    }
    $isMock = empty(config('services.midtrans.server_key'));

    $deskripsi = $pesanan->deskripsi;
    $courierInfo = 'Kurir Pengiriman';
    $noResi = '';
    $ongkirText = '';
    $biteshipOrderId = '';

    if ($deskripsi) {
        $parts = explode('|', $deskripsi);
        foreach ($parts as $part) {
            $part = trim($part);
            if (str_starts_with(strtolower($part), 'opsi:')) {
                $courierInfo = trim(substr($part, 5));
            } elseif (str_starts_with(strtolower($part), 'ongkir:')) {
                $ongkirText = trim(substr($part, 7));
            } elseif (str_starts_with(strtolower($part), 'no resi:')) {
                $noResi = trim(substr($part, 8));
            } elseif (str_starts_with(strtolower($part), 'biteship order id:')) {
                $biteshipOrderId = trim(substr($part, 18));
            }
        }
    }
?>

<div class="max-w-5xl mx-auto pb-16 px-6 pt-5">
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <a href="<?php echo e(route('agen.pesanan.index')); ?>" class="text-xs font-bold text-gray-400 hover:text-gray-600 flex items-center gap-1.5 mb-2 uppercase tracking-wider transition-colors">
                <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar
            </a>
            <h1 class="text-2xl pt-1 font-extrabold text-gray-800 tracking-tight">Detail Pesanan</h1>
            <p class="text-gray-500 text-sm pt-2">ID Pesanan : <?php echo e($pesanan->id); ?></p>
        </div>

        <div>
            <?php if($pesanan->status === 'pending'): ?>
                <span class="bg-amber-50 text-amber-600 border border-amber-100 px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider">Menunggu Pembayaran</span>
            <?php elseif($pesanan->status === 'diproses'): ?>
                <span class="bg-blue-50 text-blue-600 border border-blue-100 px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider">Sedang Dikemas</span>
            <?php elseif($pesanan->status === 'dikirim'): ?>
                <span class="bg-purple-50 text-purple-600 border border-purple-100 px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider">Sedang Dikirim</span>
            <?php elseif($pesanan->status === 'selesai'): ?>
                <span class="bg-green-50 text-green-600 border border-green-100 px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider">Selesai</span>
            <?php else: ?>
                <span class="bg-red-50 text-red-600 border border-red-100 px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider">Dibatalkan</span>
            <?php endif; ?>
        </div>
    </div>

    <!-- Alert Messages -->
    <?php if(session('success')): ?>
        <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded-xl mb-6 text-sm font-bold flex items-center gap-3">
            <i class="fa-solid fa-circle-check text-lg text-green-500"></i>
            <span><?php echo e(session('success')); ?></span>
        </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-xl mb-6 text-sm font-bold flex items-center gap-3">
            <i class="fa-solid fa-triangle-exclamation text-lg text-red-500"></i>
            <span><?php echo e(session('error')); ?></span>
        </div>
    <?php endif; ?>

    <?php if(session('info')): ?>
        <div class="bg-blue-50 border-l-4 border-blue-500 text-blue-700 p-4 rounded-xl mb-6 text-sm font-bold flex items-center gap-3">
            <i class="fa-solid fa-circle-info text-lg text-blue-500"></i>
            <span><?php echo e(session('info')); ?></span>
        </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
        <!-- Main Content (Left Column) -->
        <div class="lg:col-span-2 space-y-6">

            <!-- 1. Tracking Timeline Section (Only if NOT cancelled) -->
            <?php if($pesanan->status !== 'dibatalkan'): ?>
                <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm space-y-6">
                    <div class="flex items-center gap-3 pb-3 border-b border-gray-50">
                        <div class="w-9 h-9 rounded-2xl bg-[#58CC02]/10 flex items-center justify-center text-[#58CC02]">
                            <i class="fa-solid fa-map-location-dot text-base"></i>
                        </div>
                        <div>
                            <h2 class="font-extrabold text-gray-800 text-sm uppercase tracking-wider">Status Tracking Pesanan</h2>
                            <p class="text-xs text-gray-400">Pantau proses pengiriman pesanan Anda</p>
                        </div>
                    </div>

                    <!-- Stepper Timeline -->
                    <div class="relative pl-8 space-y-6 border-l-2 border-gray-100 ml-4">
                        <!-- Step 1: Pesanan Dibuat -->
                        <div class="relative">
                            <!-- Icon Indicator -->
                            <span class="absolute -left-12 top-0.5 flex h-8 w-8 items-center justify-center rounded-full bg-[#58CC02] text-white shadow-sm ring-4 ring-white">
                                <i class="fa-solid fa-check text-xs"></i>
                            </span>
                            <div>
                                <h3 class="text-sm font-extrabold text-gray-800">Pesanan Dibuat</h3>
                                <p class="text-xs text-gray-500 mt-0.5">Pesanan Anda berhasil masuk ke dalam antrean sistem.</p>
                                <span class="inline-block text-[10px] font-bold text-gray-400 mt-1 bg-gray-50 px-2 py-0.5 rounded"><?php echo e(\Carbon\Carbon::parse($pesanan->created_at)->timezone('Asia/Jakarta')->locale('id')->translatedFormat('d F Y, H:i')); ?> WIB</span>
                            </div>
                        </div>

                        <!-- Step 2: Pembayaran Diterima -->
                        <?php
                            $step2Active = in_array($pesanan->status, ['diproses', 'dikirim', 'selesai']);
                        ?>
                        <div class="relative">
                            <!-- Icon Indicator -->
                            <span class="absolute -left-12 top-0.5 flex h-8 w-8 items-center justify-center rounded-full <?php echo e($step2Active ? 'bg-[#58CC02] text-white' : 'bg-gray-100 text-gray-400'); ?> shadow-sm ring-4 ring-white">
                                <?php if($step2Active): ?>
                                    <i class="fa-solid fa-check text-xs"></i>
                                <?php else: ?>
                                    <i class="fa-solid fa-credit-card text-xs"></i>
                                <?php endif; ?>
                            </span>
                            <div>
                                <h3 class="text-sm font-extrabold <?php echo e($step2Active ? 'text-gray-800' : 'text-gray-400'); ?>">Pembayaran Diterima</h3>
                                <p class="text-xs <?php echo e($step2Active ? 'text-gray-500' : 'text-gray-400'); ?> mt-0.5">
                                    <?php if($step2Active): ?>
                                        Pembayaran berhasil diverifikasi. Pesanan sedang dikemas.
                                    <?php else: ?>
                                        Menunggu pembayaran diselesaikan oleh Agen.
                                    <?php endif; ?>
                                </p>
                                <?php if($step2Active && $pembayaran && $pembayaran->waktuDibayar): ?>
                                    <span class="inline-block text-[10px] font-bold text-gray-400 mt-1 bg-gray-50 px-2 py-0.5 rounded">
                                        <?php echo e(\Carbon\Carbon::parse($pembayaran->waktuDibayar)->timezone('Asia/Jakarta')->locale('id')->translatedFormat('d F Y, H:i')); ?> WIB
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Step 3: Dalam Pengiriman -->
                        <?php
                            $step3Active = in_array($pesanan->status, ['dikirim', 'selesai']);
                            $isPickup = str_contains(strtolower($courierInfo), 'ambil');
                        ?>
                        <div class="relative">
                            <!-- Icon Indicator -->
                            <span class="absolute -left-12 top-0.5 flex h-8 w-8 items-center justify-center rounded-full <?php echo e($step3Active ? 'bg-[#58CC02] text-white' : 'bg-gray-100 text-gray-400'); ?> shadow-sm ring-4 ring-white">
                                <?php if($step3Active): ?>
                                    <i class="fa-solid fa-check text-xs"></i>
                                <?php else: ?>
                                    <i class="fa-solid fa-truck-ramp-box text-xs"></i>
                                <?php endif; ?>
                            </span>
                            <div>
                                <h3 class="text-sm font-extrabold <?php echo e($step3Active ? 'text-gray-800' : 'text-gray-400'); ?>">
                                    <?php echo e($isPickup ? 'Siap Diambil' : 'Dalam Pengiriman'); ?>

                                </h3>
                                <p class="text-xs <?php echo e($step3Active ? 'text-gray-500' : 'text-gray-400'); ?> mt-0.5 leading-relaxed">
                                    <?php if($step3Active): ?>
                                        <?php if($isPickup): ?>
                                            Pesanan siap diambil di Gudang Utama AGRIS (Patrang, Jember).
                                        <?php else: ?>
                                            Paket dikirim via <strong><?php echo e(strtoupper($courierInfo)); ?></strong>.
                                            <?php if($noResi): ?>
                                                <br><span class="inline-flex items-center gap-1.5 mt-1.5 bg-gray-50 border border-gray-150 px-2.5 py-1 rounded-lg font-mono text-gray-700 font-bold text-xs select-all">
                                                    <i class="fa-solid fa-barcode text-gray-400"></i> Resi: <?php echo e($noResi); ?>

                                                </span>
                                                <a href="<?php echo e(route('agen.pesanan.lacak', $pesanan->id)); ?>" target="_blank" class="inline-flex items-center gap-1.5 mt-1.5 ml-2 bg-blue-50 border border-blue-100 px-2.5 py-1 rounded-lg text-blue-600 font-bold text-[10px] hover:bg-blue-100 transition">
                                                    <i class="fa-solid fa-arrow-up-right-from-square"></i> Lacak
                                                </a>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        Pesanan belum siap.
                                    <?php endif; ?>
                                </p>
                            </div>
                        </div>

                        <!-- Step 4: Selesai -->
                        <?php
                            $step4Active = $pesanan->status === 'selesai';
                        ?>
                        <div class="relative">
                            <!-- Icon Indicator -->
                            <span class="absolute -left-12 top-0.5 flex h-8 w-8 items-center justify-center rounded-full <?php echo e($step4Active ? 'bg-[#58CC02] text-white' : 'bg-gray-100 text-gray-400'); ?> shadow-sm ring-4 ring-white">
                                <?php if($step4Active): ?>
                                    <i class="fa-solid fa-check text-xs"></i>
                                <?php else: ?>
                                    <i class="fa-solid fa-circle-check text-xs"></i>
                                <?php endif; ?>
                            </span>
                            <div>
                                <h3 class="text-sm font-extrabold <?php echo e($step4Active ? 'text-gray-800' : 'text-gray-400'); ?>">Pesanan Selesai</h3>
                                <p class="text-xs <?php echo e($step4Active ? 'text-gray-500' : 'text-gray-400'); ?> mt-0.5">
                                    <?php if($step4Active): ?>
                                        Pesanan telah diterima oleh penerima.
                                    <?php else: ?>
                                        Konfirmasi penerimaan produk setelah pesanan sampai.
                                    <?php endif; ?>
                                </p>
                                <?php if($step4Active): ?>
                                    <span class="inline-block text-[10px] font-bold text-gray-400 mt-1 bg-gray-50 px-2 py-0.5 rounded"><?php echo e(\Carbon\Carbon::parse($pesanan->updated_at)->timezone('Asia/Jakarta')->locale('id')->translatedFormat('d F Y, H:i')); ?> WIB</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Detailed Simulated or Real Tracking Logs (Shown if status is dikirim or selesai) -->
                    <?php if(in_array($pesanan->status, ['dikirim', 'selesai'])): ?>
                        <div class="mt-6 pt-6 border-t border-gray-100">
                            <h4 class="font-bold text-gray-700 text-xs mb-4 uppercase tracking-wider ">Histori Pengiriman</h4>

                            <div class="relative border-l-2 border-dashed border-gray-200 pl-6 ml-2.5 space-y-5">
                                <?php if(isset($trackingData) && !empty($trackingData['history'])): ?>
                                    <?php $__currentLoopData = array_reverse($trackingData['history']); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="relative">
                                            <?php
                                                $isLatest = $loop->first;
                                                // Map Biteship status to Indonesian user friendly label
                                                $statusLabel = match($event['status']) {
                                                    'confirmed' => 'Pesanan Dikonfirmasi',
                                                    'allocated' => 'Kurir Dialokasikan',
                                                    'pickingUp' => 'Proses Penjemputan',
                                                    'picked' => 'Paket Dijemput Kurir',
                                                    'inTransit' => 'Dalam Transit / Pengiriman',
                                                    'droppingOff' => 'Kurir Menuju Lokasi Anda',
                                                    'delivered' => 'Paket Diterima',
                                                    'rejected' => 'Paket Ditolak/Bermasalah',
                                                    'cancelled' => 'Pengiriman Dibatalkan',
                                                    'returned' => 'Paket Dikembalikan',
                                                    default => strtoupper($event['status'])
                                                };
                                            ?>
                                            <span class="absolute -left-8.5 top-0.5 w-3.5 h-3.5 rounded-full <?php echo e($isLatest ? 'bg-[#58CC02]' : 'bg-gray-300'); ?> border-2 border-white shadow-sm"></span>
                                            <div class="text-xs">
                                                <span class="text-gray-400 font-bold block mb-0.5"><?php echo e(\Carbon\Carbon::parse($event['updated_at'])->timezone('Asia/Jakarta')->locale('id')->translatedFormat('d M Y, H:i')); ?> WIB</span>
                                                <p class="font-extrabold <?php echo e($isLatest ? 'text-[#58CC02]' : 'text-gray-700'); ?>"><?php echo e($statusLabel); ?></p>
                                                <p class="text-gray-500 mt-0.5 font-medium leading-relaxed"><?php echo e($event['note']); ?></p>
                                            </div>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php else: ?>
                                    <?php
                                        $updatedTime = $pesanan->updated_at;
                                        $waktuBayar = $pembayaran && $pembayaran->waktuDibayar ? \Carbon\Carbon::parse($pembayaran->waktuDibayar) : $pesanan->created_at->addHours(1);

                                        $logTime1 = $updatedTime;
                                        $logTime2 = $logTime1->copy()->subHours(2);
                                        $logTime3 = $logTime1->copy()->subHours(5);
                                        $logTime4 = $waktuBayar;
                                    ?>

                                    <?php if($isPickup): ?>
                                        <div class="relative">
                                            <span class="absolute -left-8.5 top-0.5 w-3.5 h-3.5 rounded-full bg-[#58CC02] border-2 border-white shadow-sm"></span>
                                            <div class="text-xs">
                                                <span class="text-gray-400 font-bold block mb-0.5"><?php echo e(\Carbon\Carbon::parse($logTime1)->timezone('Asia/Jakarta')->locale('id')->translatedFormat('d M Y, H:i')); ?> WIB</span>
                                                <p class="font-extrabold text-gray-800">Siap Diambil di Counter AGRIS</p>
                                                <p class="text-gray-500 mt-0.5 font-medium leading-relaxed">Pesanan Anda telah dikemas dan diletakkan pada rak pengambilan mandiri. Tunjukkan ID pesanan ini ke petugas gudang.</p>
                                            </div>
                                        </div>
                                        <div class="relative">
                                            <span class="absolute -left-8.5 top-0.5 w-3.5 h-3.5 rounded-full bg-gray-300 border-2 border-white shadow-sm"></span>
                                            <div class="text-xs">
                                                <span class="text-gray-400 font-bold block mb-0.5"><?php echo e(\Carbon\Carbon::parse($logTime2)->timezone('Asia/Jakarta')->locale('id')->translatedFormat('d M Y, H:i')); ?> WIB</span>
                                                <p class="font-extrabold text-gray-700">Dalam Proses Pengemasan</p>
                                                <p class="text-gray-500 mt-0.5 font-medium leading-relaxed">Staf gudang sedang mengumpulkan item pesanan Anda.</p>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <?php if($pesanan->status === 'selesai'): ?>
                                            <div class="relative">
                                                <span class="absolute -left-8.5 top-0.5 w-3.5 h-3.5 rounded-full bg-[#58CC02] border-2 border-white shadow-sm"></span>
                                                <div class="text-xs">
                                                    <span class="text-gray-400 font-bold block mb-0.5"><?php echo e(\Carbon\Carbon::parse($pesanan->updated_at)->timezone('Asia/Jakarta')->locale('id')->translatedFormat('d M Y, H:i')); ?> WIB</span>
                                                    <p class="font-extrabold text-[#58CC02]">Pesanan Selesai</p>
                                                    <p class="text-gray-500 mt-0.5 font-medium leading-relaxed">Paket telah diterima dengan baik oleh penerima yang bersangkutan.</p>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                        <div class="relative">
                                            <span class="absolute -left-8.5 top-0.5 w-3.5 h-3.5 rounded-full bg-[#58CC02] border-2 border-white shadow-sm"></span>
                                            <div class="text-xs">
                                                <span class="text-gray-400 font-bold block mb-0.5"><?php echo e(\Carbon\Carbon::parse($logTime1)->timezone('Asia/Jakarta')->locale('id')->translatedFormat('d M Y, H:i')); ?> WIB</span>
                                                <p class="font-extrabold text-gray-800">Paket Sedang Diantar Kurir</p>
                                                <p class="text-gray-500 mt-0.5 font-medium leading-relaxed">Kurir dari <strong><?php echo e(strtoupper($courierInfo)); ?></strong> sedang dalam perjalanan menuju alamat pengiriman Anda.</p>
                                            </div>
                                        </div>
                                        <div class="relative">
                                            <span class="absolute -left-8.5 top-0.5 w-3.5 h-3.5 rounded-full bg-gray-300 border-2 border-white shadow-sm"></span>
                                            <div class="text-xs">
                                                <span class="text-gray-400 font-bold block mb-0.5"><?php echo e(\Carbon\Carbon::parse($logTime2)->timezone('Asia/Jakarta')->locale('id')->translatedFormat('d M Y, H:i')); ?> WIB</span>
                                                <p class="font-extrabold text-gray-700">Paket Diserahkan ke Ekspedisi</p>
                                                <p class="text-gray-500 mt-0.5 font-medium leading-relaxed">Paket pesanan diserahkan ke gerai kurir <strong><?php echo e(strtoupper($courierInfo)); ?></strong> terdekat untuk diteruskan.</p>
                                            </div>
                                        </div>
                                        <div class="relative">
                                            <span class="absolute -left-8.5 top-0.5 w-3.5 h-3.5 rounded-full bg-gray-300 border-2 border-white shadow-sm"></span>
                                            <div class="text-xs">
                                                <span class="text-gray-400 font-bold block mb-0.5"><?php echo e(\Carbon\Carbon::parse($logTime3)->timezone('Asia/Jakarta')->locale('id')->translatedFormat('d M Y, H:i')); ?> WIB</span>
                                                <p class="font-extrabold text-gray-700">Pengemasan Produk Selesai</p>
                                                <p class="text-gray-500 mt-0.5 font-medium leading-relaxed">Staf gudang AGRIS telah selesai mengemas produk pesanan Anda dengan aman.</p>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                    <div class="relative">
                                        <span class="absolute -left-8.5 top-0.5 w-3.5 h-3.5 rounded-full bg-gray-300 border-2 border-white shadow-sm"></span>
                                        <div class="text-xs">
                                            <span class="text-gray-400 font-bold block mb-0.5"><?php echo e(\Carbon\Carbon::parse($logTime4)->timezone('Asia/Jakarta')->locale('id')->translatedFormat('d M Y, H:i')); ?> WIB</span>
                                            <p class="font-extrabold text-gray-700">Pembayaran Terverifikasi</p>
                                            <p class="text-gray-500 mt-0.5 font-medium leading-relaxed">Pembayaran online berhasil dikonfirmasi. Mempersiapkan pesanan untuk dipacking.</p>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <!-- 2. Daftar Produk -->
            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
                <h2 class="font-extrabold text-gray-800 text-xs mb-4 uppercase tracking-wider text-gray-400 pb-2 border-b border-gray-50">Daftar Produk</h2>
                <div class="divide-y divide-gray-100">
                    <?php $__currentLoopData = $pesanan->detailPesanans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $refunds = \App\Models\Refund::where('detailPesananId', $detail->id)->get();
                            $refundedQty = $refunds->whereIn('status', ['pending', 'disetujui'])->sum('jumlah');
                            $maxQty = $detail->jumlahPesanan - $refundedQty;
                        ?>
                        <div class="flex items-center gap-3 md:gap-4 py-4 first:pt-0 last:pb-0">
                            <div class="w-12 h-12 md:w-14 md:h-14 bg-gray-50 rounded-xl overflow-hidden border border-gray-100 flex items-center justify-center p-1 shrink-0">
                                <?php if($detail->produk && $detail->produk->fotoProduk): ?>
                                    <img src="<?php echo e(asset('storage/' . $detail->produk->fotoProduk)); ?>" class="w-full h-full object-cover">
                                <?php else: ?>
                                    <i class="fa-solid fa-image text-xl text-gray-300"></i>
                                <?php endif; ?>
                            </div>

                            <div class="grow min-w-0">
                                <h4 class="font-bold text-gray-800 text-[11px] md:text-xs truncate">
                                    <?php echo e($detail->produk ? $detail->produk->namaProduk : 'Produk Telah Dihapus'); ?>

                                </h4>
                                <p class="text-[10px] md:text-[11px] text-gray-400 font-semibold mt-0.5">
                                    <?php echo e($detail->jumlahPesanan); ?> barang x Rp <?php echo e(number_format($detail->harga_satuan, 0, ',', '.')); ?>

                                </p>
                                <?php if($refunds->isNotEmpty()): ?>
                                    <?php $__currentLoopData = $refunds; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ref): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <span class="block mt-1 text-[9px] font-bold <?php echo e($ref->status === 'disetujui' ? 'text-green-600' : ($ref->status === 'pending' ? 'text-amber-600' : 'text-red-500')); ?>">
                                            Refund <?php echo e($ref->jumlah); ?> unit (<?php echo e(strtoupper($ref->status)); ?>)
                                        </span>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>
                            </div>

                            <div class="text-right shrink-0 flex items-center gap-3">
                                <div class="text-right">
                                    <span class="font-bold text-gray-800 text-[11px] md:text-xs block">
                                        Rp <?php echo e(number_format($detail->subtotal, 0, ',', '.')); ?>

                                    </span>
                                </div>
                                <?php if($pesanan->status === 'selesai' && $maxQty > 0): ?>
                                    <button type="button" onclick="openRefundModal('<?php echo e($detail->id); ?>', '<?php echo e(addslashes($detail->produk->namaProduk ?? '')); ?>', <?php echo e($detail->harga_satuan); ?>, <?php echo e($maxQty); ?>)" class="text-[10px] bg-red-50 hover:bg-red-100 text-red-650 px-2.5 py-1 rounded-lg border border-red-200 transition font-bold cursor-pointer">
                                        Refund
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>

            <!-- 3. Rincian Alamat Pengiriman -->
            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm space-y-4">
                <div class="flex items-center gap-3 pb-3 border-b border-gray-50">
                    <div class="w-8 h-8 rounded-xl bg-gray-50 flex items-center justify-center text-gray-400">
                        <i class="fa-solid fa-location-dot text-sm"></i>
                    </div>
                    <h2 class="font-extrabold text-gray-800 text-xs uppercase tracking-wider text-gray-400">Alamat Penerima</h2>
                </div>
                <div class="text-xs space-y-1.5 leading-relaxed">
                    <p class="font-extrabold text-gray-800 text-sm"><?php echo e($pesanan->user->namaLengkap); ?></p>
                    <p class="text-gray-500 font-bold"><?php echo e($pesanan->user->noTelp); ?></p>
                    <p class="text-gray-600 mt-1 font-medium"><?php echo e($pesanan->alamat_pengiriman); ?></p>
                </div>
            </div>

        </div>

        <!-- Sidebar Summary & Control Actions (Right Column) -->
        <div class="space-y-6">
            <!-- 0. Info Pengiriman -->
            <?php if(in_array($pesanan->status_pesanan, ['diproses', 'dikirim', 'selesai'])): ?>
            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
                <div class="flex items-center gap-3 mb-4 pb-3 border-b border-gray-50">
                    <div class="w-8 h-8 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600">
                        <i class="fa-solid fa-truck-fast text-sm"></i>
                    </div>
                    <h3 class="font-extrabold text-gray-800 text-xs uppercase tracking-wider text-gray-400">Info Pengiriman</h3>
                </div>
                <div class="space-y-3">
                    <div class="bg-gray-50 rounded-2xl p-3 border border-gray-100">
                        <div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Kurir Pengiriman</div>
                        <span class="font-extrabold text-gray-800 text-sm uppercase"><?php echo e($courierInfo); ?></span>
                    </div>
                    <?php if($noResi && !str_contains($noResi, 'AMBIL')): ?>
                    <div class="bg-gray-50 rounded-2xl p-3 border border-gray-100">
                        <div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1.5">Nomor Resi</div>
                        <div class="flex items-center justify-between gap-2">
                            <span class="font-mono font-extrabold text-gray-800 text-sm select-all"><?php echo e($noResi); ?></span>
                            <button onclick="copyResiSidebar(this, '<?php echo e($noResi); ?>')" class="text-gray-400 hover:text-[#58CC02] transition cursor-pointer" title="Salin Resi">
                                <i class="fa-regular fa-copy text-xs"></i>
                            </button>
                        </div>
                    </div>
                    <?php endif; ?>
                    <?php if($ongkirText): ?>
                    <div class="bg-gray-50 rounded-2xl p-3 border border-gray-100">
                        <div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Ongkos Kirim</div>
                        <span class="font-extrabold text-gray-800 text-sm"><?php echo e($ongkirText); ?></span>
                    </div>
                    <?php endif; ?>
                    <?php if($noResi && !str_contains($noResi, 'AMBIL')): ?>
                    <a href="<?php echo e(route('agen.pesanan.lacak', $pesanan->id)); ?>" target="_blank" class="block w-full bg-blue-50 hover:bg-blue-100 text-blue-600 py-2.5 rounded-2xl font-black text-xs flex items-center justify-center gap-2 transition border border-blue-100 cursor-pointer">
                        <i class="fa-solid fa-map-location-dot"></i> Lacak Pengiriman<?php echo e($biteshipOrderId ? ' via Biteship' : ''); ?>

                    </a>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- 1. Ringkasan Tagihan -->
            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm relative overflow-hidden">
                <div class="absolute top-0 right-0 w-20 h-20 bg-gradient-to-bl from-green-50 to-transparent rounded-bl-full pointer-events-none"></div>
                <h2 class="font-extrabold text-gray-800 text-xs mb-4 uppercase tracking-wider text-gray-400">Ringkasan Transaksi</h2>

                <div class="space-y-3 pb-3 border-b border-gray-50 text-xs">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-400 font-semibold">Tanggal Transaksi</span>
                        <span class="text-gray-700 font-extrabold"><?php echo e(\Carbon\Carbon::parse($pesanan->created_at)->timezone('Asia/Jakarta')->locale('id')->translatedFormat('d F Y, H:i')); ?> WIB</span>
                    </div>
                    <?php if($pembayaran && $pembayaran->waktuDibayar): ?>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-400 font-semibold">Tanggal Pembayaran</span>
                            <span class="text-gray-700 font-extrabold"><?php echo e(\Carbon\Carbon::parse($pembayaran->waktuDibayar)->timezone('Asia/Jakarta')->locale('id')->translatedFormat('d F Y, H:i')); ?> WIB</span>
                        </div>
                    <?php endif; ?>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-400 font-semibold">Pilihan Metode Pembayaran</span>
                        <span class="text-gray-700 font-black uppercase text-right">
                            <?php if($pembayaran): ?>
                                <?php
                                    $type = strtolower($pembayaran->paymentType ?? '');
                                    if ($type === 'midtrans_snap' || $type === 'simulasi_midtrans' || !$type) {
                                        $displayMethod = 'MIDTRANS ONLINE';
                                    } else {
                                        $displayMethod = strtoupper(str_replace('_', ' ', $pembayaran->paymentType));
                                    }
                                ?>
                                <?php echo e($displayMethod); ?>

                            <?php else: ?>
                                MIDTRANS ONLINE
                            <?php endif; ?>
                        </span>
                    </div>
                    <?php if($noResi): ?>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-400 font-semibold">No Resi</span>
                            <span class="text-gray-700 font-mono font-bold select-all bg-gray-50 px-1.5 py-0.5 rounded border border-gray-150"><?php echo e($noResi); ?></span>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="flex justify-between items-center pt-4">
                    <span class="text-gray-800 font-extrabold text-xs">Total Tagihan</span>
                    <span class="text-lg font-black text-[#58CC02]">
                        Rp <?php echo e(number_format($pesanan->total_harga, 0, ',', '.')); ?>

                    </span>
                </div>
            </div>

            <!-- 2. Aksi Pesanan (Shown only if diproses) -->
            <?php if($pesanan->status === 'diproses'): ?>
                <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm space-y-3">
                    <h3 class="font-extrabold text-gray-800 text-xs mb-4 uppercase tracking-wider text-gray-400 pb-2 border-b border-gray-50">Aksi Pesanan</h3>

                    <!-- Batal Pesanan -->
                    <form action="<?php echo e(route('agen.pesanan.batal', $pesanan->id)); ?>" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini? Stok produk akan dikembalikan.')">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="w-full border border-red-200 text-red-600 hover:bg-red-50 py-2.5 rounded-2xl font-black text-xs flex items-center justify-center gap-2 transition duration-200 cursor-pointer">
                            <i class="fa-solid fa-ban"></i> Batalkan Pesanan
                        </button>
                    </form>
                </div>
            <?php endif; ?>

            <!-- 3. Mark Diterima Button (Shown only if status is dikirim) -->
            <?php if($pesanan->status === 'dikirim'): ?>
                <div class="bg-white p-6 rounded-3xl border border-gray-150 shadow-sm space-y-3">
                    <h3 class="font-extrabold text-gray-800 text-xs mb-4 uppercase tracking-wider text-gray-400 pb-2 border-b border-gray-50">Konfirmasi Penerimaan</h3>
                    <p class="text-xs text-gray-400 font-semibold leading-relaxed">Pesanan Anda telah dikirimkan. Harap klik tombol di bawah ini jika barang sudah Anda terima dengan baik.</p>

                    <form action="<?php echo e(route('agen.pesanan.diterima', $pesanan->id)); ?>" method="POST" onsubmit="return confirm('Konfirmasi bahwa Anda telah menerima pesanan ini? Aksi ini tidak dapat dibatalkan.')">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="w-full bg-[#0f8629] hover:bg-[#0c6b20] text-white py-3 rounded-2xl font-black text-xs flex items-center justify-center gap-2 shadow-sm transition duration-200 cursor-pointer">
                            <i class="fa-solid fa-circle-check"></i> Pesanan Sudah Diterima
                        </button>
                    </form>
                </div>
            <?php endif; ?>

            <!-- 4. Selesaikan Pembayaran (Shown only if status is pending) -->
            <?php if($pesanan->status === 'pending'): ?>
                <div class="bg-white p-6 rounded-3xl border border-gray-150 shadow-sm space-y-3">
                    <h3 class="font-extrabold text-gray-800 text-xs mb-4 uppercase tracking-wider text-gray-400 pb-2 border-b border-gray-50">Selesaikan Pembayaran</h3>
                    <p class="text-xs text-gray-400 font-semibold leading-relaxed">Pesanan Anda telah disimpan. Silakan lakukan pembayaran agar pesanan dapat segera diproses.</p>

                    <?php if($pembayaran && $pembayaran->snapToken): ?>
                        <button id="btnPayNow" class="w-full bg-[#58CC02] hover:bg-[#46a302] text-white py-3 rounded-2xl font-black text-xs flex items-center justify-center gap-2 shadow-sm transition duration-200 cursor-pointer">
                            <i class="fa-solid fa-credit-card"></i> Bayar Sekarang
                        </button>
                    <?php else: ?>
                        <div class="p-3 bg-red-50 text-red-600 rounded-2xl text-[11px] font-bold text-center">
                            Gagal memuat Snap token pembayaran.
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php if($pesanan->status === 'pending' && $pembayaran && $pembayaran->snapToken): ?>
<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function() {
        const btnPay = document.getElementById('btnPayNow');
        if (btnPay) {
            btnPay.addEventListener('click', function() {
                // Disable button immediately to prevent spam click
                btnPay.disabled = true;
                btnPay.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-2"></i> Memproses...';
                btnPay.className = "w-full bg-slate-300 text-slate-500 py-3 rounded-2xl font-black text-xs flex items-center justify-center gap-2 cursor-wait transition duration-200";

                window.snap.pay('<?php echo e($pembayaran->snapToken); ?>', {
                    onSuccess: function(result) {
                        window.location.href = `/agen/pesanan/<?php echo e($pesanan->id); ?>?status=success`;
                    },
                    onPending: function(result) {
                        window.location.href = `/agen/pesanan/<?php echo e($pesanan->id); ?>`;
                    },
                    onError: function(result) {
                        // Re-enable button on error
                        btnPay.disabled = false;
                        btnPay.innerHTML = '<i class="fa-solid fa-credit-card"></i> Bayar Sekarang';
                        btnPay.className = "w-full bg-[#58CC02] hover:bg-[#46a302] text-white py-3 rounded-2xl font-black text-xs flex items-center justify-center gap-2 shadow-sm transition duration-200 cursor-pointer";
                        window.location.href = `/agen/pesanan/<?php echo e($pesanan->id); ?>`;
                    },
                    onClose: function() {
                        // Re-enable button on close
                        btnPay.disabled = false;
                        btnPay.innerHTML = '<i class="fa-solid fa-credit-card"></i> Bayar Sekarang';
                        btnPay.className = "w-full bg-[#58CC02] hover:bg-[#46a302] text-white py-3 rounded-2xl font-black text-xs flex items-center justify-center gap-2 shadow-sm transition duration-200 cursor-pointer";
                        window.location.href = `/agen/pesanan/<?php echo e($pesanan->id); ?>`;
                    }
                });
            });
        }
    });
</script>
<?php endif; ?>

<?php
    $orderRefunds = \App\Models\Refund::where('pesananId', $pesanan->id)->with('detailPesanan.produk')->get();
?>

<?php if($orderRefunds->isNotEmpty()): ?>
    <div class="bg-white p-4 md:p-6 rounded-3xl border border-gray-100 shadow-sm mt-6">
        <h2 class="font-extrabold text-[10px] md:text-xs mb-4 uppercase tracking-wider text-gray-400 pb-2 border-b border-gray-50">Daftar Pengajuan Refund Pesanan Ini</h2>
        <div class="divide-y divide-gray-150">
            <?php $__currentLoopData = $orderRefunds; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $refund): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="py-4 first:pt-0 last:pb-0 flex flex-col sm:flex-row sm:items-center justify-between gap-4 text-xs font-semibold">
                    <div class="flex items-center gap-3">
                        <?php if($refund->foto_bukti): ?>
                            <a href="<?php echo e(asset('storage/' . $refund->foto_bukti)); ?>" target="_blank" class="w-10 h-10 rounded-lg overflow-hidden border border-gray-150 shrink-0 block hover:opacity-85 transition">
                                <img src="<?php echo e(asset('storage/' . $refund->foto_bukti)); ?>" class="w-full h-full object-cover">
                            </a>
                        <?php endif; ?>
                        <div>
                            <p class="text-gray-800"><?php echo e($refund->detailPesanan->produk->namaProduk ?? 'Produk Telah Dihapus'); ?> (<?php echo e($refund->jumlah); ?> unit)</p>
                            <p class="text-[10px] text-gray-400 mt-0.5">Alasan: <?php echo e($refund->alasan); ?></p>
                            <?php if($refund->catatan_admin): ?>
                                <p class="text-[10px] text-red-500 mt-0.5">Catatan Admin: <?php echo e($refund->catatan_admin); ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 justify-between sm:justify-end">
                        <div class="text-right">
                            <span class="text-[9px] text-gray-400 block uppercase font-bold">Nominal</span>
                            <span class="font-black text-gray-900">Rp <?php echo e(number_format($refund->nominal, 0, ',', '.')); ?></span>
                        </div>
                        <div>
                            <?php if($refund->status === 'pending'): ?>
                                <span class="bg-amber-50 text-amber-600 border border-amber-200 px-2 py-0.5 rounded-full text-[9px] uppercase font-black tracking-wide">Pending</span>
                            <?php elseif($refund->status === 'disetujui'): ?>
                                <span class="bg-green-50 text-green-600 border border-green-200 px-2 py-0.5 rounded-full text-[9px] uppercase font-black tracking-wide">Disetujui</span>
                            <?php else: ?>
                                <span class="bg-red-50 text-red-600 border border-red-200 px-2 py-0.5 rounded-full text-[9px] uppercase font-black tracking-wide">Ditolak</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
<?php endif; ?>

<div id="refundModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 overflow-x-hidden overflow-y-auto outline-none focus:outline-none">
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm transition-opacity duration-300 modal-overlay" onclick="closeRefundModal()"></div>

    <div class="relative bg-white rounded-3xl p-6 md:p-8 max-w-md w-full shadow-2xl border border-gray-100 transition-all duration-300 transform scale-95 opacity-0" id="refundModalContent">
        <h3 class="text-xl font-extrabold text-gray-900 mb-4">Form Pengajuan Refund</h3>

        <form action="<?php echo e(route('agen.refund.store')); ?>" method="POST" enctype="multipart/form-data" class="space-y-4">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="pesananId" value="<?php echo e($pesanan->id); ?>">
            <input type="hidden" name="detailPesananId" id="modalDetailPesananId">

            <div class="space-y-1.5">
                <label class="block text-xs font-black text-gray-400 uppercase tracking-wider">Nama Produk</label>
                <input type="text" id="modalNamaProduk" readonly class="w-full bg-gray-100 border border-gray-200 rounded-2xl py-3 px-4 text-xs font-bold text-gray-600 focus:outline-none">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-1.5">
                    <label for="modalJumlah" class="block text-xs font-black text-gray-400 uppercase tracking-wider">Jumlah Refund</label>
                    <input type="number" name="jumlah" id="modalJumlah" min="1" required class="w-full bg-gray-50 border border-gray-200 rounded-2xl py-3 px-4 text-xs font-bold focus:outline-none focus:bg-white focus:border-green-500 transition duration-200">
                    <span id="modalMaxQtyHint" class="text-[9px] text-gray-400 font-bold block mt-1"></span>
                </div>

                <div class="space-y-1.5">
                    <label class="block text-xs font-black text-gray-400 uppercase tracking-wider">Nominal Refund</label>
                    <div class="relative">
                        <span class="absolute left-4 top-3.5 text-xs font-bold text-gray-400">Rp</span>
                        <input type="text" id="modalNominalVisual" readonly class="w-full bg-gray-100 border border-gray-200 rounded-2xl py-3 pl-9 pr-4 text-xs font-bold text-gray-600 focus:outline-none" value="0">
                    </div>
                </div>
            </div>

            <div class="space-y-1.5">
                <label for="modalAlasan" class="block text-xs font-black text-gray-400 uppercase tracking-wider">Alasan Refund</label>
                <textarea name="alasan" id="modalAlasan" rows="3" required placeholder="Sebutkan detail kerusakan atau masalah produk..." class="w-full bg-gray-50 border border-gray-200 rounded-2xl py-3 px-4 text-xs font-bold focus:outline-none focus:bg-white focus:border-green-500 transition duration-200"></textarea>
            </div>

            <div class="space-y-1.5">
                <label for="modalFoto" class="block text-xs font-black text-gray-400 uppercase tracking-wider">Foto Bukti Barang</label>
                <input type="file" name="foto_bukti" id="modalFoto" accept="image/*" required class="w-full bg-gray-50 border border-gray-200 rounded-2xl py-3 px-4 text-xs font-bold focus:outline-none focus:bg-white focus:border-green-500 transition duration-200">
            </div>

            <div class="flex gap-3 pt-2">
                <button type="button" onclick="closeRefundModal()" class="flex-1 py-3.5 border border-gray-200 hover:bg-gray-50 text-gray-700 font-bold rounded-2xl transition duration-200 text-xs cursor-pointer">
                    Batal
                </button>
                <button type="submit" class="flex-1 py-3.5 bg-[#58CC02] hover:bg-[#46a302] text-white font-bold rounded-2xl transition duration-200 text-xs shadow-sm cursor-pointer">
                    Kirim Pengajuan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
let refundPricePerUnit = 0;
let refundMaxQty = 0;

function openRefundModal(detailId, productName, price, maxQty) {
    document.getElementById('modalDetailPesananId').value = detailId;
    document.getElementById('modalNamaProduk').value = productName;
    document.getElementById('modalJumlah').value = 1;
    document.getElementById('modalJumlah').max = maxQty;
    document.getElementById('modalMaxQtyHint').textContent = `Maksimal: ${maxQty} unit`;
    document.getElementById('modalAlasan').value = '';
    document.getElementById('modalFoto').value = '';

    refundPricePerUnit = price;
    refundMaxQty = maxQty;

    calculateModalNominal();

    const modal = document.getElementById('refundModal');
    const content = document.getElementById('refundModalContent');
    document.body.style.overflow = 'hidden';
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    setTimeout(() => {
        content.classList.remove('scale-95', 'opacity-0');
        content.classList.add('scale-100', 'opacity-100');
    }, 10);
}

function closeRefundModal() {
    const modal = document.getElementById('refundModal');
    const content = document.getElementById('refundModalContent');
    content.classList.remove('scale-100', 'opacity-100');
    content.classList.add('scale-95', 'opacity-0');
    setTimeout(() => {
        modal.classList.remove('flex');
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }, 300);
}

document.getElementById('modalJumlah').addEventListener('input', function() {
    let val = parseInt(this.value);
    if (isNaN(val) || val < 1) {
        this.value = '';
        document.getElementById('modalNominalVisual').value = '0';
        return;
    }
    if (val > refundMaxQty) {
        this.value = refundMaxQty;
    }
    calculateModalNominal();
});

function calculateModalNominal() {
    const qty = parseInt(document.getElementById('modalJumlah').value) || 0;
    const total = qty * refundPricePerUnit;
    document.getElementById('modalNominalVisual').value = new Intl.NumberFormat('id-ID').format(total);
}

function copyResiSidebar(btn, resi) {
    navigator.clipboard.writeText(resi).then(() => {
        btn.innerHTML = '<i class="fa-solid fa-check text-[#58CC02] text-xs"></i>';
        setTimeout(() => {
            btn.innerHTML = '<i class="fa-regular fa-copy text-xs"></i>';
        }, 1500);
    });
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.agen', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\project\Agris\resources\views\agen\pesanan\show.blade.php ENDPATH**/ ?>