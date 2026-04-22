<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Profil - AGRIS</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100">

<div class="flex min-h-screen">

    <!-- Sidebar -->
    <div class="w-64 bg-[#58CC02] text-white p-6">
        <h1 class="text-2xl font-bold mb-8">AGRIS</h1>

        <ul class="space-y-4">
            <li><a href="{{ route('profile') }}" class="underline font-semibold">Profil</a></li>
            <li>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button>Logout</button>
                </form>
            </li>
        </ul>
    </div>

    <!-- Content -->
    <div class="flex-1 p-10">

        <h2 class="text-3xl font-bold mb-6">Profil Saya</h2>

        @if(session('success'))
            <div class="mb-4 bg-green-100 text-green-700 p-3 rounded">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white p-8 rounded-lg shadow">

            <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                @csrf

                <!-- FOTO -->
                <div class="mb-6 text-center">
                    @if($user->fotoProfil)
                        <img src="{{ asset('storage/'.$user->fotoProfil) }}"
                             class="w-32 h-32 rounded-full mx-auto object-cover">
                    @else
                        <div class="w-32 h-32 bg-gray-300 rounded-full mx-auto"></div>
                    @endif

                    <input type="file" name="fotoProfil" class="mt-3">
                </div>

                <!-- DATA DASAR -->
                <div class="grid grid-cols-2 gap-6">

                    <div>
                        <label>Username</label>
                        <input type="text" name="username"
                               value="{{ $user->username }}"
                               class="w-full border p-2 rounded">
                        @error('username')
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label>Email</label>
                        <input type="email" name="email"
                               value="{{ $user->email }}"
                               class="w-full border p-2 rounded">
                        @error('email')
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label>No Telepon</label>
                        <input type="text" name="noTelp"
                               value="{{ $user->noTelp }}"
                               class="w-full border p-2 rounded">
                        @error('noTelp')
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label>Detail Alamat</label>
                        <input type="text" name="detailAlamat"
                               value="{{ $user->detailAlamat }}"
                               class="w-full border p-2 rounded">
                    </div>

                </div>

                <!-- ALAMAT HIERARKI (DIPERBAIKI) -->
                <div class="mt-8">

                    <h3 class="text-xl font-semibold mb-4">Alamat Wilayah</h3>

                    <div class="grid grid-cols-2 gap-6">

                        <div>
                            <label>Provinsi</label>
                            <input type="text"
                                   value="{{ $user->desa?->kecamatan?->kabupaten?->provinsi?->namaProvinsi ?? '-' }}"
                                   class="w-full border p-2 rounded bg-gray-100"
                                   readonly>
                        </div>

                        <div>
                            <label>Kabupaten</label>
                            <input type="text"
                                   value="{{ $user->desa?->kecamatan?->kabupaten?->namaKabupaten ?? '-' }}"
                                   class="w-full border p-2 rounded bg-gray-100"
                                   readonly>
                        </div>

                        <div>
                            <label>Kecamatan</label>
                            <input type="text"
                                   value="{{ $user->desa?->kecamatan?->namaKecamatan ?? '-' }}"
                                   class="w-full border p-2 rounded bg-gray-100"
                                   readonly>
                        </div>

                        <div>
                            <label>Desa</label>
                            <input type="text"
                                   value="{{ $user->desa?->namaDesa ?? '-' }}"
                                   class="w-full border p-2 rounded bg-gray-100"
                                   readonly>
                        </div>

                    </div>

                </div>

                <!-- GANTI PASSWORD -->
                <div class="mt-8">

                    <h3 class="text-xl font-semibold mb-4">Ubah Password</h3>

                    <div class="grid grid-cols-2 gap-6">

                        <div>
                            <label>Password Baru</label>
                            <input type="password"
                                   name="password"
                                   class="w-full border p-2 rounded">
                            @error('password')
                            <p class="text-red-500 text-sm">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label>Konfirmasi Password</label>
                            <input type="password"
                                   name="password_confirmation"
                                   class="w-full border p-2 rounded">
                        </div>

                    </div>

                    <p class="text-sm text-gray-500 mt-2">
                        Kosongkan jika tidak ingin mengganti password.
                    </p>

                </div>

                <div class="mt-8">
                    <button class="bg-[#58CC02] text-white px-6 py-2 rounded hover:bg-green-600">
                        Simpan Perubahan
                    </button>
                </div>

            </form>

        </div>

    </div>
</div>

</body>
</html>