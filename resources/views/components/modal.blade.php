@props([
    'id' => 'confirmModal',
    'title' => 'Konfirmasi',
    'message' => 'Apakah Anda yakin?',
    'confirmText' => 'Iya',
    'cancelText' => 'Batal',
    'confirmId' => 'submitForm',
    'cancelId' => 'closeModal'
])

<div id="{{ $id }}" class="fixed inset-0 z-300 hidden items-center justify-center p-4 overflow-x-hidden overflow-y-auto outline-none focus:outline-none modal-container">
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm transition-opacity duration-300 modal-overlay" data-modal-id="{{ $id }}"></div>

    <div id="content-{{ $id }}" class="relative bg-white rounded-[2.5rem] p-8 md:p-10 max-w-sm w-full shadow-2xl text-center border border-gray-100 transition-all duration-300 transform scale-95 opacity-0">
        <div class="w-20 h-20 bg-green-100 text-[#58CC02] rounded-full flex items-center justify-center mx-auto mb-6 text-4xl shadow-sm">
            <i class="fa-solid fa-question"></i>
        </div>

        <h3 class="text-2xl font-black text-gray-800 mb-2">{{ $title }}</h3>
        <p class="text-gray-500 font-medium mb-8 leading-relaxed">
            {{ $message }}
        </p>

        <div class="flex gap-3">
            <button type="button" id="{{ $cancelId }}" class="flex-1 py-4 bg-red-500 hover:bg-red-400 text-white font-bold rounded-2xl transition-all active:scale-95 shadow-md">
                {{ $cancelText }}
            </button>

            <button type="button" id="{{ $confirmId }}" class="flex-1 py-4 bg-[#58CC02] hover:bg-[#4fb802] text-white font-bold rounded-2xl transition-all shadow-lg shadow-green-100 active:scale-95">
                {{ $confirmText }}
            </button>
        </div>
    </div>
</div>

@once
<script>
    window.openModal = function(id) {
        const modal = document.getElementById(id);
        const content = document.getElementById('content-' + id);

        if (!modal || !content) return;

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
        });
    });
</script>
@endonce
