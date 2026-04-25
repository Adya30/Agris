<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin')</title>
    <link rel="icon" type="image/png" href="{{ asset('images/icon.png') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 font-sans antialiased">

    <div id="progressBarContainer" class="hidden fixed top-0 left-0 w-full h-1 bg-gray-200 z-[9999]">
        <div id="progressBar" class="h-full bg-[#58CC02] w-0 transition-all duration-300 ease-linear"></div>
    </div>

    {{-- 1. SIDEBAR: Hanya muncul jika BUKAN halaman profil --}}
    @if(!Route::is('admin.profile'))
        @include('components.sidebar-admin')
    @endif

    {{-- 2. WRAPPER KONTEN --}}
    {{-- Class md:ml-64 hanya dipasang jika BUKAN halaman profil agar konten tidak lowong di kiri --}}
    <div class="{{ !Route::is('admin.profile') ? 'md:ml-64' : '' }} transition-all duration-300">

        {{-- 3. TOPBAR: Selalu tampil di semua halaman admin --}}
        @include('components.topbar-admin')

        <main class="pt-20 p-8 min-h-screen">
            @yield('content')
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Dropdown Profile Logic
            const adminBtn = document.getElementById('adminDropdownBtn');
            const adminMenu = document.getElementById('adminDropdownMenu');

            if(adminBtn && adminMenu) {
                adminBtn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    adminMenu.classList.toggle('hidden');
                });
                document.addEventListener('click', () => {
                    adminMenu.classList.add('hidden');
                });
            }

            // Progress Bar Logic
            const forms = document.querySelectorAll('form');
            const progressContainer = document.getElementById('progressBarContainer');
            const progressBar = document.getElementById('progressBar');

            forms.forEach(form => {
                form.addEventListener('submit', function () {
                    progressContainer.classList.remove('hidden');
                    let width = 0;
                    const interval = setInterval(() => {
                        if (width >= 90) {
                            clearInterval(interval);
                        } else {
                            width += 10;
                            progressBar.style.width = width + "%";
                        }
                    }, 100);
                });
            });
        });
    </script>
</body>
</html>
