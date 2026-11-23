@extends('layouts.agen')

@section('title', 'Upload MOU Kemitraan - AGRIS')

@section('content')
<div class="max-w-4xl mx-auto pt-5 pb-12 px-6">
    @if($kemitraan)
    @php
        $steps = [
            'Menunggu Upload MOU' => 1,
            'Menunggu Verifikasi MOU' => 2,
            'Aktif' => 3
        ];
        $currentStep = $steps[$kemitraan->statusPengajuan] ?? 1;
        $isFailed = $kemitraan->statusPengajuan == 'Ditolak';
    @endphp

    <div class="bg-white rounded-3xl border border-gray-100 p-8 shadow-sm mb-12" data-aos="fade-up">
        <div class="relative flex items-center justify-between mb-12">
            <div class="absolute left-0 top-1/2 -translate-y-1/2 w-full h-1 bg-gray-100 z-0"></div>
            <div class="absolute left-0 top-1/2 -translate-y-1/2 h-1 {{ $isFailed ? 'bg-red-500' : 'bg-[#58CC02]' }} z-0" style="width: {{ $isFailed ? '100' : ($currentStep - 1) * 50 }}%"></div>

            @foreach(['Upload MOU', 'Verifikasi', 'Selesai'] as $index => $label)
            <div class="relative z-10 flex flex-col items-center">
                <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold {{ $currentStep > $index ? 'bg-[#58CC02] text-white' : ($currentStep == $index + 1 ? 'bg-white border-4 border-[#58CC02] text-[#58CC02]' : 'bg-white border-4 border-gray-100 text-gray-300') }}">
                    @if($currentStep > $index + 1)
                        <i class="fa-solid fa-check"></i>
                    @elseif($isFailed && $index + 1 >= $currentStep)
                        <i class="fa-solid fa-xmark text-red-500"></i>
                    @else
                        {{ $index + 1 }}
                    @endif
                </div>
                <span class="absolute top-12 text-[10px] font-bold uppercase tracking-wider whitespace-nowrap {{ $currentStep >= $index + 1 ? 'text-[#58CC02]' : 'text-gray-400' }}">{{ $label }}</span>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <div class="mb-8 text-center" data-aos="fade-up" data-aos-delay="100">
        <h1 class="text-2xl font-bold text-gray-800">Lengkapi Dokumen Kemitraan</h1>
        <p class="text-gray-500">Unggah file MOU dalam format PDF untuk diverifikasi oleh admin.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div class="md:col-span-1" data-aos="fade-right" data-aos-delay="150">
            <div class="bg-white rounded-3xl border border-gray-100 p-6 shadow-sm mb-6">
                <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2 text-sm uppercase tracking-wider">
                    <i class="fa-solid fa-list-check text-[#58CC02]"></i> Ketentuan
                </h3>
                <ul class="space-y-3 text-xs text-gray-500">
                    <li class="flex gap-2">
                        <i class="fa-solid fa-circle-check text-green-500 mt-0.5"></i>
                        <span>File wajib PDF (Maks. 1MB).</span>
                    </li>
                    <li class="flex gap-2">
                        <i class="fa-solid fa-circle-check text-green-500 mt-0.5"></i>
                        <span>Gunakan template yang telah disediakan.</span>
                    </li>
                </ul>
                <div class="mt-6 pt-6 border-t border-gray-50">
                    <a href="https://docs.google.com/document/d/1l4LvWVaSuImZOfhnV_KPClyWzXpBd3Qy9Ask56TmjCI/export?format=docx" download class="flex items-center justify-center gap-2 w-full py-3 bg-gray-50 text-gray-600 text-xs font-bold rounded-xl border border-gray-100 hover:bg-gray-100 transition-all">
                        <i class="fa-solid fa-download"></i> Download Template
                    </a>
                </div>
            </div>
        </div>

        <div class="md:col-span-2" data-aos="fade-left" data-aos-delay="150">
            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-2xl text-red-600 text-sm">
                    <div class="flex items-center gap-2 font-bold mb-1">
                        <i class="fa-solid fa-circle-exclamation"></i>
                        <span>Gagal mengunggah dokumen:</span>
                    </div>
                    <ul class="list-disc list-inside text-xs space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form id="formUploadMou" action="{{ route('kemitraan.store') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-3xl border border-gray-100 p-8 shadow-sm">
                @csrf
                <label for="fileKemitraan" id="dropzone" class="border-2 border-dashed border-gray-200 rounded-3xl p-10 text-center hover:border-[#58CC02] hover:bg-green-50/20 transition-all group relative block cursor-pointer">
                    <input type="file" name="fileKemitraan" id="fileKemitraan" class="sr-only" accept=".pdf,application/pdf" required>

                    <div id="uploadPlaceholder" class="relative z-10">
                        <div class="w-16 h-16 bg-gray-50 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:bg-green-50 transition-colors">
                            <i class="fa-solid fa-file-pdf text-2xl text-gray-400 group-hover:text-[#58CC02]"></i>
                        </div>
                        <p class="text-gray-700 font-bold mb-1">Pilih file PDF</p>
                        <p class="text-gray-400 text-xs mb-3">Klik di sini atau seret file PDF ke area ini</p>
                        <span class="inline-flex items-center gap-1.5 px-4 py-2 bg-[#58CC02]/10 text-[#58CC02] text-xs font-bold rounded-xl group-hover:bg-[#58CC02] group-hover:text-white transition-all">
                            <i class="fa-solid fa-folder-open"></i> Cari Berkas PDF
                        </span>
                    </div>

                    <div id="previewContainer" class="hidden relative z-30">
                        <div class="flex items-center justify-between bg-green-50 p-4 rounded-2xl border border-green-100 mb-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center text-[#58CC02] shadow-sm">
                                    <i class="fa-solid fa-file-lines text-xl"></i>
                                </div>
                                <div class="text-left">
                                    <p id="previewName" class="text-sm font-bold text-gray-800 truncate max-w-xs">document.pdf</p>
                                    <p id="previewSize" class="text-[10px] text-gray-500 font-semibold">0 KB</p>
                                </div>
                            </div>
                            <button type="button" id="btnRemoveFile" class="text-gray-400 hover:text-red-500 transition-colors cursor-pointer border-none bg-transparent p-2">
                                <i class="fa-solid fa-circle-xmark text-xl"></i>
                            </button>
                        </div>
                        <div class="flex items-center justify-center gap-4">
                            <button type="button" id="btnViewPdf" class="inline-flex items-center gap-2 text-[#58CC02] text-xs font-bold hover:underline bg-transparent cursor-pointer border-none">
                                <i class="fa-solid fa-eye"></i> Lihat Isi Dokumen
                            </button>
                            <span class="text-gray-300">|</span>
                            <span class="text-xs text-gray-500 font-medium">Klik untuk mengganti berkas</span>
                        </div>
                    </div>
                </label>

                <div id="errorMessage" class="hidden mt-4 p-4 bg-red-50 border border-red-100 rounded-2xl flex items-center gap-3 text-red-600 text-sm">
                    <i class="fa-solid fa-triangle-exclamation shrink-0"></i>
                    <span id="errorText">Format file tidak didukung. Mohon unggah file dalam format PDF (Maks. 1MB).</span>
                </div>

                <div class="mt-8">
                    <button type="submit" id="btnSubmitDirect" disabled class="w-full py-4 bg-gray-200 text-gray-400 font-black rounded-2xl transition-all cursor-not-allowed border-none flex items-center justify-center gap-2 uppercase tracking-widest text-sm">
                        <i class="fa-solid fa-paper-plane"></i> Kirim Dokumen Kemitraan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const dropzone = document.getElementById('dropzone');
        const fileInput = document.getElementById('fileKemitraan');
        const uploadPlaceholder = document.getElementById('uploadPlaceholder');
        const previewContainer = document.getElementById('previewContainer');
        const errorMessage = document.getElementById('errorMessage');
        const errorText = document.getElementById('errorText');
        const previewName = document.getElementById('previewName');
        const previewSize = document.getElementById('previewSize');
        const btnViewPdf = document.getElementById('btnViewPdf');
        const btnRemove = document.getElementById('btnRemoveFile');
        const form = document.getElementById('formUploadMou');
        const btnSubmit = document.getElementById('btnSubmitDirect');

        let pdfUrl = null;

        function setSubmitState(enabled) {
            if (!btnSubmit) return;
            if (enabled) {
                btnSubmit.disabled = false;
                btnSubmit.className = 'w-full py-4 bg-[#58CC02] text-white font-black rounded-2xl shadow-lg shadow-green-100 uppercase tracking-widest hover:opacity-90 transition-all cursor-pointer border-none flex items-center justify-center gap-2 text-sm';
            } else {
                btnSubmit.disabled = true;
                btnSubmit.className = 'w-full py-4 bg-gray-200 text-gray-400 font-black rounded-2xl transition-all cursor-not-allowed border-none flex items-center justify-center gap-2 uppercase tracking-widest text-sm';
            }
        }

        ['dragenter', 'dragover'].forEach(eventName => {
            dropzone.addEventListener(eventName, (e) => {
                e.preventDefault();
                e.stopPropagation();
                dropzone.classList.add('border-[#58CC02]', 'bg-green-50/40');
            }, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropzone.addEventListener(eventName, (e) => {
                e.preventDefault();
                e.stopPropagation();
                dropzone.classList.remove('border-[#58CC02]', 'bg-green-50/40');
            }, false);
        });

        dropzone.addEventListener('drop', (e) => {
            const dt = e.dataTransfer;
            if (dt && dt.files && dt.files.length > 0) {
                fileInput.files = dt.files;
                handleFileSelect(dt.files[0]);
            }
        });

        fileInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                handleFileSelect(this.files[0]);
            }
        });

        function handleFileSelect(file) {
            const isPdf = file.type === 'application/pdf' || file.name.toLowerCase().endsWith('.pdf');

            if (!isPdf) {
                if (errorText) errorText.innerText = 'Format file tidak didukung. Mohon unggah file dalam format PDF.';
                errorMessage.classList.remove('hidden');
                errorMessage.classList.add('flex');
                resetPreview();
                return;
            }

            if (file.size > 1024 * 1024) {
                if (errorText) errorText.innerText = 'Ukuran file dokumen MOU terlalu besar! Maksimal 1MB.';
                errorMessage.classList.remove('hidden');
                errorMessage.classList.add('flex');
                resetPreview();
                return;
            }

            errorMessage.classList.add('hidden');
            errorMessage.classList.remove('flex');
            previewName.innerText = file.name;
            previewSize.innerText = (file.size / 1024).toFixed(1) + ' KB';

            if (pdfUrl) URL.revokeObjectURL(pdfUrl);
            pdfUrl = URL.createObjectURL(file);

            uploadPlaceholder.classList.add('hidden');
            previewContainer.classList.remove('hidden');
            setSubmitState(true);
        }

        function resetPreview() {
            fileInput.value = '';
            if (pdfUrl) {
                URL.revokeObjectURL(pdfUrl);
                pdfUrl = null;
            }
            uploadPlaceholder.classList.remove('hidden');
            previewContainer.classList.add('hidden');
            setSubmitState(false);
        }

        btnViewPdf.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            if (pdfUrl) window.open(pdfUrl, '_blank');
        });

        btnRemove.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            errorMessage.classList.add('hidden');
            errorMessage.classList.remove('flex');
            resetPreview();
        });

        form.addEventListener('submit', function() {
            if (btnSubmit && !btnSubmit.disabled) {
                btnSubmit.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin mr-2"></i> Mengunggah Dokumen...';
                btnSubmit.classList.add('opacity-75', 'cursor-wait');
                btnSubmit.disabled = true;
            }
        });

        if (window.Echo) {
            window.Echo.channel('kemitraan-status')
                .listen('.KemitraanUpdated', (e) => {
                    if (e.id == "{{ $kemitraan->id ?? '' }}" || (e.userId && e.userId == "{{ Auth::id() }}")) {
                        window.location.reload();
                    }
                });
        }
    });
</script>
@endsection
