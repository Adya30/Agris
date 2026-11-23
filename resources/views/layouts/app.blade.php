<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Agris')</title>
    <link rel="icon" type="image/png" href="{{ asset('images/icon.svg') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script>
        window.pusherKey = "{{ config('broadcasting.connections.pusher.key') }}";
        window.pusherCluster = "{{ config('broadcasting.connections.pusher.options.cluster', 'ap1') }}";
        window.pusherScheme = "{{ config('broadcasting.connections.pusher.options.scheme', 'https') }}";
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-700 text-gray-800 scroll-smooth font-sans antialiased">

<div id="progressBarContainer" class="hidden fixed top-0 left-0 w-full h-1 bg-gray-200 z-110">
    <div id="progressBar" class="h-full bg-[#58CC02] w-0 transition-all duration-300 ease-linear"></div>
</div>

<div class="fixed top-5 right-5 z-100 space-y-3">
    @if(session('success'))
        <div class="alert-info flex items-center w-full max-w-xs p-4 rounded-2xl shadow-xl border border-green-200 bg-green-50" role="alert">
            <div class="inline-flex items-center justify-center shrink-0 w-10 h-10 rounded-full bg-green-600 text-white">
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
            <div class="inline-flex items-center justify-center shrink-0 w-10 h-10 rounded-full bg-red-600 text-white">
                <i class="fa-solid fa-xmark text-sm"></i>
            </div>
            <div class="ms-3">
                <div class="text-sm font-bold text-red-800">Gagal</div>
                <div class="text-xs text-red-700 mt-0.5">{{ session('error') }}</div>
            </div>
        </div>
    @endif
</div>

<div>
@yield('content')
</div>

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

    const alerts = document.querySelectorAll('.alert-info');
    alerts.forEach(alert => {
        alert.style.opacity = '0';
        alert.style.transform = 'translateX(20px)';
        alert.style.transition = "all 0.5s ease";

        setTimeout(() => {
            alert.style.opacity = '1';
            alert.style.transform = 'translateX(0)';
        }, 100);

        setTimeout(() => {
            alert.style.opacity = '0';
            alert.style.transform = 'translateX(20px)';
            setTimeout(() => { alert.remove(); }, 500);
        }, 4000);
    });
});
</script>

<x-modal 
    id="modalFileExceeded" 
    title="Batas Ukuran File" 
    message="Ukuran file atau foto yang Anda pilih melebihi batas maksimal 1MB (1.024 KB). Harap kompres atau pilih file yang lebih kecil." 
    confirmText="Mengerti" 
    cancelText="Tutup" 
    confirmId="btnDismissFileSize" 
    cancelId="btnDismissFileSize2" 
/>

<script>
    window.showFileSizeModal = function(customMessage) {
        const modal = document.getElementById('modalFileExceeded');
        if (modal) {
            if (customMessage) {
                const msgEl = modal.querySelector('p');
                if (msgEl) msgEl.textContent = customMessage;
            }
            if (typeof window.openModal === 'function') {
                window.openModal('modalFileExceeded');
            } else {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }
        } else {
            alert(customMessage || 'Ukuran file tidak boleh lebih dari 1MB.');
        }
    };

    document.addEventListener('DOMContentLoaded', function() {
        ['btnDismissFileSize', 'btnDismissFileSize2'].forEach(function(id) {
            const el = document.getElementById(id);
            if (el) {
                el.addEventListener('click', function(e) {
                    e.preventDefault();
                    if (typeof window.closeModal === 'function') {
                        window.closeModal('modalFileExceeded');
                    } else {
                        document.getElementById('modalFileExceeded')?.classList.add('hidden');
                    }
                });
            }
        });
    });
</script>

</body>
</html>
