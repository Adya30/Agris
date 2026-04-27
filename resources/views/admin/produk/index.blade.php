@extends('layouts.admin')

@section('title', 'Manajemen Produk - AGRIS')

@section('content')
<div class="max-w-7xl mx-auto pt-2">
    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Daftar Produk</h1>
            <p class="text-gray-500 text-sm">Kelola dashboard Anda</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.produk.trash') }}" class="bg-white border border-gray-200 text-gray-600 px-5 py-2.5 rounded-xl hover:bg-gray-50 transition flex items-center shadow-sm">
                <i class="fa-solid fa-box-archive mr-2 text-orange-500"></i> Stok Kosong
            </a>
            <a href="{{ route('admin.produk.create') }}" class="bg-[#58CC02] hover:bg-[#46a302] text-white px-6 py-2.5 rounded-xl transition shadow-md font-bold flex items-center">
                <i class="fa-solid fa-plus mr-2"></i> Tambah Produk
            </a>
        </div>
    </div>

    {{-- Filter Bar --}}
    <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 mb-8">
        <form action="{{ route('admin.produk.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
            <div class="relative flex-1">
                <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama benih..."
                    class="w-full pl-11 pr-4 py-2.5 rounded-xl border border-gray-100 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-[#58CC02] outline-none transition">
            </div>
            <select name="kategori" class="px-4 py-2.5 rounded-xl border border-gray-100 bg-gray-50 outline-none focus:ring-2 focus:ring-[#58CC02] min-w-[200px]">
                <option value="">Semua Kategori</option>
                @foreach($kategoris as $k)
                    <option value="{{ $k->id }}" {{ request('kategori') == $k->id ? 'selected' : '' }}>
                        {{ strtoupper($k->jenisKategori) }} ({{ $k->mutu }})
                    </option>
                @endforeach
            </select>
            <button type="submit" class="bg-gray-800 text-white px-8 py-2.5 rounded-xl hover:bg-black transition font-bold">Terapkan</button>
        </form>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse($produks as $item)
            <div class="bg-white rounded-3xl border border-gray-100 p-5 shadow-sm hover:shadow-md transition group flex flex-col">
                {{-- Foto Produk --}}
                <div class="relative aspect-square rounded-2xl overflow-hidden bg-gray-100 mb-4">
                    @if($item->fotoProduk)
                        <img src="{{ asset('storage/'.$item->fotoProduk) }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                    @else
                        <div class="flex items-center justify-center h-full text-gray-300">
                            <i class="fa-solid fa-image text-4xl"></i>
                        </div>
                    @endif
                </div>

                {{-- Info Kategori & Mutu (Dibawah Foto) --}}
                <div class="flex items-center gap-2 mb-2">
                    <span class="text-[10px] font-black uppercase text-[#58CC02] bg-[#58CC02]/10 px-2 py-0.5 rounded-md">
                        {{ $item->kategori->jenisKategori }}
                    </span>
                    <span class="text-[10px] font-black uppercase text-gray-500 bg-gray-100 px-2 py-0.5 rounded-md">
                        Mutu {{ $item->kategori->mutu }}
                    </span>
                </div>

                {{-- Nama & Harga --}}
                <h3 class="font-bold text-gray-800 truncate">{{ $item->namaProduk }}</h3>
                <p class="text-[#58CC02] font-black text-lg mb-2">
                    Rp {{ number_format($item->harga, 0, ',', '.') }}<span class="text-xs text-gray-400 font-medium">/kg</span>
                </p>

                {{-- Deskripsi (Dibatasi agar tidak merusak layout) --}}
                <p class="text-gray-500 text-xs leading-relaxed mb-4 flex-grow">
                    {{ Str::limit($item->deskripsi, 60, '...') }}
                </p>

                {{-- Footer Card: Stok & Action --}}
                <div class="flex items-center justify-between pt-4 border-t border-gray-50">
                    <div class="flex flex-col">
                        <span class="text-[10px] text-gray-400 uppercase font-bold tracking-wider">Stok</span>
                        <span class="text-sm font-black text-gray-700">{{ $item->stok }} <span class="text-[10px] font-medium">Kg</span></span>
                    </div>

                    <div class="flex gap-2">
                        <a href="{{ route('admin.produk.edit', $item->id) }}" class="w-9 h-9 flex items-center justify-center rounded-xl bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition">
                            <i class="fa-solid fa-pen-to-square text-xs"></i>
                        </a>
                        <form action="{{ route('admin.produk.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Arsipkan produk ini?')">
                            @csrf @method('DELETE')
                            <button class="w-9 h-9 flex items-center justify-center rounded-xl bg-red-50 text-red-600 hover:bg-red-600 hover:text-white transition">
                                <i class="fa-solid fa-box-archive text-xs"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-20 text-center bg-white rounded-3xl border-2 border-dashed border-gray-100">
                <i class="fa-solid fa-box-open text-6xl text-gray-200 mb-4"></i>
                <p class="text-gray-400 font-medium">Belum ada produk ditemukan.</p>
            </div>
        @endforelse
    </div>

    <div class="mt-10">{{ $produks->links() }}</div>
</div>
@endsection
