<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Verifikasi OTP - AGRIS</title>
    @vite('resources/css/app.css')
</head>
<body class="min-h-screen flex items-center justify-center bg-gray-100">

<div class="bg-white p-8 rounded-lg shadow w-full max-w-md">

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

        <button class="w-full bg-[#58CC02] text-white py-3 rounded">
            Verifikasi
        </button>
    </form>

    <form method="POST" action="{{ route('otp.resend') }}" class="mt-4 text-center">
        @csrf
        <button class="text-sm text-gray-600 hover:underline">
            Kirim Ulang OTP
        </button>
    </form>

</div>

</body>
</html>