@extends('layouts.agen')

@section('title', 'Daftar Produk - AGRIS')

@section('content')
<div class="max-w-7xl mx-auto pb-10 px-4">
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Daftar Produk</h1>
            <p class="text-gray-500 text-sm">Cari dan pilih produk berdasarkan kategori yang tersedia</p>
        </div>
    </div>

    <div class="bg-white p-4 md:p-5 rounded-2xl shadow-sm border border-gray-100 mb-8">
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

            <div class="w-full md:flex-1">
                <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1 ml-1">Isi Karung</label>
                <select name="karung" class="w-full px-4 py-2.5 rounded-xl border border-gray-100 bg-gray-50 outline-none focus:ring-2 focus:ring-[#58CC02] text-sm cursor-pointer appearance-none">
                    <option value="">Semua Ukuran</option>
                    @foreach($daftarKarung as $k)
                        <option value="{{ $k }}" {{ request('karung') == $k ? 'selected' : '' }}>{{ $k }} Kg</option>
                    @endforeach
                </select>
            </div>

            <div class="w-full md:w-auto">
                <button type="submit" class="w-full md:w-auto bg-gray-800 hover:bg-black text-white px-8 py-2.5 rounded-xl transition font-bold text-sm flex items-center justify-center shadow-sm">
                    <i class="fa-solid fa-filter mr-2"></i> Filter
                </button>
            </div>
        </form>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6">
        @forelse($produks as $item)
        <div class="group bg-white rounded-3xl border border-gray-100 p-3 md:p-5 shadow-sm hover:shadow-md transition flex flex-col h-full relative">
            <a href="{{ route('agen.produk.show', $item->id) }}" class="relative aspect-4/4 rounded-2xl overflow-hidden bg-gray-50 mb-4 flex items-center justify-center">
                @if($item->fotoProduk)
                    <img src="{{ $item->fotoProduk }}" class="w-full h-full object-contain p-2 group-hover:scale-105 transition duration-500" alt="{{ $item->namaProduk }}">
                @else
                    <div class="flex items-center justify-center h-full text-gray-200">
                        <i class="fa-solid fa-image text-4xl"></i>
                    </div>
                @endif

                @if($item->stok <= 0)
                    <div class="absolute inset-0 bg-black/40 flex items-center justify-center z-10">
                        <span class="bg-red-500 text-white text-[10px] font-bold px-3 py-1 rounded-full uppercase">Stok Habis</span>
                    </div>
                @endif
            </a>

            <div class="flex flex-wrap gap-1.5 mb-2">
                <span class="text-[9px] font-bold uppercase text-[#58CC02] bg-[#58CC02]/10 px-2 py-0.5 rounded-md">
                    {{ $item->kategori->jenisKategori }}
                </span>
                <span class="text-[9px] font-bold uppercase text-blue-500 bg-blue-50 px-2 py-0.5 rounded-md">
                    {{ $item->kategori->karung }} Kg
                </span>
                <span class="text-[9px] font-bold uppercase text-orange-400 bg-orange-50 px-2 py-0.5 rounded-md">
                    {{ $item->kategori->mutu }}
                </span>
            </div>

            <a href="{{ route('agen.produk.show', $item->id) }}" class="grow">
                <h3 class="font-bold text-gray-800 text-sm mb-2 line-clamp-2 leading-snug hover:text-[#58CC02] transition-colors">{{ $item->namaProduk }}</h3>
                <p class="text-[#58CC02] font-bold text-lg md:text-xl">
                    Rp {{ number_format($item->harga, 0, ',', '.') }}
                </p>
            </a>

            <div class="mt-4">
                <div class="flex items-center justify-between pt-3 border-t border-gray-50 mb-3">
                    <span class="text-[10px] font-bold {{ $item->stok > 5 ? 'text-gray-500' : 'text-orange-500' }} uppercase tracking-tight">
                        Stok : {{ $item->stok }}
                    </span>
                </div>

                @if($item->stok > 0)
                    <button type="button" class="w-full bg-[#58CC02] hover:bg-[#46A302] text-white py-2.5 rounded-xl transition font-bold text-xs flex items-center justify-center gap-2 shadow-sm">
                        <i class="fa-solid fa-cart-plus"></i> Tambah Pesanan
                    </button>
                @else
                    <button type="button" disabled class="w-full bg-gray-100 text-gray-400 py-2.5 rounded-xl font-bold text-xs flex items-center justify-center gap-2 cursor-not-allowed">
                        Stok Habis
                    </button>
                @endif
            </div>
        </div>
        @empty
            <div class="col-span-full py-20 text-center bg-gray-50 rounded-3xl border-2 border-dashed border-gray-200">
                <i class="fa-solid fa-box-open text-5xl text-gray-200 mb-4"></i>
                <p class="text-gray-400 font-bold">Produk tidak ditemukan.</p>
            </div>
        @endforelse
    </div>

    <div class="mt-10 px-4 md:px-0">
        {{ $produks->links() }}
    </div>
</div>
@endsection
