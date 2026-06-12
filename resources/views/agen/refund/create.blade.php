@extends('layouts.agen')

@section('title', 'Pengajuan Refund - AGRIS')

@section('content')
<div class="max-w-3xl mx-auto pb-16 px-4 sm:px-6 pt-5">

    {{-- Header --}}
    <div class="mb-8">
        <a href="{{ route('agen.pesanan.show', $pesanan->id) }}"
           class="text-xs font-bold text-gray-400 hover:text-gray-600 flex items-center gap-1.5 mb-3 uppercase tracking-wider transition-colors">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Detail Pesanan
        </a>
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-2xl bg-red-50 flex items-center justify-center text-red-500 shrink-0">
                <i class="fa-solid fa-rotate-left text-base"></i>
            </div>
            <div>
                <h1 class="text-xl font-extrabold text-gray-900 tracking-tight">Pengajuan Refund</h1>
                <p class="text-xs text-gray-400 font-semibold mt-0.5">Pesanan #{{ strtoupper(substr($pesanan->id, -8)) }}</p>
            </div>
        </div>
    </div>

    {{-- Validation Errors --}}
    @if($errors->any())
        <div class="mb-6 bg-red-50 border border-red-200 rounded-2xl p-4 flex items-start gap-3">
            <i class="fa-solid fa-circle-exclamation text-red-500 mt-0.5 shrink-0"></i>
            <div>
                <p class="text-xs font-black text-red-700 mb-1">Terdapat kesalahan pada form:</p>
                <ul class="text-xs text-red-600 font-semibold space-y-0.5 list-disc list-inside">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-5 gap-6">

        {{-- Left: Form --}}
        <div class="md:col-span-3 space-y-5">

            {{-- Product Card --}}
            <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-5">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">Produk yang Direfund</p>
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 bg-gray-50 rounded-2xl overflow-hidden border border-gray-100 flex items-center justify-center p-1 shrink-0">
                        @if($detail->produk && $detail->produk->fotoProduk)
                            <img src="{{ asset('storage/' . $detail->produk->fotoProduk) }}" class="w-full h-full object-cover rounded-xl">
                        @else
                            <i class="fa-solid fa-image text-2xl text-gray-300"></i>
                        @endif
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="font-extrabold text-gray-900 text-sm leading-snug">{{ $detail->produk->namaProduk ?? 'Produk Telah Dihapus' }}</p>
                        <p class="text-xs text-gray-400 font-semibold mt-1">
                            {{ $detail->jumlahPesanan }} unit &times;
                            Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}
                        </p>
                        <div class="mt-2 flex items-center gap-2 flex-wrap">
                            <span class="bg-red-50 text-red-600 border border-red-100 px-2.5 py-1 rounded-xl text-[10px] font-black">
                                Harga/unit: Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}
                            </span>
                            <span class="text-[10px] font-bold text-gray-400">Maks. {{ $maxQty }} unit</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Form --}}
            <form id="refundForm" action="{{ route('agen.refund.store') }}" method="POST" enctype="multipart/form-data"
                  class="bg-white rounded-3xl border border-gray-100 shadow-sm p-5 md:p-6 space-y-5">
                @csrf
                <input type="hidden" name="pesananId" value="{{ $pesanan->id }}">
                <input type="hidden" name="detailPesananId" value="{{ $detail->id }}">

                {{-- Jumlah & Nominal --}}
                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label for="jumlah" class="block text-[10px] font-black text-gray-400 uppercase tracking-widest">
                            Jumlah Refund <span class="text-red-400">*</span>
                        </label>
                        <input type="number" name="jumlah" id="jumlah"
                               min="1" max="{{ $maxQty }}"
                               value="{{ old('jumlah', 1) }}"
                               required
                               class="w-full bg-gray-50 border border-gray-200 rounded-2xl py-3 px-4 text-sm font-bold text-gray-800 focus:outline-none focus:bg-white focus:border-red-400 transition duration-200 @error('jumlah') border-red-400 bg-red-50 @enderror">
                        <span class="text-[9px] text-gray-400 font-bold block">Maks. {{ $maxQty }} unit</span>
                        @error('jumlah')
                            <p class="text-[10px] text-red-500 font-bold">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-1.5">
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest">
                            Nominal Refund
                        </label>
                        <div class="relative">
                            <span class="absolute left-4 top-3.5 text-sm font-bold text-gray-400">Rp</span>
                            <input type="text" id="nominalVisual" readonly
                                   value="{{ number_format($detail->harga_satuan, 0, ',', '.') }}"
                                   class="w-full bg-red-50 border border-red-100 rounded-2xl py-3 pl-9 pr-4 text-sm font-black text-red-700 focus:outline-none cursor-not-allowed">
                        </div>
                        <span id="nominalHint" class="text-[9px] text-gray-400 font-bold block">
                            Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }} &times; 1 unit
                        </span>
                    </div>
                </div>

                {{-- Alasan --}}
                <div class="space-y-1.5">
                    <label for="alasan" class="block text-[10px] font-black text-gray-400 uppercase tracking-widest">
                        Alasan Refund <span class="text-red-400">*</span>
                    </label>
                    <textarea name="alasan" id="alasan" rows="4" required
                              placeholder="Jelaskan secara detail masalah yang terjadi pada produk (misalnya: barang rusak, tidak sesuai deskripsi, dsb.)..."
                              class="w-full bg-gray-50 border border-gray-200 rounded-2xl py-3 px-4 text-xs font-semibold text-gray-700 focus:outline-none focus:bg-white focus:border-red-400 transition duration-200 resize-none leading-relaxed @error('alasan') border-red-400 bg-red-50 @enderror">{{ old('alasan') }}</textarea>
                    @error('alasan')
                        <p class="text-[10px] text-red-500 font-bold">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Foto Bukti — menggunakan window.previewImage dari upload-handler.js --}}
                <div class="space-y-1.5">
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest">
                        Foto Bukti Barang <span class="text-red-400">*</span>
                    </label>

                    <label for="foto_bukti"
                           id="imageContainer"
                           class="flex flex-col items-center justify-center w-full min-h-40 border-2 border-dashed border-gray-300 rounded-2xl cursor-pointer bg-gray-50 hover:bg-gray-100 transition duration-200 group relative overflow-hidden">

                        {{-- Preview (menggunakan ID yang dikenali upload-handler.js) --}}
                        <img id="previewImg" src="" alt="Preview"
                             class="hidden absolute inset-0 w-full h-full object-cover rounded-2xl">

                        <div id="placeholderIcon" class="flex flex-col items-center justify-center py-8 px-4 text-center">
                            <div class="w-12 h-12 rounded-2xl bg-red-50 flex items-center justify-center mb-3 group-hover:scale-110 transition-transform duration-200">
                                <i class="fa-solid fa-cloud-arrow-up text-red-400 text-lg"></i>
                            </div>
                            <p class="text-xs font-bold text-gray-500">Klik atau seret foto ke sini</p>
                            <p class="text-[10px] text-gray-400 font-semibold mt-1">JPG, PNG, WebP — maks. 5 MB</p>
                        </div>
                    </label>

                    <p id="clientError" class="hidden text-[10px] text-red-500 font-bold mt-1"></p>

                    <input type="file" name="foto_bukti" id="foto_bukti" accept="image/*"
                           onchange="previewImage(this)"
                           class="hidden">

                    {{-- Indikator file terpilih --}}
                    <p id="fileNameDisplay" class="hidden text-[10px] font-bold text-gray-500 mt-1 flex items-center gap-1">
                        <i class="fa-solid fa-image text-red-400"></i>
                        <span id="fileNameText"></span>
                    </p>

                    @error('foto_bukti')
                        <p class="text-[10px] text-red-500 font-bold">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Submit Buttons --}}
                <div class="flex gap-3 pt-1">
                    <a href="{{ route('agen.pesanan.show', $pesanan->id) }}"
                       class="flex-1 py-3.5 border border-gray-200 hover:bg-gray-50 text-gray-600 font-bold rounded-2xl transition duration-200 text-xs text-center cursor-pointer">
                        Batal
                    </a>
                    <button type="button" id="btnOpenConfirm"
                            onclick="validateAndConfirm()"
                            class="flex-1 py-3.5 bg-red-500 hover:bg-red-600 text-white font-black rounded-2xl transition duration-200 text-xs flex items-center justify-center gap-2 shadow-sm cursor-pointer">
                        <i class="fa-solid fa-paper-plane"></i> Kirim Pengajuan
                    </button>
                </div>
            </form>
        </div>

        {{-- Right: Info & History --}}
        <div class="md:col-span-2 space-y-5">

            {{-- Info Card --}}
            <div class="bg-gradient-to-br from-red-50 to-orange-50 border border-red-100 rounded-3xl p-5">
                <div class="flex items-center gap-2 mb-3">
                    <i class="fa-solid fa-circle-info text-red-400 text-sm"></i>
                    <p class="text-[10px] font-black text-red-500 uppercase tracking-widest">Ketentuan Refund</p>
                </div>
                <ul class="text-xs text-red-700 font-semibold space-y-2 leading-relaxed">
                    <li class="flex items-start gap-2">
                        <i class="fa-solid fa-check text-red-400 mt-0.5 shrink-0 text-[10px]"></i>
                        Refund hanya dapat diajukan untuk pesanan yang telah berstatus <strong>Selesai</strong>.
                    </li>
                    <li class="flex items-start gap-2">
                        <i class="fa-solid fa-check text-red-400 mt-0.5 shrink-0 text-[10px]"></i>
                        Jumlah refund berdasarkan jumlah satuan produk yang dibeli (maks. {{ $maxQty }} unit).
                    </li>
                    <li class="flex items-start gap-2">
                        <i class="fa-solid fa-check text-red-400 mt-0.5 shrink-0 text-[10px]"></i>
                        Foto bukti <strong>wajib dilampirkan</strong> — foto harus jelas dan relevan.
                    </li>
                    <li class="flex items-start gap-2">
                        <i class="fa-solid fa-check text-red-400 mt-0.5 shrink-0 text-[10px]"></i>
                        Nominal dihitung otomatis: harga satuan &times; jumlah unit yang direfund.
                    </li>
                    <li class="flex items-start gap-2">
                        <i class="fa-solid fa-check text-red-400 mt-0.5 shrink-0 text-[10px]"></i>
                        Admin akan memproses pengajuan dalam 1–3 hari kerja.
                    </li>
                </ul>
            </div>

            {{-- Ringkasan Pesanan --}}
            <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-5">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">Ringkasan Pesanan</p>
                <div class="space-y-2 text-xs">
                    <div class="flex justify-between">
                        <span class="text-gray-400 font-semibold">No. Pesanan</span>
                        <span class="text-gray-800 font-mono font-bold">#{{ strtoupper(substr($pesanan->id, -8)) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400 font-semibold">Tanggal Pesan</span>
                        <span class="text-gray-700 font-bold">{{ \Carbon\Carbon::parse($pesanan->created_at)->timezone('Asia/Jakarta')->translatedFormat('d M Y') }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-400 font-semibold">Status</span>
                        <span class="bg-green-50 text-green-600 border border-green-100 px-2.5 py-0.5 rounded-full text-[9px] font-black uppercase">Selesai</span>
                    </div>
                    <div class="flex justify-between pt-2 border-t border-gray-50">
                        <span class="text-gray-700 font-extrabold">Total Tagihan</span>
                        <span class="text-[#58CC02] font-black">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            {{-- Riwayat Refund Item Ini --}}
            @if($existingRefunds->isNotEmpty())
            <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-5">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">Riwayat Refund Item Ini</p>
                <div class="space-y-3">
                    @foreach($existingRefunds as $ref)
                        <div class="flex items-center justify-between py-2 border-b border-gray-50 last:border-0">
                            <div>
                                <p class="text-xs font-bold text-gray-700">{{ $ref->jumlah }} unit</p>
                                <p class="text-[10px] text-gray-400 font-semibold">Rp {{ number_format($ref->nominal, 0, ',', '.') }}</p>
                            </div>
                            @if($ref->status === 'pending')
                                <span class="bg-amber-50 text-amber-600 border border-amber-200 px-2 py-0.5 rounded-full text-[9px] uppercase font-black">Pending</span>
                            @elseif($ref->status === 'disetujui')
                                <span class="bg-green-50 text-green-600 border border-green-200 px-2 py-0.5 rounded-full text-[9px] uppercase font-black">Disetujui</span>
                            @else
                                <span class="bg-red-50 text-red-600 border border-red-200 px-2 py-0.5 rounded-full text-[9px] uppercase font-black">Ditolak</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

        </div>
    </div>
</div>

{{-- Modal Konfirmasi (menggunakan komponen modal yang sudah ada) --}}
<x-modal
    id="refundConfirmModal"
    title="Kirim Pengajuan?"
    message="Pastikan foto bukti sudah terlampir dan data sudah benar. Pengajuan tidak dapat diubah setelah dikirim."
    confirmText="Kirim"
    cancelText="Cek Lagi"
    confirmId="btnConfirmSubmit"
    cancelId="btnCancelRefund"
/>

<script>
(function () {
    const pricePerUnit  = {{ $detail->harga_satuan }};
    const maxQty        = {{ $maxQty }};
    const jumlahInput   = document.getElementById('jumlah');
    const nominalVisual = document.getElementById('nominalVisual');

    // ── Nominal auto-update ─────────────────────────────────
    function formatRupiah(val) {
        return new Intl.NumberFormat('id-ID').format(val);
    }

    function updateNominal() {
        let qty = parseInt(jumlahInput.value);
        if (isNaN(qty) || qty < 1) qty = 1;
        if (qty > maxQty) { qty = maxQty; jumlahInput.value = maxQty; }
        const total = qty * pricePerUnit;
        nominalVisual.value = formatRupiah(total);
        // Update hint: harga per item × jumlah
        const hint = document.getElementById('nominalHint');
        if (hint) {
            hint.innerHTML = `Rp ${formatRupiah(pricePerUnit)} &times; ${qty} unit`;
        }
    }

    jumlahInput.addEventListener('input', updateNominal);
    updateNominal();

    // ── Tampilkan nama file yang dipilih ────────────────────
    document.getElementById('foto_bukti').addEventListener('change', function () {
        const display  = document.getElementById('fileNameDisplay');
        const nameText = document.getElementById('fileNameText');
        if (this.files && this.files[0]) {
            nameText.textContent = this.files[0].name;
            display.classList.remove('hidden');
        } else {
            display.classList.add('hidden');
        }
    });

    // ── Validasi sebelum buka modal konfirmasi ──────────────
    window.validateAndConfirm = function () {
        const form      = document.getElementById('refundForm');
        const fotoInput = document.getElementById('foto_bukti');
        const clientErr = document.getElementById('clientError');
        const container = document.getElementById('imageContainer');

        // Reset error state
        clientErr.classList.add('hidden');
        clientErr.innerText = '';
        container.classList.remove('border-red-500');
        container.classList.add('border-gray-300');

        // Validasi foto wajib
        if (!fotoInput.files || fotoInput.files.length === 0) {
            clientErr.innerText = 'Foto bukti barang wajib dilampirkan.';
            clientErr.classList.remove('hidden');
            container.classList.replace('border-gray-300', 'border-red-500');
            container.scrollIntoView({ behavior: 'smooth', block: 'center' });
            return;
        }

        // Validasi form HTML5
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        // Buka modal konfirmasi
        window.openModal('refundConfirmModal');
    };

    // ── Tombol konfirmasi di modal submit form ──────────────
    document.addEventListener('DOMContentLoaded', function () {
        const confirmBtn = document.getElementById('btnConfirmSubmit');
        const cancelBtn  = document.getElementById('btnCancelRefund');

        if (confirmBtn) {
            confirmBtn.addEventListener('click', function () {
                document.getElementById('refundForm').submit();
            });
        }

        if (cancelBtn) {
            cancelBtn.addEventListener('click', function () {
                window.closeModal('refundConfirmModal');
            });
        }
    });
})();
</script>
@endsection
