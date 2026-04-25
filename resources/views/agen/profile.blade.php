@extends('layouts.agen')

@section('title', 'Profil Agen')

@section('content')

<div class="max-w-5xl mx-auto py-3 px-4">
    <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-100">

        {{-- Header & Foto Profil --}}
        <div class="h-32 bg-green-600 relative">
            <div class="absolute -bottom-12 left-8">
                <div class="relative group">
                    <img id="previewFoto" src="{{ $user->fotoProfil ? asset('storage/' . $user->fotoProfil) : 'https://ui-avatars.com/api/?name='.urlencode($user->namaLengkap).'&background=58CC02&color=fff' }}" class="w-32 h-32 rounded-2xl object-cover border-4 border-white shadow-lg bg-white">
                    <label for="fotoProfil" class="absolute inset-0 items-center justify-center bg-black/50 rounded-2xl opacity-0 group-hover:opacity-100 transition cursor-pointer hidden" id="overlayFoto">
                        <span class="text-white text-xs font-medium text-center px-2">Klik untuk Ganti Foto</span>
                    </label>
                </div>
            </div>
            <div id="fotoRule" class="hidden absolute bottom-2 right-4 bg-white/20 backdrop-blur-md px-3 py-1 rounded-lg border border-white/30">
                <p class="text-[10px] text-white leading-tight">
                    * Format: JPG, PNG, JPEG (Maks. 2MB)
                </p>
            </div>
        </div>

        <div class="pt-16 pb-8 px-8">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">{{ $user->namaLengkap }}</h2>
                    {{-- EMAIL SEKARANG DI SINI --}}
                    <p class="text-gray-500 italic">{{ $user->email }}</p>
                </div>

                <div class="flex gap-2">
                    <button type="button" id="editBtn" class="flex items-center gap-2 px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl transition">
                        <i class="fas fa-edit"></i>
                        Edit Profil
                    </button>
                    <button type="button" id="cancelBtn" class="hidden px-5 py-2.5 bg-red-50 text-red-600 font-semibold rounded-xl hover:bg-red-100 transition">Batal</button>
                </div>
            </div>

            @if(session('success'))
                <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded-r-xl flex items-center gap-3">
                    <i class="fas fa-check-circle text-green-500"></i>
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('agen.profile.update') }}" method="POST" enctype="multipart/form-data" id="formProfile">
                @csrf
                @method('PUT')

                <input type="file" name="fotoProfil" id="fotoProfil" class="hidden" accept="image/*">
                @error('fotoProfil') <p class="error-text mb-4">{{ $message }}</p> @enderror

                <div class="grid md:grid-cols-2 gap-x-8 gap-y-6">
                    {{-- Nama Lengkap --}}
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Nama Lengkap</label>
                        <input type="text" name="namaLengkap" value="{{ old('namaLengkap', $user->namaLengkap) }}" class="form-input editable" disabled>
                        @error('namaLengkap') <p class="error-text">{{ $message }}</p> @enderror
                    </div>

                    {{-- Username (SEKARANG BISA DIEDIT) --}}
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Username</label>
                        <div class="relative">
                            <input type="text" name="username" value="{{ old('username', $user->username) }}" class="form-input editable pl-8" disabled>
                        </div>
                        @error('username') <p class="error-text">{{ $message }}</p> @enderror
                    </div>

                    {{-- Nomor Telepon --}}
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Nomor Telepon</label>
                        <input type="text" name="noTelp" value="{{ old('noTelp', $user->noTelp) }}" class="form-input editable" disabled>
                        @error('noTelp') <p class="error-text">{{ $message }}</p> @enderror
                    </div>

                    {{-- Baris Jenis Kelamin & Tanggal Lahir --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-1">
                            <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Jenis Kelamin</label>
                            <select name="jenisKelamin" class="form-input editable" disabled>
                                <option value="Laki-laki" {{ old('jenisKelamin', $user->jenisKelamin) == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="Perempuan" {{ old('jenisKelamin', $user->jenisKelamin) == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                            @error('jenisKelamin') <p class="error-text">{{ $message }}</p> @enderror
                        </div>
                        <div class="space-y-1">
                            <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Tanggal Lahir</label>
                            <input type="date" name="tanggalLahir" value="{{ old('tanggalLahir', $user->tanggalLahir ? $user->tanggalLahir->format('Y-m-d') : '') }}" class="form-input editable" disabled>
                            @error('tanggalLahir') <p class="error-text">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                {{-- Informasi Alamat --}}
                <div class="mt-10 pt-10 border-t border-gray-100">
                    <h3 class="text-lg font-bold text-gray-800 mb-6 flex items-center gap-2">
                        <i class="fas fa-map-marker-alt text-green-500"></i>
                        Informasi Alamat
                    </h3>

                    <div class="grid md:grid-cols-2 gap-6">
                        <div class="space-y-1">
                            <select id="provinsi" class="form-input editable" disabled>
                                <option value="">{{ $user->desa->kecamatan->kabupaten->provinsi->namaProvinsi ?? 'Pilih Provinsi' }}</option>
                            </select>
                        </div>

                        <div class="space-y-1">
                            <select id="kabupaten" class="form-input editable" disabled>
                                <option value="">{{ $user->desa->kecamatan->kabupaten->namaKabupaten ?? 'Pilih Kabupaten' }}</option>
                            </select>
                        </div>

                        <div class="space-y-1">
                            <select id="kecamatan" class="form-input editable" disabled>
                                <option value="">{{ $user->desa->kecamatan->namaKecamatan ?? 'Pilih Kecamatan' }}</option>
                            </select>
                        </div>

                        <div class="space-y-1">
                            <select name="desaId" id="desa" class="form-input editable" disabled>
                                <option value="{{ $user->desaId }}">{{ $user->desa->namaDesa ?? 'Pilih Desa' }}</option>
                            </select>
                            @error('desaId') <p class="error-text">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="mt-6 space-y-1">
                        <textarea name="detailAlamat" rows="3" placeholder="Detail Alamat (Nama Jalan, Blok, No. Rumah)" class="form-input editable" disabled>{{ old('detailAlamat', $user->detailAlamat) }}</textarea>
                        @error('detailAlamat') <p class="error-text">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Tombol Simpan --}}
                <div id="saveSection" class="hidden mt-12">
                    <button type="submit" class="w-full md:w-auto px-10 py-4 bg-[#58CC02] hover:bg-green-600 text-white font-bold rounded-2xl shadow-lg shadow-green-200 transition-all transform hover:-translate-y-1">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const editBtn = document.getElementById('editBtn');
    const cancelBtn = document.getElementById('cancelBtn');
    const saveSection = document.getElementById('saveSection');
    const overlayFoto = document.getElementById('overlayFoto');
    const fotoRule = document.getElementById('fotoRule');
    const editableInputs = document.querySelectorAll('.editable');
    const baseUrl = "{{ url('wilayah') }}";

    function enableEditMode() {
        editableInputs.forEach(input => input.removeAttribute('disabled'));
        editBtn.classList.add('hidden');
        cancelBtn.classList.remove('hidden');
        saveSection.classList.remove('hidden');
        overlayFoto.classList.add('flex');
        overlayFoto.classList.remove('hidden');
        fotoRule.classList.remove('hidden');

        loadProvinsi();
    }

    editBtn.addEventListener('click', enableEditMode);

    cancelBtn.addEventListener('click', () => {
        window.location.href = "{{ route('agen.profile.update') }}";
    });

    @if ($errors->any())
        document.addEventListener('DOMContentLoaded', () => {
            enableEditMode();
            const firstError = document.querySelector('.error-text');
            if (firstError) {
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        });
    @endif

    document.getElementById('fotoProfil').addEventListener('change', function(e) {
        if (this.files && this.files[0]) {
            document.getElementById('previewFoto').src = URL.createObjectURL(this.files[0]);
        }
    });

    function renderOptions(elementId, data, placeholder) {
        const select = document.getElementById(elementId);
        const currentValue = select.value;
        const currentText = select.options[0] ? select.options[0].text : placeholder;

        select.innerHTML = `<option value="${currentValue}">${currentText}</option>`;

        if (Array.isArray(data)) {
            data.forEach(item => {
                if (item.id != currentValue) {
                    const option = document.createElement('option');
                    option.value = item.id;
                    option.textContent = item.name;
                    select.appendChild(option);
                }
            });
        }
    }

    async function loadProvinsi() {
        try {
            const response = await fetch(`${baseUrl}/provinsi`);
            const data = await response.json();
            renderOptions('provinsi', data, 'Pilih Provinsi');
        } catch (e) { console.error("Error load provinsi", e); }
    }

    document.getElementById('provinsi').addEventListener('change', async function () {
        const id = this.value;
        if (!id) return;
        const res = await fetch(`${baseUrl}/kabupaten/${id}`);
        const selectKab = document.getElementById('kabupaten');
        selectKab.innerHTML = '<option value="">-- Pilih Kabupaten --</option>';
        (await res.json()).forEach(item => {
            const opt = document.createElement('option');
            opt.value = item.id; opt.textContent = item.name;
            selectKab.appendChild(opt);
        });
        document.getElementById('kecamatan').innerHTML = '<option value="">-- Pilih Kecamatan --</option>';
        document.getElementById('desa').innerHTML = '<option value="">-- Pilih Desa --</option>';
    });

    document.getElementById('kabupaten').addEventListener('change', async function () {
        const id = this.value;
        if (!id) return;
        const res = await fetch(`${baseUrl}/kecamatan/${id}`);
        const selectKec = document.getElementById('kecamatan');
        selectKec.innerHTML = '<option value="">-- Pilih Kecamatan --</option>';
        (await res.json()).forEach(item => {
            const opt = document.createElement('option');
            opt.value = item.id; opt.textContent = item.name;
            selectKec.appendChild(opt);
        });
        document.getElementById('desa').innerHTML = '<option value="">-- Pilih Desa --</option>';
    });

    document.getElementById('kecamatan').addEventListener('change', async function () {
        const id = this.value;
        if (!id) return;
        const res = await fetch(`${baseUrl}/desa/${id}`);
        const selectDesa = document.getElementById('desa');
        selectDesa.innerHTML = '<option value="">-- Pilih Desa --</option>';
        (await res.json()).forEach(item => {
            const opt = document.createElement('option');
            opt.value = item.id; opt.textContent = item.name;
            selectDesa.appendChild(opt);
        });
    });

</script>
@endsection
