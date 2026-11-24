<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'Agris'); ?></title>
    <link rel="icon" type="image/png" href="<?php echo e(asset('images/icon.svg')); ?>">
    <script>
        window.pusherKey = "<?php echo e(config('broadcasting.connections.pusher.key')); ?>";
        window.pusherCluster = "<?php echo e(config('broadcasting.connections.pusher.options.cluster', 'ap1')); ?>";
        window.pusherScheme = "<?php echo e(config('broadcasting.connections.pusher.options.scheme', 'https')); ?>";
    </script>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
</head>
<body class="bg-gray-700 text-gray-800 scroll-smooth font-sans antialiased overflow-x-hidden">

<div id="progressBarContainer" class="hidden fixed top-0 left-0 w-full h-1 bg-gray-200 z-110">
    <div id="progressBar" class="h-full bg-[#58CC02] w-0 transition-all duration-300 ease-linear"></div>
</div>

<div class="fixed top-5 right-5 z-100 space-y-3">
    <?php if(session('success')): ?>
        <div class="alert-info flex items-center w-full max-w-xs p-4 rounded-2xl shadow-xl border border-green-200 bg-green-50" role="alert">
            <div class="inline-flex items-center justify-center shrink-0 w-10 h-10 rounded-full bg-green-600 text-white">
                <i class="fa-solid fa-check text-sm"></i>
            </div>
            <div class="ms-3">
                <div class="text-sm font-bold text-green-800">Berhasil</div>
                <div class="text-xs text-green-700 mt-0.5"><?php echo e(session('success')); ?></div>
            </div>
        </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div class="alert-info flex items-center w-full max-w-xs p-4 rounded-2xl shadow-xl border border-red-200 bg-red-50" role="alert">
            <div class="inline-flex items-center justify-center shrink-0 w-10 h-10 rounded-full bg-red-600 text-white">
                <i class="fa-solid fa-xmark text-sm"></i>
            </div>
            <div class="ms-3">
                <div class="text-sm font-bold text-red-800">Gagal</div>
                <div class="text-xs text-red-700 mt-0.5"><?php echo e(session('error')); ?></div>
            </div>
        </div>
    <?php endif; ?>
</div>

<div>
<?php echo $__env->yieldContent('content'); ?>
</div>

<?php echo $__env->yieldPushContent('scripts'); ?>

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

<?php if (isset($component)) { $__componentOriginal9f64f32e90b9102968f2bc548315018c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9f64f32e90b9102968f2bc548315018c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.modal','data' => ['id' => 'modalFileExceeded','title' => 'Batas Ukuran File','message' => 'Ukuran file atau foto yang Anda pilih melebihi batas maksimal 1MB (1.024 KB). Harap kompres atau pilih file yang lebih kecil.','confirmText' => 'Mengerti','cancelText' => 'Tutup','confirmId' => 'btnDismissFileSize','cancelId' => 'btnDismissFileSize2']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('modal'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'modalFileExceeded','title' => 'Batas Ukuran File','message' => 'Ukuran file atau foto yang Anda pilih melebihi batas maksimal 1MB (1.024 KB). Harap kompres atau pilih file yang lebih kecil.','confirmText' => 'Mengerti','cancelText' => 'Tutup','confirmId' => 'btnDismissFileSize','cancelId' => 'btnDismissFileSize2']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9f64f32e90b9102968f2bc548315018c)): ?>
<?php $attributes = $__attributesOriginal9f64f32e90b9102968f2bc548315018c; ?>
<?php unset($__attributesOriginal9f64f32e90b9102968f2bc548315018c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9f64f32e90b9102968f2bc548315018c)): ?>
<?php $component = $__componentOriginal9f64f32e90b9102968f2bc548315018c; ?>
<?php unset($__componentOriginal9f64f32e90b9102968f2bc548315018c); ?>
<?php endif; ?>

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
<?php /**PATH D:\project\Agris\resources\views/layouts/app.blade.php ENDPATH**/ ?>