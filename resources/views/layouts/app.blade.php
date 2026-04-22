<!DOCTYPE html>
<html lang="id"> {{-- Menggunakan 'id' seperti yang kamu gunakan sebelumnya --}}
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    {{-- Placeholder untuk title halaman. Defaultnya 'Agris' --}}
    <title>@yield('title', 'Agris')</title>

    {{-- Favicon global --}}
    <link rel="icon" type="image/png" href="{{ asset('images/icon.png') }}">

    {{-- Font Awesome (dari CDN) --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"> {{-- Pastikan versi terbaru --}}

    {{-- Vite untuk CSS dan JS utama (misal: Tailwind CSS) --}}
    @vite('resources/css/app.css')

    {{-- Untuk menambahkan CSS spesifik dari child view (opsional) --}}
    @stack('styles')
</head>
<body class="bg-white text-gray-800"> {{-- Kelas body yang umum kamu pakai --}}

    {{-- Ini adalah placeholder utama tempat konten dari child view akan di-render --}}
    @yield('content')

    {{-- Untuk menambahkan JavaScript spesifik dari child view (opsional, diletakkan di akhir body untuk performa) --}}
    @stack('scripts')

</body>
</html>