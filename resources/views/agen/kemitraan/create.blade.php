@extends('layouts.agen')

@section('title', 'Buat Pengajuan Mitra - AGRIS')

@section('content')
<div class="max-w-4xl mx-auto pt-4 pb-12 px-4">
    @if($kemitraan)
    @php
        $steps = [
            'diproses' => 1,
            'Menunggu Upload MOU' => 2,
            'Menunggu Verifikasi MOU' => 3,
            'Aktif' => 4
        ];
        $currentStep = $steps[$kemitraan->statusPengajuan] ?? 1;
        $isFailed = $kemitraan->statusPengajuan == 'Dibatalkan';
    @endphp

    <div class="bg-white rounded-3xl border border-gray-100 p-8 shadow-sm mb-12">
        <div class="relative flex items-center justify-between mb-12">
            <div class="absolute left-0 top-1/2 -translate-y-1/2 w-full h-1 bg-gray-100 z-0"></div>
            <div class="absolute left-0 top-1/2 -translate-y-1/2 h-1 {{ $isFailed ? 'bg-red-500' : 'bg-[#58CC02]' }} z-0" style="width: {{ $isFailed ? '100' : ($currentStep - 1) * 33.33 }}%"></div>

            @foreach(['Biodata', 'Persetujuan', 'Upload MOU', 'Selesai'] as $index => $label)
            <div class="relative z-10 flex flex-col items-center">
                <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold {{ $currentStep > $index ? 'bg-[#58CC02] text-white' : ($currentStep == $index + 1 ? 'bg-white border-4 border-[#58CC02] text-[#58CC02]' : 'bg-white border-4 border-gray-100 text-gray-300') }}">
                    @if($currentStep > $index + 1)
                        <i class="fa-solid fa-check"></i>
                    @elseif($isFailed && $index + 1 >= $currentStep)
                        <i class="fa-solid fa-xmark text-red-500"></i>
                    @else
                        {{ $index + 1 }}
                    @endif
                </div>
                <span class="absolute top-12 text-[10px] font-bold uppercase tracking-wider whitespace-nowrap {{ $currentStep >= $index + 1 ? 'text-[#58CC02]' : 'text-gray-400' }}">{{ $label }}</span>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-800 text-center">Konfirmasi Biodata</h1>
        <p class="text-gray-500 text-center">Pastikan data profil Anda sudah benar sebelum mengajukan kemitraan.</p>
    </div>

    <form id="formKemitraan" action="{{ route('kemitraan.store') }}" method="POST" class="bg-white rounded-4xl border border-gray-100 p-10 shadow-sm">
        @csrf
        <div class="space-y-6">
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Nama Lengkap</label>
                <input type="text" name="namaLengkap" value="{{ old('namaLengkap', $user->namaLengkap) }}" class="w-full px-5 py-4 rounded-2xl border border-gray-100 focus:ring-4 focus:ring-[#58CC02]/10 focus:border-[#58CC02] outline-none font-medium" required>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Nomor Telepon (WhatsApp)</label>
                <input type="text" name="noTelp" value="{{ old('noTelp', $user->noTelp) }}" class="w-full px-5 py-4 rounded-2xl border border-gray-100 focus:ring-4 focus:ring-[#58CC02]/10 focus:border-[#58CC02] outline-none font-medium" required>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Provinsi</label>
                    <select id="provinsi" name="provinsiId" data-old="{{ old('provinsiId', $user->desa->kecamatan->kabupaten->provinsiId ?? '') }}" class="w-full px-5 py-4 rounded-2xl border border-gray-100 focus:ring-4 focus:ring-[#58CC02]/10 focus:border-[#58CC02] outline-none font-medium appearance-none" required>
                        <option value="">Pilih Provinsi</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Kabupaten</label>
                    <select id="kabupaten" name="kabupatenId" data-old="{{ old('kabupatenId', $user->desa->kecamatan->kabupatenId ?? '') }}" class="w-full px-5 py-4 rounded-2xl border border-gray-100 focus:ring-4 focus:ring-[#58CC02]/10 focus:border-[#58CC02] outline-none font-medium appearance-none" required disabled>
                        <option value="">Pilih Kabupaten</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Kecamatan</label>
                    <select id="kecamatan" name="kecamatanId" data-old="{{ old('kecamatanId', $user->desa->kecamatanId ?? '') }}" class="w-full px-5 py-4 rounded-2xl border border-gray-100 focus:ring-4 focus:ring-[#58CC02]/10 focus:border-[#58CC02] outline-none font-medium appearance-none" required disabled>
                        <option value="">Pilih Kecamatan</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Desa</label>
                    <select id="desa" name="desaId" data-old="{{ old('desaId', $user->desaId ?? '') }}" class="w-full px-5 py-4 rounded-2xl border border-gray-100 focus:ring-4 focus:ring-[#58CC02]/10 focus:border-[#58CC02] outline-none font-medium appearance-none" required disabled>
                        <option value="">Pilih Desa</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Alamat Lengkap (Jalan/No. Rumah)</label>
                <textarea name="detailAlamat" rows="4" class="w-full px-5 py-4 rounded-2xl border border-gray-100 focus:ring-4 focus:ring-[#58CC02]/10 focus:border-[#58CC02] outline-none font-medium resize-none" required>{{ old('detailAlamat', $user->detailAlamat) }}</textarea>
            </div>

            <div class="pt-4">
                <button type="button" id="btnConfirmSubmit" class="w-full py-4 bg-[#58CC02] text-white font-black rounded-2xl shadow-lg shadow-green-100 uppercase tracking-widest">
                    {{ $kemitraan && $kemitraan->statusPengajuan == 'Ditolak' ? 'Kirim Ulang Pengajuan' : 'Kirim Pengajuan' }}
                </button>
            </div>
        </div>
    </form>
</div>

<x-modal id="kemitraanModal" message="Apakah data biodata yang Anda masukkan sudah benar?" confirmText="Iya, Kirim" cancelText="Batal" confirmId="btnFinalSubmit" cancelId="btnCancelKemitraan" />

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const provSelect = document.getElementById('provinsi');
        const kabSelect = document.getElementById('kabupaten');
        const kecSelect = document.getElementById('kecamatan');
        const desaSelect = document.getElementById('desa');
        const form = document.getElementById('formKemitraan');

        const oldProv = provSelect.getAttribute('data-old');
        const oldKab = kabSelect.getAttribute('data-old');
        const oldKec = kecSelect.getAttribute('data-old');
        const oldDesa = desaSelect.getAttribute('data-old');

        async function initWilayah() {
            const provinces = await fetch('https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json').then(r => r.json());
            provinces.forEach(prov => {
                let opt = new Option(prov.name, prov.id);
                if(prov.id === oldProv) opt.selected = true;
                provSelect.add(opt);
            });

            if (oldProv) await loadKabupaten(oldProv, oldKab);
            if (oldKab) await loadKecamatan(oldKab, oldKec);
            if (oldKec) await loadDesa(oldKec, oldDesa);
        }

        async function loadKabupaten(provId, selectedId = null) {
            kabSelect.innerHTML = '<option value="">Pilih Kabupaten</option>';
            const regencies = await fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/regencies/${provId}.json`).then(r => r.json());
            regencies.forEach(kab => {
                let opt = new Option(kab.name, kab.id);
                if(kab.id === selectedId) opt.selected = true;
                kabSelect.add(opt);
            });
            kabSelect.disabled = false;
        }

        async function loadKecamatan(kabId, selectedId = null) {
            kecSelect.innerHTML = '<option value="">Pilih Kecamatan</option>';
            const districts = await fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/districts/${kabId}.json`).then(r => r.json());
            districts.forEach(kec => {
                let opt = new Option(kec.name, kec.id);
                if(kec.id === selectedId) opt.selected = true;
                kecSelect.add(opt);
            });
            kecSelect.disabled = false;
        }

        async function loadDesa(kecId, selectedId = null) {
            desaSelect.innerHTML = '<option value="">Pilih Desa</option>';
            const villages = await fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/villages/${kecId}.json`).then(r => r.json());
            villages.forEach(desa => {
                let opt = new Option(desa.name, desa.id);
                if(desa.id === selectedId) opt.selected = true;
                desaSelect.add(opt);
            });
            desaSelect.disabled = false;
        }

        provSelect.addEventListener('change', function() {
            kecSelect.innerHTML = '<option value="">Pilih Kecamatan</option>';
            desaSelect.innerHTML = '<option value="">Pilih Desa</option>';
            kecSelect.disabled = true;
            desaSelect.disabled = true;
            if (this.value) loadKabupaten(this.value);
        });

        kabSelect.addEventListener('change', function() {
            desaSelect.innerHTML = '<option value="">Pilih Desa</option>';
            desaSelect.disabled = true;
            if (this.value) loadKecamatan(this.value);
        });

        kecSelect.addEventListener('change', function() {
            if (this.value) loadDesa(this.value);
        });

        initWilayah();

        document.getElementById('btnConfirmSubmit').addEventListener('click', function() {
            if(form.checkValidity()) {
                if (typeof openModal === 'function') {
                    openModal('kemitraanModal');
                } else {
                    document.getElementById('kemitraanModal').classList.remove('hidden');
                }
            } else {
                form.reportValidity();
            }
        });

        document.getElementById('btnFinalSubmit').addEventListener('click', function() {
            this.disabled = true;
            form.submit();
        });

        document.getElementById('btnCancelKemitraan').addEventListener('click', function() {
            if (typeof closeModal === 'function') {
                closeModal('kemitraanModal');
            } else {
                document.getElementById('kemitraanModal').classList.add('hidden');
            }
        });
    });
</script>
@endsection
