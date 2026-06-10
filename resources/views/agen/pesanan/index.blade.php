@extends('layouts.agen')

@section('title', 'Riwayat Transaksi - AGRIS')

@section('content')
<div class="max-w-5xl mx-auto pt-5 pb-12 px-4 sm:px-6">
    <div class="mb-8">
        <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900 tracking-tight">Daftar Transaksi</h1>
        <p class="text-gray-500 text-xs md:text-sm mt-1">Pantau status pesanan dan riwayat belanja Anda dengan mudah</p>
    </div>

    @php
        $activeTab = $activeTab ?? 'transaksi';
    @endphp

    <div class="flex bg-gray-100 rounded-2xl p-1 mb-8 max-w-xs md:max-w-sm">
        <a href="{{ route('agen.pesanan.index', ['tab' => 'transaksi']) }}" class="flex-1 text-center py-2 px-3 rounded-xl text-xs font-black transition {{ $activeTab === 'transaksi' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-900' }}">
            Transaksi Saya
        </a>
        <a href="{{ route('agen.pesanan.index', ['tab' => 'keuangan']) }}" class="flex-1 text-center py-2 px-3 rounded-xl text-xs font-black transition {{ $activeTab === 'keuangan' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-900' }}">
            Riwayat Transaksi
        </a>
    </div>

    @if($activeTab === 'transaksi')
        @php $activeStatus = $status ?? 'all'; @endphp
        <div class="flex overflow-x-auto bg-white rounded-2xl border border-gray-200 p-1 mb-8 shadow-sm scrollbar-none">
            <a href="{{ route('agen.pesanan.index', ['tab' => 'transaksi', 'status' => 'all']) }}" class="flex-1 text-center py-3 px-4 rounded-xl text-xs font-black transition-all whitespace-nowrap {{ $activeStatus === 'all' ? 'bg-[#58CC02] text-white shadow-sm' : 'text-gray-500 hover:text-gray-800' }}">
                Semua
            </a>
            <a href="{{ route('agen.pesanan.index', ['tab' => 'transaksi', 'status' => 'diproses']) }}" class="flex-1 text-center py-3 px-4 rounded-xl text-xs font-black transition-all whitespace-nowrap {{ $activeStatus === 'diproses' ? 'bg-[#58CC02] text-white shadow-sm' : 'text-gray-500 hover:text-gray-800' }}">
                Dikemas
            </a>
            <a href="{{ route('agen.pesanan.index', ['tab' => 'transaksi', 'status' => 'dikirim']) }}" class="flex-1 text-center py-3 px-4 rounded-xl text-xs font-black transition-all whitespace-nowrap {{ $activeStatus === 'dikirim' ? 'bg-[#58CC02] text-white shadow-sm' : 'text-gray-500 hover:text-gray-800' }}">
                Dikirim
            </a>
        </div>

        @if($pesanans->isEmpty())
            <div class="py-24 text-center bg-white rounded-3xl border border-gray-100 shadow-sm px-4">
                <i class="fa-solid fa-receipt text-5xl text-gray-200 mb-4"></i>
                <p class="text-gray-400 font-extrabold uppercase text-xs tracking-widest mb-4">Tidak Ada Transaksi Aktif.</p>
                <a href="{{ route('agen.produk.index') }}" class="inline-block bg-[#58CC02] text-white px-6 py-2.5 rounded-xl font-bold text-sm hover:bg-[#46A302] transition">
                    Mulai Belanja
                </a>
            </div>
        @else
            <div class="space-y-6">
                @foreach($pesanans as $pesanan)
                    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-4 md:p-6 hover:shadow-md transition duration-200">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 border-b border-gray-50 mb-4">
                            <div class="flex items-center gap-3">
                                <span class="text-[10px] md:text-xs font-black text-gray-400 font-mono">No. {{ $loop->iteration }}</span>
                                <span class="text-xs font-medium text-gray-300">•</span>
                                <span class="text-[10px] md:text-xs text-gray-500 font-bold">{{ \Carbon\Carbon::parse($pesanan->created_at)->timezone('Asia/Jakarta')->locale('id')->translatedFormat('d F Y H:i') }} WIB</span>
                            </div>
                            <div>
                                @if($pesanan->status_pesanan === 'diproses')
                                    <span class="bg-blue-50 text-blue-600 border border-blue-100 px-3.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wider">Dikemas</span>
                                @elseif($pesanan->status_pesanan === 'dikirim')
                                    <span class="bg-[#58CC02]/5 text-[#58CC02] border border-[#58CC02]/20 px-3.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wider">Dikirim</span>
                                @elseif($pesanan->status_pesanan === 'selesai')
                                    <span class="bg-green-50 text-green-600 border border-green-100 px-3.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wider">Selesai</span>
                                @else
                                    <span class="bg-red-50 text-red-600 border border-red-100 px-3.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wider">Dibatalkan</span>
                                @endif
                            </div>
                        </div>

                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                            <div class="grow min-w-0">
                                @php $firstDetail = $pesanan->detailPesanans->first(); @endphp
                                @if($firstDetail && $firstDetail->produk)
                                    <div class="flex items-center gap-4">
                                        <div class="w-14 h-14 md:w-16 md:h-16 bg-gray-50 rounded-2xl overflow-hidden border border-gray-100 flex items-center justify-center p-1 shrink-0">
                                            @if($firstDetail->produk->fotoProduk)
                                                <img src="{{ asset('storage/' . $firstDetail->produk->fotoProduk) }}" class="w-full h-full object-cover rounded-xl">
                                            @else
                                                <i class="fa-solid fa-image text-xl text-gray-300"></i>
                                            @endif
                                        </div>
                                        <div class="min-w-0">
                                            <h4 class="font-extrabold text-gray-800 text-xs md:text-sm truncate">{{ $firstDetail->produk->namaProduk }}</h4>
                                            <p class="text-[10px] md:text-xs text-gray-400 font-bold mt-1">
                                                {{ $firstDetail->jumlahPesanan }} barang x Rp {{ number_format($firstDetail->harga_satuan, 0, ',', '.') }}
                                            </p>
                                            @if($pesanan->detailPesanans->count() > 1)
                                                <p class="text-[10px] md:text-xs text-[#58CC02] font-black mt-1.5 flex items-center gap-1">
                                                    <i class="fa-solid fa-layer-group text-[10px]"></i> +{{ $pesanan->detailPesanans->count() - 1 }} produk lainnya
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                @else
                                    <p class="text-gray-400 text-xs md:text-sm font-medium">Produk telah dihapus</p>
                                @endif
                            </div>

                            <div class="border-t border-gray-100 md:hidden my-2"></div>

                            <div class="flex flex-col sm:flex-row sm:items-center justify-between md:justify-end gap-4 shrink-0 w-full md:w-auto">
                                <div class="text-left md:text-right">
                                    <span class="text-[9px] font-black text-gray-400 block uppercase tracking-wider">Total Tagihan</span>
                                    <span class="font-black text-gray-900 text-base md:text-lg">
                                        Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}
                                    </span>
                                </div>
                                <div class="flex flex-wrap items-center gap-2 w-full sm:w-auto">
                                    <a href="{{ route('agen.pesanan.show', $pesanan->id) }}" class="flex-1 sm:flex-initial text-center border border-gray-200 hover:border-gray-300 text-gray-700 bg-white px-3.5 py-2 rounded-xl text-xs font-black transition">
                                        Detail
                                    </a>

                                    @if($pesanan->status_pesanan === 'diproses')
                                        <form action="{{ route('agen.pesanan.batal', $pesanan->id) }}" method="POST" class="flex-1 sm:flex-initial" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini? Stok produk akan dikembalikan.')">
                                            @csrf
                                            <button type="submit" class="w-full text-center border border-red-200 text-red-600 hover:bg-red-50 px-3.5 py-2 rounded-xl text-xs font-black transition">
                                                Batal
                                            </button>
                                        </form>
                                    @endif

                                    @if($pesanan->status_pesanan === 'dikirim')
                                        <form action="{{ route('agen.pesanan.diterima', $pesanan->id) }}" method="POST" class="flex-1 sm:flex-initial" onsubmit="return confirm('Apakah Anda yakin pesanan sudah sampai dan diterima dengan baik? Transaksi akan diselesaikan.')">
                                            @csrf
                                            <button type="submit" class="w-full text-center bg-blue-600 hover:bg-blue-700 text-white px-3.5 py-2 rounded-xl text-xs font-black transition shadow-sm">
                                                Diterima
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    @else
        @php $subTab = $subTab ?? 'selesai'; @endphp
        <div class="flex overflow-x-auto bg-white rounded-2xl border border-gray-200 p-1 mb-8 shadow-sm scrollbar-none">
            <a href="{{ route('agen.pesanan.index', ['tab' => 'keuangan', 'sub' => 'selesai']) }}" class="flex-1 text-center py-3 px-4 rounded-xl text-xs font-black transition-all whitespace-nowrap {{ $subTab === 'selesai' ? 'bg-[#58CC02] text-white shadow-sm' : 'text-gray-500 hover:text-gray-800' }}">
                Selesai
            </a>
            <a href="{{ route('agen.pesanan.index', ['tab' => 'keuangan', 'sub' => 'refund']) }}" class="flex-1 text-center py-3 px-4 rounded-xl text-xs font-black transition-all whitespace-nowrap {{ $subTab === 'refund' ? 'bg-[#58CC02] text-white shadow-sm' : 'text-gray-500 hover:text-gray-800' }}">
                Refund
            </a>
            <a href="{{ route('agen.pesanan.index', ['tab' => 'keuangan', 'sub' => 'batal']) }}" class="flex-1 text-center py-3 px-4 rounded-xl text-xs font-black transition-all whitespace-nowrap {{ $subTab === 'batal' ? 'bg-[#58CC02] text-white shadow-sm' : 'text-gray-500 hover:text-gray-800' }}">
                Dibatalkan
            </a>
        </div>

        @if($subTab === 'refund')
            @if(empty($refunds) || count($refunds) === 0)
                <div class="py-24 text-center bg-white rounded-3xl border border-gray-100 shadow-sm px-4">
                    <i class="fa-solid fa-rotate-left text-5xl text-gray-200 mb-4"></i>
                    <p class="text-gray-400 font-extrabold uppercase text-xs tracking-widest">Tidak Ada Pengajuan Refund.</p>
                </div>
            @else
                <div class="space-y-6">
                    @foreach($refunds as $refund)
                        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-4 md:p-6 hover:shadow-md transition duration-200">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 border-b border-gray-50 mb-4">
                                <div class="flex items-center gap-3">
                                    <span class="text-[10px] md:text-xs font-black text-gray-400 font-mono">No. {{ $loop->iteration }}</span>
                                    <span class="text-xs font-medium text-gray-300">•</span>
                                    <span class="text-[10px] md:text-xs text-gray-500 font-bold">Pengajuan: {{ \Carbon\Carbon::parse($refund->created_at)->timezone('Asia/Jakarta')->locale('id')->translatedFormat('d F Y H:i') }} WIB</span>
                                </div>
                                <div>
                                    @if($refund->status === 'pending')
                                        <span class="bg-amber-50 text-amber-600 border border-amber-100 px-3.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wider">Pending</span>
                                    @elseif($refund->status === 'disetujui')
                                        <span class="bg-green-50 text-green-600 border border-green-100 px-3.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wider">Disetujui</span>
                                    @else
                                        <span class="bg-red-50 text-red-600 border border-red-100 px-3.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wider">Ditolak</span>
                                    @endif
                                </div>
                            </div>

                            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                                <div class="grow min-w-0">
                                    <div class="flex items-center gap-4">
                                        <div class="w-14 h-14 md:w-16 md:h-16 bg-gray-50 rounded-2xl overflow-hidden border border-gray-100 flex items-center justify-center p-1 shrink-0">
                                            @if($refund->detailPesanan->produk && $refund->detailPesanan->produk->fotoProduk)
                                                <img src="{{ asset('storage/' . $refund->detailPesanan->produk->fotoProduk) }}" class="w-full h-full object-cover rounded-xl">
                                            @else
                                                <i class="fa-solid fa-image text-xl text-gray-300"></i>
                                            @endif
                                        </div>
                                        <div class="min-w-0">
                                            <h4 class="font-extrabold text-gray-800 text-xs md:text-sm truncate">{{ $refund->detailPesanan->produk->namaProduk ?? 'Produk Dihapus' }}</h4>
                                            <p class="text-[10px] md:text-xs text-gray-400 font-bold mt-1">
                                                {{ $refund->jumlah }} barang x Rp {{ number_format($refund->detailPesanan->harga_satuan ?? 0, 0, ',', '.') }}
                                            </p>
                                            <p class="text-[10px] text-gray-500 font-medium mt-1">Alasan: {{ $refund->alasan }}</p>
                                            @if($refund->catatan_admin)
                                                <p class="text-[10px] text-red-600 font-bold mt-1">Catatan Admin: {{ $refund->catatan_admin }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="border-t border-gray-100 md:hidden my-2"></div>

                                <div class="flex flex-col sm:flex-row sm:items-center justify-between md:justify-end gap-4 shrink-0 w-full md:w-auto">
                                    <div class="text-left md:text-right">
                                        <span class="text-[9px] font-black text-gray-400 block uppercase tracking-wider">Nominal Refund</span>
                                        <span class="font-black text-gray-900 text-base md:text-lg">
                                            Rp {{ number_format($refund->nominal, 0, ',', '.') }}
                                        </span>
                                    </div>
                                    <div class="flex flex-wrap items-center gap-2 w-full sm:w-auto">
                                        <a href="{{ route('agen.pesanan.show', $refund->pesananId) }}" class="flex-1 sm:flex-initial text-center border border-gray-200 hover:border-gray-300 text-gray-700 bg-white px-3.5 py-2 rounded-xl text-xs font-black transition">
                                            Detail Pesanan
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        @else
            @if(empty($pesanans) || count($pesanans) === 0)
                <div class="py-24 text-center bg-white rounded-3xl border border-gray-100 shadow-sm px-4">
                    <i class="fa-solid fa-receipt text-5xl text-gray-200 mb-4"></i>
                    <p class="text-gray-400 font-extrabold uppercase text-xs tracking-widest">Tidak Ada Transaksi.</p>
                </div>
            @else
                <div class="space-y-6">
                    @foreach($pesanans as $pesanan)
                        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-4 md:p-6 hover:shadow-md transition duration-200">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 border-b border-gray-50 mb-4">
                                <div class="flex items-center gap-3">
                                    <span class="text-[10px] md:text-xs font-black text-gray-400 font-mono">No. {{ $loop->iteration }}</span>
                                    <span class="text-xs font-medium text-gray-300">•</span>
                                    <span class="text-[10px] md:text-xs text-gray-500 font-bold">{{ \Carbon\Carbon::parse($pesanan->created_at)->timezone('Asia/Jakarta')->locale('id')->translatedFormat('d F Y H:i') }} WIB</span>
                                </div>
                                <div>
                                    @if($pesanan->status_pesanan === 'selesai')
                                        <span class="bg-green-50 text-green-600 border border-green-100 px-3.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wider">Selesai</span>
                                    @else
                                        <span class="bg-red-50 text-red-600 border border-red-100 px-3.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wider">Dibatalkan</span>
                                    @endif
                                </div>
                            </div>

                            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                                <div class="grow min-w-0">
                                    @php $firstDetail = $pesanan->detailPesanans->first(); @endphp
                                    @if($firstDetail && $firstDetail->produk)
                                        <div class="flex items-center gap-4">
                                            <div class="w-14 h-14 md:w-16 md:h-16 bg-gray-50 rounded-2xl overflow-hidden border border-gray-100 flex items-center justify-center p-1 shrink-0">
                                                @if($firstDetail->produk->fotoProduk)
                                                    <img src="{{ asset('storage/' . $firstDetail->produk->fotoProduk) }}" class="w-full h-full object-cover rounded-xl">
                                                @else
                                                    <i class="fa-solid fa-image text-xl text-gray-300"></i>
                                                @endif
                                            </div>
                                            <div class="min-w-0">
                                                <h4 class="font-extrabold text-gray-800 text-xs md:text-sm truncate">{{ $firstDetail->produk->namaProduk }}</h4>
                                                <p class="text-[10px] md:text-xs text-gray-400 font-bold mt-1">
                                                    {{ $firstDetail->jumlahPesanan }} barang x Rp {{ number_format($firstDetail->harga_satuan, 0, ',', '.') }}
                                                </p>
                                                @if($pesanan->detailPesanans->count() > 1)
                                                    <p class="text-[10px] md:text-xs text-[#58CC02] font-black mt-1.5 flex items-center gap-1">
                                                        <i class="fa-solid fa-layer-group text-[10px]"></i> +{{ $pesanan->detailPesanans->count() - 1 }} produk lainnya
                                                    </p>
                                                @endif
                                            </div>
                                        </div>
                                    @else
                                        <p class="text-gray-400 text-xs md:text-sm font-medium">Produk telah dihapus</p>
                                    @endif
                                </div>

                                <div class="border-t border-gray-100 md:hidden my-2"></div>

                                <div class="flex flex-col sm:flex-row sm:items-center justify-between md:justify-end gap-4 shrink-0 w-full md:w-auto">
                                    <div class="text-left md:text-right">
                                        <span class="text-[9px] font-black text-gray-400 block uppercase tracking-wider">Total Tagihan</span>
                                        <span class="font-black text-gray-900 text-base md:text-lg">
                                            Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}
                                        </span>
                                    </div>
                                    <div class="flex flex-wrap items-center gap-2 w-full sm:w-auto">
                                        <a href="{{ route('agen.pesanan.show', $pesanan->id) }}" class="flex-1 sm:flex-initial text-center border border-gray-200 hover:border-gray-300 text-gray-700 bg-white px-3.5 py-2 rounded-xl text-xs font-black transition">
                                            Detail
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        @endif
    @endif
</div>
@endsection
