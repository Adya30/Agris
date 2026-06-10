<?php $__env->startSection('title', 'Detail Transaksi #' . $pesanan->id . ' - Admin AGRIS'); ?>

<?php $__env->startSection('content'); ?>
<?php
    $pembayaran = $pesanan->pembayaran;
    $paymentInfo = null;
    if ($pembayaran && $pembayaran->payment_info) {
        $paymentInfo = json_decode($pembayaran->payment_info, true);
    }

    $biteshipOrderId = null;
    if ($pesanan->deskripsi) {
        $parts = explode('|', $pesanan->deskripsi);
        foreach ($parts as $part) {
            $part = trim($part);
            if (str_starts_with(strtolower($part), 'biteship order id:')) {
                $biteshipOrderId = trim(substr($part, 18));
            }
        }
    }
?>
<div class="max-w-6xl mx-auto pt-5 pb-12">
    <!-- Breadcrumb & Header -->
    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <a href="<?php echo e(route('admin.pesanan.index')); ?>" class="inline-flex items-center gap-1 text-[10px] md:text-xs font-bold text-gray-400 hover:text-gray-600 transition uppercase tracking-wider mb-2">
                <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar Transaksi
            </a>
            <h1 class="text-2xl md:text-3xl font-extrabold text-gray-950 tracking-tight">Detail Pesanan</h1>
            <p class="text-[10px] md:text-xs text-gray-500 font-mono font-bold mt-1">ID Transaksi: <?php echo e($pesanan->id); ?></p>
        </div>
        <div>
            <span class="text-[10px] md:text-xs text-gray-400 font-bold">Tanggal: <?php echo e(\Carbon\Carbon::parse($pesanan->created_at)->timezone('Asia/Jakarta')->locale('id')->translatedFormat('d F Y H:i')); ?> WIB</span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
        
        <!-- Left Side: Order Details, Delivery & Billing -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- 1. Detail Produk Dipesan -->
            <div class="bg-white p-4 md:p-6 rounded-3xl border border-gray-150 shadow-sm">
                <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-100">
                    <div class="w-8 h-8 rounded-xl bg-green-50 flex items-center justify-center text-[#0f8629]">
                        <i class="fa-solid fa-seedling text-sm"></i>
                    </div>
                    <h2 class="font-extrabold text-gray-800 text-xs md:text-sm">Manifest Produk Dipesan</h2>
                </div>
                
                <div class="divide-y divide-gray-100">
                    <?php $__currentLoopData = $pesanan->detailPesanans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="flex items-center gap-3 md:gap-4 py-4 first:pt-0 last:pb-0">
                            <div class="w-12 h-12 md:w-14 md:h-14 bg-gray-50 rounded-xl overflow-hidden border border-gray-100 flex items-center justify-center p-1 shrink-0">
                                <?php if($detail->produk && $detail->produk->fotoProduk): ?>
                                    <img src="<?php echo e(asset('storage/' . $detail->produk->fotoProduk)); ?>" class="w-full h-full object-cover rounded-lg">
                                <?php else: ?>
                                    <i class="fa-solid fa-image text-lg text-gray-300"></i>
                                <?php endif; ?>
                            </div>
                            <div class="grow min-w-0">
                                <h4 class="font-extrabold text-gray-800 text-[11px] md:text-xs truncate"><?php echo e($detail->produk->namaProduk ?? 'Produk Dihapus'); ?></h4>
                                <p class="text-[10px] md:text-[11px] text-gray-400 mt-1 font-bold">
                                    <?php echo e($detail->jumlahPesanan); ?> unit • Rp <?php echo e(number_format($detail->harga_satuan, 0, ',', '.')); ?>

                                </p>
                            </div>
                            <div class="text-right shrink-0">
                                <span class="font-black text-gray-900 text-xs md:text-sm">
                                    Rp <?php echo e(number_format($detail->subtotal, 0, ',', '.')); ?>

                                </span>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>

            <!-- 2. Informasi Pengiriman -->
            <div class="bg-white p-4 md:p-6 rounded-3xl border border-gray-150 shadow-sm">
                <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-100">
                    <div class="w-8 h-8 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600">
                        <i class="fa-solid fa-truck-fast text-sm"></i>
                    </div>
                    <h2 class="font-extrabold text-gray-800 text-xs md:text-sm">Informasi & Alamat Pengiriman</h2>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-xs text-gray-600 font-bold leading-relaxed">
                    <div class="space-y-3">
                        <div>
                            <span class="text-[9px] text-gray-400 font-black uppercase tracking-wider block">Penerima</span>
                            <span class="text-gray-800 text-xs md:text-sm font-black"><?php echo e($pesanan->user->namaLengkap); ?></span>
                        </div>
                        <div>
                            <span class="text-[9px] text-gray-400 font-black uppercase tracking-wider block">Nomor Telepon</span>
                            <span class="text-gray-800 text-xs md:text-sm"><?php echo e($pesanan->user->noTelp); ?></span>
                        </div>
                        <div>
                            <span class="text-[9px] text-gray-400 font-black uppercase tracking-wider block">Opsi Kurir / Deskripsi</span>
                            <span class="text-gray-800 bg-gray-50 border border-gray-100 rounded-lg px-2.5 py-1.5 inline-block font-mono mt-1 text-[10px] md:text-[11px]">
                                <?php echo e($pesanan->deskripsi); ?>

                            </span>
                        </div>
                    </div>
                    
                    <div>
                        <span class="text-[9px] text-gray-400 font-black uppercase tracking-wider block mb-1">Alamat Tujuan</span>
                        <div class="bg-gray-50 border border-gray-100 p-3 rounded-2xl">
                            <p class="text-gray-700 text-xs md:text-sm leading-normal"><?php echo e($pesanan->alamat_pengiriman); ?></p>
                            <?php if($pesanan->user->desa): ?>
                                <p class="text-gray-400 mt-2 font-semibold text-[10px] md:text-xs">
                                    Desa <?php echo e($pesanan->user->desa->namaDesa); ?>, Kec. <?php echo e($pesanan->user->desa->kecamatan->namaKecamatan ?? ''); ?>, <?php echo e($pesanan->user->desa->kecamatan->kabupaten->namaKabupaten ?? ''); ?>, <?php echo e($pesanan->user->desa->kecamatan->kabupaten->provinsi->namaProvinsi ?? ''); ?>

                                </p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <?php if(in_array($pesanan->status_pesanan, ['dikirim', 'selesai'])): ?>
            <!-- Tracking Status Pengiriman -->
            <div class="bg-white p-4 md:p-6 rounded-3xl border border-gray-150 shadow-sm">
                <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-100">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-xl bg-purple-50 flex items-center justify-center text-purple-600">
                            <i class="fa-solid fa-map-location-dot text-sm"></i>
                        </div>
                        <h2 class="font-extrabold text-gray-800 text-xs md:text-sm">Tracking Status Pengiriman (Biteship)</h2>
                    </div>
                    <?php if(!empty($biteshipOrderId)): ?>
                        <a href="https://track.biteship.com/<?php echo e($biteshipOrderId); ?>" target="_blank" class="inline-flex items-center gap-1.5 bg-blue-50 border border-blue-100 px-3 py-1 rounded-lg text-blue-650 font-bold text-[10px] hover:bg-blue-100 transition">
                            <i class="fa-solid fa-arrow-up-right-from-square"></i> Lacak di Biteship
                        </a>
                    <?php endif; ?>
                </div>
                
                <div class="relative border-l-2 border-dashed border-gray-200 pl-6 ml-2.5 space-y-5">
                    <?php if(isset($trackingData) && !empty($trackingData['history'])): ?>
                        <?php $__currentLoopData = array_reverse($trackingData['history']); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="relative">
                                <?php
                                    $isLatest = $loop->first;
                                    $statusLabel = match($event['status']) {
                                        'confirmed' => 'Pesanan Dikonfirmasi',
                                        'allocated' => 'Kurir Dialokasikan',
                                        'pickingUp' => 'Proses Penjemputan',
                                        'picked' => 'Paket Dijemput Kurir',
                                        'inTransit' => 'Dalam Transit / Pengiriman',
                                        'droppingOff' => 'Kurir Menuju Lokasi Tujuan',
                                        'delivered' => 'Paket Diterima',
                                        'rejected' => 'Paket Ditolak/Bermasalah',
                                        'cancelled' => 'Pengiriman Dibatalkan',
                                        'returned' => 'Paket Dikembalikan',
                                        default => strtoupper($event['status'])
                                    };
                                ?>
                                <span class="absolute -left-8.5 top-0.5 w-3.5 h-3.5 rounded-full <?php echo e($isLatest ? 'bg-purple-600' : 'bg-gray-300'); ?> border-2 border-white shadow-sm"></span>
                                <div class="text-xs">
                                    <span class="text-gray-400 font-bold block mb-0.5"><?php echo e(\Carbon\Carbon::parse($event['updated_at'])->timezone('Asia/Jakarta')->locale('id')->translatedFormat('d M Y, H:i')); ?> WIB</span>
                                    <p class="font-extrabold <?php echo e($isLatest ? 'text-purple-600' : 'text-gray-700'); ?>"><?php echo e($statusLabel); ?></p>
                                    <p class="text-gray-500 mt-0.5 font-medium leading-relaxed"><?php echo e($event['note']); ?></p>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php else: ?>
                        <!-- Fallback simulation if no real tracking data -->
                        <?php
                            $updatedTime = $pesanan->updated_at;
                            $waktuBayar = $pembayaran && $pembayaran->waktuDibayar ? \Carbon\Carbon::parse($pembayaran->waktuDibayar) : $pesanan->created_at->addHours(1);

                            $logTime1 = $updatedTime;
                            $logTime2 = $logTime1->copy()->subHours(2);
                            $logTime3 = $logTime1->copy()->subHours(5);
                            $logTime4 = $waktuBayar;

                            $deskripsi = $pesanan->deskripsi;
                            $courierInfo = 'Kurir';
                            $noResi = '';
                            if ($deskripsi) {
                                $parts = explode('|', $deskripsi);
                                foreach ($parts as $part) {
                                    $part = trim($part);
                                    if (str_starts_with(strtolower($part), 'opsi:')) {
                                        $courierInfo = trim(substr($part, 5));
                                    } elseif (str_starts_with(strtolower($part), 'no resi:')) {
                                        $noResi = trim(substr($part, 8));
                                    }
                                }
                            }
                            $isAmbil = str_contains(strtolower($courierInfo), 'ambil');
                        ?>

                        <?php if($isAmbil): ?>
                            <div class="relative">
                                <span class="absolute -left-8.5 top-0.5 w-3.5 h-3.5 rounded-full bg-blue-600 border-2 border-white shadow-sm"></span>
                                <div class="text-xs">
                                    <span class="text-gray-400 font-bold block mb-0.5"><?php echo e(\Carbon\Carbon::parse($logTime1)->timezone('Asia/Jakarta')->locale('id')->translatedFormat('d M Y, H:i')); ?> WIB</span>
                                    <p class="font-extrabold text-gray-800">Siap Diambil di Counter AGRIS</p>
                                    <p class="text-gray-500 mt-0.5 font-medium leading-relaxed">Pesanan telah dikemas dan diletakkan pada rak pengambilan mandiri.</p>
                                </div>
                            </div>
                            <div class="relative">
                                <span class="absolute -left-8.5 top-0.5 w-3.5 h-3.5 rounded-full bg-gray-300 border-2 border-white shadow-sm"></span>
                                <div class="text-xs">
                                    <span class="text-gray-400 font-bold block mb-0.5"><?php echo e(\Carbon\Carbon::parse($logTime2)->timezone('Asia/Jakarta')->locale('id')->translatedFormat('d M Y, H:i')); ?> WIB</span>
                                    <p class="font-extrabold text-gray-700">Dalam Proses Pengemasan</p>
                                    <p class="text-gray-500 mt-0.5 font-medium leading-relaxed">Staf gudang sedang mengumpulkan item pesanan.</p>
                                </div>
                            </div>
                        <?php else: ?>
                            <?php if($pesanan->status_pesanan === 'selesai'): ?>
                                <div class="relative">
                                    <span class="absolute -left-8.5 top-0.5 w-3.5 h-3.5 rounded-full bg-green-600 border-2 border-white shadow-sm"></span>
                                    <div class="text-xs">
                                        <span class="text-gray-400 font-bold block mb-0.5"><?php echo e(\Carbon\Carbon::parse($pesanan->updated_at)->timezone('Asia/Jakarta')->locale('id')->translatedFormat('d M Y, H:i')); ?> WIB</span>
                                        <p class="font-extrabold text-green-600">Pesanan Selesai</p>
                                        <p class="text-gray-500 mt-0.5 font-medium leading-relaxed">Paket telah diterima dengan baik oleh penerima.</p>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <div class="relative">
                                <span class="absolute -left-8.5 top-0.5 w-3.5 h-3.5 rounded-full bg-blue-600 border-2 border-white shadow-sm"></span>
                                <div class="text-xs">
                                    <span class="text-gray-400 font-bold block mb-0.5"><?php echo e(\Carbon\Carbon::parse($logTime1)->timezone('Asia/Jakarta')->locale('id')->translatedFormat('d M Y, H:i')); ?> WIB</span>
                                    <p class="font-extrabold text-gray-800">Paket Sedang Diantar Kurir</p>
                                    <p class="text-gray-500 mt-0.5 font-medium leading-relaxed">Kurir dari <strong><?php echo e(strtoupper($courierInfo)); ?></strong> sedang dalam perjalanan menuju alamat pengiriman. (Nomor Resi: <?php echo e($noResi); ?>)</p>
                                </div>
                            </div>
                            <div class="relative">
                                <span class="absolute -left-8.5 top-0.5 w-3.5 h-3.5 rounded-full bg-gray-300 border-2 border-white shadow-sm"></span>
                                <div class="text-xs">
                                    <span class="text-gray-400 font-bold block mb-0.5"><?php echo e(\Carbon\Carbon::parse($logTime2)->timezone('Asia/Jakarta')->locale('id')->translatedFormat('d M Y, H:i')); ?> WIB</span>
                                    <p class="font-extrabold text-gray-700">Paket Diserahkan ke Ekspedisi</p>
                                    <p class="text-gray-500 mt-0.5 font-medium leading-relaxed">Paket pesanan diserahkan ke gerai kurir terdekat.</p>
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

            <!-- 3. Rincian Pembayaran (Midtrans) -->
            <div class="bg-white p-4 md:p-6 rounded-3xl border border-gray-150 shadow-sm">
                <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-100">
                    <div class="w-8 h-8 rounded-xl bg-amber-50 flex items-center justify-center text-amber-600">
                        <i class="fa-solid fa-wallet text-sm"></i>
                    </div>
                    <h2 class="font-extrabold text-gray-800 text-xs md:text-sm">Rincian Pembayaran</h2>
                </div>

                <?php if($pesanan->pembayaran): ?>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-xs font-bold">
                        <div>
                            <span class="text-[9px] text-gray-400 font-black uppercase tracking-wider block">Total Tagihan</span>
                            <span class="text-base md:text-lg font-black text-gray-800 mt-1 block">Rp <?php echo e(number_format($pesanan->pembayaran->totalPembayaran, 0, ',', '.')); ?></span>
                        </div>
                        <div>
                            <span class="text-[9px] text-gray-400 font-black uppercase tracking-wider block">Status Midtrans</span>
                            <div class="mt-1">
                                <?php if($pesanan->pembayaran->statusPembayaran === 'berhasil'): ?>
                                    <span class="bg-green-50 text-green-600 border border-green-200 px-3 py-1 rounded-full text-[9px] md:text-[10px] uppercase font-black tracking-wider inline-flex items-center gap-1">
                                        <i class="fa-solid fa-check"></i> BERHASIL
                                    </span>
                                <?php elseif($pesanan->pembayaran->statusPembayaran === 'pending'): ?>
                                    <span class="bg-amber-50 text-amber-600 border border-amber-200 px-3 py-1 rounded-full text-[9px] md:text-[10px] uppercase font-black tracking-wider inline-flex items-center gap-1">
                                        <i class="fa-solid fa-clock"></i> PENDING
                                    </span>
                                <?php else: ?>
                                    <span class="bg-red-50 text-red-600 border border-red-200 px-3 py-1 rounded-full text-[9px] md:text-[10px] uppercase font-black tracking-wider inline-flex items-center gap-1">
                                        <i class="fa-solid fa-xmark"></i> GAGAL
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="space-y-2 font-mono text-[9px] md:text-[10px] text-gray-400">
                            <div>
                                <span class="block font-sans uppercase font-black text-[9px]">Pilihan Metode Pembayaran</span>
                                <span class="text-gray-700 font-black text-xs">
                                    <?php
                                        $type = strtolower($pesanan->pembayaran->paymentType ?? '');
                                        if ($type === 'midtrans_snap' || $type === 'simulasi_midtrans' || !$type) {
                                            $displayMethod = 'MIDTRANS ONLINE';
                                        } else {
                                            $displayMethod = strtoupper(str_replace('_', ' ', $pesanan->pembayaran->paymentType));
                                        }
                                    ?>
                                    <?php echo e($displayMethod); ?>

                                </span>
                            </div>
                            <div>
                                <span class="block font-sans uppercase font-black text-[9px]">ID Transaksi</span>
                                <span class="text-gray-500 select-all font-bold break-all"><?php echo e($pesanan->pembayaran->transactionId ?? '-'); ?></span>
                            </div>
                        </div>
                        <?php if($pesanan->pembayaran->snapToken): ?>
                            <div class="mt-4 pt-4 border-t border-gray-100 col-span-1 md:col-span-3 text-xs font-bold">
                                <span class="text-[9px] text-gray-400 font-black uppercase tracking-wider block mb-2">Midtrans Snap Token</span>
                                <div class="bg-gray-50 p-3.5 rounded-2xl font-mono text-[10px] md:text-[11px] select-all tracking-wider text-gray-700 border border-gray-150 break-all">
                                    <?php echo e($pesanan->pembayaran->snapToken); ?>

                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php else: ?>
                    <p class="text-gray-400 text-xs font-semibold">Rincian pembayaran belum dibuat.</p>
                <?php endif; ?>
            </div>

        </div>

        <!-- Right Side: Order Status Control panel -->
        <div class="lg:sticky lg:top-28 space-y-6">
            <div class="bg-white p-4 md:p-6 rounded-3xl border border-gray-150 shadow-sm relative overflow-hidden">
                <!-- Header Status Panel -->
                <div class="absolute top-0 right-0 w-20 h-20 bg-gradient-to-bl from-green-50 to-transparent pointer-events-none rounded-bl-full"></div>
                
                <h2 class="font-extrabold text-gray-800 text-xs md:text-sm mb-4 pb-3 border-b border-gray-100 uppercase tracking-wider">Status & Aksi Respon</h2>

                <!-- Current Status Badge Display -->
                <div class="mb-6 bg-gray-50 border border-gray-100 p-4 rounded-2xl flex items-center justify-between">
                    <span class="text-[10px] md:text-xs text-gray-400 font-black uppercase tracking-wider">Status Saat Ini</span>
                    <div>
                        <?php if($pesanan->status_pesanan === 'pending'): ?>
                            <span class="bg-amber-50 text-amber-600 border border-amber-200 px-3 py-1 rounded-xl text-[10px] md:text-xs font-black uppercase">Belum Bayar</span>
                        <?php elseif($pesanan->status_pesanan === 'diproses'): ?>
                            <span class="bg-blue-50 text-blue-600 border border-blue-200 px-3 py-1 rounded-xl text-[10px] md:text-xs font-black uppercase">Dikemas</span>
                        <?php elseif($pesanan->status_pesanan === 'dikirim'): ?>
                            <span class="bg-purple-50 text-purple-600 border border-purple-200 px-3 py-1 rounded-xl text-[10px] md:text-xs font-black uppercase">Dikirim</span>
                        <?php elseif($pesanan->status_pesanan === 'selesai'): ?>
                            <span class="bg-green-50 text-green-600 border border-green-200 px-3 py-1 rounded-xl text-[10px] md:text-xs font-black uppercase">Selesai</span>
                        <?php else: ?>
                            <span class="bg-red-50 text-red-600 border border-red-200 px-3 py-1 rounded-xl text-[10px] md:text-xs font-black uppercase">Dibatalkan</span>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Control Board Buttons -->
                <div class="space-y-4">
                    <?php if($pesanan->status_pesanan === 'pending'): ?>
                        <!-- 1. Pending Actions: Approve payment or Cancel -->
                        <div class="space-y-3">
                            <p class="text-[10px] md:text-[11px] text-gray-400 font-bold leading-normal">Pembayaran belum terkonfirmasi oleh Midtrans. Jika pelanggan telah membayar via jalur alternatif, Anda dapat mengonfirmasi pembayaran secara manual.</p>
                            
                            <form action="<?php echo e(route('admin.pesanan.action', $pesanan->id)); ?>" method="POST">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="action" value="proses">
                                <button type="submit" class="w-full bg-[#0f8629] hover:bg-[#0c6b20] text-white py-3 rounded-2xl font-black text-xs flex items-center justify-center gap-2 shadow-sm transition">
                                    <i class="fa-solid fa-credit-card"></i> Konfirmasi Bayar & Proses
                                </button>
                            </form>

                            <form action="<?php echo e(route('admin.pesanan.action', $pesanan->id)); ?>" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan transaksi ini? Stok produk akan dikembalikan.')">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="action" value="batal">
                                <button type="submit" class="w-full border border-red-200 text-red-600 hover:bg-red-50 py-3 rounded-2xl font-black text-xs flex items-center justify-center gap-2 transition">
                                    <i class="fa-solid fa-ban"></i> Batalkan Pesanan
                                </button>
                            </form>
                        </div>

                    <?php elseif($pesanan->status_pesanan === 'diproses'): ?>
                        <!-- 2. Processing (Dikemas) Actions: Ship with tracking code or Cancel -->
                        <div class="space-y-4">
                            <?php
                                $isAmbil = str_contains(strtolower($pesanan->deskripsi), 'ambil');
                            ?>

                            <?php if($isAmbil): ?>
                                <p class="text-[10px] md:text-[11px] text-gray-400 font-bold leading-normal">Pesanan ini menggunakan opsi <strong>Ambil di Tempat</strong>. Klik tombol di bawah ini untuk menandai pesanan siap diambil.</p>
                                
                                <form id="formKirimPesanan" action="<?php echo e(route('admin.pesanan.action', $pesanan->id)); ?>" method="POST" class="space-y-3">
                                    <?php echo csrf_field(); ?>
                                    <input type="hidden" name="action" value="kirim">
                                    <button type="submit" id="btnKirimPesanan" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-2xl font-black text-xs flex items-center justify-center gap-2 shadow-sm transition cursor-pointer">
                                        <i class="fa-solid fa-warehouse"></i> Siapkan untuk Diambil
                                    </button>
                                </form>
                            <?php else: ?>
                                <p class="text-[10px] md:text-[11px] text-gray-400 font-bold leading-normal">Pesanan sedang dikemas. Klik tombol di bawah ini untuk memproses pengiriman dan membuat nomor resi otomatis dari Biteship Sandbox.</p>
                                
                                <form id="formKirimPesanan" action="<?php echo e(route('admin.pesanan.action', $pesanan->id)); ?>" method="POST" class="space-y-3">
                                    <?php echo csrf_field(); ?>
                                    <input type="hidden" name="action" value="kirim">
                                    <button type="submit" id="btnKirimPesanan" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-2xl font-black text-xs flex items-center justify-center gap-2 shadow-sm transition cursor-pointer">
                                        <i class="fa-solid fa-paper-plane"></i> Kirim &amp; Generate Resi Biteship
                                    </button>
                                </form>
                            <?php endif; ?>

                            <script type="text/javascript">
                                document.addEventListener('DOMContentLoaded', function() {
                                    const form = document.getElementById('formKirimPesanan');
                                    const btn = document.getElementById('btnKirimPesanan');
                                    if (form && btn) {
                                        form.addEventListener('submit', function() {
                                            btn.disabled = true;
                                            btn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin mr-2"></i> Menghubungkan Biteship...';
                                            btn.className = "w-full bg-slate-300 text-slate-500 py-3 rounded-2xl font-black text-xs flex items-center justify-center gap-2 cursor-wait transition";
                                        });
                                    }
                                });
                            </script>

                            <div class="border-t border-gray-100 my-4"></div>

                            <form action="<?php echo e(route('admin.pesanan.action', $pesanan->id)); ?>" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan transaksi ini? Stok produk akan dikembalikan.')">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="action" value="batal">
                                <button type="submit" class="w-full border border-red-200 text-red-600 hover:bg-red-50 py-3 rounded-2xl font-black text-xs flex items-center justify-center gap-2 transition">
                                    <i class="fa-solid fa-ban"></i> Batalkan Pesanan
                                </button>
                            </form>
                        </div>

                    <?php elseif($pesanan->status_pesanan === 'dikirim'): ?>
                        <!-- 3. Shipped Actions: Wait for client to complete or complete manually -->
                        <div class="space-y-3">
                            <p class="text-[10px] md:text-[11px] text-gray-400 font-bold leading-normal">Pesanan sedang dalam perjalanan ke alamat tujuan. Pelanggan dapat menandai pesanan selesai dari akun mereka, atau Anda dapat menyelesaikannya secara manual jika konfirmasi kurir telah diterima.</p>
                            
                            <form action="<?php echo e(route('admin.pesanan.action', $pesanan->id)); ?>" method="POST">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="action" value="selesai">
                                <button type="submit" class="w-full bg-[#0f8629] hover:bg-[#0c6b20] text-white py-3 rounded-2xl font-black text-xs flex items-center justify-center gap-2 shadow-sm transition">
                                    <i class="fa-solid fa-circle-check"></i> Tandai Selesai (Manual)
                                </button>
                            </form>
                        </div>

                    <?php elseif($pesanan->status_pesanan === 'selesai'): ?>
                        <!-- 4. Completed Order Status -->
                        <div class="text-center py-6 bg-green-50 border border-green-100 rounded-2xl">
                            <i class="fa-solid fa-circle-check text-green-500 text-3xl mb-2"></i>
                            <h4 class="font-extrabold text-green-800 text-[10px] md:text-xs uppercase tracking-wider">Transaksi Selesai</h4>
                            <p class="text-[9px] md:text-[10px] text-green-700 mt-1 leading-normal px-4 font-bold">Pesanan telah sampai di tangan pelanggan dan transaksi ditutup.</p>
                        </div>

                    <?php elseif($pesanan->status_pesanan === 'dibatalkan'): ?>
                        <!-- 5. Cancelled Order Status -->
                        <div class="text-center py-6 bg-red-50 border border-red-100 rounded-2xl">
                            <i class="fa-solid fa-circle-xmark text-red-500 text-3xl mb-2"></i>
                            <h4 class="font-extrabold text-red-800 text-[10px] md:text-xs uppercase tracking-wider">Transaksi Dibatalkan</h4>
                            <p class="text-[9px] md:text-[10px] text-red-700 mt-1 leading-normal px-4 font-bold">Pesanan telah dibatalkan dan stok barang telah dikembalikan.</p>
                        </div>
                    <?php endif; ?>
                </div>

            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\project\Agris\resources\views\admin\pesanan\show.blade.php ENDPATH**/ ?>