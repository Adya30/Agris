@extends('layouts.app')

@section('title', 'Register - AGRIS')

@section('content')

<div class="flex min-h-screen w-full">

    <div class="hidden md:flex w-1/2 bg-[#58CC02] flex-col justify-between p-12">

        <div>
            <img src="{{ asset('images/icon.png') }}" class="w-20">
        </div>

        <div class="flex justify-center items-center flex-1">
            <img src="{{ asset('images/plant.png') }}" class="w-50">
        </div>

        <div class="text-white text-center text-lg font-medium leading-relaxed mb-6">
            Masuk dan Temukan <br> Keseimbangan Alam Dalam Setiap Tanam
        </div>

    </div>

    <div class="w-full md:w-1/2 flex items-center justify-center relative py-10 overflow-y-auto bg-gray-100 text-sm">

        <a href="{{ route('login') }}" class="absolute top-6 left-6 text-gray-600 hover:text-[#58CC02] transition">
            <i class="fa-solid fa-arrow-left text-xl"></i>
        </a>

        <div class="w-full max-w-md px-8">

            <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">
                Daftar Akun AGRIS
            </h2>
            <div id="progressBarContainer" class="hidden fixed top-0 left-0 w-full h-1 bg-gray-200 z-50"> <div id="progressBar" class="h-full bg-[#58CC02] w-0 transition-all duration-500 ease-linear"> </div> </div>
            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="mb-3">
                    <input type="text" name="namaLengkap" value="{{ old('namaLengkap') }}" placeholder="Nama Lengkap" class="w-full bg-transparent border-b border-gray-400 focus:border-[#58CC02] focus:outline-none py-2">
                    @error('namaLengkap')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-2">
                    <input type="text" name="username" value="{{ old('username') }}" placeholder="Username" class="w-full bg-transparent border-b border-gray-400 focus:border-[#58CC02] focus:outline-none py-2">
                    @error('username')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- EMAIL --}}
                <div class="mb-3">
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="Email" class="w-full bg-transparent border-b border-gray-400 focus:border-[#58CC02] focus:outline-none py-2">
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>


                <div class="mb-3">
                    <input type="text" name="noTelp" value="{{ old('noTelp') }}" placeholder="Nomor Telepon" class="w-full bg-transparent border-b border-gray-400 focus:border-[#58CC02] focus:outline-none py-2">
                    @error('noTelp')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-3 relative">
                    <input type="password" name="password" id="password" placeholder="Password" class="w-full bg-transparent border-b border-gray-400 focus:border-[#58CC02] focus:outline-none py-2 pr-10">
                    <span onclick="togglePassword('password','eye1')" class="absolute right-2 top-2 cursor-pointer text-gray-500 hover:text-[#58CC02]">
                        <i id="eye1" class="fa-solid fa-eye"></i>
                    </span>

                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-3 relative">
                    <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Konfirmasi Password" class="w-full bg-transparent border-b border-gray-400 focus:border-[#58CC02] focus:outline-none py-2 pr-10">
                    <span onclick="togglePassword('password_confirmation','eye2')" class="absolute right-2 top-2 cursor-pointer text-gray-500 hover:text-[#58CC02]">
                        <i id="eye2" class="fa-solid fa-eye"></i>
                    </span>
                </div>

                <button type="submit" class="w-full bg-[#58CC02] hover:bg-green-600 text-white py-2 rounded-full cursor-pointer text-lg transition">
                    Daftar
                </button>

            </form>

            <div class="flex items-center gap-2 my-4">
                <div class="flex-1 h-px bg-gray-400"></div>
                <span class="text-sm text-gray-600">atau</span>
                <div class="flex-1 h-px bg-gray-400"></div>
            </div>

            <a href="{{ route('google.login') }}" class="w-full flex items-center justify-center border border-gray-500 rounded-full py-3 hover:bg-gray-200 transition">
                <img src="https://www.svgrepo.com/show/475656/google-color.svg" class="w-5 h-5 mr-2">
                Login dengan Google
            </a>

            <div class="text-center text-sm mt-6">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="text-[#58CC02] font-semibold hover:underline">
                    Login
                </a>
            </div>

        </div>
    </div>

</div>

@endsection


@push('scripts')
<script>
    function togglePassword(fieldId, eyeId) {
        const field = document.getElementById(fieldId);
        const eye = document.getElementById(eyeId);

        if (field.type === "password") {
            field.type = "text";
            eye.classList.remove("fa-eye");
            eye.classList.add("fa-eye-slash");
        } else {
            field.type = "password";
            eye.classList.remove("fa-eye-slash");
            eye.classList.add("fa-eye");
        }
    }
</script>
@endpush
