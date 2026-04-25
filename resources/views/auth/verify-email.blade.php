<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Verifikasi Email - AGRIS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @vite('resources/css/app.css')

</head>
<body class="min-h-screen bg-gradient-to-r from-green-600 to-green-500 flex items-center justify-center">

    <div class="bg-white shadow-2xl rounded-2xl p-8 w-full max-w-md">

        <div class="text-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">
                Verifikasi Email Anda
            </h2>
            <p class="text-sm text-gray-500 mt-2">
                Kami telah mengirim link verifikasi ke email Anda.
                Silakan cek inbox atau folder spam.
            </p>
        </div>

        {{-- Success message --}}
        @if (session('success'))
            <div class="mb-4 p-3 rounded-lg bg-green-100 text-green-700 text-sm">
                {{ session('success') }}
            </div>
        @endif

        {{-- Warning message --}}
        @if (session('warning'))
            <div class="mb-4 p-3 rounded-lg bg-yellow-100 text-yellow-700 text-sm">
                {{ session('warning') }}
            </div>
        @endif

        {{-- Resend Button --}}
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button
                type="submit"
                class="w-full bg-green-600 hover:bg-green-700 transition duration-200 text-white py-2.5 rounded-xl font-semibold">
                Kirim Ulang Email Verifikasi
            </button>
        </form>

        {{-- Logout --}}
        <div class="mt-6 text-center">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button
                    type="submit"
                    class="text-sm text-gray-500 hover:text-red-500 transition">
                    Keluar dari akun
                </button>
            </form>
        </div>

    </div>

</body>
</html>
