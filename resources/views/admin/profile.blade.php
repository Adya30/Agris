@extends('layouts.admin')

@section('content')
<div class="max-w-6xl mx-auto py-10 px-4">

    <div class="mb-6">
        <a href="{{ route('admin.produk.index') }}"
           class="inline-flex items-center gap-2 px-5 py-2.5 border-2 text-white bg-[#58CC02] rounded-2xl transition-all duration-300 font-bold group">
            <i class="fas fa-arrow-left transition-transform"></i>
            Dashboard
        </a>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-xl shadow-sm flex items-center">
            <i class="fas fa-check-circle mr-3"></i> {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-xl shadow-sm">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data" id="formProfile">
        @csrf
        @method('PUT')

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="flex justify-between items-center px-10 py-8 border-b border-gray-100 bg-gray-50/50">
                <h1 class="text-2xl font-black text-gray-800 uppercase">Profil</h1>
                <div class="flex gap-3">
                    <button type="button" id="editBtn" class="px-8 py-2.5 bg-[#58CC02] text-white font-bold rounded-xl transition">Edit Profil</button>
                    <button type="button" id="cancelBtn" class="hidden px-8 py-2.5 bg-red-500 text-white font-bold rounded-xl transition">Batal</button>
                    <button type="button" id="confirmBtn" class="hidden px-8 py-2.5 bg-[#58CC02] text-white font-bold rounded-xl transition shadow-lg shadow-green-100">Simpan Perubahan</button>
                </div>
            </div>

            <div class="p-10 flex flex-col lg:flex-row gap-16">

                <div class="w-full lg:w-1/3 flex flex-col items-center">
                    <div class="group relative w-64 h-64 rounded-full overflow-hidden shadow-xl bg-gray-50">
                        <img id="previewFoto"
                             src="{{ $user->fotoProfil ?? 'https://ui-avatars.com/api/?name='.urlencode($user->username) }}"
                             class="w-full h-full object-cover">

                        <label for="fotoProfil" id="overlayFoto" class="hidden absolute inset-0 bg-black/40 items-center justify-center cursor-pointer transition">
                            <i class="fas fa-camera text-white text-4xl"></i>
                        </label>
                    </div>
                    <input type="file" name="fotoProfil" id="fotoProfil" class="hidden" accept="image/*">
                    <label for="fotoProfil" id="btnPilihFoto" class="hidden mt-6 px-6 py-2 border-2 bg-[#58CC02] text-white rounded-xl text-sm font-bold cursor-pointer transition">
                        Edit Foto
                    </label>
                </div>

                <div class="w-full lg:w-2/3 space-y-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-3">Username</label>
                            <input type="text" name="username" value="{{ old('username', $user->username) }}"
                                   class="form-input editable w-full rounded-2xl border-gray-200 focus:border-[#58CC02] focus:ring-0 disabled:bg-gray-50 disabled:text-gray-400 py-3" disabled>
                        </div>
                        <div>
                            <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-3">Email</label>
                            {{-- Sekarang bisa diedit (editable) --}}
                            <input type="email" name="email" value="{{ old('email', $user->email) }}"
                                   class="form-input editable w-full rounded-2xl border-gray-200 focus:border-[#58CC02] focus:ring-0 disabled:bg-gray-50 disabled:text-gray-400 py-3" disabled>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-3">Alamat Lengkap</label>
                        <textarea name="detailAlamat"
                                  class="form-input editable w-full rounded-2xl border-gray-200 focus:border-[#58CC02] focus:ring-0 disabled:bg-gray-50 disabled:text-gray-400 resize-none py-3"
                                  rows="3" disabled>{{ old('detailAlamat', $user->detailAlamat) }}</textarea>
                    </div>

                    {{-- WILAYAH SECTION --}}
                    <div class="grid grid-cols-2 gap-4">
                        <select id="provinsi" class="form-input editable rounded-2xl border-gray-200 disabled:bg-gray-50 py-3 text-sm" disabled>
                            <option value="">{{ $user->desa->kecamatan->kabupaten->provinsi->namaProvinsi ?? 'Pilih Provinsi' }}</option>
                        </select>
                        <select id="kabupaten" class="form-input editable rounded-2xl border-gray-200 disabled:bg-gray-50 py-3 text-sm" disabled>
                            <option value="">{{ $user->desa->kecamatan->kabupaten->namaKabupaten ?? 'Pilih Kabupaten' }}</option>
                        </select>
                        <select id="kecamatan" class="form-input editable rounded-2xl border-gray-200 disabled:bg-gray-50 py-3 text-sm" disabled>
                            <option value="">{{ $user->desa->kecamatan->namaKecamatan ?? 'Pilih Kecamatan' }}</option>
                        </select>
                        <select name="desaId" id="desa" class="form-input editable rounded-2xl border-gray-200 disabled:bg-gray-50 py-3 text-sm" disabled>
                            <option value="{{ $user->desaId }}">{{ $user->desa->namaDesa ?? 'Pilih Desa' }}</option>
                        </select>
                    </div>

                    {{-- PASSWORD SECTION --}}
                    <div id="passwordSection" class="hidden pt-8 border-t border-gray-100 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div>
                                <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-3">Password Lama</label>
                                <input type="password" name="current_password" class="form-input w-full rounded-2xl border-gray-200 focus:border-[#58CC02] focus:ring-0 py-3" placeholder="Wajib jika ganti password">
                            </div>
                            <div>
                                <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-3">Password Baru</label>
                                <input type="password" name="password" class="form-input w-full rounded-2xl border-gray-200 focus:border-[#58CC02] focus:ring-0 py-3" placeholder="Min. 8 karakter">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

{{-- MODAL KONFIRMASI --}}
<div id="confirmModal" class="hidden fixed inset-0 z-[100] flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>
    <div class="relative bg-white rounded-[2.5rem] p-10 max-w-sm w-full shadow-2xl text-center border border-gray-100">
        <div class="w-20 h-20 bg-green-100 text-[#58CC02] rounded-full flex items-center justify-center mx-auto mb-6 text-4xl">
            <i class="fas fa-check-double"></i>
        </div>
        <h3 class="text-2xl font-black text-gray-800 mb-2 uppercase tracking-tighter">Simpan Data?</h3>
        <p class="text-gray-500 font-medium mb-8">Data profil Anda akan langsung diperbarui di sistem.</p>
        <div class="flex gap-3">
            <button type="button" id="closeModal" class="flex-1 py-4 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold rounded-2xl transition">Batal</button>
            <button type="button" id="submitForm" class="flex-1 py-4 bg-[#58CC02] hover:bg-[#4fb802] text-white font-bold rounded-2xl transition shadow-lg shadow-green-100">Ya, Update</button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const editBtn = document.getElementById('editBtn');
        const cancelBtn = document.getElementById('cancelBtn');
        const confirmBtn = document.getElementById('confirmBtn');
        const editableInputs = document.querySelectorAll('.editable');
        const passwordSection = document.getElementById('passwordSection');
        const baseUrl = "https://www.emsifa.com/api-wilayah-indonesia/api";

        editBtn.addEventListener('click', () => {
            editableInputs.forEach(input => input.disabled = false);

            editBtn.classList.add('hidden');
            cancelBtn.classList.remove('hidden');
            confirmBtn.classList.remove('hidden');
            passwordSection.classList.remove('hidden');

            document.getElementById('overlayFoto').classList.replace('hidden', 'flex');
            document.getElementById('btnPilihFoto').classList.remove('hidden');

            loadProvinsi();
        });

        cancelBtn.addEventListener('click', () => {
            window.location.reload();
        });

        confirmBtn.addEventListener('click', () => {
            document.getElementById('confirmModal').classList.remove('hidden');
        });

        document.getElementById('closeModal').addEventListener('click', () => {
            document.getElementById('confirmModal').classList.add('hidden');
        });

        document.getElementById('submitForm').addEventListener('click', () => {
            editableInputs.forEach(input => input.disabled = false);
            document.getElementById('formProfile').submit();
        });

        document.getElementById('fotoProfil').addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    document.getElementById('previewFoto').src = e.target.result;
                };
                reader.readAsDataURL(this.files[0]);
            }
        });

        async function loadProvinsi() {
            try {
                const res = await fetch(`${baseUrl}/provinces.json`);
                const data = await res.json();
                const select = document.getElementById('provinsi');
                const currentVal = select.options[0].text;
                select.innerHTML = `<option value="">${currentVal}</option>`;
                data.forEach(item => select.add(new Option(item.name, item.id)));
            } catch (e) { console.error("Gagal load wilayah"); }
        }

        document.getElementById('provinsi').addEventListener('change', async function() {
            if(!this.value) return;
            const res = await fetch(`${baseUrl}/regencies/${this.value}.json`);
            const data = await res.json();
            updateSelect('kabupaten', data, 'Kabupaten');
        });

        document.getElementById('kabupaten').addEventListener('change', async function() {
            if(!this.value) return;
            const res = await fetch(`${baseUrl}/districts/${this.value}.json`);
            const data = await res.json();
            updateSelect('kecamatan', data, 'Kecamatan');
        });

        document.getElementById('kecamatan').addEventListener('change', async function() {
            if(!this.value) return;
            const res = await fetch(`${baseUrl}/villages/${this.value}.json`);
            const data = await res.json();
            updateSelect('desa', data, 'Desa');
        });

        function updateSelect(id, data, label) {
            const select = document.getElementById(id);
            select.innerHTML = `<option value="">Pilih ${label}</option>`;
            data.forEach(item => select.add(new Option(item.name, item.id)));
        }
    });
</script>
@endsection
