@extends('layouts.app')

@section('title', 'Register - AGRIS')

@section('content')

<div class="min-h-screen flex items-center justify-center bg-gray-100">

    <div class="bg-white p-8 rounded-lg shadow w-full max-w-md">

        <div class="mb-4">
            <a href="{{ route('register') }}" class="inline-flex items-center gap-2 text-gray-600 hover:text-[#58CC02] transition text-sm">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
        </div>

        <h2 class="text-2xl font-bold text-center mb-6">
            Verifikasi OTP
        </h2>

        @if(session('error'))
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        @if(session('success'))
            <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('otp.verify') }}">
            @csrf

            <input type="text"
                name="otp"
                maxlength="6"
                placeholder="Masukkan 6 digit OTP"
                class="w-full border p-3 text-center text-xl tracking-widest rounded mb-4">

            <button class="w-full bg-[#58CC02] text-white py-3 cursor-pointer rounded">
                Verifikasi
            </button>
        </form>

        <form method="POST" action="{{ route('otp.resend') }}" class="mt-4 text-center">
            @csrf
            <button class="text-sm text-gray-600 cursor-pointer hover:underline">
                Kirim Ulang OTP
            </button>
        </form>

    </div>

</div>

@endsection
