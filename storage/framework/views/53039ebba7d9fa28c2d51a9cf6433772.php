<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'id' => 'confirmModal',
    'title' => 'Konfirmasi',
    'message' => 'Apakah Anda yakin?',
    'confirmText' => 'Iya',
    'cancelText' => 'Batal',
    'confirmId' => 'submitForm',
    'cancelId' => 'closeModal'
]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter(([
    'id' => 'confirmModal',
    'title' => 'Konfirmasi',
    'message' => 'Apakah Anda yakin?',
    'confirmText' => 'Iya',
    'cancelText' => 'Batal',
    'confirmId' => 'submitForm',
    'cancelId' => 'closeModal'
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $lowerId = strtolower($id);
    $lowerTitle = strtolower($title);
    $isDanger = str_contains($lowerId, 'hapus') || str_contains($lowerId, 'delete') || str_contains($lowerId, 'batal') || str_contains($lowerId, 'hentikan') || str_contains($lowerId, 'tolak') || str_contains($lowerId, 'error') || str_contains($lowerId, 'logout') || str_contains($lowerTitle, 'hapus') || str_contains($lowerTitle, 'batal') || str_contains($lowerTitle, 'keluar');
    $isWarning = str_contains($lowerId, 'stok') || str_contains($lowerId, 'berat') || str_contains($lowerId, 'kurang') || str_contains($lowerId, 'mitra') || str_contains($lowerId, 'file') || str_contains($lowerId, 'ukuran') || str_contains($lowerTitle, 'stok') || str_contains($lowerTitle, 'kurang') || str_contains($lowerTitle, 'perhatian') || str_contains($lowerTitle, 'belum') || str_contains($lowerTitle, 'ukuran') || str_contains($lowerTitle, 'batas');
    $isSuccess = str_contains($lowerId, 'diterima') || str_contains($lowerId, 'sukses') || str_contains($lowerId, 'berhasil') || str_contains($lowerId, 'aktif') || str_contains($lowerTitle, 'diterima') || str_contains($lowerTitle, 'berhasil');

    if ($isDanger) {
        $iconClass = (str_contains($lowerId, 'hapus') || str_contains($lowerId, 'delete')) ? 'fa-solid fa-trash-can' : ((str_contains($lowerId, 'logout') || str_contains($lowerTitle, 'keluar')) ? 'fa-solid fa-right-from-bracket' : 'fa-solid fa-triangle-exclamation');
        $badgeClass = 'bg-red-50 text-red-500 border border-red-100 ring-8 ring-red-50/60 shadow-sm';
        $confirmBtnClass = 'bg-red-500 hover:bg-red-600 shadow-red-200';
    } elseif ($isWarning) {
        $iconClass = 'fa-solid fa-circle-exclamation';
        $badgeClass = 'bg-amber-50 text-amber-500 border border-amber-100 ring-8 ring-amber-50/60 shadow-sm';
        $confirmBtnClass = 'bg-amber-500 hover:bg-amber-600 shadow-amber-200';
    } elseif ($isSuccess) {
        $iconClass = 'fa-solid fa-circle-check';
        $badgeClass = 'bg-emerald-50 text-emerald-600 border border-emerald-100 ring-8 ring-emerald-50/60 shadow-sm';
        $confirmBtnClass = 'bg-[#0f8629] hover:bg-[#0c6b20] shadow-emerald-200';
    } else {
        $iconClass = 'fa-solid fa-circle-question';
        $badgeClass = 'bg-green-50 text-[#58CC02] border border-green-100 ring-8 ring-green-50/60 shadow-sm';
        $confirmBtnClass = 'bg-[#58CC02] hover:bg-[#46a302] shadow-green-100';
    }
?>

<div id="<?php echo e($id); ?>" class="fixed inset-0 z-300 hidden items-center justify-center p-4 overflow-x-hidden overflow-y-auto outline-none focus:outline-none modal-container">
    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity duration-300 modal-overlay" data-modal-id="<?php echo e($id); ?>"></div>

    <div id="content-<?php echo e($id); ?>" class="relative bg-white rounded-[2.5rem] p-8 md:p-10 max-w-sm w-full shadow-2xl text-center border border-gray-100 transition-all duration-300 transform scale-95 opacity-0">
        <div class="w-20 h-20 rounded-3xl flex items-center justify-center mx-auto mb-6 text-3xl <?php echo e($badgeClass); ?> transition-transform duration-300">
            <i class="<?php echo e($iconClass); ?>"></i>
        </div>

        <h3 class="text-2xl font-black text-slate-800 mb-2 tracking-tight"><?php echo e($title); ?></h3>
        <p class="text-slate-500 text-sm font-medium mb-8 leading-relaxed">
            <?php echo e($message); ?>

        </p>

        <div class="flex gap-3">
            <button type="button" id="<?php echo e($cancelId); ?>" class="flex-1 py-3.5 px-4 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-2xl transition-all active:scale-95 border border-slate-200/80 text-sm cursor-pointer">
                <?php echo e($cancelText); ?>

            </button>

            <button type="button" id="<?php echo e($confirmId); ?>" class="flex-1 py-3.5 px-4 <?php echo e($confirmBtnClass); ?> text-white font-bold rounded-2xl transition-all shadow-md active:scale-95 text-sm cursor-pointer btn-confirm-modal">
                <?php echo e($confirmText); ?>

            </button>
        </div>
    </div>
</div>

<?php if (! $__env->hasRenderedOnce('90e10eab-b1e5-4c51-93b8-150075fd2f6d')): $__env->markAsRenderedOnce('90e10eab-b1e5-4c51-93b8-150075fd2f6d'); ?>
<script>
    window.openModal = function(id) {
        const modal = document.getElementById(id);
        const content = document.getElementById('content-' + id);

        if (!modal || !content) return;

        const confirmBtn = modal.querySelector('.btn-confirm-modal');
        if (confirmBtn) {
            if (confirmBtn.disabled || confirmBtn.style.pointerEvents === 'none') {
                confirmBtn.disabled = false;
                confirmBtn.style.pointerEvents = '';
                confirmBtn.classList.remove('cursor-wait');
                confirmBtn.classList.add('hover:bg-[#4fb802]', 'active:scale-95');
                if (confirmBtn.hasAttribute('data-original-text')) {
                    confirmBtn.innerHTML = confirmBtn.getAttribute('data-original-text');
                }
            } else {
                confirmBtn.setAttribute('data-original-text', confirmBtn.innerHTML);
            }
        }

        document.body.style.overflow = 'hidden';
        modal.classList.remove('hidden');
        modal.classList.add('flex');

        setTimeout(() => {
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    window.closeModal = function(id) {
        const modal = document.getElementById(id);
        const content = document.getElementById('content-' + id);

        if (!modal || !content) return;

        content.classList.remove('scale-100', 'opacity-100');
        content.classList.add('scale-95', 'opacity-0');

        setTimeout(() => {
            modal.classList.remove('flex');
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }, 300);
    }

    document.addEventListener('DOMContentLoaded', () => {
        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('modal-overlay')) {
                const modalId = e.target.getAttribute('data-modal-id');
                window.closeModal(modalId);
            }

            const confirmBtn = e.target.closest('.btn-confirm-modal');
            if (confirmBtn) {
                if (confirmBtn.style.pointerEvents === 'none' || confirmBtn.disabled) {
                    e.preventDefault();
                    e.stopImmediatePropagation();
                    return;
                }

                const btnText = confirmBtn.textContent.trim().toLowerCase();
                const triggerTexts = ['iya', 'ya', 'hapus', 'lengkapi', 'setuju', 'proses', 'tambah', 'simpan', 'update', 'kirim'];
                if (triggerTexts.includes(btnText)) {
                    if (!confirmBtn.hasAttribute('data-original-text')) {
                        confirmBtn.setAttribute('data-original-text', confirmBtn.innerHTML);
                    }

                    confirmBtn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin mr-2"></i> Memproses';
                    confirmBtn.classList.add('cursor-wait');
                    confirmBtn.classList.remove('hover:bg-[#4fb802]', 'active:scale-95');
                    confirmBtn.style.pointerEvents = 'none';
                    setTimeout(() => {
                        confirmBtn.disabled = true;
                    }, 10);
                }
            }
        });
    });
</script>
<?php endif; ?>
<?php /**PATH D:\project\Agris\resources\views/components/modal.blade.php ENDPATH**/ ?>