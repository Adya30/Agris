@extends('layouts.app')

@section('title', 'Lupa Password - AGRIS')

@section('content')

<div class="min-h-screen bg-gray-100 flex items-center justify-center">

    <div class="bg-white w-full max-w-md p-8 rounded-lg shadow relative">


        <h2 class="text-2xl font-bold text-center mb-6">
            Lupa Password
        </h2>

        <p class="text-sm text-gray-600 text-center mb-6">
            Masukkan email yang terdaftar, kami akan kirimkan link untuk mereset password Anda.
        </p>

        @if(session('success'))
            <div class="bg-green-100 text-green-700 p-3 rounded mb-4 text-sm">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4 text-sm">
                {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="mb-5">
                <input type="email"
                    name="email"
                    value="{{ old('email') }}"
                    placeholder="Masukkan Email"
                    class="w-full border-b border-gray-400 focus:border-[#58CC02] focus:outline-none py-2">

                @error('email')
                    <p class="text-red-500 text-sm mt-1">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <button type="submit" class="w-full bg-[#58CC02] hover:bg-green-600 text-white py-3 rounded-full cursor-pointer text-lg transition">
                Kirim Link Reset
            </button>
        </form>

        <div class="text-center mt-6 text-sm">
            Kembali ke
            <a href="{{ route('login') }}"
            class="text-[#58CC02] font-semibold hover:underline">
                Login
            </a>
        </div>

    </div>
</div>
