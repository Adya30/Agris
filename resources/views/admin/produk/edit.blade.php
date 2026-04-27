@extends('layouts.admin')

@section('title', 'Edit Produk - AGRIS')

@section('content')
<div class="max-w-5xl mx-auto pt-4 pb-12">
    {{-- Header --}}
    <div class="flex items-center gap-4 mb-6 px-4 md:px-0">
        <div>
            <h1 class="text-xl font-bold text-gray-800">Edit Data Produk</h1>
            <p class="text-xs text-gray-500">Mengubah varietas <span class="text-[#58CC02] font-semibold">{{ $produk->namaProduk }}</span></p>
        </div>
    </div>

    <form action="{{ route('admin.produk.update', $produk->id) }}" method="POST" enctype="multipart/form-data" id="formProduk">
        @csrf
        @method('PUT')
        <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden mx-4 md:mx-0">
            <div class="flex flex-col lg:flex-row">

                {{-- SISI KIRI: PREVIEW & UPLOAD FOTO --}}
                <div class="lg:w-1/3 bg-gray-50 p-8 border-b lg:border-b-0 lg:border-r border-gray-200">
                    <div class="flex flex-col items-center">
                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-4">Foto Produk</span>

                        <div class="relative cursor-pointer">
                            <div class="w-44 h-44 rounded-xl overflow-hidden bg-white border-2 border-dashed @error('fotoProduk') border-red-500 @else border-gray-300 @enderror flex items-center justify-center">
                                @if($produk->fotoProduk)
                                    <img id="previewImg" src="{{ asset('storage/'.$produk->fotoProduk) }}" class="w-full h-full object-cover">
                                    <div id="placeholderIcon" class="hidden text-center text-gray-300">
                                        <i class="fa-solid fa-camera text-3xl mb-1"></i>
                                        <p class="text-[10px] font-medium">Klik untuk ganti</p>
                                    </div>
                                @else
                                    <img id="previewImg" src="#" class="w-full h-full object-cover hidden">
                                    <div id="placeholderIcon" class="text-center text-gray-300">
                                        <i class="fa-solid fa-camera text-3xl mb-1"></i>
                                        <p class="text-[10px] font-medium">Klik untuk upload</p>
                                    </div>
                                @endif
                            </div>
                            <input type="file" name="fotoProduk" id="fotoInput" class="absolute inset-0 opacity-0 cursor-pointer" onchange="previewImage(this)">
                        </div>
                        @error('fotoProduk')
                            <p class="text-red-500 text-[11px] mt-2 font-semibold italic text-center">{{ $message }}</p>
                        @enderror
                        <p class="text-[10px] text-gray-400 mt-4 text-center">Abaikan jika tidak ingin mengganti foto</p>
                    </div>
                </div>

                {{-- SISI KANAN: FORM INPUT --}}
                <div class="lg:w-2/3 p-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                        {{-- Nama Produk --}}
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Varietas Benih</label>
                            <input type="text" name="namaProduk" value="{{ old('namaProduk', $produk->namaProduk) }}"
                                class="w-full px-4 py-3 rounded-xl border @error('namaProduk') border-red-500 @else border-gray-300 @enderror focus:border-[#58CC02] focus:ring-1 focus:ring-[#58CC02] outline-none transition"
                                placeholder="Contoh: Padi Pandan Wangi Super">
                            @error('namaProduk')
                                <p class="text-red-500 text-xs mt-1 font-medium italic">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Jenis Komoditas --}}
                        <div class="md:col-span-1">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Jenis Komoditas</label>
                            <select id="selectJenis" class="w-full px-4 py-3 rounded-xl border @error('kategoriId') border-red-500 @else border-gray-300 @enderror focus:border-[#58CC02] outline-none appearance-none bg-white">
                                <option value="">-- Pilih Jenis --</option>
                                @foreach($kategoris->unique('jenisKategori') as $k)
                                    <option value="{{ $k->jenisKategori }}"
                                        {{ old('selectJenis', $produk->kategori->jenisKategori) == $k->jenisKategori ? 'selected' : '' }}>
                                        {{ strtoupper($k->jenisKategori) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Standar Mutu --}}
                        <div class="md:col-span-1">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Standar Mutu</label>
                            <select name="kategoriId" id="selectMutu" class="w-full px-4 py-3 rounded-xl border @error('kategoriId') border-red-500 @else border-gray-300 @enderror bg-white outline-none">
                                {{-- Akan diisi oleh JS --}}
                                <option value="{{ $produk->kategoriId }}">{{ strtoupper($produk->kategori->mutu) }}</option>
                            </select>
                            @error('kategoriId')
                                <p class="text-red-500 text-xs mt-1 font-medium italic">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Stok --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Stok Tersedia (Kg)</label>
                            <input type="number" name="stok" step="any" value="{{ old('stok', $produk->stok) }}"
                                class="w-full px-4 py-3 rounded-xl border @error('stok') border-red-500 @else border-gray-300 @enderror outline-none"
                                placeholder="0.00">
                            @error('stok')
                                <p class="text-red-500 text-xs mt-1 font-medium italic">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Harga --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Harga per Kg (Rp)</label>
                            <input type="text" id="hargaVisual" class="w-full px-4 py-3 rounded-xl border @error('harga') border-red-500 @else border-gray-300 @enderror outline-none font-bold text-gray-800" placeholder="0">
                            <input type="hidden" name="harga" id="hargaAsli" value="{{ old('harga', (int)$produk->getAttributes()['harga']) }}">
                            @error('harga')
                                <p class="text-red-500 text-xs mt-1 font-medium italic">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Deskripsi --}}
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Keterangan Produk</label>
                            <textarea name="deskripsi" rows="3" class="w-full px-4 py-3 rounded-xl border border-gray-300 outline-none resize-none" placeholder="Tambahkan deskripsi singkat...">{{ old('deskripsi', $produk->deskripsi) }}</textarea>
                        </div>
                    </div>

                    <div class="mt-8 flex gap-3">
                        <button type="button" onclick="openConfirmModal()" class="flex-[2] bg-[#58CC02] text-white py-3.5 rounded-xl font-bold active:bg-[#46a302] transition shadow-sm">
                            Simpan Perubahan
                        </button>
                        <a href="{{ route('admin.produk.index') }}" class="flex-1 bg-gray-100 text-center text-gray-600 py-3.5 rounded-xl font-bold hover:bg-gray-200 transition">
                            Batal
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<x-modal id="modalUpdateProduk" title="Konfirmasi Update" message="Simpan perubahan data produk ini?" confirmText="Simpan" cancelText="Batal" confirmId="btnSubmitForm" cancelId="btnCloseModal" />

<script>
    const kategoris = @json($kategoris);
    const selectedKategoriId = "{{ $produk->kategoriId }}";
    const selectJenis = document.getElementById('selectJenis');
    const selectMutu = document.getElementById('selectMutu');
    const hargaVisual = document.getElementById('hargaVisual');
    const hargaAsli = document.getElementById('hargaAsli');

    // 1. Logika Dropdown Dinamis (Sama dengan Create)
    function filterMutu(selectedJenis, targetId = null) {
        selectMutu.innerHTML = '<option value="">-- Pilih Mutu --</option>';
        if (selectedJenis) {
            const filtered = kategoris.filter(k => k.jenisKategori === selectedJenis);
            filtered.forEach(k => {
                const option = document.createElement('option');
                option.value = k.id;
                option.textContent = k.mutu.toUpperCase();
                if(targetId && k.id == targetId) option.selected = true;
                selectMutu.appendChild(option);
            });
        }
    }

    selectJenis.addEventListener('change', function() {
        filterMutu(this.value);
    });

    // Inisialisasi awal saat halaman edit dibuka
    filterMutu(selectJenis.value, selectedKategoriId);

    // 2. Logika Rupiah
    hargaVisual.addEventListener('keyup', function() { formatRupiah(this); });

    function formatRupiah(input) {
        let value = input.value.replace(/[^,\d]/g, '').toString();
        let split = value.split(',');
        let sisa = split[0].length % 3;
        let rupiah = split[0].substr(0, sisa);
        let ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        if (ribuan) {
            let separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }
        rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
        input.value = rupiah;
        hargaAsli.value = value.replace(/\./g, '').replace(',', '.');
    }

    // Inisialisasi harga saat load
    if (hargaAsli.value) {
        hargaVisual.value = hargaAsli.value.replace('.', ',');
        formatRupiah(hargaVisual);
    }

    // 3. Modal & Preview
    function openConfirmModal() {
        document.getElementById('modalUpdateProduk').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeConfirmModal() {
        document.getElementById('modalUpdateProduk').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    document.getElementById('btnSubmitForm').addEventListener('click', () => {
        document.getElementById('formProduk').submit();
    });

    document.getElementById('btnCloseModal').addEventListener('click', closeConfirmModal);

    function previewImage(input) {
        const preview = document.getElementById('previewImg');
        const placeholder = document.getElementById('placeholderIcon');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = e => {
                preview.src = e.target.result;
                preview.classList.remove('hidden');
                placeholder.classList.add('hidden');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection
