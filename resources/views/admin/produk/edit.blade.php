@extends('layouts.admin')

@section('title', 'Edit Produk - AGRIS')

@section('content')
<div class="max-w-4xl mx-auto py-10 px-4">
    {{-- Header --}}
    <div class="flex items-center gap-4 mb-10">
        <a href="{{ route('admin.produk.index') }}" class="w-11 h-11 flex items-center justify-center rounded-full bg-white shadow-sm border border-gray-100 hover:bg-gray-50 text-gray-600 transition">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-2xl font-extrabold text-gray-800">Edit Informasi Produk</h1>
            <p class="text-gray-500 text-sm">Perbarui detail untuk produk <span class="font-bold text-[#58CC02]">"{{ $produk->namaProduk }}"</span></p>
        </div>
    </div>

    {{-- Error Alert jika ada validasi gagal --}}
    @if ($errors->any())
        <div class="mb-8 p-5 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-r-2xl shadow-sm">
            <p class="font-bold mb-2">Periksa kembali input Anda:</p>
            <ul class="list-disc list-inside text-sm space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <form action="{{ route('admin.produk.update', $produk->id) }}" method="POST" enctype="multipart/form-data" class="p-8 md:p-10">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-3 gap-10">

                {{-- Bagian Kiri: Preview & Ganti Foto --}}
                <div class="text-center">
                    <label class="block text-sm font-black text-gray-400 uppercase tracking-wider mb-4">Foto Produk</label>
                    <div class="relative w-full aspect-square rounded-3xl bg-gray-50 overflow-hidden border-2 border-dashed border-gray-100 mb-5 group">
                        @if($produk->fotoProduk)
                            <img src="{{ asset('storage/' . $produk->fotoProduk) }}" class="w-full h-full object-cover shadow-inner transition duration-300 group-hover:scale-105">
                        @else
                            <div class="flex flex-col items-center justify-center h-full text-gray-300">
                                <i class="fa-solid fa-image text-6xl mb-2"></i>
                                <span class="text-xs font-medium">Belum ada foto</span>
                            </div>
                        @endif
                    </div>

                    {{-- Input File Tersembunyi --}}
                    <input type="file" name="fotoProduk" id="fotoProduk" class="hidden" accept="image/*">

                    {{-- Tombol Pemicu Input File --}}
                    <label for="fotoProduk" class="inline-block px-6 py-2.5 bg-white border border-gray-200 text-gray-700 font-bold rounded-xl text-sm cursor-pointer hover:bg-gray-50 transition shadow-sm">
                        <i class="fa-solid fa-cloud-arrow-up mr-2 text-[#58CC02]"></i>Ganti Foto
                    </label>
                    <p class="text-xs text-gray-400 mt-2">Maks. 2MB (JPG, JPEG, PNG)</p>
                </div>

                {{-- Bagian Kanan: Input Data Produk --}}
                <div class="md:col-span-2 space-y-7">

                    {{-- Nama Produk --}}
                    <div>
                        <label class="block text-sm font-black text-gray-400 uppercase tracking-wider mb-2">Nama Produk</label>
                        <input type="text" name="namaProduk" value="{{ old('namaProduk', $produk->namaProduk) }}" class="w-full px-5 py-4 rounded-2xl border border-gray-100 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-[#58CC02] focus:border-[#58CC02] outline-none transition font-medium" placeholder="Nama benih..." required>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Stok --}}
                        <div>
                            <label class="block text-sm font-black text-gray-400 uppercase tracking-wider mb-2">Stok Tersedia (Kg)</label>
                            <input type="number" name="stok" step="0.01" value="{{ old('stok', $produk->stok) }}" class="w-full px-5 py-4 rounded-2xl border border-gray-100 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-[#58CC02] focus:border-[#58CC02] outline-none transition font-bold" required>
                        </div>

                        {{-- Harga --}}
                        <div>
                            <label class="block text-sm font-black text-gray-400 uppercase tracking-wider mb-2">Harga Per Kg (Rp)</label>
                            {{-- Kita mengambil attribute harga mentah untuk diinput kembali --}}
                            <input type="number" name="harga" value="{{ old('harga', number_format($produk->getAttributes()['harga'], 0, '', '')) }}" class="w-full px-5 py-4 rounded-2xl border border-gray-100 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-[#58CC02] focus:border-[#58CC02] outline-none transition font-bold text-[#58CC02]" required>
                        </div>
                    </div>

                    {{-- Kategori & Mutu --}}
                    <div>
                        <label class="block text-sm font-black text-gray-400 uppercase tracking-wider mb-2">Kategori Produk & Mutu</label>
                        <select name="kategoriId" class="w-full px-5 py-4 rounded-2xl border border-gray-100 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-[#58CC02] focus:border-[#58CC02] outline-none transition font-medium appearance-none" required>
                            @foreach($kategoris as $k)
                                <option value="{{ $k->id }}" {{ $produk->kategoriId == $k->id ? 'selected' : '' }}>
                                    {{ strtoupper($k->jenisKategori) }} — Standar Mutu {{ $k->mutu }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Deskripsi --}}
                    <div>
                        <label class="block text-sm font-black text-gray-400 uppercase tracking-wider mb-2">Deskripsi Produk</label>
                        <textarea name="deskripsi" rows="6" class="w-full px-5 py-4 rounded-2xl border border-gray-100 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-[#58CC02] focus:border-[#58CC02] outline-none transition font-medium resize-none" placeholder="Jelaskan detail dan keunggulan produk di sini...">{{ old('deskripsi', $produk->deskripsi) }}</textarea>
                    </div>
                </div>
            </div>

            {{-- Tombol Aksi --}}
            <div class="mt-12 pt-8 border-t border-gray-100 flex flex-col md:flex-row gap-4">
                <a href="{{ route('admin.produk.index') }}" class="flex-1 px-8 py-4 bg-gray-100 text-gray-600 text-center font-extrabold rounded-2xl hover:bg-gray-200 transition">
                    <i class="fa-solid fa-xmark mr-2"></i>Batalkan Perubahan
                </a>
                <button type="submit" class="flex-[2] px-8 py-4 bg-[#58CC02] text-white font-extrabold rounded-2xl hover:bg-[#4fb802] transition shadow-lg shadow-[#58CC02]/30 flex items-center justify-center gap-2">
                    <i class="fa-solid fa-floppy-disk"></i>Update Informasi Produk
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Sederhana JavaScript untuk Preview Foto instan sebelum upload --}}
<script>
    const fileInput = document.getElementById('fotoProduk');
    const previewFoto = document.querySelector('.relative.w-full.aspect-square img');
    const fallbackFoto = document.querySelector('.relative.w-full.aspect-square .flex');

    if (fileInput) {
        fileInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    // Jika elemen img belum ada (misalnya saat 'Belum ada foto'), buat elemen img baru
                    if (!previewFoto) {
                        fallbackFoto.classList.add('hidden');
                        const img = document.createElement('img');
                        img.className = "w-full h-full object-cover shadow-inner transition duration-300";
                        img.src = e.target.result;
                        fallbackFoto.parentElement.appendChild(img);
                    } else {
                        // Jika img sudah ada, tinggal ganti src-nya
                        previewFoto.src = e.target.result;
                    }
                };
                reader.readAsDataURL(this.files[0]);
            }
        });
    }
</script>
@endsection
