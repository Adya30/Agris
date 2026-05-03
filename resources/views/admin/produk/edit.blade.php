@extends('layouts.admin')

@section('title', 'Edit Produk - AGRIS')

@section('content')
<div class="max-w-5xl mx-auto pt-4 pb-12">
    <div class="flex items-center gap-4 mb-6 px-4 md:px-0">
        <div>
            <h1 class="text-xl font-bold text-gray-800">Edit Data Produk</h1>
            <p class="text-xs text-gray-500">Mengubah varietas <span class="text-[#58CC02] font-semibold">{{ $produk->namaProduk }}</span></p>
        </div>
    </div>

    @if ($errors->any())
    <div class="mb-4 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 mx-4 md:mx-0 rounded-r-xl shadow-sm">
        <p class="text-sm font-bold">Terjadi kesalahan input:</p>
        <ul class="list-disc list-inside text-xs mt-1">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('admin.produk.update', $produk->id) }}" method="POST" enctype="multipart/form-data" id="formProduk">
        @csrf
        @method('PUT')
        <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden mx-4 md:mx-0 shadow-sm">
            <div class="flex flex-col lg:flex-row">
                <div class="lg:w-1/3 bg-gray-50 p-8 border-b lg:border-b-0 lg:border-r border-gray-200">
                    <div class="flex flex-col items-center">
                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-4">Foto Produk</span>
                        <div class="relative cursor-pointer group">
                            <div id="imageContainer" @class([
                                'w-44 h-44 rounded-xl overflow-hidden bg-white border-2 border-dashed flex items-center justify-center transition-colors',
                                'border-red-500' => $errors->has('fotoProduk'),
                                'border-gray-300' => !$errors->has('fotoProduk'),
                            ])>
                                @if($produk->fotoProduk)
                                    <img id="previewImg" src="{{ asset('storage/'.$produk->fotoProduk) }}" class="w-full h-full object-cover">
                                    <div id="placeholderIcon" class="hidden text-center text-gray-300 group-hover:text-gray-400">
                                        <i class="fa-solid fa-camera text-3xl mb-1"></i>
                                        <p class="text-[10px] font-medium">Ganti Foto</p>
                                    </div>
                                @else
                                    <img id="previewImg" src="#" class="w-full h-full object-cover hidden">
                                    <div id="placeholderIcon" class="text-center text-gray-300 group-hover:text-gray-400">
                                        <i class="fa-solid fa-camera text-3xl mb-1"></i>
                                        <p class="text-[10px] font-medium">Klik untuk upload</p>
                                    </div>
                                @endif
                            </div>
                            <input type="file" name="fotoProduk" id="fotoInput" accept=".jpg,.jpeg,.png"
                                class="absolute inset-0 opacity-0 cursor-pointer"
                                onchange="previewImage(this)">
                        </div>
                        <p class="text-[10px] text-gray-400 mt-2 font-medium text-center">Format: JPG, JPEG, PNG (Maks. 10MB)</p>
                    </div>
                </div>

                <div class="lg:w-2/3 p-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Varietas Benih</label>
                            <input type="text" name="namaProduk" value="{{ old('namaProduk', $produk->namaProduk) }}"
                                @class([ 'w-full px-4 py-3 rounded-xl border outline-none transition focus:ring-1 focus:ring-[#58CC02] focus:border-[#58CC02]', 'border-red-500' => $errors->has('namaProduk'), 'border-gray-300' => !$errors->has('namaProduk'), ])>
                        </div>

                        <div class="md:col-span-1">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Jenis Komoditas</label>
                            <select name="jenis" id="selectJenis" @class(['w-full px-4 py-3 rounded-xl border outline-none bg-white appearance-none', 'border-red-500' => $errors->has('jenis'), 'border-gray-300' => !$errors->has('jenis')])>
                                <option value="">-- Pilih Jenis --</option>
                                @foreach($kategoris->unique('jenisKategori') as $k)
                                    <option value="{{ $k->jenisKategori }}" {{ (old('jenis', $produk->kategori->jenisKategori) == $k->jenisKategori) ? 'selected' : '' }}>
                                        {{ strtoupper($k->jenisKategori) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="md:col-span-1">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Standar Mutu</label>
                            <select name="mutu" id="selectMutu" @class(['w-full px-4 py-3 rounded-xl border outline-none bg-white appearance-none', 'border-red-500' => $errors->has('mutu'), 'border-gray-300' => !$errors->has('mutu')])>
                                <option value="">-- Pilih Mutu --</option>
                            </select>
                        </div>

                        <div class="md:col-span-1">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Berat Karung (Kg)</label>
                            <input type="number" name="karung" step="0.1" min="0" value="{{ old('karung', $produk->kategori->karung) }}"
                                @class([ 'w-full px-4 py-3 rounded-xl border outline-none transition focus:border-[#58CC02]', 'border-red-500' => $errors->has('karung'), 'border-gray-300' => !$errors->has('karung'), ])
                                placeholder="Contoh: 5">
                        </div>

                        <div class="md:col-span-1">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Stok (Karung)</label>
                            <input type="number" name="stok" step="1" min="0" value="{{ old('stok', $produk->stok) }}"
                                @class([ 'w-full px-4 py-3 rounded-xl border outline-none transition focus:border-[#58CC02]', 'border-red-500' => $errors->has('stok'), 'border-gray-300' => !$errors->has('stok'), ])>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Harga per Karung (Rp)</label>
                            <input type="text" id="hargaVisual"
                                @class([ 'w-full px-4 py-3 rounded-xl border outline-none font-bold text-gray-800 transition focus:border-[#58CC02]', 'border-red-500' => $errors->has('harga'), 'border-gray-300' => !$errors->has('harga'), ])
                                placeholder="0" oninput="formatRupiah(this)">
                            <input type="number" name="harga" id="hargaAsli" value="{{ old('harga', (int)$produk->getAttributes()['harga']) }}" class="hidden">
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Keterangan</label>
                            <textarea name="deskripsi" rows="3" class="w-full px-4 py-3 rounded-xl border border-gray-300 outline-none resize-none focus:border-[#58CC02]">{{ old('deskripsi', $produk->deskripsi) }}</textarea>
                        </div>
                    </div>

                    <div class="mt-8 flex gap-3">
                        <button type="button" onclick="openModal('modalUpdateProduk')" class="flex-2 bg-[#58CC02] text-white px-6 py-3.5 rounded-xl font-bold active:bg-[#46a302] transition shadow-sm">
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

<x-modal id="modalUpdateProduk" title="Konfirmasi" message="Simpan perubahan data produk?" confirmText="Iya, Update" cancelText="Batal" confirmId="btnSubmitForm" cancelId="btnCloseModal" />

<script>
    const dataKategori = @json($kategoris);
    const selectJenis = document.getElementById('selectJenis');
    const selectMutu = document.getElementById('selectMutu');
    const hargaVisual = document.getElementById('hargaVisual');
    const hargaAsli = document.getElementById('hargaAsli');

    function populateDropdown(target, data, selectedValue = null) {
        data.forEach(item => {
            const opt = document.createElement('option');
            opt.value = item;
            opt.textContent = item.toString().toUpperCase();
            if (selectedValue && item.toString() === selectedValue.toString()) opt.selected = true;
            target.appendChild(opt);
        });
    }

    function resetDropdown(target, placeholder) {
        target.innerHTML = `<option value="">${placeholder}</option>`;
    }

    window.onload = function() {
        const initialJenis = "{{ old('jenis', $produk->kategori->jenisKategori) }}";
        const initialMutu = "{{ old('mutu', $produk->kategori->mutu) }}";

        if (initialJenis) {
            const filteredMutu = [...new Set(dataKategori.filter(k => k.jenisKategori === initialJenis).map(k => k.mutu))];
            populateDropdown(selectMutu, filteredMutu, initialMutu);
        }

        if (hargaAsli.value) {
            hargaVisual.value = new Intl.NumberFormat('id-ID').format(hargaAsli.value);
        }
    };

    selectJenis.addEventListener('change', function() {
        resetDropdown(selectMutu, '-- Pilih Mutu --');
        if (this.value) {
            const filteredMutu = [...new Set(dataKategori.filter(k => k.jenisKategori === this.value).map(k => k.mutu))];
            populateDropdown(selectMutu, filteredMutu);
        }
    });

    function formatRupiah(el) {
        let val = el.value.replace(/[^0-9]/g, '');
        hargaAsli.value = val;
        el.value = val ? new Intl.NumberFormat('id-ID').format(val) : '';
    }

    function previewImage(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = e => {
                const preview = document.getElementById('previewImg');
                const icon = document.getElementById('placeholderIcon');
                preview.src = e.target.result;
                preview.classList.remove('hidden');
                icon.classList.add('hidden');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    document.getElementById('btnSubmitForm').addEventListener('click', () => {
        document.getElementById('formProduk').submit();
    });

    document.getElementById('btnCloseModal').addEventListener('click', () => {
        closeModal('modalUpdateProduk');
    });
</script>
@endsection
