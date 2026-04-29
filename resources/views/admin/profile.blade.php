@extends('layouts.admin')

@section('content')
<div class="max-w-full mx-auto py-8">
    <div class="flex flex-col lg:flex-row gap-4 items-start">
        <div class="w-full lg:w-auto">
            <a href="{{ route('admin.produk.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 border-2 text-white bg-[#58CC02] rounded-2xl transition-all duration-300 font-bold group">
                <i class="fas fa-arrow-left"></i> Beranda
            </a>
        </div>

        <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data" id="formProfile" class="flex-1 w-full">
            @csrf
            @method('PUT')

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="flex justify-between items-center px-10 py-8 border-b border-gray-100 bg-gray-50/50">
                    <h1 class="text-3xl font-black text-gray-800">Profil</h1>
                    <div class="flex gap-3">
                        <button type="button" id="editBtn" class="px-8 py-2.5 bg-[#58CC02] text-white font-bold rounded-xl transition">Edit</button>
                        <button type="button" id="cancelBtn" class="hidden px-8 py-2.5 bg-red-500 text-white font-bold rounded-xl transition">Batal</button>
                        <button type="button" id="confirmBtn" class="hidden px-8 py-2.5 bg-[#58CC02] text-white font-bold rounded-xl transition shadow-lg shadow-green-100">Simpan</button>
                    </div>
                </div>

                <div class="p-10 flex flex-col lg:flex-row gap-16">
                    <div class="w-full lg:w-1/3 flex flex-col items-center">
                        <div class="group relative w-64 h-64 rounded-full overflow-hidden shadow-xl bg-gray-50 border-4 border-white">
                            <img id="previewFoto" src="{{ $user->fotoProfil ?? 'https://ui-avatars.com/api/?name='.urlencode($user->username) }}" class="w-full h-full object-cover">
                            <label for="fotoProfil" id="overlayFoto" class="hidden absolute inset-0 bg-black/40 items-center justify-center cursor-pointer transition">
                                <i class="fas fa-camera text-white text-4xl"></i>
                            </label>
                        </div>
                        <input type="file" name="fotoProfil" id="fotoProfil" class="hidden" accept="image/*">
                        <label for="fotoProfil" id="btnPilihFoto" class="hidden mt-6 px-6 py-2 border-2 bg-[#58CC02] text-white rounded-xl text-sm font-bold cursor-pointer transition">Edit Foto</label>
                        <p id="infoFoto" class="hidden text-start pt-5 text-xs text-blue-500 leading-relaxed">
                            Besar file maks 10mb <br> Ektensi file: .PNG, .JPG, .JPEG
                        </p>
                    </div>

                    <div class="w-full lg:w-2/3 space-y-8">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div>
                                <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-3 ml-1">Email</label>
                                <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-input editable w-full rounded-2xl border-gray-200 bg-gray-50/50 py-3 px-4 disabled:text-black transition-all font-medium" disabled>
                            </div>
                            <div>
                                <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-3 ml-1">Nomor Telepon</label>
                                <input type="text" name="noTelp" value="{{ old('noTelp', $user->noTelp) }}" class="form-input editable w-full rounded-2xl border-gray-200 bg-gray-50/50 py-3 px-4 disabled:text-black transition-all font-medium" disabled>
                            </div>
                        </div>

                        <div class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <div>
                                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-3 ml-1">Provinsi</label>
                                    <div id="provinsiView" class="py-3 px-4 rounded-2xl border border-gray-200 bg-gray-50/50 font-medium text-black">
                                        {{ $user->desa->kecamatan->kabupaten->provinsi->namaProvinsi ?? '-' }}
                                    </div>
                                    <select id="provinsi" name="provinsiId" class="hidden form-input editable w-full rounded-2xl border-[#58CC02] bg-white py-3 px-4 text-sm focus:ring-0">
                                        <option value="">{{ $user->desa->kecamatan->kabupaten->provinsi->namaProvinsi ?? 'Pilih Provinsi' }}</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-3 ml-1">Kabupaten</label>
                                    <div id="kabupatenView" class="py-3 px-4 rounded-2xl border border-gray-200 bg-gray-50/50 font-medium text-black">
                                        {{ $user->desa->kecamatan->kabupaten->namaKabupaten ?? '-' }}
                                    </div>
                                    <select id="kabupaten" name="kabupatenId" class="hidden form-input editable w-full rounded-2xl border-[#58CC02] bg-white py-3 px-4 text-sm focus:ring-0">
                                        <option value="">{{ $user->desa->kecamatan->kabupaten->namaKabupaten ?? 'Pilih Kabupaten' }}</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-3 ml-1">Kecamatan</label>
                                    <div id="kecamatanView" class="py-3 px-4 rounded-2xl border border-gray-200 bg-gray-50/50 font-medium text-black">
                                        {{ $user->desa->kecamatan->namaKecamatan ?? '-' }}
                                    </div>
                                    <select id="kecamatan" name="kecamatanId" class="hidden form-input editable w-full rounded-2xl border-[#58CC02] bg-white py-3 px-4 text-sm focus:ring-0">
                                        <option value="">{{ $user->desa->kecamatan->namaKecamatan ?? 'Pilih Kecamatan' }}</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-3 ml-1">Desa</label>
                                    <div id="desaView" class="py-3 px-4 rounded-2xl border border-gray-200 bg-gray-50/50 font-medium text-black">
                                        {{ $user->desa->namaDesa ?? '-' }}
                                    </div>
                                    <select id="desa" name="desaId" class="hidden form-input editable w-full rounded-2xl border-[#58CC02] bg-white py-3 px-4 text-sm focus:ring-0">
                                        <option value="{{ $user->desaId }}">{{ $user->desa->namaDesa ?? 'Pilih Desa' }}</option>
                                    </select>
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-3 ml-1">Detail Alamat</label>
                                <textarea name="detailAlamat" class="form-input editable w-full rounded-2xl border-gray-200 bg-gray-50/50 py-3 px-4 disabled:text-black transition-all font-medium resize-none" rows="2" disabled>{{ old('detailAlamat', $user->detailAlamat) }}</textarea>
                            </div>
                        </div>

                        {{-- Password Section (Temporarily Commented) --}}
                        {{-- <div id="passwordSection" class="hidden pt-8 border-t border-gray-100 space-y-6">...</div> --}}
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<x-modal id="confirmModal" message="Apakah yakin melakukan perubahan profil?" confirmText="Iya" cancelText="Batal" />

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Elements
        const editBtn = document.getElementById('editBtn');
        const cancelBtn = document.getElementById('cancelBtn');
        const confirmBtn = document.getElementById('confirmBtn');
        const editableInputs = document.querySelectorAll('.editable');
        const modal = document.getElementById('confirmModal');
        const infoFoto = document.getElementById('infoFoto');
        const passwordSection = document.getElementById('passwordSection'); // Bisa null jika di-comment

        // Modal Confirm Button - Pastikan ID 'submitForm' ada di dalam x-modal anda
        const btnSubmitForm = document.getElementById('submitForm');
        const baseUrl = "https://www.emsifa.com/api-wilayah-indonesia/api";

        const views = ['provinsiView', 'kabupatenView', 'kecamatanView', 'desaView'];
        const selects = ['provinsi', 'kabupaten', 'kecamatan', 'desa'];

        function activateEditMode() {
            // Enable Inputs
            editableInputs.forEach(input => {
                input.disabled = false;
                input.classList.remove('bg-gray-50/50', 'border-gray-200');
                input.classList.add('bg-white', 'border-[#58CC02]');
            });

            // Toggle Address Views
            views.forEach(id => {
                const el = document.getElementById(id);
                if(el) el.classList.add('hidden');
            });
            selects.forEach(id => {
                const el = document.getElementById(id);
                if(el) el.classList.remove('hidden');
            });

            // Toggle Buttons & Info
            if(infoFoto) infoFoto.classList.remove('hidden');
            if(editBtn) editBtn.classList.add('hidden');
            if(cancelBtn) cancelBtn.classList.remove('hidden');
            if(confirmBtn) confirmBtn.classList.remove('hidden');

            // Check if password section exists before removing hidden
            if(passwordSection) passwordSection.classList.remove('hidden');

            // Photo Overlay
            const overlay = document.getElementById('overlayFoto');
            const btnPilih = document.getElementById('btnPilihFoto');
            if(overlay) overlay.classList.replace('hidden', 'flex');
            if(btnPilih) btnPilih.classList.remove('hidden');

            loadProvinsi();
        }

        // Initialize Edit Mode if there are errors
        @if($errors->any()) activateEditMode(); @endif

        // Button Listeners
        if(editBtn) editBtn.addEventListener('click', activateEditMode);
        if(cancelBtn) cancelBtn.addEventListener('click', () => window.location.reload());
        if(confirmBtn) confirmBtn.addEventListener('click', () => modal.classList.remove('hidden'));

        const closeModal = document.getElementById('closeModal');
        if(closeModal) closeModal.addEventListener('click', () => modal.classList.add('hidden'));

        // Handle Submit from Modal
        if(btnSubmitForm) {
            btnSubmitForm.addEventListener('click', () => {
                editableInputs.forEach(input => input.disabled = false);
                document.getElementById('formProfile').submit();
            });
        }

        // Photo Preview
        document.getElementById('fotoProfil').addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = (e) => document.getElementById('previewFoto').src = e.target.result;
                reader.readAsDataURL(this.files[0]);
            }
        });

        // API WILAYAH LOGIC
        async function loadProvinsi() {
            try {
                const res = await fetch(`${baseUrl}/provinces.json`);
                const data = await res.json();
                const select = document.getElementById('provinsi');
                if(!select) return;
                const defaultText = select.options[0] ? select.options[0].text : 'Pilih Provinsi';
                select.innerHTML = `<option value="">${defaultText}</option>`;
                data.forEach(item => select.add(new Option(item.name, item.id)));
            } catch (e) { console.error("Gagal load provinsi"); }
        }

        document.getElementById('provinsi').addEventListener('change', async function() {
            if(!this.value) return;
            const res = await fetch(`${baseUrl}/regencies/${this.value}.json`);
            updateSelect('kabupaten', await res.json(), 'Kabupaten');
        });

        document.getElementById('kabupaten').addEventListener('change', async function() {
            if(!this.value) return;
            const res = await fetch(`${baseUrl}/districts/${this.value}.json`);
            updateSelect('kecamatan', await res.json(), 'Kecamatan');
        });

        document.getElementById('kecamatan').addEventListener('change', async function() {
            if(!this.value) return;
            const res = await fetch(`${baseUrl}/villages/${this.value}.json`);
            updateSelect('desa', await res.json(), 'Desa');
        });

        function updateSelect(id, data, label) {
            const select = document.getElementById(id);
            if(!select) return;
            select.innerHTML = `<option value="">Pilih ${label}</option>`;
            data.forEach(item => select.add(new Option(item.name, item.id)));
        }
    });
</script>
@endsection
