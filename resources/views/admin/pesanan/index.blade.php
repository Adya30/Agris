@extends('layouts.admin')

@section('title', 'Manajemen Transaksi - Admin AGRIS')

@section('content')
<div class="max-w-7xl mx-auto pt-2 pb-10 px-4 md:px-0">

    <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Daftar Transaksi</h1>
            <p class="text-gray-500 text-sm">Kelola pesanan, pengiriman, dan status pembayaran pelanggan</p>
        </div>
    </div>

    <div class="flex border-b border-gray-200 mb-6 gap-2 text-xs md:text-sm font-bold">
        <a href="{{ route('admin.pesanan.index', ['tab' => 'aktif']) }}"
            class="pb-2.5 px-4 transition-all relative {{ $activeTab === 'aktif' ? 'text-gray-950 after:absolute after:bottom-0 after:left-0 after:w-full after:h-0.5 after:bg-[#58CC02]' : 'text-gray-400 hover:text-gray-600' }}">
            Transaksi Aktif
        </a>
        <a href="{{ route('admin.pesanan.index', ['tab' => 'riwayat']) }}"
            class="pb-2.5 px-4 transition-all relative {{ $activeTab === 'riwayat' ? 'text-gray-950 after:absolute after:bottom-0 after:left-0 after:w-full after:h-0.5 after:bg-[#58CC02]' : 'text-gray-400 hover:text-gray-600' }}">
            Riwayat Transaksi
        </a>
        <a href="{{ route('admin.pesanan.index', ['tab' => 'refund']) }}"
            class="pb-2.5 px-4 transition-all relative {{ $activeTab === 'refund' ? 'text-gray-950 after:absolute after:bottom-0 after:left-0 after:w-full after:h-0.5 after:bg-[#58CC02]' : 'text-gray-400 hover:text-gray-600' }}">
            Refund
        </a>
    </div>

    <div class="bg-white p-4 md:p-5 rounded-xl shadow-sm border border-gray-100 mb-8">
        <form action="{{ route('admin.pesanan.index') }}" method="GET" class="flex flex-col md:flex-row items-end gap-4">
            <input type="hidden" name="tab" value="{{ $activeTab }}">

            <div class="w-full md:flex-1">
                <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1 ml-1">Cari</label>
                <div class="relative w-full">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nomor, nama pelanggan..."
                        class="w-full pl-11 pr-4 py-2.5 rounded-xl border border-gray-100 bg-gray-50 outline-none focus:ring-2 focus:ring-[#58CC02] text-sm transition">
                </div>
            </div>

            @if($activeTab !== 'refund')
            <div class="w-full md:w-52">
                <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1 ml-1">Status</label>
                <select name="status" onchange="this.form.submit()"
                    class="w-full px-4 py-2.5 rounded-xl border border-gray-100 bg-gray-50 outline-none focus:ring-2 focus:ring-[#58CC02] text-sm cursor-pointer appearance-none">
                    <option value="all" {{ request('status') === 'all' ? 'selected' : '' }}>Semua Status</option>
                    @if($activeTab === 'aktif')
                        <option value="diproses" {{ request('status') === 'diproses' ? 'selected' : '' }}>Dikemas (Diproses)</option>
                        <option value="dikirim" {{ request('status') === 'dikirim' ? 'selected' : '' }}>Dikirim</option>
                    @else
                        <option value="selesai" {{ request('status') === 'selesai' ? 'selected' : '' }}>Selesai</option>
                        <option value="dibatalkan" {{ request('status') === 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                    @endif
                </select>
            </div>
            @else
            <div class="w-full md:w-52">
                <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1 ml-1">Status Refund</label>
                <select name="refund_status" onchange="this.form.submit()"
                    class="w-full px-4 py-2.5 rounded-xl border border-gray-100 bg-gray-50 outline-none focus:ring-2 focus:ring-[#58CC02] text-sm cursor-pointer appearance-none">
                    <option value="all" {{ ($refundStatus ?? 'all') === 'all' ? 'selected' : '' }}>Semua Status</option>
                    <option value="pending" {{ ($refundStatus ?? '') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="disetujui" {{ ($refundStatus ?? '') === 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                    <option value="ditolak" {{ ($refundStatus ?? '') === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>
            @endif

            <div class="w-full md:w-auto flex gap-2">
                <button type="submit" class="w-full md:w-auto bg-gray-800 hover:bg-black text-white px-8 py-2.5 rounded-xl transition font-bold text-sm flex items-center justify-center shadow-sm">
                    <i class="fa-solid fa-filter mr-2"></i> Filter
                </button>

                @if(request()->anyFilled(['search', 'status', 'refund_status']))
                    <a href="{{ route('admin.pesanan.index', ['tab' => $activeTab]) }}" class="text-center border border-gray-200 hover:bg-gray-50 text-gray-600 px-6 py-2.5 rounded-xl font-bold text-xs transition flex items-center justify-center">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <div id="orders-list-container">
        @if($activeTab !== 'refund')

        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden hidden md:block">
            <table class="w-full text-left border-collapse table-fixed">
                <colgroup>
                    <col class="w-12">
                    <col class="w-36">
                    <col class="w-32">
                    <col class="w-32">
                    <col class="w-auto">
                    <col class="w-24">
                    <col class="w-24">
                    <col class="w-20">
                </colgroup>
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="py-3 px-3 text-xs font-black text-gray-400 uppercase tracking-wider">No.</th>
                        <th class="py-3 px-3 text-xs font-black text-gray-400 uppercase tracking-wider">Pelanggan</th>
                        <th class="py-3 px-3 text-xs font-black text-gray-400 uppercase tracking-wider">Tanggal</th>
                        <th class="py-3 px-3 text-xs font-black text-gray-400 uppercase tracking-wider">Total Tagihan</th>
                        <th class="py-3 px-3 text-xs font-black text-gray-400 uppercase tracking-wider">Opsi Pengiriman</th>
                        <th class="py-3 px-3 text-xs font-black text-gray-400 uppercase tracking-wider">Status Order</th>
                        <th class="py-3 px-3 text-xs font-black text-gray-400 uppercase tracking-wider">Status Bayar</th>
                        <th class="py-3 px-3 text-xs font-black text-gray-400 uppercase tracking-wider text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm font-bold text-gray-700">
                    @forelse($pesanans as $pesanan)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="py-3 px-3 text-xs font-black text-gray-500">
                                {{ ($pesanans->firstItem() ?? 1) + $loop->index }}
                            </td>
                            <td class="py-3 px-3">
                                <div class="flex flex-col">
                                    <span class="truncate text-sm">{{ $pesanan->user->namaLengkap ?? 'Dihapus' }}</span>
                                    <span class="text-xs text-gray-400 font-bold mt-0.5 truncate">{{ '@' . ($pesanan->user->username ?? 'user') }}</span>
                                </div>
                            </td>
                            <td class="py-3 px-3 text-xs text-gray-500">
                                {{ \Carbon\Carbon::parse($pesanan->created_at)->timezone('Asia/Jakarta')->locale('id')->translatedFormat('d M Y H:i') }} WIB
                            </td>
                            <td class="py-3 px-3 text-gray-900">
                                Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}
                            </td>
                            <td class="py-3 px-3">
                                <span class="block truncate text-xs text-gray-500" title="{{ $pesanan->deskripsi }}">{{ $pesanan->deskripsi }}</span>
                            </td>
                            <td class="py-3 px-3">
                                @if($pesanan->status_pesanan === 'pending')
                                    <span class="bg-amber-50 text-amber-600 border border-amber-100 px-2 py-0.5 rounded-full text-[10px] uppercase font-black tracking-wide whitespace-nowrap">Menunggu</span>
                                @elseif($pesanan->status_pesanan === 'diproses')
                                    <span class="bg-blue-50 text-blue-600 border border-blue-100 px-2 py-0.5 rounded-full text-[10px] uppercase font-black tracking-wide whitespace-nowrap">Dikemas</span>
                                @elseif($pesanan->status_pesanan === 'dikirim')
                                    <span class="bg-purple-50 text-purple-600 border border-purple-100 px-2 py-0.5 rounded-full text-[10px] uppercase font-black tracking-wide whitespace-nowrap">Dikirim</span>
                                @elseif($pesanan->status_pesanan === 'selesai')
                                    <span class="bg-green-50 text-green-600 border border-green-100 px-2 py-0.5 rounded-full text-[10px] uppercase font-black tracking-wide whitespace-nowrap">Selesai</span>
                                @else
                                    <span class="bg-red-50 text-red-600 border border-red-100 px-2 py-0.5 rounded-full text-[10px] uppercase font-black tracking-wide whitespace-nowrap">Batal</span>
                                @endif
                            </td>
                            <td class="py-3 px-3">
                                @if($pesanan->pembayaran)
                                    @if($pesanan->pembayaran->statusPembayaran === 'berhasil')
                                        <span class="text-green-600 text-xs flex items-center gap-1">
                                            <i class="fa-solid fa-circle-check"></i> Lunas
                                        </span>
                                    @elseif($pesanan->pembayaran->statusPembayaran === 'pending')
                                        <span class="text-amber-600 text-xs flex items-center gap-1">
                                            <i class="fa-solid fa-clock"></i> Pending
                                        </span>
                                    @else
                                        <span class="text-red-600 text-xs flex items-center gap-1">
                                            <i class="fa-solid fa-circle-xmark"></i> Gagal
                                        </span>
                                    @endif
                                @else
                                    <span class="text-gray-400 text-xs">-</span>
                                @endif
                            </td>
                            <td class="py-3 px-3 text-center">
                                <a href="{{ route('admin.pesanan.show', $pesanan->id) }}" class="inline-block bg-[#58CC02] hover:bg-[#46a302] text-white px-3 py-1.5 rounded-xl text-xs font-black transition shadow-sm whitespace-nowrap">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-16 text-center text-gray-400">
                                <i class="fa-solid fa-box-open text-4xl mb-3 block"></i>
                                Tidak ditemukan data transaksi.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            @if($pesanans->hasPages())
                <div class="bg-gray-50 px-6 py-4 border-t border-gray-100">
                    {{ $pesanans->links() }}
                </div>
            @endif
        </div>

        <div class="space-y-4 md:hidden">
            @forelse($pesanans as $pesanan)
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 space-y-4">

                    <div class="flex justify-between items-center pb-3 border-b border-gray-100">
                        <span class="font-bold text-xs text-gray-500">No: {{ ($pesanans->firstItem() ?? 1) + $loop->index }}</span>
                        <span class="text-[10px] text-gray-400 font-bold">
                            {{ \Carbon\Carbon::parse($pesanan->created_at)->timezone('Asia/Jakarta')->locale('id')->translatedFormat('d M Y H:i') }} WIB
                        </span>
                    </div>

                    <div class="grid grid-cols-2 gap-3 text-xs">
                        <div>
                            <span class="text-[9px] text-gray-400 font-black uppercase tracking-wider block mb-0.5">Pelanggan</span>
                            <div class="flex flex-col">
                                <span class="font-bold text-gray-800 text-xs">{{ $pesanan->user->namaLengkap ?? 'Dihapus' }}</span>
                                <span class="text-[10px] text-gray-400">@{{ $pesanan->user->username ?? 'user' }}</span>
                            </div>
                        </div>
                        <div>
                            <span class="text-[9px] text-gray-400 font-black uppercase tracking-wider block mb-0.5">Pengiriman</span>
                            <span class="font-medium text-gray-700 text-[11px] truncate block">{{ $pesanan->deskripsi }}</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3 pt-2">
                        <div>
                            <span class="text-[9px] text-gray-400 font-black uppercase tracking-wider block mb-0.5">Total Tagihan</span>
                            <span class="font-black text-gray-900 text-xs">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</span>
                        </div>
                        <div class="space-y-1">
                            <span class="text-[9px] text-gray-400 font-black uppercase tracking-wider block mb-0.5">Status</span>
                            <div class="flex flex-wrap gap-1">

                                @if($pesanan->status_pesanan === 'pending')
                                    <span class="bg-amber-50 text-amber-600 border border-amber-100 px-2 py-0.5 rounded-full text-[9px] uppercase font-black tracking-wide">Menunggu</span>
                                @elseif($pesanan->status_pesanan === 'diproses')
                                    <span class="bg-blue-50 text-blue-600 border border-blue-100 px-2 py-0.5 rounded-full text-[9px] uppercase font-black tracking-wide">Dikemas</span>
                                @elseif($pesanan->status_pesanan === 'dikirim')
                                    <span class="bg-purple-50 text-purple-600 border border-purple-100 px-2 py-0.5 rounded-full text-[9px] uppercase font-black tracking-wide">Dikirim</span>
                                @elseif($pesanan->status_pesanan === 'selesai')
                                    <span class="bg-green-50 text-green-600 border border-green-100 px-2 py-0.5 rounded-full text-[9px] uppercase font-black tracking-wide">Selesai</span>
                                @else
                                    <span class="bg-red-50 text-red-600 border border-red-100 px-2 py-0.5 rounded-full text-[9px] uppercase font-black tracking-wide">Batal</span>
                                @endif

                                @if($pesanan->pembayaran)
                                    @if($pesanan->pembayaran->statusPembayaran === 'berhasil')
                                        <span class="bg-green-50 text-green-700 border border-green-100 px-2 py-0.5 rounded-full text-[9px] uppercase font-black tracking-wide">Lunas</span>
                                    @elseif($pesanan->pembayaran->statusPembayaran === 'pending')
                                        <span class="bg-amber-50 text-amber-700 border border-amber-100 px-2 py-0.5 rounded-full text-[9px] uppercase font-black tracking-wide">Pending</span>
                                    @else
                                        <span class="bg-red-50 text-red-700 border border-red-100 px-2 py-0.5 rounded-full text-[9px] uppercase font-black tracking-wide">Gagal</span>
                                    @endif
                                @else
                                    <span class="bg-gray-50 text-gray-500 border border-gray-200 px-2 py-0.5 rounded-full text-[9px] uppercase font-black tracking-wide">-</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="pt-3 border-t border-gray-100 flex justify-end">
                        <a href="{{ route('admin.pesanan.show', $pesanan->id) }}" class="w-full text-center bg-[#58CC02] hover:bg-[#46a302] text-white py-2.5 rounded-xl text-xs font-black transition shadow-sm">
                            Respon
                        </a>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-10 text-center text-gray-400">
                    <i class="fa-solid fa-box-open text-4xl mb-3 block"></i>
                    Tidak ditemukan data transaksi.
                </div>
            @endforelse

            @if($pesanans->hasPages())
                <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm mt-4">
                    {{ $pesanans->links() }}
                </div>
            @endif
        </div>
    @else

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
                        @forelse($refunds as $refund)
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="py-4 px-6 text-xs font-black text-gray-500">
                                    {{ ($refunds->firstItem() ?? 1) + $loop->index }}
                                </td>
                                <td class="py-4 px-6 text-xs text-gray-500">
                                    {{ \Carbon\Carbon::parse($refund->created_at)->timezone('Asia/Jakarta')->locale('id')->translatedFormat('d M Y H:i') }} WIB
                                </td>
                                <td class="py-4 px-6">
                                    <div class="flex flex-col">
                                        <span class="text-gray-800">{{ $refund->pesanan->user->namaLengkap ?? 'Dihapus' }}</span>
                                        <span class="text-[10px] text-gray-400 font-normal">@&#8203;{{ $refund->pesanan->user->username ?? 'user' }}</span>
                                    </div>
                                </td>
                                <td class="py-4 px-6">
                                    <div class="min-w-0">
                                        <p class="text-xs text-gray-800">{{ $refund->detailPesanan->produk->namaProduk ?? 'Produk Telah Dihapus' }}</p>
                                        <p class="text-[10px] text-gray-400 font-semibold mt-0.5">{{ $refund->jumlah }} unit x Rp {{ number_format($refund->detailPesanan->harga_satuan ?? 0, 0, ',', '.') }}</p>
                                    </div>
                                </td>
                                <td class="py-4 px-6 text-gray-950 font-black">
                                    Rp {{ number_format($refund->nominal, 0, ',', '.') }}
                                </td>
                                <td class="py-4 px-6">
                                    @if($refund->status === 'pending')
                                        <span class="bg-amber-50 text-amber-600 border border-amber-200 px-2.5 py-0.5 rounded-full text-[10px] uppercase font-black tracking-wide">Pending</span>
                                    @elseif($refund->status === 'disetujui')
                                        <span class="bg-green-50 text-green-600 border border-green-200 px-2.5 py-0.5 rounded-full text-[10px] uppercase font-black tracking-wide">Disetujui</span>
                                    @else
                                        <span class="bg-red-50 text-red-600 border border-red-200 px-2.5 py-0.5 rounded-full text-[10px] uppercase font-black tracking-wide">Ditolak</span>
                                    @endif
                                </td>
                                <td class="py-4 px-6 text-right">
                                    <a href="{{ route('admin.refund.show', $refund->id) }}" class="inline-block border border-gray-200 hover:border-gray-300 text-gray-700 bg-white px-3.5 py-2 rounded-xl text-xs font-black transition">
                                        Detail & Verifikasi
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-16 text-center text-gray-400">
                                    <i class="fa-solid fa-rotate-left text-4xl mb-3 block"></i>
                                    Tidak ditemukan data pengajuan refund.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($refunds->hasPages())
                <div class="bg-gray-50 px-6 py-4 border-t border-gray-100">
                    {{ $refunds->links() }}
                </div>
            @endif
        </div>

        <div class="space-y-4 md:hidden">
            @forelse($refunds as $refund)
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 space-y-4">
                    <div class="flex justify-between items-center pb-2 border-b border-gray-100">
                        <div class="text-left">
                            <span class="text-[9px] text-gray-400 block uppercase font-bold">Agen</span>
                            <span class="font-bold text-gray-800 text-xs">{{ $refund->pesanan->user->namaLengkap ?? 'Dihapus' }}</span>
                        </div>
                        @if($refund->status === 'pending')
                            <span class="bg-amber-50 text-amber-600 border border-amber-200 px-2.5 py-0.5 rounded-full text-[9px] uppercase font-black tracking-wide">Pending</span>
                        @elseif($refund->status === 'disetujui')
                            <span class="bg-green-50 text-green-600 border border-green-200 px-2.5 py-0.5 rounded-full text-[9px] uppercase font-black tracking-wide">Disetujui</span>
                        @else
                            <span class="bg-red-50 text-red-600 border border-red-200 px-2.5 py-0.5 rounded-full text-[9px] uppercase font-black tracking-wide">Ditolak</span>
                        @endif
                    </div>

                    <div class="flex items-center gap-3">
                        @if($refund->foto_bukti && $refund->foto_bukti !== 'refunds/cancel_order.png' && file_exists(public_path('storage/' . $refund->foto_bukti)))
                            <div class="w-12 h-12 rounded-xl overflow-hidden border border-gray-150 shrink-0">
                                <img src="{{ asset('storage/' . $refund->foto_bukti) }}" class="w-full h-full object-cover">
                            </div>
                        @else
                            <div class="w-12 h-12 rounded-xl bg-gray-50 border border-gray-150 flex items-center justify-center shrink-0">
                                <span class="text-[8px] font-black text-gray-400 text-center uppercase tracking-tighter leading-none px-1">Tanpa<br>Foto</span>
                            </div>
                        @endif
                        <div class="min-w-0">
                            <h4 class="font-extrabold text-gray-800 text-xs truncate">{{ $refund->detailPesanan->produk->namaProduk ?? 'Produk Telah Dihapus' }}</h4>
                            <p class="text-[10px] text-gray-400 font-bold mt-0.5">
                                {{ $refund->jumlah }} unit x Rp {{ number_format($refund->detailPesanan->harga_satuan ?? 0, 0, ',', '.') }}
                            </p>
                            <p class="text-[10px] text-gray-500 font-mono mt-0.5">No: {{ ($refunds->firstItem() ?? 1) + $loop->index }}</p>
                        </div>
                    </div>

                    <div class="flex justify-between items-center pt-2 border-t border-gray-100 text-xs">
                        <div>
                            <span class="text-[9px] text-gray-400 block uppercase font-bold">Tanggal Pengajuan</span>
                            <span class="text-[10px] text-gray-500 font-semibold">{{ \Carbon\Carbon::parse($refund->created_at)->timezone('Asia/Jakarta')->locale('id')->translatedFormat('d M Y H:i') }} WIB</span>
                        </div>
                        <div class="text-right shrink-0">
                            <span class="text-[9px] text-gray-400 block uppercase font-bold">Nominal Refund</span>
                            <span class="font-black text-gray-900 text-sm">Rp {{ number_format($refund->nominal, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <div class="pt-2">
                        <a href="{{ route('admin.refund.show', $refund->id) }}" class="block text-center w-full bg-gray-50 hover:bg-gray-100 border border-gray-200 text-gray-700 py-2.5 rounded-xl text-xs font-black transition">
                            Detail & Verifikasi
                        </a>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-10 text-center text-gray-400">
                    <i class="fa-solid fa-rotate-left text-4xl mb-3 block"></i>
                    Tidak ditemukan data pengajuan refund.
                </div>
            @endforelse

            @if($refunds->hasPages())
                <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm mt-4">
                    {{ $refunds->links() }}
                </div>
            @endif
        </div>
    @endif
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const activeOrderIds = @json(
            $activeTab === 'refund' 
                ? (collect($refunds)->pluck('pesananId')->unique()->values()->all() ?? [])
                : ($pesanans->pluck('id')->all() ?? [])
        );

        if (window.Echo && activeOrderIds.length > 0) {
            activeOrderIds.forEach(orderId => {
                window.Echo.channel('order.' + orderId)
                    .listen('.OrderStatusUpdated', (e) => {
                        console.log('Order status updated via Reverb on admin index page for order #' + orderId, e);
                        
                        // Fetch the current page content and update the list without refreshing the page
                        fetch(window.location.href)
                            .then(response => response.text())
                            .then(html => {
                                const parser = new DOMParser();
                                const doc = parser.parseFromString(html, 'text/html');
                                const newContainer = doc.getElementById('orders-list-container');
                                const oldContainer = document.getElementById('orders-list-container');
                                if (newContainer && oldContainer) {
                                    oldContainer.innerHTML = newContainer.innerHTML;
                                }
                            })
                            .catch(err => console.error('Error fetching updated orders list:', err));
                    });
            });
        }
    });
</script>
@endsection
