@extends('layouts.agen')

@section('content')
<div class="max-w-4xl mx-auto pt-4 pb-12 px-4 md:px-0">
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-800">Status Kemitraan Agen</h1>
        <p class="text-gray-500 text-sm">Pantau sejauh mana proses pengajuan kemitraan Anda.</p>
    </div>

    @if(!$kemitraan || $kemitraan->statusPengajuan == 'Ditolak')
    <div class="bg-white rounded-3xl border border-gray-100 p-12 text-center shadow-sm">
        <div class="w-20 h-20 {{ !$kemitraan ? 'bg-green-50 text-[#58CC02]' : 'bg-red-50 text-red-500' }} rounded-full flex items-center justify-center mx-auto mb-6">
            <i class="fa-solid {{ !$kemitraan ? 'fa-handshake' : 'fa-circle-xmark' }} text-3xl"></i>
        </div>

        <h2 class="text-xl font-bold text-gray-800 mb-2">
            {{ !$kemitraan ? 'Mulai Kemitraan' : 'Kemitraan Terhenti / Ditolak' }}
        </h2>

        <p class="text-gray-500 mb-8 max-w-md mx-auto">
            {{ !$kemitraan
                ? 'Bergabunglah menjadi mitra resmi untuk mendapatkan akses penuh ke fitur distribusi produk.'
                : 'Status kemitraan Anda saat ini tidak aktif atau pengajuan sebelumnya ditolak. Silakan ajukan kembali untuk mengaktifkan status agen Anda.' }}
        </p>

        <a href="{{ route('kemitraan.create') }}" class="inline-flex items-center px-8 py-3 {{ !$kemitraan ? 'bg-[#58CC02]' : 'bg-red-500' }} text-white font-bold rounded-2xl hover:opacity-90 transition-all shadow-lg {{ !$kemitraan ? 'shadow-green-100' : 'shadow-red-100' }}">
            {{ !$kemitraan ? 'Isi Form Biodata' : 'Ajukan Kembali Sekarang' }}
        </a>
    </div>
    @else
    @php
        $steps = [
            'diproses' => 1,
            'Menunggu Upload MOU' => 2,
            'Menunggu Verifikasi MOU' => 3,
            'Aktif' => 4
        ];
        $currentStep = $steps[$kemitraan->statusPengajuan] ?? 0;
    @endphp

    <div class="bg-white rounded-3xl border border-gray-100 p-8 shadow-sm mb-6">
        <div class="relative flex items-center justify-between mb-16 px-4">
            <div class="absolute left-0 top-1/2 -translate-y-1/2 w-full h-1 bg-gray-100 z-0"></div>
            <div class="absolute left-0 top-1/2 -translate-y-1/2 h-1 bg-[#58CC02] transition-all duration-500 z-0" style="width: {{ ($currentStep - 1) * 33.33 }}%"></div>

            @foreach(['Biodata', 'Persetujuan', 'Upload MOU', 'Selesai'] as $index => $label)
            <div class="relative z-10 flex flex-col items-center">
                <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold transition-all {{ $currentStep > $index ? 'bg-[#58CC02] text-white' : ($currentStep == $index + 1 ? 'bg-white border-4 border-[#58CC02] text-[#58CC02]' : 'bg-white border-4 border-gray-100 text-gray-300') }}">
                    @if($currentStep > $index + 1)
                        <i class="fa-solid fa-check text-xs"></i>
                    @else
                        <span class="text-xs">{{ $index + 1 }}</span>
                    @endif
                </div>
                <span class="absolute top-12 text-[10px] font-bold uppercase tracking-widest whitespace-nowrap {{ $currentStep >= $index + 1 ? 'text-[#58CC02]' : 'text-gray-400' }}">{{ $label }}</span>
            </div>
            @endforeach
        </div>

        <div class="mt-20 pt-8 border-t border-gray-50">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-center p-6 bg-gray-50 rounded-3xl border border-gray-100">
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] mb-1">Status Pengajuan</p>
                    <div class="flex items-center gap-3">
                        <h3 class="text-xl font-bold text-gray-800">{{ strtoupper($kemitraan->statusPengajuan) }}</h3>
                        @if($kemitraan->statusPengajuan == 'Aktif')
                            <i class="fa-solid fa-circle-check text-[#58CC02] text-xl"></i>
                        @endif
                    </div>
                </div>

                <div class="flex flex-col gap-3">
                    @if($kemitraan->statusPengajuan == 'Menunggu Upload MOU')
                        <a href="{{ asset('templates/template_mou.docx') }}" download class="w-full py-3 bg-white border-2 border-dashed border-gray-200 text-gray-600 text-xs font-bold rounded-xl hover:border-[#58CC02] hover:text-[#58CC02] transition-all flex items-center justify-center gap-2">
                            <i class="fa-solid fa-download"></i> Unduh Template MOU
                        </a>
                        <button type="button" id="btnTriggerFile" class="w-full py-4 bg-blue-600 text-white text-xs font-bold rounded-xl shadow-lg shadow-blue-100 hover:bg-blue-700 transition-all flex items-center justify-center gap-2 uppercase tracking-widest">
                            <i class="fa-solid fa-file-arrow-up text-sm"></i> Unggah Dokumen
                        </button>
                    @elseif($kemitraan->statusPengajuan == 'Menunggu Verifikasi MOU')
                        <div class="flex items-center justify-center gap-3 text-blue-600 font-bold bg-blue-50 py-4 rounded-xl border border-blue-100 text-xs uppercase tracking-widest">
                            <i class="fa-solid fa-spinner fa-spin"></i> Dokumen Sedang Direview
                        </div>
                    @elseif($kemitraan->statusPengajuan == 'Aktif')
                        <div class="flex items-center justify-center gap-3 text-[#58CC02] font-bold bg-green-50 py-4 rounded-xl border border-green-100 text-xs uppercase tracking-widest">
                            <i class="fa-solid fa-shield-check"></i> Kemitraan Aktif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<div id="modalUpload" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm p-4">
    <div class="bg-white rounded-[2.5rem] w-full max-w-md p-10 shadow-2xl transform transition-all scale-95" id="modalContent">
        <div class="text-center mb-8">
            <div class="w-20 h-20 bg-blue-50 text-blue-600 rounded-3xl flex items-center justify-center mx-auto mb-6 rotate-3">
                <i class="fa-solid fa-file-pdf text-3xl"></i>
            </div>
            <h3 class="text-2xl font-bold text-gray-800">Simpan Dokumen?</h3>
            <p class="text-gray-500 text-sm mt-3 leading-relaxed">File Anda akan dikonversi dan disimpan langsung ke dalam sistem database kami secara aman.</p>
        </div>
        <div class="flex flex-col gap-3">
            <button id="btnSubmitUpload" class="w-full py-4 bg-[#58CC02] text-white font-bold rounded-2xl hover:bg-[#46a302] transition-all shadow-lg shadow-green-100 uppercase tracking-widest text-xs">Ya, Kirim Sekarang</button>
            <button id="btnCancelUpload" class="w-full py-4 bg-gray-50 text-gray-400 font-bold rounded-2xl hover:bg-gray-100 transition-all uppercase tracking-widest text-xs text-center">Batal</button>
        </div>
    </div>
</div>

<form id="upload-form" action="{{ $kemitraan ? route('kemitraan.uploadMou', $kemitraan->id) : '#' }}" method="POST" enctype="multipart/form-data" class="hidden">
    @csrf
    <input type="file" name="fileKemitraan" id="inputManualFile" accept=".pdf">
</form>

<script>
    const modal = document.getElementById('modalUpload');
    const modalContent = document.getElementById('modalContent');
    const fileInput = document.getElementById('inputManualFile');
    const triggerBtn = document.getElementById('btnTriggerFile');
    const form = document.getElementById('upload-form');

    function openModal() {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        setTimeout(() => modalContent.classList.remove('scale-95'), 10);
    }

    function closeModal() {
        modalContent.classList.add('scale-95');
        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            fileInput.value = '';
        }, 200);
    }

    triggerBtn?.addEventListener('click', () => fileInput.click());

    fileInput?.addEventListener('change', function() {
        if(this.files.length > 0) {
            const fileName = this.files[0].name;
            if (this.files[0].type !== "application/pdf") {
                alert("Mohon unggah file dalam format PDF.");
                this.value = '';
                return;
            }
            openModal();
        }
    });

    document.getElementById('btnSubmitUpload')?.addEventListener('click', function() {
        this.disabled = true;
        this.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin mr-2"></i> MENGIRIM...';
        form.submit();
    });

    document.getElementById('btnCancelUpload')?.addEventListener('click', closeModal);

    window.onclick = function(event) {
        if (event.target == modal) closeModal();
    }
</script>
@endsection
