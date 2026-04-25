@extends('layouts.admin')

@section('title', 'Tambah Produk Baru - AGRIS')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('admin.produk.index') }}" class="w-11 h-11 flex items-center justify-center rounded-2xl bg-white shadow-sm border border-gray-100 hover:bg-gray-50 text-gray-600 transition">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-2xl font-extrabold text-gray-800">Tambah Benih Baru</h1>
            <p class="text-gray-500 text-sm">Lengkapi detail benih di bawah ini.</p>
        </div>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <form action="{{ route('admin.produk.store') }}" method="POST" enctype="multipart/form-data" class="p-8">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Nama Produk</label>
                    <input type="text" name="namaProduk" class="w-full px-4 py-3.5 rounded-2xl border border-gray-100 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-[#58CC02] outline-none transition" placeholder="Contoh: Benih Padi Ciherang" required>
                </div>

                <div class="md:col-span-1">
                    <div class="flex justify-between items-center mb-2">
                        <label class="block text-sm font-bold text-gray-700">Kategori & Mutu</label>
                        <button type="button" onclick="document.getElementById('modalKategori').classList.remove('hidden')" class="text-[#58CC02] text-xs font-bold hover:underline">
                            + Tambah Baru
                        </button>
                    </div>
                    <select name="kategoriId" class="w-full px-4 py-3.5 rounded-2xl border border-gray-100 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-[#58CC02] outline-none transition appearance-none" required>
                        <option value="">Pilih Kategori</option>
                        @foreach($kategoris as $k)
                            <option value="{{ $k->id }}">{{ strtoupper($k->jenisKategori) }} (Mutu {{ $k->mutu }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Foto Produk</label>
                    <input type="file" name="fotoProduk" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-[#58CC02]/10 file:text-[#58CC02]">
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Stok (Kg)</label>
                    <input type="number" name="stok" step="0.01" class="w-full px-4 py-3.5 rounded-2xl border border-gray-100 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-[#58CC02] outline-none transition" required>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Harga (Rp)</label>
                    <input type="number" name="harga" class="w-full px-4 py-3.5 rounded-2xl border border-gray-100 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-[#58CC02] outline-none transition" required>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Deskripsi</label>
                    <textarea name="deskripsi" rows="4" class="w-full px-4 py-3.5 rounded-2xl border border-gray-100 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-[#58CC02] outline-none transition"></textarea>
                </div>
            </div>

            <div class="mt-10 pt-6 border-t border-gray-50 flex gap-4">
                <a href="{{ route('admin.produk.index') }}" class="flex-1 px-6 py-4 bg-gray-100 text-gray-600 text-center font-bold rounded-2xl hover:bg-gray-200 transition">Batal</a>
                <button type="submit" class="flex-[2] px-6 py-4 bg-[#58CC02] text-white font-bold rounded-2xl hover:bg-[#46a302] transition shadow-lg shadow-[#58CC02]/30">Simpan Produk</button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL TAMBAH KATEGORI --}}
<div id="modalKategori" class="fixed inset-0 z-[100] hidden flex items-center justify-center p-4">
    <div class="fixed inset-0 bg-black/50" onclick="this.parentElement.classList.add('hidden')"></div>
    <div class="relative bg-white w-full max-w-md rounded-3xl p-8 shadow-2xl">
        <h3 class="text-xl font-bold mb-6">Tambah Jenis & Mutu</h3>
        <form action="{{ route('admin.kategori.store') }}" method="POST">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Jenis Kategori</label>
                    <select name="jenisKategori" class="w-full px-4 py-3 rounded-xl border border-gray-100 bg-gray-50 outline-none">
                        <option value="padi">Padi</option>
                        <option value="jagung">Jagung</option>
                        <option value="beras">Beras</option>
                        <option value="gabah">Gabah</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Pilihan Mutu</label>
                    <select name="mutu" class="w-full px-4 py-3 rounded-xl border border-gray-100 bg-gray-50 outline-none">
                        <option value="A">Mutu A</option>
                        <option value="B">Mutu B</option>
                        <option value="C">Mutu C</option>
                    </select>
                </div>
            </div>
            <div class="mt-8 flex gap-3">
                <button type="button" onclick="this.closest('#modalKategori').classList.add('hidden')" class="flex-1 py-3 font-bold text-gray-500">Batal</button>
                <button type="submit" class="flex-1 py-3 bg-gray-800 text-white rounded-xl font-bold shadow-lg">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection
