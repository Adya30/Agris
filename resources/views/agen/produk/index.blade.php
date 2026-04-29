@extends('layouts.agen')

@section('title', 'Katalog Produk - AGRIS')

@section('content')
<div class="max-w-7xl mx-auto sm:px-6 lg:p-2">

    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Katalog Benih Unggul</h1>
            <p class="text-gray-500 mt-1 text-base">Pilih dan pesan benih kualitas terbaik untuk pertanian Anda.</p>
        </div>

        <!-- Cart Preview (Optional) -->
        <a href="#" class="relative inline-flex items-center bg-white border border-gray-200 px-6 py-3 rounded-2xl shadow-sm hover:shadow-md transition group">
            <i class="fa-solid fa-cart-shopping text-[#58CC02] mr-3 text-xl"></i>
            <span class="font-bold text-gray-700">Keranjang Saya</span>
            <span class="absolute -top-2 -right-2 bg-red-500 text-white text-[10px] font-bold px-2 py-1 rounded-full border-2 border-white">0</span>
        </a>
    </div>

    <!-- Filter & Search Bar -->
    <div class="bg-white p-5 rounded-3xl shadow-sm border border-gray-100 mb-10">
        <form action="{{ route('agen.produk.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-4">
            <!-- Search -->
            <div class="md:col-span-7 relative">
                <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Cari benih padi, jagung, atau lainnya..."
                    class="w-full pl-12 pr-4 py-3 rounded-2xl border-none bg-gray-50 focus:ring-2 focus:ring-[#58CC02] transition outline-none text-gray-700">
            </div>

            <!-- Category -->
            <div class="md:col-span-3">
                <select name="kategori" class="w-full px-4 py-3 rounded-2xl border-none bg-gray-50 focus:ring-2 focus:ring-[#58CC02] outline-none text-gray-700 appearance-none cursor-pointer">
                    <option value="">Semua Kategori</option>
                    @foreach($kategoris as $k)
                        <option value="{{ $k->id }}" {{ request('kategori') == $k->id ? 'selected' : '' }}>
                            {{ $k->jenisKategori }} ({{ $k->mutu }})
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Submit -->
            <div class="md:col-span-2">
                <button type="submit" class="w-full bg-[#58CC02] text-white py-3 rounded-2xl hover:bg-[#46a302] transition font-bold shadow-lg shadow-green-100">
                    Cari Produk
                </button>
            </div>
        </form>
    </div>

    <!-- Product Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
        @forelse($produks as $item)
        <div class="group bg-white rounded-[2rem] border border-gray-100 overflow-hidden hover:shadow-2xl hover:shadow-gray-200 transition-all duration-500 flex flex-col h-full relative">

            <!-- Badge Diskon/Baru (Opsional) -->
            @if($item->stok > 0 && $item->stok < 10)
                <div class="absolute top-4 left-4 z-10 bg-orange-500 text-white text-[10px] font-black uppercase px-3 py-1 rounded-full shadow-sm">
                    Stok Terbatas
                </div>
            @endif

            <!-- Image Section -->
            <div class="relative overflow-hidden aspect-[4/5]">
                @if($item->fotoProduk)
                    <img src="{{ asset('storage/'.$item->fotoProduk) }}" alt="{{ $item->namaProduk }}"
                        class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                @else
                    <div class="w-full h-full bg-gray-100 flex items-center justify-center">
                        <i class="fa-solid fa-seedling text-5xl text-gray-200"></i>
                    </div>
                @endif

                <!-- Quick Action Overlay -->
                <div class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2">
                    {{-- <a href="{{ route('agen.produk.show', $item->id) }}" class="bg-white p-3 rounded-full hover:bg-[#58CC02] hover:text-white transition shadow-lg">
                        <i class="fa-solid fa-eye"></i>
                    </a> --}}
                </div>
            </div>

            <!-- Content Section -->
            <div class="p-6 flex flex-col flex-1">
                <div class="flex items-center gap-2 mb-3">
                    <span class="text-[10px] font-bold uppercase text-[#58CC02] bg-[#58CC02]/10 px-2.5 py-1 rounded-lg">
                        {{ $item->kategori->jenisKategori }}
                    </span>
                    <span class="text-[10px] font-bold uppercase text-gray-400">
                        {{ $item->kategori->mutu }}
                    </span>
                </div>

                {{-- <a href="{{ route('agen.produk.show', $item->id) }}" class="block mb-2">
                    <h3 class="font-bold text-gray-800 text-lg hover:text-[#58CC02] transition line-clamp-2 leading-snug">
                        {{ $item->namaProduk }}
                    </h3>
                </a> --}}

                <div class="mt-auto pt-4 flex flex-col gap-4">
                    <div>
                        <p class="text-[11px] text-gray-400 font-bold uppercase tracking-wider mb-1">Harga Agen</p>
                        <div class="flex items-baseline gap-1">
                            <span class="text-2xl font-black text-gray-900">Rp {{ number_format($item->harga, 0, ',', '.') }}</span>
                            <span class="text-xs text-gray-400 font-medium">/kg</span>
                        </div>
                    </div>

                    <!-- Add to Cart Button -->
                    @if($item->stok > 0)
                    {{-- <form action="#" method="POST">
                        @csrf
                        <button type="submit" class="w-full bg-gray-900 text-white py-3.5 rounded-2xl font-bold flex items-center justify-center gap-2 hover:bg-[#58CC02] transition-colors shadow-lg active:scale-95 duration-150">
                            <i class="fa-solid fa-plus text-xs"></i>
                            Tambah Keranjang
                        </button>
                    </form> --}}
                    @else
                    <button disabled class="w-full bg-gray-100 text-gray-400 py-3.5 rounded-2xl font-bold cursor-not-allowed">
                        Stok Habis
                    </button>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full py-20 text-center">
            <div class="bg-gray-50 rounded-[3rem] p-12 inline-block">
                <i class="fa-solid fa-search text-6xl text-gray-200 mb-6 block"></i>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Produk Tidak Ditemukan</h3>
                <p class="text-gray-500">Coba gunakan kata kunci lain atau filter kategori yang berbeda.</p>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-16 flex justify-center">
        {{ $produks->links() }}
    </div>
</div>
@endsection
