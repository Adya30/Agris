@extends('layouts.admin')

@section('title', 'Daftar Pengajuan Refund - Admin AGRIS')

@section('content')
<div class="max-w-7xl mx-auto pt-5 pb-12 px-4 sm:px-6">
    <div class="mb-8">
        <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900 tracking-tight">Daftar Pengajuan Refund</h1>
        <p class="text-gray-500 text-xs md:text-sm mt-1">Verifikasi dan kelola permohonan pengembalian dana dari agen</p>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded-xl mb-6 text-xs md:text-sm font-bold flex items-center gap-3">
            <i class="fa-solid fa-circle-check text-lg text-green-500"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <div class="flex overflow-x-auto bg-white rounded-2xl border border-gray-200 p-1 mb-8 shadow-sm scrollbar-none">
        <a href="{{ route('admin.refund.index', ['status' => 'all']) }}" class="flex-1 text-center py-3 px-4 rounded-xl text-xs font-black transition-all whitespace-nowrap {{ $status === 'all' ? 'bg-[#0f8629] text-white shadow-sm' : 'text-gray-500 hover:text-gray-800' }}">
            Semua
        </a>
        <a href="{{ route('admin.refund.index', ['status' => 'pending']) }}" class="flex-1 text-center py-3 px-4 rounded-xl text-xs font-black transition-all whitespace-nowrap {{ $status === 'pending' ? 'bg-[#0f8629] text-white shadow-sm' : 'text-gray-500 hover:text-gray-800' }}">
            Pending
        </a>
        <a href="{{ route('admin.refund.index', ['status' => 'disetujui']) }}" class="flex-1 text-center py-3 px-4 rounded-xl text-xs font-black transition-all whitespace-nowrap {{ $status === 'disetujui' ? 'bg-[#0f8629] text-white shadow-sm' : 'text-gray-500 hover:text-gray-800' }}">
            Disetujui
        </a>
        <a href="{{ route('admin.refund.index', ['status' => 'ditolak']) }}" class="flex-1 text-center py-3 px-4 rounded-xl text-xs font-black transition-all whitespace-nowrap {{ $status === 'ditolak' ? 'bg-[#0f8629] text-white shadow-sm' : 'text-gray-500 hover:text-gray-800' }}">
            Ditolak
        </a>
    </div>

    @if($refunds->isEmpty())
        <div class="py-24 text-center bg-white rounded-3xl border border-gray-100 shadow-sm px-4">
            <i class="fa-solid fa-rotate-left text-5xl text-gray-200 mb-4"></i>
            <p class="text-gray-400 font-extrabold uppercase text-xs tracking-widest">Tidak Ada Pengajuan Refund.</p>
        </div>
    @else
        <div class="bg-white rounded-3xl border border-gray-200 shadow-sm overflow-hidden hidden md:block">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100">
                            <th class="py-4 px-6 text-xs font-black text-gray-400 uppercase tracking-wider">Tanggal</th>
                            <th class="py-4 px-6 text-xs font-black text-gray-400 uppercase tracking-wider">Agen</th>
                            <th class="py-4 px-6 text-xs font-black text-gray-400 uppercase tracking-wider">ID Pesanan</th>
                            <th class="py-4 px-6 text-xs font-black text-gray-400 uppercase tracking-wider">Produk</th>
                            <th class="py-4 px-6 text-xs font-black text-gray-400 uppercase tracking-wider">Nominal</th>
                            <th class="py-4 px-6 text-xs font-black text-gray-400 uppercase tracking-wider">Status</th>
                            <th class="py-4 px-6 text-xs font-black text-gray-400 uppercase tracking-wider text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm font-bold text-gray-700">
                        @foreach($refunds as $refund)
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="py-4 px-6 text-xs text-gray-500">
                                    {{ \Carbon\Carbon::parse($refund->created_at)->timezone('Asia/Jakarta')->locale('id')->translatedFormat('d M Y H:i') }} WIB
                                </td>
                                <td class="py-4 px-6">
                                    <div class="flex flex-col">
                                        <span class="text-gray-800">{{ $refund->pesanan->user->namaLengkap ?? 'Dihapus' }}</span>
                                        <span class="text-[10px] text-gray-400 font-normal">@&#8203;{{ $refund->pesanan->user->username ?? 'user' }}</span>
                                    </div>
                                </td>
                                <td class="py-4 px-6 font-mono text-xs text-gray-600">
                                    #{{ substr($refund->pesananId, 0, 8) }}
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
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="space-y-4 md:hidden">
            @foreach($refunds as $refund)
                <div class="bg-white rounded-3xl border border-gray-200 shadow-sm p-5 space-y-4">
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
                        @if($refund->foto_bukti)
                            <div class="w-12 h-12 rounded-xl overflow-hidden border border-gray-150 shrink-0">
                                <img src="{{ asset('storage/' . $refund->foto_bukti) }}" class="w-full h-full object-cover">
                            </div>
                        @endif
                        <div class="min-w-0">
                            <h4 class="font-extrabold text-gray-800 text-xs truncate">{{ $refund->detailPesanan->produk->namaProduk ?? 'Produk Telah Dihapus' }}</h4>
                            <p class="text-[10px] text-gray-400 font-bold mt-0.5">
                                {{ $refund->jumlah }} unit x Rp {{ number_format($refund->detailPesanan->harga_satuan ?? 0, 0, ',', '.') }}
                            </p>
                            <p class="text-[10px] text-gray-500 font-mono mt-0.5">Pesanan #{{ substr($refund->pesananId, 0, 8) }}</p>
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
            @endforeach
        </div>
    @endif
</div>
@endsection
