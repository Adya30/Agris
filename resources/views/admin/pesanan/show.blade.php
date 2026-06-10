@extends('layouts.admin')

@section('title', 'Detail Transaksi #' . $pesanan->id . ' - Admin AGRIS')

@section('content')
@php
    $pembayaran = $pesanan->pembayaran;
    $paymentInfo = null;
    if ($pembayaran && $pembayaran->payment_info) {
        $paymentInfo = json_decode($pembayaran->payment_info, true);
    }

    $biteshipOrderId = null;
    $noResi = null;
    $courierInfo = 'Kurir';
    if ($pesanan->deskripsi) {
        $parts = explode('|', $pesanan->deskripsi);
        foreach ($parts as $part) {
            $part = trim($part);
            $lowerPart = strtolower($part);
            if (str_starts_with($lowerPart, 'biteship order id:')) {
                $biteshipOrderId = trim(substr($part, 18));
            } elseif (str_starts_with($lowerPart, 'no resi:')) {
                $noResi = trim(substr($part, 8));
            } elseif (str_starts_with($lowerPart, 'opsi:')) {
                $courierInfo = trim(substr($part, 5));
            }
        }
    }
@endphp
<div class="max-w-6xl mx-auto pt-5 pb-12">
    <!-- Breadcrumb & Header -->
    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <a href="{{ route('admin.pesanan.index') }}" class="inline-flex items-center gap-1 text-[10px] md:text-xs font-bold text-gray-400 hover:text-gray-600 transition uppercase tracking-wider mb-2">
                <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar Transaksi
            </a>
            <h1 class="text-2xl md:text-3xl font-extrabold text-gray-950 tracking-tight">Detail Pesanan</h1>
            <p class="text-[10px] md:text-xs text-gray-500 font-mono font-bold mt-1">Nomor Pesanan: {{ $pesanan->id }}</p>
        </div>
        <div>
            <span class="text-[10px] md:text-xs text-gray-400 font-bold">Tanggal: {{ \Carbon\Carbon::parse($pesanan->created_at)->timezone('Asia/Jakarta')->locale('id')->translatedFormat('d F Y H:i') }} WIB</span>
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
                    @foreach($pesanan->detailPesanans as $detail)
                        <div class="flex items-center gap-3 md:gap-4 py-4 first:pt-0 last:pb-0">
                            <div class="w-12 h-12 md:w-14 md:h-14 bg-gray-50 rounded-xl overflow-hidden border border-gray-100 flex items-center justify-center p-1 shrink-0">
                                @if($detail->produk && $detail->produk->fotoProduk)
                                    <img src="{{ asset('storage/' . $detail->produk->fotoProduk) }}" class="w-full h-full object-cover rounded-lg">
                                @else
                                    <i class="fa-solid fa-image text-lg text-gray-300"></i>
                                @endif
                            </div>
                            <div class="grow min-w-0">
                                <h4 class="font-extrabold text-gray-800 text-[11px] md:text-xs truncate">{{ $detail->produk->namaProduk ?? 'Produk Dihapus' }}</h4>
                                <p class="text-[10px] md:text-[11px] text-gray-400 mt-1 font-bold">
                                    {{ $detail->jumlahPesanan }} unit • Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}
                                </p>
                            </div>
                            <div class="text-right shrink-0">
                                <span class="font-black text-gray-900 text-xs md:text-sm">
                                    Rp {{ number_format($detail->subtotal, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                    @endforeach
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
                            <span class="text-gray-800 text-xs md:text-sm font-black">{{ $pesanan->user->namaLengkap }}</span>
                        </div>
                        <div>
                            <span class="text-[9px] text-gray-400 font-black uppercase tracking-wider block">Nomor Telepon</span>
                            <span class="text-gray-800 text-xs md:text-sm">{{ $pesanan->user->noTelp }}</span>
                        </div>
                        <div>
                            <span class="text-[9px] text-gray-400 font-black uppercase tracking-wider block">Opsi Kurir / Deskripsi</span>
                            <span class="text-gray-800 bg-gray-50 border border-gray-100 rounded-lg px-2.5 py-1.5 inline-block font-mono mt-1 text-[10px] md:text-[11px]">
                                {{ $pesanan->deskripsi }}
                            </span>
                        </div>
                    </div>
                    
                    <div>
                        <span class="text-[9px] text-gray-400 font-black uppercase tracking-wider block mb-1">Alamat Tujuan</span>
                        <div class="bg-gray-50 border border-gray-100 p-3 rounded-2xl">
                            <p class="text-gray-700 text-xs md:text-sm leading-normal">{{ $pesanan->alamat_pengiriman }}</p>
                            @if($pesanan->user->desa)
                                <p class="text-gray-400 mt-2 font-semibold text-[10px] md:text-xs">
                                    Desa {{ $pesanan->user->desa->namaDesa }}, Kec. {{ $pesanan->user->desa->kecamatan->namaKecamatan ?? '' }}, {{ $pesanan->user->desa->kecamatan->kabupaten->namaKabupaten ?? '' }}, {{ $pesanan->user->desa->kecamatan->kabupaten->provinsi->namaProvinsi ?? '' }}
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
                       @if($pesanan->status_pesanan !== 'dibatalkan')
            @php
                $biteshipOrderId = null;
                $noResi = null;
                $courierInfo = 'Kurir';
                if ($pesanan->deskripsi) {
                    $parts = explode('|', $pesanan->deskripsi);
                    foreach ($parts as $part) {
                        $part = trim($part);
                        $lowerPart = strtolower($part);
                        if (str_starts_with($lowerPart, 'biteship order id:')) {
                            $biteshipOrderId = trim(substr($part, 18));
                        } elseif (str_starts_with($lowerPart, 'no resi:')) {
                            $noResi = trim(substr($part, 8));
                        } elseif (str_starts_with($lowerPart, 'opsi:')) {
                            $courierInfo = trim(substr($part, 5));
                        }
                    }
                }
                $isPickup = str_contains(strtolower($courierInfo), 'ambil');
                
                // Biteship tracking status
                $biteshipStatus = $trackingData['status'] ?? null;
                
                // Initialize stage states: 'completed', 'active', 'waiting'
                $stage1Status = 'waiting'; 
                $stage2Status = 'waiting';
                $stage3Status = 'waiting';
                
                $stage1Time = null;
                $stage2Time = null;
                $stage3Time = null;

                if ($isPickup) {
                    // Stage 1 (Pesanan Dikemas)
                    if ($pesanan->status_pesanan === 'diproses') {
                        $stage1Status = 'active';
                    } elseif (in_array($pesanan->status_pesanan, ['dikirim', 'selesai'])) {
                        $stage1Status = 'completed';
                    }
                    
                    // Stage 2 (Siap Diambil)
                    if ($pesanan->status_pesanan === 'dikirim') {
                        $stage2Status = 'active';
                    } elseif ($pesanan->status_pesanan === 'selesai') {
                        $stage2Status = 'completed';
                    }
                    
                    // Stage 3 (Selesai Diambil)
                    if ($pesanan->status_pesanan === 'selesai') {
                        $stage3Status = 'completed';
                    }
                } else {
                    if (!empty($biteshipStatus)) {
                        $pickingUpStatuses = ['confirmed', 'allocated', 'scheduled', 'picking_up', 'pickingUp', 'picked'];
                        $inTransitStatuses = ['in_transit', 'inTransit', 'dropping_off', 'droppingOff'];
                        $deliveredStatuses = ['delivered'];
                        
                        // Stage 1: Penjemputan
                        if (in_array($biteshipStatus, $pickingUpStatuses)) {
                            $stage1Status = 'active';
                        } elseif (in_array($biteshipStatus, array_merge($inTransitStatuses, $deliveredStatuses)) || $pesanan->status_pesanan === 'selesai') {
                            $stage1Status = 'completed';
                        }
                        
                        // Stage 2: Dalam Pengiriman
                        if (in_array($biteshipStatus, $inTransitStatuses)) {
                            $stage2Status = 'active';
                        } elseif (in_array($biteshipStatus, $deliveredStatuses) || $pesanan->status_pesanan === 'selesai') {
                            $stage2Status = 'completed';
                        }
                        
                        // Stage 3: Diterima
                        if (in_array($biteshipStatus, $deliveredStatuses) || $pesanan->status_pesanan === 'selesai') {
                            $stage3Status = 'completed';
                        }
                    } else {
                        // Fallback based on internal status
                        if ($pesanan->status_pesanan === 'diproses') {
                            $stage1Status = 'active';
                        } elseif ($pesanan->status_pesanan === 'dikirim') {
                            $stage1Status = 'completed';
                            $stage2Status = 'active';
                        } elseif ($pesanan->status_pesanan === 'selesai') {
                            $stage1Status = 'completed';
                            $stage2Status = 'completed';
                            $stage3Status = 'completed';
                        }
                    }
                }
                
                // Extract timestamps from history
                if (isset($trackingData['history']) && !empty($trackingData['history'])) {
                    foreach ($trackingData['history'] as $historyEvent) {
                        $histStatus = $historyEvent['status'] ?? '';
                        $histTime = \Carbon\Carbon::parse($historyEvent['updated_at'])->timezone('Asia/Jakarta')->locale('id')->translatedFormat('d M Y, H:i') . ' WIB';
                        
                        if (in_array($histStatus, ['confirmed', 'allocated', 'scheduled', 'picking_up', 'pickingUp', 'picked'])) {
                            $stage1Time = $histTime;
                        }
                        if (in_array($histStatus, ['in_transit', 'inTransit', 'dropping_off', 'droppingOff'])) {
                            $stage2Time = $histTime;
                        }
                        if ($histStatus === 'delivered') {
                            $stage3Time = $histTime;
                        }
                    }
                }
                
                // Fallback times
                if (!$stage1Time && in_array($stage1Status, ['active', 'completed'])) {
                    $stage1Time = \Carbon\Carbon::parse($pesanan->updated_at)->timezone('Asia/Jakarta')->locale('id')->translatedFormat('d M Y, H:i') . ' WIB';
                }
                if (!$stage2Time && in_array($stage2Status, ['active', 'completed']) && $pesanan->status_pesanan === 'dikirim') {
                    $stage2Time = \Carbon\Carbon::parse($pesanan->updated_at)->timezone('Asia/Jakarta')->locale('id')->translatedFormat('d M Y, H:i') . ' WIB';
                }
                if (!$stage3Time && $stage3Status === 'completed' && $pesanan->status_pesanan === 'selesai') {
                    $stage3Time = \Carbon\Carbon::parse($pesanan->updated_at)->timezone('Asia/Jakarta')->locale('id')->translatedFormat('d M Y, H:i') . ' WIB';
                }
            @endphp
            <!-- Tracking Status Pengiriman -->
            <div class="bg-white p-4 md:p-6 rounded-3xl border border-gray-150 shadow-sm space-y-6">
                <div class="flex items-center justify-between mb-2 pb-4 border-b border-gray-100">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-xl bg-purple-50 flex items-center justify-center text-purple-600">
                            <i class="fa-solid fa-map-location-dot text-sm"></i>
                        </div>
                        <h2 class="font-extrabold text-gray-800 text-xs md:text-sm">Status Tracking Pengiriman</h2>
                    </div>
                    @php
                        $trackId = $biteshipOrderId ?: $noResi;
                    @endphp
                    @if(!empty($trackId) && !str_contains(strtoupper($trackId), 'AMBIL'))
                        <a href="https://track.biteship.com/{{ $trackId }}" target="_blank" class="inline-flex items-center gap-1.5 bg-blue-50 border border-blue-100 px-3 py-1 rounded-lg text-blue-650 font-bold text-[10px] hover:bg-blue-100 transition">
                            <i class="fa-solid fa-arrow-up-right-from-square"></i> Lacak di Biteship
                        </a>
                    @endif
                </div>

                <!-- Stepper UI -->
                <div class="relative pl-8 space-y-8 border-l-2 border-gray-100 ml-4 py-2">
                    
                    @if($isPickup)
                        <!-- STEP 1: Pesanan Dikemas -->
                        <div class="relative">
                            <span class="absolute -left-12 top-0.5 flex h-8 w-8 items-center justify-center rounded-full {{ $stage1Status === 'completed' ? 'bg-[#0f8629] text-white ring-4 ring-green-100' : ($stage1Status === 'active' ? 'bg-blue-600 text-white ring-4 ring-blue-100 animate-pulse' : 'bg-gray-100 text-gray-400 ring-4 ring-gray-50') }} shadow-sm">
                                @if($stage1Status === 'completed')
                                    <i class="fa-solid fa-check text-xs"></i>
                                @elseif($stage1Status === 'active')
                                    <i class="fa-solid fa-box-open text-xs"></i>
                                @else
                                    <i class="fa-solid fa-box text-xs"></i>
                                @endif
                            </span>
                            <div>
                                <div class="flex items-center gap-2 flex-wrap">
                                    <h3 class="text-sm font-extrabold {{ $stage1Status !== 'waiting' ? 'text-gray-800' : 'text-gray-400' }}">Pesanan Dikemas</h3>
                                    @if($stage1Status === 'completed')
                                        <span class="bg-green-50 text-[#0f8629] border border-green-200 px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider">Selesai</span>
                                    @elseif($stage1Status === 'active')
                                        <span class="bg-blue-50 text-blue-700 border border-blue-200 px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider animate-pulse">Aktif</span>
                                    @else
                                        <span class="bg-gray-100 text-gray-500 border border-gray-200 px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider">Menunggu</span>
                                    @endif
                                </div>
                                <p class="text-xs {{ $stage1Status !== 'waiting' ? 'text-gray-500' : 'text-gray-400' }} mt-1">
                                    @if($stage1Status === 'completed')
                                        Pesanan telah selesai dikemas oleh staf gudang.
                                    @elseif($stage1Status === 'active')
                                        Pesanan sedang dikemas oleh staf gudang.
                                    @else
                                        Menunggu proses pembayaran selesai dikonfirmasi.
                                    @endif
                                </p>
                                @if($stage1Time)
                                    <span class="inline-block text-[10px] font-bold text-gray-400 mt-1 bg-gray-50 px-2 py-0.5 rounded">{{ $stage1Time }}</span>
                                @endif
                            </div>
                        </div>

                        <!-- STEP 2: Siap Diambil -->
                        <div class="relative">
                            <span class="absolute -left-12 top-0.5 flex h-8 w-8 items-center justify-center rounded-full {{ $stage2Status === 'completed' ? 'bg-[#0f8629] text-white ring-4 ring-green-100' : ($stage2Status === 'active' ? 'bg-blue-600 text-white ring-4 ring-blue-100 animate-pulse' : 'bg-gray-100 text-gray-400 ring-4 ring-gray-50') }} shadow-sm">
                                @if($stage2Status === 'completed')
                                    <i class="fa-solid fa-check text-xs"></i>
                                @elseif($stage2Status === 'active')
                                    <i class="fa-solid fa-store text-xs"></i>
                                @else
                                    <i class="fa-solid fa-store text-xs"></i>
                                @endif
                            </span>
                            <div>
                                <div class="flex items-center gap-2 flex-wrap">
                                    <h3 class="text-sm font-extrabold {{ $stage2Status !== 'waiting' ? 'text-gray-800' : 'text-gray-400' }}">Siap Diambil</h3>
                                    @if($stage2Status === 'completed')
                                        <span class="bg-green-50 text-[#0f8629] border border-green-200 px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider">Selesai</span>
                                    @elseif($stage2Status === 'active')
                                        <span class="bg-blue-50 text-blue-700 border border-blue-200 px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider animate-pulse">Aktif</span>
                                    @else
                                        <span class="bg-gray-100 text-gray-500 border border-gray-200 px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider">Menunggu</span>
                                    @endif
                                </div>
                                <p class="text-xs {{ $stage2Status !== 'waiting' ? 'text-gray-500' : 'text-gray-400' }} mt-1">
                                    @if($stage2Status === 'completed')
                                        Pesanan telah selesai diserahkan ke Agen.
                                    @elseif($stage2Status === 'active')
                                        Pesanan siap diambil di counter Gudang Utama AGRIS.
                                    @else
                                        Menunggu pesanan selesai dikemas.
                                    @endif
                                </p>
                                @if($stage2Time)
                                    <span class="inline-block text-[10px] font-bold text-gray-400 mt-1 bg-gray-50 px-2 py-0.5 rounded">{{ $stage2Time }}</span>
                                @endif
                            </div>
                        </div>

                        <!-- STEP 3: Selesai Diambil -->
                        <div class="relative">
                            <span class="absolute -left-12 top-0.5 flex h-8 w-8 items-center justify-center rounded-full {{ $stage3Status === 'completed' ? 'bg-[#0f8629] text-white ring-4 ring-green-100' : 'bg-gray-100 text-gray-400 ring-4 ring-gray-50' }} shadow-sm">
                                @if($stage3Status === 'completed')
                                    <i class="fa-solid fa-check text-xs"></i>
                                @else
                                    <i class="fa-solid fa-circle-check text-xs"></i>
                                @endif
                            </span>
                            <div>
                                <div class="flex items-center gap-2 flex-wrap">
                                    <h3 class="text-sm font-extrabold {{ $stage3Status !== 'waiting' ? 'text-gray-800' : 'text-gray-400' }}">Selesai Diambil</h3>
                                    @if($stage3Status === 'completed')
                                        <span class="bg-green-50 text-[#0f8629] border border-green-200 px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider">Selesai</span>
                                    @else
                                        <span class="bg-gray-100 text-gray-500 border border-gray-200 px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider">Menunggu</span>
                                    @endif
                                </div>
                                <p class="text-xs {{ $stage3Status !== 'waiting' ? 'text-gray-500' : 'text-gray-400' }} mt-1">
                                    @if($stage3Status === 'completed')
                                        Seluruh proses transaksi selesai. Produk telah diterima oleh Agen.
                                    @else
                                        Menunggu pengambilan selesai dilakukan.
                                    @endif
                                </p>
                                @if($stage3Time)
                                    <span class="inline-block text-[10px] font-bold text-gray-400 mt-1 bg-gray-50 px-2 py-0.5 rounded">{{ $stage3Time }}</span>
                                @endif
                            </div>
                        </div>
                    @else
                        <!-- STEP 1: Penjemputan -->
                        <div class="relative">
                            <span class="absolute -left-12 top-0.5 flex h-8 w-8 items-center justify-center rounded-full {{ $stage1Status === 'completed' ? 'bg-[#0f8629] text-white ring-4 ring-green-100' : ($stage1Status === 'active' ? 'bg-blue-600 text-white ring-4 ring-blue-100 animate-pulse' : 'bg-gray-100 text-gray-400 ring-4 ring-gray-50') }} shadow-sm">
                                @if($stage1Status === 'completed')
                                    <i class="fa-solid fa-check text-xs"></i>
                                @elseif($stage1Status === 'active')
                                    <i class="fa-solid fa-truck-ramp-box text-xs"></i>
                                @else
                                    <i class="fa-solid fa-truck-ramp-box text-xs"></i>
                                @endif
                            </span>
                            <div>
                                <div class="flex items-center gap-2 flex-wrap">
                                    <h3 class="text-sm font-extrabold {{ $stage1Status !== 'waiting' ? 'text-gray-800' : 'text-gray-400' }}">Penjemputan (Pickup)</h3>
                                    @if($stage1Status === 'completed')
                                        <span class="bg-green-50 text-[#0f8629] border border-green-200 px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider">Selesai</span>
                                    @elseif($stage1Status === 'active')
                                        <span class="bg-blue-50 text-blue-700 border border-blue-200 px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider animate-pulse">Aktif</span>
                                    @else
                                        <span class="bg-gray-100 text-gray-500 border border-gray-200 px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider">Menunggu</span>
                                    @endif
                                </div>
                                <p class="text-xs {{ $stage1Status !== 'waiting' ? 'text-gray-500' : 'text-gray-400' }} mt-1">
                                    @if($stage1Status === 'completed')
                                        Paket telah dijemput dan diserahkan ke kurir ekspedisi.
                                    @elseif($stage1Status === 'active')
                                        Paket sedang disiapkan dan menunggu penjemputan oleh kurir.
                                    @else
                                        Menunggu proses pembayaran selesai dikonfirmasi.
                                    @endif
                                </p>
                                @if($stage1Time)
                                    <span class="inline-block text-[10px] font-bold text-gray-400 mt-1 bg-gray-50 px-2 py-0.5 rounded">{{ $stage1Time }}</span>
                                @endif
                            </div>
                        </div>

                        <!-- STEP 2: Dalam Pengiriman -->
                        <div class="relative">
                            <span class="absolute -left-12 top-0.5 flex h-8 w-8 items-center justify-center rounded-full {{ $stage2Status === 'completed' ? 'bg-[#0f8629] text-white ring-4 ring-green-100' : ($stage2Status === 'active' ? 'bg-blue-600 text-white ring-4 ring-blue-100 animate-pulse' : 'bg-gray-100 text-gray-400 ring-4 ring-gray-50') }} shadow-sm">
                                @if($stage2Status === 'completed')
                                    <i class="fa-solid fa-check text-xs"></i>
                                @elseif($stage2Status === 'active')
                                    <i class="fa-solid fa-truck text-xs"></i>
                                @else
                                    <i class="fa-solid fa-truck text-xs"></i>
                                @endif
                            </span>
                            <div>
                                <div class="flex items-center gap-2 flex-wrap">
                                    <h3 class="text-sm font-extrabold {{ $stage2Status !== 'waiting' ? 'text-gray-800' : 'text-gray-400' }}">Dalam Pengiriman</h3>
                                    @if($stage2Status === 'completed')
                                        <span class="bg-green-50 text-[#0f8629] border border-green-200 px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider">Selesai</span>
                                    @elseif($stage2Status === 'active')
                                        <span class="bg-blue-50 text-blue-700 border border-blue-200 px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider animate-pulse">Aktif</span>
                                    @else
                                        <span class="bg-gray-100 text-gray-500 border border-gray-200 px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider">Menunggu</span>
                                    @endif
                                </div>
                                <p class="text-xs {{ $stage2Status !== 'waiting' ? 'text-gray-500' : 'text-gray-400' }} mt-1">
                                    @if($stage2Status === 'completed')
                                        Paket telah sampai di kota / daerah tujuan pengiriman.
                                    @elseif($stage2Status === 'active')
                                        Paket sedang dalam perjalanan menuju alamat Agen. (Kurir: {{ strtoupper($courierInfo) }})
                                    @else
                                        Menunggu paket dijemput oleh pihak ekspedisi.
                                    @endif
                                </p>
                                @if($stage2Time)
                                    <span class="inline-block text-[10px] font-bold text-gray-400 mt-1 bg-gray-50 px-2 py-0.5 rounded">{{ $stage2Time }}</span>
                                @endif
                            </div>
                        </div>

                        <!-- STEP 3: Diterima -->
                        <div class="relative">
                            <span class="absolute -left-12 top-0.5 flex h-8 w-8 items-center justify-center rounded-full {{ $stage3Status === 'completed' ? 'bg-[#0f8629] text-white ring-4 ring-green-100' : 'bg-gray-100 text-gray-400 ring-4 ring-gray-50' }} shadow-sm">
                                @if($stage3Status === 'completed')
                                    <i class="fa-solid fa-check text-xs"></i>
                                @else
                                    <i class="fa-solid fa-circle-check text-xs"></i>
                                @endif
                            </span>
                            <div>
                                <div class="flex items-center gap-2 flex-wrap">
                                    <h3 class="text-sm font-extrabold {{ $stage3Status !== 'waiting' ? 'text-gray-800' : 'text-gray-400' }}">Diterima</h3>
                                    @if($stage3Status === 'completed')
                                        <span class="bg-green-50 text-[#0f8629] border border-green-200 px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider">Selesai</span>
                                    @else
                                        <span class="bg-gray-100 text-gray-500 border border-gray-200 px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider">Menunggu</span>
                                    @endif
                                </div>
                                <p class="text-xs {{ $stage3Status !== 'waiting' ? 'text-gray-500' : 'text-gray-400' }} mt-1">
                                    @if($stage3Status === 'completed')
                                        Transaksi selesai. Paket telah diterima oleh Agen.
                                    @else
                                        Menunggu paket sampai di alamat tujuan dan dikonfirmasi Agen.
                                    @endif
                                </p>
                                @if($stage3Time)
                                    <span class="inline-block text-[10px] font-bold text-gray-400 mt-1 bg-gray-50 px-2 py-0.5 rounded">{{ $stage3Time }}</span>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Detailed Biteship History Section (as collapsible list) -->
                @if(isset($trackingData) && !empty($trackingData['history']))
                    <div class="mt-6 pt-4 border-t border-gray-100">
                        <details class="group cursor-pointer">
                            <summary class="flex justify-between items-center text-xs font-extrabold text-gray-500 uppercase tracking-wider list-none select-none">
                                <span>Histori Log Pengiriman Detail (Biteship)</span>
                                <span class="transition-transform group-open:rotate-180"><i class="fa-solid fa-chevron-down"></i></span>
                            </summary>
                            <div class="relative border-l-2 border-dashed border-gray-200 pl-6 ml-2.5 mt-4 space-y-4">
                                @foreach(array_reverse($trackingData['history']) as $event)
                                    <div class="relative">
                                        @php
                                            $isLatest = $loop->first;
                                            $statusLabel = match($event['status']) {
                                                'confirmed' => 'Pesanan Dikonfirmasi',
                                                'allocated' => 'Kurir Dialokasikan',
                                                'pickingUp', 'picking_up' => 'Proses Penjemputan',
                                                'picked' => 'Paket Dijemput Kurir',
                                                'inTransit', 'in_transit' => 'Dalam Transit / Pengiriman',
                                                'droppingOff', 'dropping_off' => 'Kurir Menuju Lokasi Tujuan',
                                                'delivered' => 'Paket Diterima',
                                                'rejected' => 'Paket Ditolak/Bermasalah',
                                                'cancelled' => 'Pengiriman Dibatalkan',
                                                'returned' => 'Paket Dikembalikan',
                                                default => strtoupper($event['status'])
                                            };
                                        @endphp
                                        <span class="absolute -left-8.5 top-0.5 w-3.5 h-3.5 rounded-full {{ $isLatest ? 'bg-purple-600' : 'bg-gray-300' }} border-2 border-white shadow-sm"></span>
                                        <div class="text-xs">
                                            <span class="text-gray-400 font-bold block mb-0.5">{{ \Carbon\Carbon::parse($event['updated_at'])->timezone('Asia/Jakarta')->locale('id')->translatedFormat('d M Y, H:i') }} WIB</span>
                                            <p class="font-extrabold {{ $isLatest ? 'text-purple-600' : 'text-gray-700' }}">{{ $statusLabel }}</p>
                                            <p class="text-gray-500 mt-0.5 font-medium leading-relaxed">{{ $event['note'] }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </details>
                    </div>
                @endif
            </div>
            @endif

            <!-- 3. Rincian Pembayaran (Midtrans) -->
            <div class="bg-white p-4 md:p-6 rounded-3xl border border-gray-150 shadow-sm">
                <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-100">
                    <div class="w-8 h-8 rounded-xl bg-amber-50 flex items-center justify-center text-amber-600">
                        <i class="fa-solid fa-wallet text-sm"></i>
                    </div>
                    <h2 class="font-extrabold text-gray-800 text-xs md:text-sm">Rincian Pembayaran</h2>
                </div>

                @if($pesanan->pembayaran)
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-xs font-bold">
                        <div>
                            <span class="text-[9px] text-gray-400 font-black uppercase tracking-wider block">Total Tagihan</span>
                            <span class="text-base md:text-lg font-black text-gray-800 mt-1 block">Rp {{ number_format($pesanan->pembayaran->totalPembayaran, 0, ',', '.') }}</span>
                        </div>
                        <div>
                            <span class="text-[9px] text-gray-400 font-black uppercase tracking-wider block">Status Midtrans</span>
                            <div class="mt-1">
                                @if($pesanan->pembayaran->statusPembayaran === 'berhasil')
                                    <span class="bg-green-50 text-green-600 border border-green-200 px-3 py-1 rounded-full text-[9px] md:text-[10px] uppercase font-black tracking-wider inline-flex items-center gap-1">
                                        <i class="fa-solid fa-check"></i> BERHASIL
                                    </span>
                                @elseif($pesanan->pembayaran->statusPembayaran === 'pending')
                                    <span class="bg-amber-50 text-amber-600 border border-amber-200 px-3 py-1 rounded-full text-[9px] md:text-[10px] uppercase font-black tracking-wider inline-flex items-center gap-1">
                                        <i class="fa-solid fa-clock"></i> PENDING
                                    </span>
                                @else
                                    <span class="bg-red-50 text-red-600 border border-red-200 px-3 py-1 rounded-full text-[9px] md:text-[10px] uppercase font-black tracking-wider inline-flex items-center gap-1">
                                        <i class="fa-solid fa-xmark"></i> GAGAL
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="space-y-2 font-mono text-[9px] md:text-[10px] text-gray-400">
                            <div>
                                <span class="block font-sans uppercase font-black text-[9px]">Pilihan Metode Pembayaran</span>
                                <span class="text-gray-700 font-black text-xs">
                                    @php
                                        $type = strtolower($pesanan->pembayaran->paymentType ?? '');
                                        if ($type === 'midtrans_snap' || $type === 'simulasi_midtrans' || !$type) {
                                            $displayMethod = 'MIDTRANS ONLINE';
                                        } else {
                                            $displayMethod = strtoupper(str_replace('_', ' ', $pesanan->pembayaran->paymentType));
                                        }
                                    @endphp
                                    {{ $displayMethod }}
                                </span>
                            </div>
                            <div>
                                <span class="block font-sans uppercase font-black text-[9px]">ID Transaksi</span>
                                <span class="text-gray-500 select-all font-bold break-all">{{ $pesanan->pembayaran->transactionId ?? '-' }}</span>
                            </div>
                        </div>
                        @if($pesanan->pembayaran->snapToken)
                            <div class="mt-4 pt-4 border-t border-gray-100 col-span-1 md:col-span-3 text-xs font-bold">
                                <span class="text-[9px] text-gray-400 font-black uppercase tracking-wider block mb-2">Midtrans Snap Token</span>
                                <div class="bg-gray-50 p-3.5 rounded-2xl font-mono text-[10px] md:text-[11px] select-all tracking-wider text-gray-700 border border-gray-150 break-all">
                                    {{ $pesanan->pembayaran->snapToken }}
                                </div>
                            </div>
                        @endif
                    </div>
                @else
                    <p class="text-gray-400 text-xs font-semibold">Rincian pembayaran belum dibuat.</p>
                @endif
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
                        @if($pesanan->status_pesanan === 'pending')
                            <span class="bg-amber-50 text-amber-600 border border-amber-200 px-3 py-1 rounded-xl text-[10px] md:text-xs font-black uppercase">Belum Bayar</span>
                        @elseif($pesanan->status_pesanan === 'diproses')
                            <span class="bg-blue-50 text-blue-600 border border-blue-200 px-3 py-1 rounded-xl text-[10px] md:text-xs font-black uppercase">Dikemas</span>
                        @elseif($pesanan->status_pesanan === 'dikirim')
                            <span class="bg-purple-50 text-purple-600 border border-purple-200 px-3 py-1 rounded-xl text-[10px] md:text-xs font-black uppercase">Dikirim</span>
                        @elseif($pesanan->status_pesanan === 'selesai')
                            <span class="bg-green-50 text-green-600 border border-green-200 px-3 py-1 rounded-xl text-[10px] md:text-xs font-black uppercase">Selesai</span>
                        @else
                            <span class="bg-red-50 text-red-600 border border-red-200 px-3 py-1 rounded-xl text-[10px] md:text-xs font-black uppercase">Dibatalkan</span>
                        @endif
                    </div>
                </div>

                <!-- Control Board Buttons -->
                <div class="space-y-4">
                    @if($pesanan->status_pesanan === 'pending')
                        <!-- 1. Pending Actions: Approve payment or Cancel -->
                        <div class="space-y-3">
                            <p class="text-[10px] md:text-[11px] text-gray-400 font-bold leading-normal">Pembayaran belum terkonfirmasi oleh Midtrans. Jika pelanggan telah membayar via jalur alternatif, Anda dapat mengonfirmasi pembayaran secara manual.</p>
                            
                            <form action="{{ route('admin.pesanan.action', $pesanan->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="action" value="proses">
                                <button type="submit" class="w-full bg-[#0f8629] hover:bg-[#0c6b20] text-white py-3 rounded-2xl font-black text-xs flex items-center justify-center gap-2 shadow-sm transition">
                                    <i class="fa-solid fa-credit-card"></i> Konfirmasi Bayar & Proses
                                </button>
                            </form>

                            <form action="{{ route('admin.pesanan.action', $pesanan->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan transaksi ini? Stok produk akan dikembalikan.')">
                                @csrf
                                <input type="hidden" name="action" value="batal">
                                <button type="submit" class="w-full border border-red-200 text-red-600 hover:bg-red-50 py-3 rounded-2xl font-black text-xs flex items-center justify-center gap-2 transition">
                                    <i class="fa-solid fa-ban"></i> Batalkan Pesanan
                                </button>
                            </form>
                        </div>

                    @elseif($pesanan->status_pesanan === 'diproses')
                        <!-- 2. Processing (Dikemas) Actions: Ship with tracking code or Cancel -->
                        <div class="space-y-4">
                            @php
                                $isAmbil = str_contains(strtolower($pesanan->deskripsi), 'ambil');
                            @endphp

                            @if($isAmbil)
                                <p class="text-[10px] md:text-[11px] text-gray-400 font-bold leading-normal">Pesanan ini menggunakan opsi <strong>Ambil di Tempat</strong>. Klik tombol di bawah ini untuk menandai pesanan siap diambil.</p>
                                
                                <form id="formKirimPesanan" action="{{ route('admin.pesanan.action', $pesanan->id) }}" method="POST" class="space-y-3">
                                    @csrf
                                    <input type="hidden" name="action" value="kirim">
                                    <button type="submit" id="btnKirimPesanan" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-2xl font-black text-xs flex items-center justify-center gap-2 shadow-sm transition cursor-pointer">
                                        <i class="fa-solid fa-warehouse"></i> Siapkan untuk Diambil
                                    </button>
                                </form>
                            @else
                                <p class="text-[10px] md:text-[11px] text-gray-400 font-bold leading-normal">Pesanan sedang dikemas. Klik tombol di bawah ini untuk memproses pengiriman dan membuat nomor resi otomatis dari Biteship Sandbox.</p>
                                
                                <form id="formKirimPesanan" action="{{ route('admin.pesanan.action', $pesanan->id) }}" method="POST" class="space-y-3">
                                    @csrf
                                    <input type="hidden" name="action" value="kirim">
                                    <button type="submit" id="btnKirimPesanan" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-2xl font-black text-xs flex items-center justify-center gap-2 shadow-sm transition cursor-pointer">
                                        <i class="fa-solid fa-paper-plane"></i> Kirim &amp; Generate Resi Biteship
                                    </button>
                                </form>
                            @endif

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

                            <form action="{{ route('admin.pesanan.action', $pesanan->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan transaksi ini? Stok produk akan dikembalikan.')">
                                @csrf
                                <input type="hidden" name="action" value="batal">
                                <button type="submit" class="w-full border border-red-200 text-red-600 hover:bg-red-50 py-3 rounded-2xl font-black text-xs flex items-center justify-center gap-2 transition">
                                    <i class="fa-solid fa-ban"></i> Batalkan Pesanan
                                </button>
                            </form>
                        </div>

                    @elseif($pesanan->status_pesanan === 'dikirim')
                        <!-- Shipped Actions: Real-time update info -->
                        <div class="text-center py-6 bg-purple-50 border border-purple-100 rounded-2xl">
                            <i class="fa-solid fa-truck text-purple-500 text-3xl mb-2"></i>
                            <h4 class="font-extrabold text-purple-800 text-[10px] md:text-xs uppercase tracking-wider">Proses Pengambilan / Pengiriman</h4>
                            <p class="text-[9px] md:text-[10px] text-purple-700 mt-1 leading-normal px-4 font-bold">Pesanan dalam proses. Pantau status pengiriman</p>
                        </div>

                    @elseif($pesanan->status_pesanan === 'selesai')
                        <!-- 4. Completed Order Status -->
                        <div class="text-center py-6 bg-green-50 border border-green-100 rounded-2xl">
                            <i class="fa-solid fa-circle-check text-green-500 text-3xl mb-2"></i>
                            <h4 class="font-extrabold text-green-800 text-[10px] md:text-xs uppercase tracking-wider">Transaksi Selesai</h4>
                            <p class="text-[9px] md:text-[10px] text-green-700 mt-1 leading-normal px-4 font-bold">Pesanan telah sampai di tangan pelanggan dan transaksi ditutup.</p>
                        </div>

                    @elseif($pesanan->status_pesanan === 'dibatalkan')
                        <!-- 5. Cancelled Order Status -->
                        <div class="text-center py-6 bg-red-50 border border-red-100 rounded-2xl">
                            <i class="fa-solid fa-circle-xmark text-red-500 text-3xl mb-2"></i>
                            <h4 class="font-extrabold text-red-800 text-[10px] md:text-xs uppercase tracking-wider">Transaksi Dibatalkan</h4>
                            <p class="text-[9px] md:text-[10px] text-red-700 mt-1 leading-normal px-4 font-bold">Pesanan telah dibatalkan dan stok barang telah dikembalikan.</p>
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (window.Echo) {
            window.Echo.channel('order.{{ $pesanan->id }}')
                .listen('.OrderStatusUpdated', (e) => {
                    console.log('Order status updated via Reverb:', e);
                    window.location.reload();
                });
        }
    });
</script>
@endsection

