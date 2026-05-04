@extends('layouts.agen')

@section('title', 'Profil Agen - AGRIS')

@section('content')
<div class="max-w-full mx-auto py-8">
    <div class="flex flex-col lg:flex-row gap-4 items-start">
        <form action="{{ route('agen.profile.update') }}" method="POST" enctype="multipart/form-data" id="formProfile" class="flex-1 w-full">
            @csrf
            @method('PUT')

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="flex justify-between items-center px-10 py-8 border-b border-gray-100 bg-gray-50/50">
                    <h1 class="text-3xl font-black text-gray-800">Profil Agen</h1>
                    <div class="flex gap-3">
                        <button type="button" id="editBtn" class="px-8 py-2.5 bg-[#58CC02] text-white font-bold rounded-xl transition">Edit</button>
                        <button type="button" id="cancelBtn" class="hidden px-8 py-2.5 bg-red-500 text-white font-bold rounded-xl transition">Batal</button>
                        <button type="button" id="confirmBtn" class="hidden px-8 py-2.5 bg-[#58CC02] text-white font-bold rounded-xl transition shadow-lg shadow-green-100">Simpan</button>
                    </div>
                </div>

                <div class="p-10 flex flex-col lg:flex-row gap-16">
                    <div class="w-full lg:w-1/3 flex flex-col items-center">
                        <div class="group relative w-64 h-64 rounded-full overflow-hidden shadow-xl bg-gray-50 border-4 border-white">
                            <img id="previewFoto" src="{{ $user->fotoProfil ?? 'https://ui-avatars.com/api/?name='.urlencode($user->namaLengkap ?? 'User') }}" class="w-full h-full object-cover">
                            <label for="fotoProfil" id="overlayFoto" class="hidden absolute inset-0 bg-black/40 items-center justify-center cursor-pointer transition">
                                <i class="fas fa-camera text-white text-4xl"></i>
                            </label>
                        </div>
                        <input type="file" name="fotoProfil" id="fotoProfil" class="hidden" accept="image/*">
                        <label for="fotoProfil" id="btnPilihFoto" class="hidden mt-6 px-6 py-2 border-2 bg-[#58CC02] text-white rounded-xl text-sm font-bold cursor-pointer transition">Edit Foto</label>
                        <p id="infoFoto" class="hidden text-start pt-5 text-xs text-blue-500 leading-relaxed">
                            Besar file maks 10mb <br> Ekstensi file: .PNG, .JPG, .JPEG
                        </p>
                    </div>

                    <div class="w-full lg:w-2/3 space-y-8">
                        <div class="space-y-6">
                            <div>
                                <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-3 ml-1">Nama Lengkap</label>
                                <input type="text" name="namaLengkap" value="{{ old('namaLengkap', $user->namaLengkap) }}" class="form-input editable w-full rounded-2xl border-gray-200 bg-gray-50/50 py-3 px-4 disabled:text-black transition-all font-medium" disabled>
                                @error('namaLengkap') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <div>
                                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-3 ml-1">Email</label>
                                    <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-input editable w-full rounded-2xl border-gray-200 bg-gray-50/50 py-3 px-4 disabled:text-black transition-all font-medium" disabled>
                                    @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-3 ml-1">Nomor Telepon</label>
                                    <input type="text" name="noTelp" value="{{ old('noTelp', $user->noTelp) }}" class="form-input editable w-full rounded-2xl border-gray-200 bg-gray-50/50 py-3 px-4 disabled:text-black transition-all font-medium" disabled>
                                    @error('noTelp') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <div>
                                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-3 ml-1">Provinsi</label>
                                    <div id="provinsiView" class="py-3 px-4 rounded-2xl border border-gray-200 bg-gray-50/50 font-medium text-black">
                                        {{ $user->desa->kecamatan->kabupaten->provinsi->namaProvinsi ?? '-' }}
                                    </div>
                                    <select id="provinsi" name="provinsiId" data-old="{{ $user->desa->kecamatan->kabupaten->provinsi->id ?? '' }}" class="hidden form-input editable w-full rounded-2xl border-[#58CC02] bg-white py-3 px-4 text-sm focus:ring-0">
                                        <option value="">Pilih Provinsi</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-3 ml-1">Kabupaten</label>
                                    <div id="kabupatenView" class="py-3 px-4 rounded-2xl border border-gray-200 bg-gray-50/50 font-medium text-black">
                                        {{ $user->desa->kecamatan->kabupaten->namaKabupaten ?? '-' }}
                                    </div>
                                    <select id="kabupaten" name="kabupatenId" data-old="{{ $user->desa->kecamatan->kabupaten->id ?? '' }}" class="hidden form-input editable w-full rounded-2xl border-[#58CC02] bg-white py-3 px-4 text-sm focus:ring-0">
                                        <option value="">Pilih Kabupaten</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-3 ml-1">Kecamatan</label>
                                    <div id="kecamatanView" class="py-3 px-4 rounded-2xl border border-gray-200 bg-gray-50/50 font-medium text-black">
                                        {{ $user->desa->kecamatan->namaKecamatan ?? '-' }}
                                    </div>
                                    <select id="kecamatan" name="kecamatanId" data-old="{{ $user->desa->kecamatan->id ?? '' }}" class="hidden form-input editable w-full rounded-2xl border-[#58CC02] bg-white py-3 px-4 text-sm focus:ring-0">
                                        <option value="">Pilih Kecamatan</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-3 ml-1">Desa</label>
                                    <div id="desaView" class="py-3 px-4 rounded-2xl border border-gray-200 bg-gray-50/50 font-medium text-black">
                                        {{ $user->desa->namaDesa ?? '-' }}
                                    </div>
                                    <select id="desa" name="desaId" data-old="{{ $user->desaId ?? '' }}" class="hidden form-input editable w-full rounded-2xl border-[#58CC02] bg-white py-3 px-4 text-sm focus:ring-0">
                                        <option value="{{ $user->desaId ?? '' }}">{{ $user->desa->namaDesa ?? 'Pilih Desa' }}</option>
                                    </select>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-3 ml-1">Detail Alamat</label>
                                <textarea name="detailAlamat" class="form-input editable w-full rounded-2xl border-gray-200 bg-gray-50/50 py-3 px-4 disabled:text-black transition-all font-medium resize-none" rows="2" disabled>{{ old('detailAlamat', $user->detailAlamat) }}</textarea>
                            </div>

                            <div id="passwordSection" class="hidden mt-8 pt-8 border-t border-gray-100">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                    <div class="relative">
                                        <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-3 ml-1">Password Lama</label>
                                        <input type="password" name="current_password" id="current_password" class="form-input editable w-full rounded-2xl border-[#58CC02] bg-white py-3 px-4 pr-12 transition-all font-medium" placeholder="Konfirmasi password lama">
                                        <button type="button" class="toggle-password absolute right-4 top-10 text-gray-400 hover:text-[#58CC02]">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        @error('current_password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                    </div>
                                    <div class="relative">
                                        <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-3 ml-1">Password Baru</label>
                                        <input type="password" name="password" id="password" class="form-input editable w-full rounded-2xl border-[#58CC02] bg-white py-3 px-4 pr-12 transition-all font-medium" placeholder="Minimal 8 karakter">
                                        <button type="button" class="toggle-password absolute right-4 top-10 text-gray-400 hover:text-[#58CC02]">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                    </div>
                                </div>
                                <p class="text-[10px] text-gray-400 mt-4 italic">*Biarkan kosong jika tidak ingin mengubah password.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<x-modal id="confirmModal" message="Apakah Anda yakin ingin memperbarui data profil agen?" confirmText="Ya, Simpan" cancelText="Batal" confirmId="btnSubmitProfile" cancelId="btnCloseProfileModal" />

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const editBtn = document.getElementById('editBtn');
        const cancelBtn = document.getElementById('cancelBtn');
        const confirmBtn = document.getElementById('confirmBtn');
        const form = document.getElementById('formProfile');
        const editableInputs = document.querySelectorAll('.editable');
        const passwordSection = document.getElementById('passwordSection');

        async function activateEditMode() {
            editableInputs.forEach(input => {
                input.disabled = false;
                input.classList.remove('bg-gray-50/50', 'border-gray-200');
                input.classList.add('bg-white', 'border-[#58CC02]');
            });

            ['provinsi', 'kabupaten', 'kecamatan', 'desa'].forEach(id => {
                const viewEl = document.getElementById(id + 'View');
                const selectEl = document.getElementById(id);
                if (viewEl) viewEl.classList.add('hidden');
                if (selectEl) selectEl.classList.remove('hidden');
            });

            editBtn?.classList.add('hidden');
            cancelBtn?.classList.remove('hidden');
            confirmBtn?.classList.remove('hidden');

            passwordSection?.classList.remove('hidden');
            document.getElementById('infoFoto')?.classList.remove('hidden');
            document.getElementById('btnPilihFoto')?.classList.remove('hidden');
            document.getElementById('overlayFoto')?.classList.replace('hidden', 'flex');

            if (typeof window.initWilayah === 'function') {
                await window.initWilayah();
            }
        }

        @if($errors->any()) activateEditMode(); @endif

        if(editBtn) {
            editBtn.addEventListener('click', (e) => {
                e.preventDefault();
                activateEditMode();
            });
        }

        if(cancelBtn) {
            cancelBtn.addEventListener('click', () => window.location.reload());
        }

        document.querySelectorAll('.toggle-password').forEach(button => {
            button.addEventListener('click', function() {
                const input = this.parentElement.querySelector('input');
                const icon = this.querySelector('i');

                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.replace('fa-eye', 'fa-eye-slash');
                } else {
                    input.type = 'password';
                    icon.classList.replace('fa-eye-slash', 'fa-eye');
                }
            });
        });

        if(confirmBtn) {
            confirmBtn.addEventListener('click', () => {
                if (typeof openModal === 'function') {
                    openModal('confirmModal');
                } else {
                    document.getElementById('confirmModal').classList.remove('hidden');
                }
            });
        }

        const btnSubmitProfile = document.getElementById('btnSubmitProfile');
        if(btnSubmitProfile) {
            btnSubmitProfile.addEventListener('click', () => form.submit());
        }

        const btnCloseProfileModal = document.getElementById('btnCloseProfileModal');
        if(btnCloseProfileModal) {
            btnCloseProfileModal.addEventListener('click', () => {
                if (typeof closeModal === 'function') {
                    closeModal('confirmModal');
                } else {
                    document.getElementById('confirmModal').classList.add('hidden');
                }
            });
        }

        document.getElementById('fotoProfil').addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = (e) => document.getElementById('previewFoto').src = e.target.result;
                reader.readAsDataURL(this.files[0]);
            }
        });
    });
</script>
@endsection
