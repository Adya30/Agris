@extends('layouts.agen')

@section('title', 'Katalog Produk - AGRIS')

@section('content')
<div class="max-w-7xl mx-auto pt-2 pb-10">
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 px-4 md:px-0 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Katalog Produk</h1>
            <p class="text-gray-500 text-sm">Pilih benih unggul untuk kebutuhan pertanian Anda</p>
        </div>
        <div class="flex gap-2 sm:gap-3">
            <a href="#" class="flex-1 md:flex-none justify-center bg-white border-2 border-[#58CC02] text-[#58CC02] hover:bg-[#58CC02] hover:text-white px-5 py-2.5 rounded-xl transition shadow-sm font-bold text-sm flex items-center">
                <i class="fa-solid fa-cart-shopping mr-2"></i> Pesanan Saya
            </a>
        </div>
    </div>

    <div class="bg-white p-4 md:p-5 rounded-2xl shadow-sm border border-gray-100 mb-8 mx-4 md:mx-0">
        <form action="{{ route('agen.produk.index') }}" method="GET" class="flex flex-col md:flex-row items-end gap-4">
            <div class="w-full md:flex-1">
                <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1 ml-1">Jenis</label>
                <select name="jenis" class="w-full px-4 py-2.5 rounded-xl border border-gray-100 bg-gray-50 outline-none focus:ring-2 focus:ring-[#58CC02] text-sm cursor-pointer appearance-none">
                    <option value="">Semua Jenis</option>
                    @foreach($daftarJenis as $j)
                        <option value="{{ $j }}" {{ request('jenis') == $j ? 'selected' : '' }}>{{ strtoupper($j) }}</option>
                    @endforeach
                </select>
            </div>

            <div class="w-full md:flex-1">
                <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1 ml-1">Mutu</label>
                <select name="mutu" class="w-full px-4 py-2.5 rounded-xl border border-gray-100 bg-gray-50 outline-none focus:ring-2 focus:ring-[#58CC02] text-sm cursor-pointer appearance-none">
                    <option value="">Semua Mutu</option>
                    @foreach($daftarMutu as $m)
                        <option value="{{ $m }}" {{ request('mutu') == $m ? 'selected' : '' }}>MUTU {{ strtoupper($m) }}</option>
                    @endforeach
                </select>
            </div>

            <div class="w-full md:w-auto">
                <button type="submit" class="w-full md:w-auto bg-[#58CC02] hover:bg-[#46a302] text-white px-8 py-2.5 rounded-xl transition font-bold text-sm flex items-center justify-center shadow-sm">
                    <i class="fa-solid fa-magnifying-glass mr-2"></i> Cari Benih
                </button>
            </div>
        </form>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 md:gap-6 px-4 md:px-0">
        @forelse($produks as $item)
        <div class="group bg-white rounded-3xl border border-gray-100 p-4 md:p-5 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col h-full relative">
            <div class="relative aspect-square rounded-2xl overflow-hidden bg-gray-50 mb-4">
                @if($item->fotoProduk)
                    <img src="{{ asset('storage/'.$item->fotoProduk) }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-500" alt="{{ $item->namaProduk }}">
                @else
                    <div class="flex items-center justify-center h-full text-gray-200">
                        <i class="fa-solid fa-image text-4xl"></i>
                    </div>
                @endif

                @if($item->stok <= 0)
                    <div class="absolute inset-0 bg-black/60 flex items-center justify-center">
                        <span class="bg-red-500 text-white text-[10px] font-bold px-4 py-1.5 rounded-full uppercase tracking-wider">Habis</span>
                    </div>
                @endif
            </div>

            <div class="flex flex-wrap gap-1.5 mb-2">
                <span class="text-[9px] font-bold uppercase text-[#58CC02] bg-[#58CC02]/10 px-2 py-0.5 rounded-md">
                    {{ $item->kategori->jenisKategori }}
                </span>
                <span class="text-[9px] font-bold uppercase text-blue-500 bg-blue-50 px-2 py-0.5 rounded-md">
                    {{ $item->kategori->karung }} Kg
                </span>
            </div>

            <h3 class="font-bold text-gray-800 text-base mb-2 line-clamp-1 leading-snug">{{ $item->namaProduk }}</h3>

            <p class="text-gray-500 text-xs line-clamp-2 mb-4 h-8">
                {{ $item->deskripsi }}
            </p>

            <div class="mt-auto">
                <div class="flex justify-between items-end mb-4">
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase">Harga Per Karung</p>
                        <p class="text-[#58CC02] font-black text-xl">
                            Rp {{ number_format($item->harga, 0, ',', '.') }}
                        </p>
                    </div>
                    <span class="text-[10px] font-bold {{ $item->stok > 5 ? 'text-gray-400' : 'text-orange-500' }}">
                        Stok: {{ $item->stok }}
                    </span>
                </div>

                <div class="grid grid-cols-2 gap-2">
                    <a href="{{ route('agen.produk.show', $item->id) }}" class="text-center bg-gray-100 hover:bg-gray-200 text-gray-600 py-2.5 rounded-xl transition font-bold text-xs">
                        Detail
                    </a>
                    @if($item->stok > 0)
                        <button type="button" class="bg-[#58CC02] hover:bg-[#46a302] text-white py-2.5 rounded-xl transition font-bold text-xs shadow-sm flex items-center justify-center gap-1">
                            <i class="fa-solid fa-plus text-[10px]"></i> Pesanan
                        </button>
                    @else
                        <button disabled class="bg-gray-100 text-gray-400 py-2.5 rounded-xl font-bold text-xs cursor-not-allowed">
                            Kosong
                        </button>
                    @endif
                </div>
            </div>
        </div>
        @empty
            <div class="col-span-full py-20 text-center bg-gray-50 rounded-3xl border-2 border-dashed border-gray-200">
                <i class="fa-solid fa-seedling text-5xl text-gray-200 mb-4"></i>
                <p class="text-gray-400 font-medium">Belum ada produk tersedia untuk saat ini.</p>
            </div>
        @endforelse
    </div>

    <div class="mt-10 px-4 md:px-0">
        {{ $produks->links() }}
    </div>
</div>
@endsection
