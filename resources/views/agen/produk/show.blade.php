@extends('layouts.agen')

@section('title', $item->namaProduk . ' - Detail Produk')

@section('content')
<div class="max-w-7xl mx-auto pt-6 pb-12 px-4 md:px-0">
    <nav class="flex mb-8 text-sm font-medium text-gray-500" aria-label="Breadcrumb">
        <i class="fa-solid fa-chevron-left mx-3 text-12 "></i>
        <span class="text-gray-800">Detail Produk</span>
    </nav>

    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="grid grid-cols-1 md:grid-cols-2">
            <div class="p-6 md:p-10 bg-gray-50 flex items-center justify-center">
                <div class="relative w-full aspect-square rounded-2xl overflow-hidden shadow-lg bg-white">
                    @if($item->fotoProduk)
                        <img src="{{ $item->fotoProduk }}" class="w-full h-full object-cover" alt="{{ $item->namaProduk }}">
                    @else
                        <div class="flex flex-col items-center justify-center h-full text-gray-300">
                            <i class="fa-solid fa-image text-8xl mb-4"></i>
                            <p class="font-bold">Foto tidak tersedia</p>
                        </div>
                    @endif
                </div>
            </div>

            <div class="p-8 md:p-12 flex flex-col">
                <div class="mb-6">
                    <span class="text-xs font-black uppercase tracking-widest text-[#58CC02] bg-[#58CC02]/10 px-3 py-1 rounded-full mb-4 inline-block">
                        {{ $item->kategori->jenisKategori }}
                    </span>
                    <h1 class="text-3xl md:text-4xl font-black text-gray-900 leading-tight mb-2">{{ $item->namaProduk }}</h1>
                    <p class="text-2xl font-bold text-[#58CC02]">Rp {{ number_format($item->harga, 0, ',', '.') }} <span class="text-sm text-gray-400 font-medium">/ Karung</span></p>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-8">
                    <div class="bg-gray-50 p-4 rounded-2xl border border-gray-100">
                        <p class="text-[10px] font-bold text-gray-400 uppercase mb-1">Mutu Produk</p>
                        <p class="font-bold text-gray-800">{{ strtoupper($item->kategori->mutu) }}</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-2xl border border-gray-100">
                        <p class="text-[10px] font-bold text-gray-400 uppercase mb-1">Ukuran Karung</p>
                        <p class="font-bold text-gray-800">{{ $item->kategori->karung }} Kg</p>
                    </div>
                </div>

                <div class="mb-8">
                    <h4 class="font-black text-gray-800 uppercase text-xs tracking-wider mb-3">Deskripsi Produk</h4>
                    <div class="text-gray-600 leading-relaxed text-sm space-y-4">
                        {!! nl2br(e($item->deskripsi ?? 'Belum ada deskripsi untuk produk ini.')) !!}
                    </div>
                </div>

                <div class="mt-auto pt-8 border-t border-gray-100 flex items-center justify-between gap-6">
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase">Stok Tersedia</p>
                        <p class="text-lg font-black {{ $item->stok > 0 ? 'text-gray-800' : 'text-red-500' }}">
                            {{ $item->stok }} <span class="text-xs font-bold text-gray-400">Karung</span>
                        </p>
                    </div>

                    @if($item->stok > 0)
                        <button class="flex-1 bg-[#58CC02] hover:bg-[#46a302] text-white py-4 rounded-2xl transition-all shadow-lg shadow-[#58CC02]/20 font-black flex items-center justify-center gap-3">
                            <i class="fa-solid fa-cart-plus"></i>
                            Tambah Pesanan
                        </button>
                    @else
                        <button disabled class="flex-1 bg-gray-100 text-gray-400 py-4 rounded-2xl font-black cursor-not-allowed">
                            Stok Habis
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
