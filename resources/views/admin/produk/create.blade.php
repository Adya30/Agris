@extends('layouts.admin')

@section('title', 'Tambah Produk Baru - AGRIS')

@section('content')
<div class="max-w-5xl mx-auto pt-4 pb-12">
    <div class="flex items-center gap-4 mb-6 px-4 md:px-0">
        <h1 class="text-xl font-bold text-gray-800">Tambah Data Produk</h1>
    </div>

    <form action="{{ route('admin.produk.store') }}" method="POST" enctype="multipart/form-data" id="formProduk">
        @csrf
        <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden mx-4 md:mx-0">
            <div class="flex flex-col lg:flex-row">

                <div class="lg:w-1/3 bg-gray-50 p-8 border-b lg:border-b-0 lg:border-r border-gray-200">
                    <div class="flex flex-col items-center">
                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-4">Foto Produk</span>

                        <div class="relative cursor-pointer">
                            <div class="w-44 h-44 rounded-xl overflow-hidden bg-white border-2 border-dashed @error('fotoProduk') border-red-500 @else border-gray-300 @enderror flex items-center justify-center">
                                <img id="previewImg" src="#" class="w-full h-full object-cover hidden">
                                <div id="placeholderIcon" class="text-center text-gray-300">
                                    <i class="fa-solid fa-camera text-3xl mb-1"></i>
                                    <p class="text-[10px] font-medium">Klik untuk upload</p>
                                </div>
                            </div>
                            <input type="file" name="fotoProduk" id="fotoInput" class="absolute inset-0 opacity-0 cursor-pointer" onchange="previewImage(this)">
                        </div>
                        @error('fotoProduk')
                            <p class="text-red-500 text-[11px] mt-2 font-semibold italic text-center">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="lg:w-2/3 p-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Varietas Benih</label>
                            <input type="text" name="namaProduk" value="{{ old('namaProduk') }}"
                                class="w-full px-4 py-3 rounded-xl border @error('namaProduk') border-red-500 @else border-gray-300 @enderror focus:border-[#58CC02] focus:ring-1 focus:ring-[#58CC02] outline-none transition"
                                placeholder="Contoh: Padi Pandan Wangi Super">
                            @error('namaProduk')
                                <p class="text-red-500 text-xs mt-1 font-medium italic">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-1">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Jenis Komoditas</label>
                            <select id="selectJenis" class="w-full px-4 py-3 rounded-xl border @error('kategoriId') border-red-500 @else border-gray-300 @enderror focus:border-[#58CC02] outline-none appearance-none bg-white">
                                <option value="">-- Pilih Jenis --</option>
                                @foreach($kategoris->unique('jenisKategori') as $k)
                                    <option value="{{ $k->jenisKategori }}" {{ old('selectJenis') == $k->jenisKategori ? 'selected' : '' }}>{{ strtoupper($k->jenisKategori) }}</option>
                                @endforeach
                            </select>
                            @error('kategoriId')
                                <p class="text-red-500 text-xs mt-1 font-medium italic">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-1">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Standar Mutu</label>
                            <select name="kategoriId" id="selectMutu" class="w-full px-4 py-3 rounded-xl border @error('kategoriId') border-red-500 @else border-gray-300 @enderror bg-gray-50 outline-none" disabled>
                                <option value="">-- Pilih Mutu --</option>
                            </select>
                            @error('kategoriId')
                                <p class="text-red-500 text-xs mt-1 font-medium italic">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Stok Masuk (Kg)</label>
                            <input type="number" name="stok" step="any" value="{{ old('stok') }}"
                                class="w-full px-4 py-3 rounded-xl border @error('stok') border-red-500 @else border-gray-300 @enderror outline-none"
                                placeholder="0.00">
                            @error('stok')
                                <p class="text-red-500 text-xs mt-1 font-medium italic">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Harga per Kg (Rp)</label>
                            <input type="text" id="hargaVisual"
                                class="w-full px-4 py-3 rounded-xl border @error('harga') border-red-500 @else border-gray-300 @enderror outline-none font-bold text-gray-800"
                                placeholder="0"
                                oninput="this.value = this.value.replace(/[^0-9]/g, ''); document.getElementById('hargaAsli').value = this.value;">
                            <input type="number" name="harga" id="hargaAsli" value="{{ old('harga') }}" class="hidden">
                            @error('harga')
                                <p class="text-red-500 text-xs mt-1 font-medium italic">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Keterangan Produk</label>
                            <textarea name="deskripsi" rows="3" class="w-full px-4 py-3 rounded-xl border border-gray-300 outline-none resize-none" placeholder="Tambahkan deskripsi singkat...">{{ old('deskripsi') }}</textarea>
                        </div>
                    </div>

                    <div class="mt-8 flex gap-3">
                        <button type="button" onclick="openConfirmModal()" class="flex-[2] bg-[#58CC02] text-white py-3.5 rounded-xl font-bold active:bg-[#46a302] transition shadow-sm">
                            Tambah
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

<x-modal id="modalKonfirmasiProduk" title="Konfirmasi" message="Apakah anda yakin ingin menambah produk ini" confirmText="Iya" cancelText="Batal" confirmId="btnSubmitForm" cancelId="btnCloseModal" />

<script>
    const kategoris = @json($kategoris);
    const selectJenis = document.getElementById('selectJenis');
    const selectMutu = document.getElementById('selectMutu');
    const hargaVisual = document.getElementById('hargaVisual');
    const hargaAsli = document.getElementById('hargaAsli');
    const modalElement = document.getElementById('modalKonfirmasiProduk');
    const btnConfirm = document.getElementById('btnSubmitForm');
    const btnCancel = document.getElementById('btnCloseModal');

    if (hargaAsli.value) {
        hargaVisual.value = hargaAsli.value;
    }

    selectJenis.addEventListener('change', function() {
        const selectedJenis = this.value;
        selectMutu.innerHTML = '<option value="">-- Pilih Mutu --</option>';

        if (selectedJenis) {
            const filtered = kategoris.filter(k => k.jenisKategori === selectedJenis);
            filtered.forEach(k => {
                const option = document.createElement('option');
                option.value = k.id;
                option.textContent = k.mutu.toUpperCase();
                selectMutu.appendChild(option);
            });
            selectMutu.disabled = false;
            selectMutu.classList.replace('bg-gray-50', 'bg-white');
        } else {
            selectMutu.disabled = true;
            selectMutu.classList.replace('bg-white', 'bg-gray-50');
        }
    });

    function openConfirmModal() {
        if (modalElement) {
            modalElement.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
    }

    function closeConfirmModal() {
        if (modalElement) {
            modalElement.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
    }

    if (btnConfirm) {
        btnConfirm.addEventListener('click', function() {
            document.getElementById('formProduk').submit();
        });
    }

    if (btnCancel) {
        btnCancel.addEventListener('click', closeConfirmModal);
    }

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
