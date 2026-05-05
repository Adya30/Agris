@extends('layouts.admin')

@section('title', 'Detail Kemitraan - AGRIS')

@section('content')
<div class="max-w-5xl mx-auto pt-4 pb-12 px-4">
    <div class="mb-8 flex items-center justify-between">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.kemitraan.index') }}" class="w-10 h-10 flex items-center justify-center rounded-full bg-white border border-gray-200 text-gray-600 hover:bg-gray-50 transition shadow-sm">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Detail Kemitraan Agen</h1>
                <p class="text-gray-500 text-sm">Validasi dokumen MOU untuk mengaktifkan status mitra.</p>
            </div>
        </div>

        @if($kemitraan->statusPengajuan == 'Aktif')
        <form id="formHentikan" action="{{ route('admin.kemitraan.action', $kemitraan->id) }}" method="POST">
            @csrf
            <input type="hidden" name="action" value="hentikan">
            <button type="button" onclick="triggerModal('modalHentikan')" class="px-6 py-2.5 bg-red-50 text-red-600 font-bold rounded-xl border border-red-100 hover:bg-red-600 hover:text-white transition-all flex items-center gap-2">
                <i class="fa-solid fa-ban"></i> Nonaktifkan Mitra
            </button>
        </form>
        @endif
    </div>

    @php
        $steps = [
            'Menunggu Upload MOU' => 1,
            'Menunggu Verifikasi MOU' => 2,
            'Aktif' => 3
        ];
        $currentStep = $steps[$kemitraan->statusPengajuan] ?? 1;
        $isFailed = $kemitraan->statusPengajuan == 'Ditolak';
    @endphp

    <div class="bg-white rounded-3xl border border-gray-100 p-8 shadow-sm mb-8">
        <div class="relative flex items-center justify-between mb-16 px-4">
            <div class="absolute left-0 top-1/2 -translate-y-1/2 w-full h-1 bg-gray-100 z-0"></div>
            <div class="absolute left-0 top-1/2 -translate-y-1/2 h-1 {{ $isFailed ? 'bg-red-500' : 'bg-[#58CC02]' }} transition-all duration-500 z-0" style="width: {{ $isFailed ? '100' : ($currentStep - 1) * 50 }}%"></div>

            @foreach(['Upload MOU', 'Verifikasi Dokumen', 'Selesai'] as $index => $label)
            <div class="relative z-10 flex flex-col items-center">
                <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold transition-all {{ $isFailed ? 'bg-red-500 text-white' : ($currentStep > $index ? 'bg-[#58CC02] text-white' : ($currentStep == $index + 1 ? 'bg-white border-4 border-[#58CC02] text-[#58CC02]' : 'bg-white border-4 border-gray-100 text-gray-300')) }}">
                    @if($isFailed)
                        <i class="fa-solid fa-xmark text-xs"></i>
                    @elseif($currentStep > $index + 1)
                        <i class="fa-solid fa-check text-xs"></i>
                    @else
                        <span class="text-xs">{{ $index + 1 }}</span>
                    @endif
                </div>
                <span class="absolute top-12 text-[10px] font-black uppercase tracking-widest whitespace-nowrap {{ $isFailed ? 'text-red-500' : ($currentStep >= $index + 1 ? 'text-[#58CC02]' : 'text-gray-400') }}">{{ $label }}</span>
            </div>
            @endforeach
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-6">
                <div>
                    <h3 class="text-xs font-black text-gray-400 uppercase tracking-[0.2em] mb-4">Profil Agen</h3>
                    <div class="flex flex-col md:flex-row gap-6 p-6 bg-gray-50 rounded-3xl border border-gray-100">
                        <img src="{{ $kemitraan->user->fotoProfil ?? 'https://ui-avatars.com/api/?name='.urlencode($kemitraan->user->namaLengkap).'&background=58CC02&color=fff' }}" class="w-24 h-24 rounded-2xl object-cover shadow-sm">
                        <div class="space-y-3 flex-1">
                            <div>
                                <p class="text-[10px] font-bold text-gray-400 uppercase">Nama Lengkap</p>
                                <p class="font-bold text-gray-800 text-lg">{{ $kemitraan->user->namaLengkap }}</p>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-[10px] font-bold text-gray-400 uppercase">Email</p>
                                    <p class="text-sm font-medium text-gray-700">{{ $kemitraan->user->email }}</p>
                                </div>
                                <div>
                                    <p class="text-[10px] font-bold text-gray-400 uppercase">WhatsApp</p>
                                    <p class="text-sm font-medium text-gray-700">{{ $kemitraan->user->noTelp ?? '-' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="p-6 bg-white border border-gray-100 rounded-3xl">
                    <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                        <i class="fa-solid fa-map-marked-alt text-[#58CC02]"></i> Informasi Alamat
                    </h4>
                    <p class="text-sm text-gray-600 leading-relaxed italic">
                        "{{ $kemitraan->user->detailAlamat ?? 'Alamat tidak tersedia.' }}"
                    </p>
                    <div class="mt-6 pt-6 border-t border-gray-50 flex justify-between items-center">
                        <div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase mb-1">Tanggal Pengajuan</p>
                            <p class="text-sm font-bold text-gray-800">{{ $kemitraan->tanggalPengajuan->format('d F Y, H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div>
                    <h3 class="text-xs font-black text-gray-400 uppercase tracking-[0.2em] mb-4">Verifikasi Dokumen</h3>
                    <div class="bg-gray-50 p-6 rounded-3xl border border-gray-100">
                        <div class="p-6 bg-white rounded-2xl border border-gray-200 border-dashed text-center mb-6">
                            @if($kemitraan->fileKemitraan)
                                <i class="fa-solid fa-file-pdf text-5xl text-red-500 mb-3"></i>
                                <p class="text-xs font-bold text-gray-800 mb-4 uppercase">File Dokumen MOU</p>
                                <a href="data:application/pdf;base64,{{ $kemitraan->fileKemitraan }}" target="_blank" class="w-full inline-flex justify-center items-center px-4 py-2.5 bg-gray-800 text-white text-xs font-bold rounded-xl hover:bg-black transition-all">
                                    <i class="fa-solid fa-eye mr-2"></i> Preview PDF
                                </a>
                            @else
                                <i class="fa-solid fa-file-circle-question text-5xl text-gray-200 mb-3"></i>
                                <p class="text-xs font-medium text-gray-400 italic">File tidak tersedia</p>
                            @endif
                        </div>

                        <div class="space-y-3">
                            @if($kemitraan->statusPengajuan == 'Menunggu Verifikasi MOU')
                                <form id="formAktifkan" action="{{ route('admin.kemitraan.verifyMou', $kemitraan->id) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="status" value="Aktif">
                                    <button type="button" onclick="triggerModal('modalAktifkan')" class="w-full py-3.5 bg-[#58CC02] text-white font-bold rounded-xl shadow-lg shadow-green-100 hover:bg-[#46a302] transition-all uppercase text-xs tracking-widest">Terima Dokumen</button>
                                </form>
                                <form id="formTolakMou" action="{{ route('admin.kemitraan.verifyMou', $kemitraan->id) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="status" value="Ditolak">
                                    <button type="button" onclick="triggerModal('modalTolakMou')" class="w-full py-3.5 bg-white text-red-600 border border-red-100 font-bold rounded-xl hover:bg-red-50 transition-all uppercase text-xs tracking-widest">Tolak Dokumen</button>
                                </form>
                            @endif

                            @if($kemitraan->statusPengajuan == 'Aktif')
                                <div class="p-4 bg-green-50 border border-green-100 rounded-2xl text-center">
                                    <p class="text-xs font-black text-[#58CC02] uppercase tracking-widest">Kemitraan Aktif</p>
                                </div>
                            @endif

                            @if($kemitraan->statusPengajuan == 'Ditolak')
                                <div class="p-4 bg-red-50 border border-red-100 rounded-2xl text-center">
                                    <p class="text-xs font-black text-red-600 uppercase tracking-widest">Pengajuan Ditolak</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<x-modal id="modalHentikan" message="Hentikan kemitraan agen ini? User akan dinonaktifkan." confirmText="Hentikan" cancelText="Batal" confirmId="btnConfirmHentikan" cancelId="btnCancelHentikan" />
<x-modal id="modalAktifkan" message="Aktifkan status kemitraan agen berdasarkan dokumen MOU ini?" confirmText="Aktifkan" cancelText="Batal" confirmId="btnConfirmAktif" cancelId="btnCancelAktif" />
<x-modal id="modalTolakMou" message="Tolak dokumen MOU? Agen harus memulai pengajuan dari awal." confirmText="Tolak" cancelText="Batal" confirmId="btnConfirmTolakMou" cancelId="btnCancelTolakMou" />

<script>
    function triggerModal(id) {
        const modal = document.getElementById(id);
        if (typeof openModal === 'function') { openModal(id); }
        else { modal.classList.remove('hidden'); modal.classList.add('flex'); }
    }

    function closeModalManual(id) {
        const modal = document.getElementById(id);
        if (typeof closeModal === 'function') { closeModal(id); }
        else { modal.classList.add('hidden'); modal.classList.remove('flex'); }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const setups = [
            { btn: 'btnConfirmHentikan', cancel: 'btnCancelHentikan', modal: 'modalHentikan', form: 'formHentikan' },
            { btn: 'btnConfirmAktif', cancel: 'btnCancelAktif', modal: 'modalAktifkan', form: 'formAktifkan' },
            { btn: 'btnConfirmTolakMou', cancel: 'btnCancelTolakMou', modal: 'modalTolakMou', form: 'formTolakMou' }
        ];

        setups.forEach(setup => {
            document.getElementById(setup.btn)?.addEventListener('click', () => document.getElementById(setup.form).submit());
            document.getElementById(setup.cancel)?.addEventListener('click', () => closeModalManual(setup.modal));
        });
    });
</script>
@endsection
