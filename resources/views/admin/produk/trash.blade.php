@extends('layouts.admin')

@section('title', 'Stok Kosong - AGRIS')

@section('content')
<div class="max-w-7xl mx-auto pt-3">
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('admin.produk.index') }}" class="w-10 h-10 flex items-center justify-center rounded-full bg-white border border-gray-200 text-gray-600 hover:bg-gray-50 transition shadow-sm">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-2xl font-extrabold text-gray-800">Stok Kosong</h1>
            <p class="text-gray-500 text-sm">Daftar benih yang tidak aktif atau stoknya habis</p>
        </div>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-50 text-gray-500 text-[10px] font-bold uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4">Informasi Produk</th>
                        <th class="px-6 py-4">Kategori</th>
                        <th class="px-6 py-4">Mutu</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($produks as $item)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded-xl bg-gray-100 overflow-hidden">
                                    @if($item->fotoProduk)
                                        <img src="{{ asset('storage/'.$item->fotoProduk) }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="flex items-center justify-center h-full text-gray-300 text-[8px]">NO IMG</div>
                                    @endif
                                </div>
                                <div>
                                    <span class="font-bold text-gray-800 block">{{ $item->namaProduk }}</span>
                                    <span class="text-xs text-red-500 font-bold">Stok: {{ $item->stok }} Kg</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 uppercase text-xs font-bold text-gray-500">{{ $item->kategori->jenisKategori }}</td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 rounded-full bg-red-50 text-red-600 uppercase text-[10px] font-bold">
                                {{ $item->kategori->mutu }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-gray-400 text-xs italic">Dihapus {{ $item->deleted_at->diffForHumans() }}</td>
                        <td class="px-6 py-3 text-right">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('admin.produk.edit', $item->id) }}" class="bg-blue-50 text-blue-600 px-4 py-2 rounded-xl text-xs font-bold hover:bg-blue-600 hover:text-white transition">
                                    Restock
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-20 text-center">
                            <div class="flex flex-col items-center">
                                <i class="fa-solid fa-circle-check text-5xl text-[#58CC02]/20 mb-4"></i>
                                <p class="text-gray-400 font-medium">Arsip kosong. Semua benih tersedia di dashboard utama.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
