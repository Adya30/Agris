<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>@yield('title', 'Agris')</title>

    <link rel="icon" type="image/png" href="{{ asset('images/icon.png') }}">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>
<body class="bg-white text-gray-800 scroll-smooth">

    <div id="progressBarContainer" class="hidden fixed top-0 left-0 w-full h-1 bg-gray-200 z-[9999]">
        <div id="progressBar" class="h-full bg-[#58CC02] w-0 transition-all duration-300 ease-linear"> </div>
    </div>

    <div class="fixed top-5 right-5 z-[9999] space-y-3">
        @if(session('success'))
            <div class="alert-info flex items-center w-full max-w-xs p-4 rounded-2xl shadow-xl border border-green-200 bg-green-50" role="alert">
                <div class="inline-flex items-center justify-center flex-shrink-0 w-10 h-10 rounded-full bg-green-600 text-white">
                    <i class="fa-solid fa-check text-sm"></i>
                </div>
                <div class="ms-3">
                    <div class="text-sm font-bold text-green-800">Berhasil</div>
                    <div class="text-xs text-green-700 mt-0.5">{{ session('success') }}</div>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="alert-info flex items-center w-full max-w-xs p-4 rounded-2xl shadow-xl border border-red-200 bg-red-50" role="alert">
                <div class="inline-flex items-center justify-center flex-shrink-0 w-10 h-10 rounded-full bg-red-600 text-white">
                    <i class="fa-solid fa-xmark text-sm"></i>
                </div>
                <div class="ms-3">
                    <div class="text-sm font-bold text-red-800">Gagal</div>
                    <div class="text-xs text-red-700 mt-0.5">{{ session('error') }}</div>
                </div>
            </div>
        @endif
    </div>

    @yield('content')

    @stack('scripts')

<script>
document.addEventListener('DOMContentLoaded', function () {

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
            }, 200);

        });
    });

    (function() {
        const alerts = document.querySelectorAll('.alert-info');

        alerts.forEach(alert => {

            // kondisi awal
            alert.style.opacity = '0';
            alert.style.transform = 'translateX(20px)';

            // animasi masuk
            setTimeout(() => {
                alert.style.transition = "all 0.5s ease";
                alert.style.opacity = '1';
                alert.style.transform = 'translateX(0)';
            }, 100);

            // animasi keluar
            setTimeout(() => {
                alert.style.opacity = '0';
                alert.style.transform = 'translateX(20px)';

                setTimeout(() => {
                    alert.remove();
                }, 500);
            }, 4000);

        });
    })();
});
</script>
</body>
</html>
