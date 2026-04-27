@props([
    'id' => 'confirmModal',
    'title' => 'Konfirmasi',
    'message' => 'Apakah Anda yakin?',
    'confirmText' => 'Iya',
    'cancelText' => 'Batal',
    'confirmId' => 'submitForm',
    'cancelId' => 'closeModal'
])

<div id="{{ $id }}" class="hidden fixed inset-0 z-[100] flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>

    <div class="relative bg-white rounded-[2.5rem] p-10 max-w-sm w-full shadow-2xl text-center border border-gray-100 transition-all transform">
        <div class="w-20 h-20 bg-green-100 text-[#58CC02] rounded-full flex items-center justify-center mx-auto mb-6 text-4xl">
            <i class="fa-solid fa-question"></i>
        </div>

        <h3 class="text-2xl font-black text-gray-800 mb-2">{{ $title }}</h3>
        <p class="text-gray-500 font-medium mb-8">{{ $message }}</p>

        <div class="flex gap-3">
            <button type="button" id="{{ $cancelId }}"
                class="flex-1 py-4 bg-red-500 hover:bg-red-400 text-white font-bold rounded-2xl transition">
                {{ $cancelText }}
            </button>
            <button type="button" id="{{ $confirmId }}"
                class="flex-1 py-4 bg-[#58CC02] hover:bg-[#4fb802] text-white font-bold rounded-2xl transition shadow-lg shadow-green-100">
                {{ $confirmText }}
            </button>
        </div>
    </div>
</div>
