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
            <p class="text-gray-500 text-sm">Produk-produk di bawah ini memerlukan restock segera.</p>
        </div>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead class="bg-gray-50 text-gray-500 text-xs font-bold uppercase">
                <tr>
                    <th class="px-6 py-4">Produk</th>
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
                            <div class="w-10 h-10 rounded-lg bg-gray-100 overflow-hidden">
                                <img src="{{ asset('storage/'.$item->fotoProduk) }}" class="w-full h-full object-cover">
                            </div>
                            <span class="font-bold text-gray-800">{{ $item->namaProduk }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 uppercase text-sm font-bold text-gray-600">{{ $item->kategori->jenisKategori }}</td>
                    <td class="px-6 py-4"><span class="px-3 py-1 rounded-full bg-red-50 text-red-600 text-xs font-black">MUTU {{ $item->kategori->mutu }}</span></td>
                    <td class="px-6 py-4 text-gray-400 text-sm">{{ $item->updated_at->diffForHumans() }}</td>
                    <td class="px-6 py-4 text-right flex justify-end gap-2">
                        <a href="{{ route('admin.produk.edit', $item->id) }}" class="bg-[#58CC02] text-white px-4 py-2 rounded-xl text-xs font-bold shadow-sm hover:bg-[#46a302]">
                            Restock
                        </a>
                        <form action="{{ route('admin.produk.forceDelete', $item->id) }}" method="POST" onsubmit="return confirm('Hapus permanen?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="bg-gray-100 text-gray-400 p-2 rounded-xl hover:bg-red-50 hover:text-red-600 transition">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="py-20 text-center text-gray-400 font-medium">Semua stok benih terpenuhi.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
