@extends('layouts.admin')

@section('title', 'Stok Kosong - AGRIS')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('admin.produk.index') }}" class="w-11 h-11 flex items-center justify-center rounded-2xl bg-white shadow-sm border border-gray-100 text-gray-600 transition">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-2xl font-extrabold text-gray-800 text-red-600">Stok Habis</h1>
            <p class="text-gray-500 text-sm">Produk-produk yang memerlukan restock segera.</p>
        </div>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-50 text-gray-500 text-[10px] font-black uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4">Informasi Produk</th>
                        <th class="px-6 py-4">Kategori</th>
                        <th class="px-6 py-4">Mutu</th>
                        <th class="px-6 py-4">Terakhir Update</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($produks as $item)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded-xl bg-gray-100 overflow-hidden border">
                                    @if($item->fotoProduk)
                                        <img src="{{ asset('storage/'.$item->fotoProduk) }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="flex items-center justify-center h-full text-gray-300 text-[8px]">NO IMG</div>
                                    @endif
                                </div>
                                <div>
                                    <span class="font-bold text-gray-800 block">{{ $item->namaProduk }}</span>
                                    <span class="text-xs text-red-500 font-bold">Stok: 0 Kg</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 uppercase text-xs font-bold text-gray-500">{{ $item->kategori->jenisKategori }}</td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 rounded-full bg-red-50 text-red-600 text-[10px] font-black uppercase border border-red-100">
                                {{ $item->kategori->mutu }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-gray-400 text-xs italic">{{ $item->updated_at->diffForHumans() }}</td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('admin.produk.edit', $item->id) }}" class="bg-[#58CC02] text-white px-4 py-2 rounded-xl text-xs font-bold shadow-sm hover:bg-[#46a302] transition">
                                    Restock
                                </a>
                                <form action="{{ route('admin.produk.forceDelete', $item->id) }}" method="POST" onsubmit="return confirm('Hapus permanen produk ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="bg-gray-100 text-gray-400 p-2.5 rounded-xl hover:bg-red-50 hover:text-red-600 transition">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-20 text-center">
                            <div class="flex flex-col items-center">
                                <i class="fa-solid fa-circle-check text-5xl text-[#58CC02]/20 mb-4"></i>
                                <p class="text-gray-400 font-medium">Semua stok benih tersedia.</p>
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
