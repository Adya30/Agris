@extends('layouts.agen')

@section('title', 'Detail Pesanan - AGRIS')

@section('content')
<script src="{{ config('services.midtrans.is_production', false) ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}" data-client-key="{{ config('services.midtrans.client_key') }}"></script>

@php
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
@endphp

<div class="max-w-5xl mx-auto pb-16 px-6 pt-5">
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <a href="{{ route('agen.pesanan.index') }}" class="text-xs font-bold text-gray-400 hover:text-gray-600 flex items-center gap-1.5 mb-2 uppercase tracking-wider transition-colors">
                <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar
            </a>
            <h1 class="text-2xl pt-1 font-extrabold text-gray-800 tracking-tight">Detail Pesanan</h1>
            <p class="text-gray-500 text-sm pt-2">ID Pesanan : {{ $pesanan->id }}</p>
        </div>

        <div>
            @if($pesanan->status === 'pending')
                <span class="bg-amber-50 text-amber-600 border border-amber-100 px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider">Menunggu Pembayaran</span>
            @elseif($pesanan->status === 'diproses')
                <span class="bg-blue-50 text-blue-600 border border-blue-100 px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider">Sedang Dikemas</span>
            @elseif($pesanan->status === 'dikirim')
                <span class="bg-purple-50 text-purple-600 border border-purple-100 px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider">Sedang Dikirim</span>
            @elseif($pesanan->status === 'selesai')
                <span class="bg-green-50 text-green-600 border border-green-100 px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider">Selesai</span>
            @else
                <span class="bg-red-50 text-red-600 border border-red-100 px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider">Dibatalkan</span>
            @endif
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded-xl mb-6 text-sm font-bold flex items-center gap-3">
            <i class="fa-solid fa-circle-check text-lg text-green-500"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-xl mb-6 text-sm font-bold flex items-center gap-3">
            <i class="fa-solid fa-triangle-exclamation text-lg text-red-500"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    @if(session('info'))
        <div class="bg-blue-50 border-l-4 border-blue-500 text-blue-700 p-4 rounded-xl mb-6 text-sm font-bold flex items-center gap-3">
            <i class="fa-solid fa-circle-info text-lg text-blue-500"></i>
            <span>{{ session('info') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
        <!-- Main Content (Left Column) -->
        <div class="lg:col-span-2 space-y-6">

            <!-- 1. Tracking Timeline Section (Only if NOT cancelled) -->
            @if($pesanan->status !== 'dibatalkan')
            @php
                $courierInfo = 'Kurir Pengiriman';
                $noResi = '';
                $ongkirText = '';
                $biteshipOrderId = '';
                if ($pesanan->deskripsi) {
                    $parts = explode('|', $pesanan->deskripsi);
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
            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm space-y-6">
                <div class="flex items-center justify-between pb-3 border-b border-gray-50">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-2xl bg-[#58CC02]/10 flex items-center justify-center text-[#58CC02]">
                            <i class="fa-solid fa-map-location-dot text-base"></i>
                        </div>
                        <div>
                            <h2 class="font-extrabold text-gray-800 text-sm uppercase tracking-wider">Status Tracking Pesanan</h2>
                            <p class="text-xs text-gray-400">Pantau proses pengiriman pesanan Anda</p>
                        </div>
                    </div>
                </div>

                <!-- Stepper UI -->
                <div class="relative pl-8 space-y-8 border-l-2 border-gray-100 ml-4 py-2">
                    
                    @if($isPickup)
                        <!-- STEP 1: Pesanan Dikemas -->
                        <div class="relative">
                            <span class="absolute -left-12 top-0.5 flex h-8 w-8 items-center justify-center rounded-full {{ $stage1Status === 'completed' ? 'bg-[#58CC02] text-white ring-4 ring-[#58CC02]/20' : ($stage1Status === 'active' ? 'bg-blue-600 text-white ring-4 ring-blue-100 animate-pulse' : 'bg-gray-100 text-gray-400 ring-4 ring-gray-50') }} shadow-sm">
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
                                        <span class="bg-green-50 text-[#58CC02] border border-green-200 px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider">Selesai</span>
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
                            <span class="absolute -left-12 top-0.5 flex h-8 w-8 items-center justify-center rounded-full {{ $stage2Status === 'completed' ? 'bg-[#58CC02] text-white ring-4 ring-[#58CC02]/20' : ($stage2Status === 'active' ? 'bg-blue-600 text-white ring-4 ring-blue-100 animate-pulse' : 'bg-gray-100 text-gray-400 ring-4 ring-gray-50') }} shadow-sm">
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
                                        <span class="bg-green-50 text-[#58CC02] border border-green-200 px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider">Selesai</span>
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
                            <span class="absolute -left-12 top-0.5 flex h-8 w-8 items-center justify-center rounded-full {{ $stage3Status === 'completed' ? 'bg-[#58CC02] text-white ring-4 ring-[#58CC02]/20' : 'bg-gray-100 text-gray-400 ring-4 ring-gray-50' }} shadow-sm">
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
                                        <span class="bg-green-50 text-[#58CC02] border border-green-200 px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider">Selesai</span>
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
                            <span class="absolute -left-12 top-0.5 flex h-8 w-8 items-center justify-center rounded-full {{ $stage1Status === 'completed' ? 'bg-[#58CC02] text-white ring-4 ring-[#58CC02]/20' : ($stage1Status === 'active' ? 'bg-blue-600 text-white ring-4 ring-blue-100 animate-pulse' : 'bg-gray-100 text-gray-400 ring-4 ring-gray-50') }} shadow-sm">
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
                                        <span class="bg-green-50 text-[#58CC02] border border-green-200 px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider">Selesai</span>
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
                            <span class="absolute -left-12 top-0.5 flex h-8 w-8 items-center justify-center rounded-full {{ $stage2Status === 'completed' ? 'bg-[#58CC02] text-white ring-4 ring-[#58CC02]/20' : ($stage2Status === 'active' ? 'bg-blue-600 text-white ring-4 ring-blue-100 animate-pulse' : 'bg-gray-100 text-gray-400 ring-4 ring-gray-50') }} shadow-sm">
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
                                        <span class="bg-green-50 text-[#58CC02] border border-green-200 px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider">Selesai</span>
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
                                        Paket sedang dalam perjalanan menuju alamat Anda. (Kurir: {{ strtoupper($courierInfo) }})
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
                            <span class="absolute -left-12 top-0.5 flex h-8 w-8 items-center justify-center rounded-full {{ $stage3Status === 'completed' ? 'bg-[#58CC02] text-white ring-4 ring-[#58CC02]/20' : 'bg-gray-100 text-gray-400 ring-4 ring-gray-50' }} shadow-sm">
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
                                        <span class="bg-green-50 text-[#58CC02] border border-green-200 px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider">Selesai</span>
                                    @else
                                        <span class="bg-gray-100 text-gray-500 border border-gray-200 px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider">Menunggu</span>
                                    @endif
                                </div>
                                <p class="text-xs {{ $stage3Status !== 'waiting' ? 'text-gray-500' : 'text-gray-400' }} mt-1">
                                    @if($stage3Status === 'completed')
                                        Transaksi selesai. Paket telah diterima dengan baik.
                                    @else
                                        Menunggu paket sampai di alamat tujuan dan silakan klik konfirmasi penerimaan.
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
                                                'droppingOff', 'dropping_off' => 'Kurir Menuju Lokasi Anda',
                                                'delivered' => 'Paket Diterima',
                                                'rejected' => 'Paket Ditolak/Bermasalah',
                                                'cancelled' => 'Pengiriman Dibatalkan',
                                                'returned' => 'Paket Dikembalikan',
                                                default => strtoupper($event['status'])
                                            };
                                        @endphp
                                        <span class="absolute -left-8.5 top-0.5 w-3.5 h-3.5 rounded-full {{ $isLatest ? 'bg-[#58CC02]' : 'bg-gray-300' }} border-2 border-white shadow-sm"></span>
                                        <div class="text-xs">
                                            <span class="text-gray-400 font-bold block mb-0.5">{{ \Carbon\Carbon::parse($event['updated_at'])->timezone('Asia/Jakarta')->locale('id')->translatedFormat('d M Y, H:i') }} WIB</span>
                                            <p class="font-extrabold {{ $isLatest ? 'text-[#58CC02]' : 'text-gray-700' }}">{{ $statusLabel }}</p>
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

            <!-- 2. Daftar Produk -->
            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
                <h2 class="font-extrabold text-gray-800 text-xs mb-4 uppercase tracking-wider text-gray-400 pb-2 border-b border-gray-50">Daftar Produk</h2>
                <div class="divide-y divide-gray-100">
                    @foreach($pesanan->detailPesanans as $detail)
                        @php
                            $refunds = \App\Models\Refund::where('detailPesananId', $detail->id)->get();
                            $refundedQty = $refunds->whereIn('status', ['pending', 'disetujui'])->sum('jumlah');
                            $maxQty = $detail->jumlahPesanan - $refundedQty;
                        @endphp
                        <div class="flex items-center gap-3 md:gap-4 py-4 first:pt-0 last:pb-0">
                            <div class="w-12 h-12 md:w-14 md:h-14 bg-gray-50 rounded-xl overflow-hidden border border-gray-100 flex items-center justify-center p-1 shrink-0">
                                @if($detail->produk && $detail->produk->fotoProduk)
                                    <img src="{{ asset('storage/' . $detail->produk->fotoProduk) }}" class="w-full h-full object-cover">
                                @else
                                    <i class="fa-solid fa-image text-xl text-gray-300"></i>
                                @endif
                            </div>

                            <div class="grow min-w-0">
                                <h4 class="font-bold text-gray-800 text-[11px] md:text-xs truncate">
                                    {{ $detail->produk ? $detail->produk->namaProduk : 'Produk Telah Dihapus' }}
                                </h4>
                                <p class="text-[10px] md:text-[11px] text-gray-400 font-semibold mt-0.5">
                                    {{ $detail->jumlahPesanan }} barang x Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}
                                </p>
                                @if($refunds->isNotEmpty())
                                    @foreach($refunds as $ref)
                                        <span class="block mt-1 text-[9px] font-bold {{ $ref->status === 'disetujui' ? 'text-green-600' : ($ref->status === 'pending' ? 'text-amber-600' : 'text-red-500') }}">
                                            Refund {{ $ref->jumlah }} unit ({{ strtoupper($ref->status) }})
                                        </span>
                                    @endforeach
                                @endif
                            </div>

                            <div class="text-right shrink-0 flex items-center gap-3">
                                <div class="text-right">
                                    <span class="font-bold text-gray-800 text-[11px] md:text-xs block">
                                        Rp {{ number_format($detail->subtotal, 0, ',', '.') }}
                                    </span>
                                </div>
                                @if($pesanan->status === 'selesai' && $maxQty > 0)
                                    <button type="button" onclick="openRefundModal('{{ $detail->id }}', '{{ addslashes($detail->produk->namaProduk ?? '') }}', {{ $detail->harga_satuan }}, {{ $maxQty }})" class="text-[10px] bg-red-50 hover:bg-red-100 text-red-650 px-2.5 py-1 rounded-lg border border-red-200 transition font-bold cursor-pointer">
                                        Refund
                                    </button>
                                @endif
                            </div>
                        </div>
                    @endforeach
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
                    <p class="font-extrabold text-gray-800 text-sm">{{ $pesanan->user->namaLengkap }}</p>
                    <p class="text-gray-500 font-bold">{{ $pesanan->user->noTelp }}</p>
                    <p class="text-gray-600 mt-1 font-medium">{{ $pesanan->alamat_pengiriman }}</p>
                </div>
            </div>

        </div>

        <!-- Sidebar Summary & Control Actions (Right Column) -->
        <div class="space-y-6">
            <!-- 0. Info Pengiriman -->
            @if(in_array($pesanan->status_pesanan, ['diproses', 'dikirim', 'selesai']))
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
                        <span class="font-extrabold text-gray-800 text-sm uppercase">{{ $courierInfo }}</span>
                    </div>
                    @if($noResi && !str_contains($noResi, 'AMBIL'))
                    <div class="bg-gray-50 rounded-2xl p-3 border border-gray-100">
                        <div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1.5">Nomor Resi</div>
                        <div class="flex items-center justify-between gap-2">
                            <span class="font-mono font-extrabold text-gray-800 text-sm select-all">{{ $noResi }}</span>
                            <button onclick="copyResiSidebar(this, '{{ $noResi }}')" class="text-gray-400 hover:text-[#58CC02] transition cursor-pointer" title="Salin Resi">
                                <i class="fa-regular fa-copy text-xs"></i>
                            </button>
                        </div>
                    </div>
                    @endif
                    @if($ongkirText)
                    <div class="bg-gray-50 rounded-2xl p-3 border border-gray-100">
                        <div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Ongkos Kirim</div>
                        <span class="font-extrabold text-gray-800 text-sm">{{ $ongkirText }}</span>
                    </div>
                    @endif
                    @if($noResi && !str_contains($noResi, 'AMBIL'))
                    <a href="{{ route('agen.pesanan.lacak', $pesanan->id) }}" target="_blank" class="block w-full bg-blue-50 hover:bg-blue-100 text-blue-600 py-2.5 rounded-2xl font-black text-xs flex items-center justify-center gap-2 transition border border-blue-100 cursor-pointer">
                        <i class="fa-solid fa-map-location-dot"></i> Lacak Pengiriman{{ $biteshipOrderId ? ' via Biteship' : '' }}
                    </a>
                    @endif
                </div>
            </div>
            @endif

            <!-- 1. Ringkasan Tagihan -->
            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm relative overflow-hidden">
                <div class="absolute top-0 right-0 w-20 h-20 bg-gradient-to-bl from-green-50 to-transparent rounded-bl-full pointer-events-none"></div>
                <h2 class="font-extrabold text-gray-800 text-xs mb-4 uppercase tracking-wider text-gray-400">Ringkasan Transaksi</h2>

                <div class="space-y-3 pb-3 border-b border-gray-50 text-xs">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-400 font-semibold">Tanggal Transaksi</span>
                        <span class="text-gray-700 font-extrabold">{{ \Carbon\Carbon::parse($pesanan->created_at)->timezone('Asia/Jakarta')->locale('id')->translatedFormat('d F Y, H:i') }} WIB</span>
                    </div>
                    @if($pembayaran && $pembayaran->waktuDibayar)
                        <div class="flex justify-between items-center">
                            <span class="text-gray-400 font-semibold">Tanggal Pembayaran</span>
                            <span class="text-gray-700 font-extrabold">{{ \Carbon\Carbon::parse($pembayaran->waktuDibayar)->timezone('Asia/Jakarta')->locale('id')->translatedFormat('d F Y, H:i') }} WIB</span>
                        </div>
                    @endif
                    <div class="flex justify-between items-center">
                        <span class="text-gray-400 font-semibold">Pilihan Metode Pembayaran</span>
                        <span class="text-gray-700 font-black uppercase text-right">
                            @if($pembayaran)
                                @php
                                    $type = strtolower($pembayaran->paymentType ?? '');
                                    if ($type === 'midtrans_snap' || $type === 'simulasi_midtrans' || !$type) {
                                        $displayMethod = 'MIDTRANS ONLINE';
                                    } else {
                                        $displayMethod = strtoupper(str_replace('_', ' ', $pembayaran->paymentType));
                                    }
                                @endphp
                                {{ $displayMethod }}
                            @else
                                MIDTRANS ONLINE
                            @endif
                        </span>
                    </div>
                    @if($noResi)
                        <div class="flex justify-between items-center">
                            <span class="text-gray-400 font-semibold">No Resi</span>
                            <span class="text-gray-700 font-mono font-bold select-all bg-gray-50 px-1.5 py-0.5 rounded border border-gray-150">{{ $noResi }}</span>
                        </div>
                    @endif
                </div>

                <div class="flex justify-between items-center pt-4">
                    <span class="text-gray-800 font-extrabold text-xs">Total Tagihan</span>
                    <span class="text-lg font-black text-[#58CC02]">
                        Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}
                    </span>
                </div>
            </div>

            <!-- 2. Aksi Pesanan (Shown only if diproses) -->
            @if($pesanan->status === 'diproses')
                <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm space-y-3">
                    <h3 class="font-extrabold text-gray-800 text-xs mb-4 uppercase tracking-wider text-gray-400 pb-2 border-b border-gray-50">Aksi Pesanan</h3>

                    <!-- Batal Pesanan -->
                    <form action="{{ route('agen.pesanan.batal', $pesanan->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini? Stok produk akan dikembalikan.')">
                        @csrf
                        <button type="submit" class="w-full border border-red-200 text-red-600 hover:bg-red-50 py-2.5 rounded-2xl font-black text-xs flex items-center justify-center gap-2 transition duration-200 cursor-pointer">
                            <i class="fa-solid fa-ban"></i> Batalkan Pesanan
                        </button>
                    </form>
                </div>
            @endif

            <!-- 3. Mark Diterima Button (Shown if status is delivered or shipping progress) -->
            @php
                $biteshipStatus = $trackingData['status'] ?? null;
                if (!empty($biteshipStatus)) {
                    // Hanya aktif jika status Biteship sudah dalam pengiriman ke lokasi (in transit / dropping off / delivered)
                    $deliveringStatuses = ['inTransit', 'in_transit', 'droppingOff', 'dropping_off', 'delivered'];
                    $canConfirmReceipt = in_array($biteshipStatus, $deliveringStatuses) && $pesanan->status_pesanan !== 'selesai';
                } else {
                    // Jika tidak ada tracking dari Biteship (manual/fallback), ikuti status pesanan dikirim
                    $canConfirmReceipt = $pesanan->status_pesanan === 'dikirim';
                }
            @endphp
            @if($canConfirmReceipt)
                <div class="bg-white p-6 rounded-3xl border border-gray-150 shadow-sm space-y-3">
                    <h3 class="font-extrabold text-gray-800 text-xs mb-4 uppercase tracking-wider text-gray-400 pb-2 border-b border-gray-50">Konfirmasi Penerimaan</h3>
                    <p class="text-xs text-gray-400 font-semibold leading-relaxed">Pesanan Anda telah dikirimkan. Harap klik tombol di bawah ini jika barang sudah Anda terima dengan baik.</p>

                    <form action="{{ route('agen.pesanan.diterima', $pesanan->id) }}" method="POST" onsubmit="return confirm('Konfirmasi bahwa Anda telah menerima pesanan ini? Aksi ini tidak dapat dibatalkan.')">
                        @csrf
                        <button type="submit" class="w-full bg-[#0f8629] hover:bg-[#0c6b20] text-white py-3 rounded-2xl font-black text-xs flex items-center justify-center gap-2 shadow-sm transition duration-200 cursor-pointer">
                            <i class="fa-solid fa-circle-check"></i> Pesanan Sudah Diterima
                        </button>
                    </form>
                </div>
            @endif

            <!-- 4. Selesaikan Pembayaran (Shown only if status is pending) -->
            @if($pesanan->status === 'pending')
                <div class="bg-white p-6 rounded-3xl border border-gray-150 shadow-sm space-y-3">
                    <h3 class="font-extrabold text-gray-800 text-xs mb-4 uppercase tracking-wider text-gray-400 pb-2 border-b border-gray-50">Selesaikan Pembayaran</h3>
                    <p class="text-xs text-gray-400 font-semibold leading-relaxed">Pesanan Anda telah disimpan. Silakan lakukan pembayaran agar pesanan dapat segera diproses.</p>

                    @if($pembayaran && $pembayaran->snapToken)
                        <button id="btnPayNow" class="w-full bg-[#58CC02] hover:bg-[#46a302] text-white py-3 rounded-2xl font-black text-xs flex items-center justify-center gap-2 shadow-sm transition duration-200 cursor-pointer">
                            <i class="fa-solid fa-credit-card"></i> Bayar Sekarang
                        </button>
                    @else
                        <div class="p-3 bg-red-50 text-red-600 rounded-2xl text-[11px] font-bold text-center">
                            Gagal memuat Snap token pembayaran.
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>

@if($pesanan->status === 'pending' && $pembayaran && $pembayaran->snapToken)
<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function() {
        const btnPay = document.getElementById('btnPayNow');
        if (btnPay) {
            btnPay.addEventListener('click', function() {
                // Disable button immediately to prevent spam click
                btnPay.disabled = true;
                btnPay.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-2"></i> Memproses...';
                btnPay.className = "w-full bg-slate-300 text-slate-500 py-3 rounded-2xl font-black text-xs flex items-center justify-center gap-2 cursor-wait transition duration-200";

                window.snap.pay('{{ $pembayaran->snapToken }}', {
                    onSuccess: function(result) {
                        window.location.href = `/agen/pesanan/{{ $pesanan->id }}?status=success`;
                    },
                    onPending: function(result) {
                        window.location.href = `/agen/pesanan/{{ $pesanan->id }}`;
                    },
                    onError: function(result) {
                        // Re-enable button on error
                        btnPay.disabled = false;
                        btnPay.innerHTML = '<i class="fa-solid fa-credit-card"></i> Bayar Sekarang';
                        btnPay.className = "w-full bg-[#58CC02] hover:bg-[#46a302] text-white py-3 rounded-2xl font-black text-xs flex items-center justify-center gap-2 shadow-sm transition duration-200 cursor-pointer";
                        window.location.href = `/agen/pesanan/{{ $pesanan->id }}`;
                    },
                    onClose: function() {
                        // Re-enable button on close
                        btnPay.disabled = false;
                        btnPay.innerHTML = '<i class="fa-solid fa-credit-card"></i> Bayar Sekarang';
                        btnPay.className = "w-full bg-[#58CC02] hover:bg-[#46a302] text-white py-3 rounded-2xl font-black text-xs flex items-center justify-center gap-2 shadow-sm transition duration-200 cursor-pointer";
                        window.location.href = `/agen/pesanan/{{ $pesanan->id }}`;
                    }
                });
            });
        }
    });
</script>
@endif

@php
    $orderRefunds = \App\Models\Refund::where('pesananId', $pesanan->id)->with('detailPesanan.produk')->get();
@endphp

@if($orderRefunds->isNotEmpty())
    <div class="bg-white p-4 md:p-6 rounded-3xl border border-gray-100 shadow-sm mt-6">
        <h2 class="font-extrabold text-[10px] md:text-xs mb-4 uppercase tracking-wider text-gray-400 pb-2 border-b border-gray-50">Daftar Pengajuan Refund Pesanan Ini</h2>
        <div class="divide-y divide-gray-150">
            @foreach($orderRefunds as $refund)
                <div class="py-4 first:pt-0 last:pb-0 flex flex-col sm:flex-row sm:items-center justify-between gap-4 text-xs font-semibold">
                    <div class="flex items-center gap-3">
                        @if($refund->foto_bukti)
                            <a href="{{ asset('storage/' . $refund->foto_bukti) }}" target="_blank" class="w-10 h-10 rounded-lg overflow-hidden border border-gray-150 shrink-0 block hover:opacity-85 transition">
                                <img src="{{ asset('storage/' . $refund->foto_bukti) }}" class="w-full h-full object-cover">
                            </a>
                        @endif
                        <div>
                            <p class="text-gray-800">{{ $refund->detailPesanan->produk->namaProduk ?? 'Produk Telah Dihapus' }} ({{ $refund->jumlah }} unit)</p>
                            <p class="text-[10px] text-gray-400 mt-0.5">Alasan: {{ $refund->alasan }}</p>
                            @if($refund->catatan_admin)
                                <p class="text-[10px] text-red-500 mt-0.5">Catatan Admin: {{ $refund->catatan_admin }}</p>
                            @endif
                        </div>
                    </div>
                    <div class="flex items-center gap-3 justify-between sm:justify-end">
                        <div class="text-right">
                            <span class="text-[9px] text-gray-400 block uppercase font-bold">Nominal</span>
                            <span class="font-black text-gray-900">Rp {{ number_format($refund->nominal, 0, ',', '.') }}</span>
                        </div>
                        <div>
                            @if($refund->status === 'pending')
                                <span class="bg-amber-50 text-amber-600 border border-amber-200 px-2 py-0.5 rounded-full text-[9px] uppercase font-black tracking-wide">Pending</span>
                            @elseif($refund->status === 'disetujui')
                                <span class="bg-green-50 text-green-600 border border-green-200 px-2 py-0.5 rounded-full text-[9px] uppercase font-black tracking-wide">Disetujui</span>
                            @else
                                <span class="bg-red-50 text-red-600 border border-red-200 px-2 py-0.5 rounded-full text-[9px] uppercase font-black tracking-wide">Ditolak</span>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif

<div id="refundModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 overflow-x-hidden overflow-y-auto outline-none focus:outline-none">
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm transition-opacity duration-300 modal-overlay" onclick="closeRefundModal()"></div>

    <div class="relative bg-white rounded-3xl p-6 md:p-8 max-w-md w-full shadow-2xl border border-gray-100 transition-all duration-300 transform scale-95 opacity-0" id="refundModalContent">
        <h3 class="text-xl font-extrabold text-gray-900 mb-4">Form Pengajuan Refund</h3>

        <form action="{{ route('agen.refund.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <input type="hidden" name="pesananId" value="{{ $pesanan->id }}">
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

if (window.Echo) {
    window.Echo.channel('order.{{ $pesanan->id }}')
        .listen('.OrderStatusUpdated', (e) => {
            console.log('Order status updated via Reverb:', e);
            window.location.reload();
        });
}
</script>
@endsection

