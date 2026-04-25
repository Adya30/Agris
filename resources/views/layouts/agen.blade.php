<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Agen')</title>
    <link rel="icon" type="image/png" href="{{ asset('images/icon.png') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">

    <div id="progressBarContainer"
        class="hidden fixed top-0 left-0 w-full h-1 bg-gray-200 z-[9999]">
        <div id="progressBar"
            class="h-full bg-[#58CC02] w-0 transition-all duration-300 ease-linear">
        </div>
    </div>

    @include('components.navbar-agen')

    <div class="pt-24 p-8">
        @yield('content')
    </div>

    @include('components.footer-agen')

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
                }, 100);

            });
        });

    });
    </script>
</body>
</html>
