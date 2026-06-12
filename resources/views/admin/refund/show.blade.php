@extends('layouts.admin')

@section('title', 'Verifikasi Refund #' . substr($refund->id, 0, 8) . ' - Admin AGRIS')

@section('content')
<div class="max-w-4xl mx-auto pt-5 pb-12 px-4 sm:px-6">
    <div class="mb-8">
        <a href="{{ route('admin.pesanan.index', ['tab' => 'refund']) }}" class="inline-flex items-center gap-1 text-[10px] md:text-xs font-bold text-gray-400 hover:text-gray-600 transition uppercase tracking-wider mb-2">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar Refund
        </a>
        <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900 tracking-tight">Verifikasi Refund</h1>
    </div>

    @if(session('error'))
        <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-xl mb-6 text-xs md:text-sm font-bold flex flex-col gap-3">
            <div class="flex items-center gap-3">
                <i class="fa-solid fa-triangle-exclamation text-lg text-red-500"></i>
                <span>{{ session('error') }}</span>
            </div>
            @if(session('show_force_local'))
                <div class="pt-2 border-t border-red-100 flex items-center justify-between gap-4">
                    <span class="text-red-600/80 font-medium">Anda dapat menyetujui refund secara lokal untuk mencatat pengembalian dana manual.</span>
                    <button type="button" onclick="handleAction('force_local')" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-xl text-xs font-black shadow-sm transition duration-200 cursor-pointer border-none whitespace-nowrap">
                        Setujui Secara Lokal (Manual)
                    </button>
                </div>
            @endif
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white p-6 rounded-3xl shadow-md space-y-6">
                <div class="flex items-center gap-3 pb-4 border-b border-gray-100">
                    <div class="w-8 h-8 rounded-xl bg-green-50 flex items-center justify-center text-[#0f8629]">
                        <i class="fa-solid fa-receipt text-sm"></i>
                    </div>
                    <h2 class="font-extrabold text-gray-800 text-xs md:text-sm uppercase tracking-wider">Detail Pengajuan</h2>
                </div>

                <div class="grid grid-cols-2 gap-4 text-xs font-bold">
                    <div>
                        <span class="text-[9px] text-gray-400 font-black uppercase tracking-wider block">Agen Pengaju</span>
                        <span class="text-sm font-bold text-gray-800 mt-1 block">{{ $refund->pesanan->user->namaLengkap ?? 'Dihapus' }} (@&#8203;{{ $refund->pesanan->user->username ?? 'user' }})</span>
                    </div>
                    <div>
                        <span class="text-[9px] text-gray-400 font-black uppercase tracking-wider block">Tanggal Pengajuan</span>
                        <span class="text-sm font-bold text-gray-800 mt-1 block">{{ \Carbon\Carbon::parse($refund->created_at)->timezone('Asia/Jakarta')->locale('id')->translatedFormat('d F Y H:i') }} WIB</span>
                    </div>
                    <div>
                        <span class="text-[9px] text-gray-400 font-black uppercase tracking-wider block">Nomor Pesanan Terkait</span>
                        <span class="text-sm font-mono text-gray-800 mt-1 block">#{{ substr($refund->pesananId, 0, 8) }}</span>
                    </div>
                    <div>
                        <span class="text-[9px] text-gray-400 font-black uppercase tracking-wider block">Status Pengajuan</span>
                        <div class="mt-1">
                            @if($refund->status === 'pending')
                                <span class="bg-amber-50 text-amber-600 border border-amber-200 px-3 py-1 rounded-full text-[9px] md:text-[10px] uppercase font-black tracking-wider inline-flex items-center gap-1">
                                    <i class="fa-solid fa-clock"></i> PENDING
                                </span>
                            @elseif($refund->status === 'disetujui')
                                <span class="bg-green-50 text-green-600 border border-green-200 px-3 py-1 rounded-full text-[9px] md:text-[10px] uppercase font-black tracking-wider inline-flex items-center gap-1">
                                    <i class="fa-solid fa-check"></i> DISETUJUI
                                </span>
                            @else
                                <span class="bg-red-50 text-red-600 border border-red-200 px-3 py-1 rounded-full text-[9px] md:text-[10px] uppercase font-black tracking-wider inline-flex items-center gap-1">
                                    <i class="fa-solid fa-xmark"></i> DITOLAK
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="border-t border-gray-100 pt-4 space-y-4">
                    <h3 class="font-extrabold text-gray-800 text-xs uppercase tracking-wider text-gray-400">Produk yang Diajukan Refund</h3>
                    <div class="flex items-center gap-4 bg-gray-50 p-4 rounded-2xl border border-gray-100">
                        <div class="grow min-w-0">
                            <h4 class="font-extrabold text-gray-850 text-xs truncate">{{ $refund->detailPesanan->produk->namaProduk ?? 'Produk Telah Dihapus' }}</h4>
                            <p class="text-[10px] text-gray-400 font-bold mt-1">
                                {{ $refund->jumlah }} unit x Rp {{ number_format($refund->detailPesanan->harga_satuan ?? 0, 0, ',', '.') }}
                            </p>
                        </div>
                        <div class="text-right shrink-0">
                            <span class="text-[9px] font-black text-gray-400 block uppercase tracking-wider">Total Refund</span>
                            <span class="font-black text-gray-900 text-base">
                                Rp {{ number_format($refund->nominal, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="border-t border-gray-100 pt-4 space-y-2 text-xs">
                    <span class="text-[9px] text-gray-400 font-black uppercase tracking-wider block">Alasan Dari Agen</span>
                    <p class="text-gray-700 bg-gray-50 p-4 rounded-2xl border border-gray-100 font-semibold leading-relaxed">{{ $refund->alasan }}</p>
                </div>

                @if($refund->catatan_admin)
                    <div class="border-t border-gray-100 pt-4 space-y-2 text-xs">
                        <span class="text-[9px] text-gray-400 font-black uppercase tracking-wider block">Catatan Admin</span>
                        <p class="text-gray-700 bg-gray-50 p-4 rounded-2xl border border-gray-100 font-semibold leading-relaxed {{ $refund->status === 'ditolak' ? 'text-red-600 font-bold border-red-100 bg-red-50/20' : '' }}">{{ $refund->catatan_admin }}</p>
                    </div>
                @endif
            </div>

            @if($refund->status === 'pending')
                <div class="bg-white p-6 rounded-3xl shadow-md space-y-4">
                    <div class="flex items-center gap-3 pb-3 border-b border-gray-100">
                        <div class="w-8 h-8 rounded-xl bg-amber-50 flex items-center justify-center text-amber-600">
                            <i class="fa-solid fa-gavel text-sm"></i>
                        </div>
                        <h2 class="font-extrabold text-gray-800 text-xs md:text-sm uppercase tracking-wider">Tindakan Keputusan</h2>
                    </div>

                    <form id="refundActionForm" action="{{ route('admin.refund.action', $refund->id) }}" method="POST" class="space-y-4">
                        @csrf
                        <input type="hidden" name="action" id="refund_action" value="">
                        
                        <div class="space-y-2 text-xs">
                            <label for="catatan_admin" class="block text-xs font-black text-gray-400 uppercase tracking-wider">Catatan Keputusan (Opsional / Wajib Jika Menolak)</label>
                            <textarea name="catatan_admin" id="catatan_admin" rows="3" placeholder="Masukkan catatan persetujuan atau alasan penolakan..." class="w-full bg-gray-50 border border-gray-200 rounded-2xl py-3 px-4 text-xs font-bold focus:outline-none focus:bg-white focus:border-green-500 transition duration-200"></textarea>
                        </div>

                        <div class="flex gap-4">
                            <button type="button" onclick="handleAction('setuju')" class="flex-1 bg-[#0f8629] hover:bg-[#0c6b20] text-white py-3 rounded-2xl font-black text-xs flex items-center justify-center gap-2 shadow-sm transition duration-200 cursor-pointer border-none">
                                <i class="fa-solid fa-circle-check"></i> Setujui Refund
                            </button>
                            <button type="button" onclick="handleAction('tolak')" class="flex-1 bg-red-600 hover:bg-red-700 text-white py-3 rounded-2xl font-black text-xs flex items-center justify-center gap-2 shadow-sm transition duration-200 cursor-pointer border-none">
                                <i class="fa-solid fa-circle-xmark"></i> Tolak Refund
                            </button>
                        </div>
                    </form>
                </div>
            @endif
        </div>

        <div class="space-y-6">
            <div class="bg-white p-6 rounded-3xl shadow-md space-y-4">
                <div class="flex items-center gap-3 pb-3 border-b border-gray-100">
                    <div class="w-8 h-8 rounded-xl bg-gray-50 flex items-center justify-center text-gray-400">
                        <i class="fa-solid fa-image text-sm"></i>
                    </div>
                    <h2 class="font-extrabold text-gray-800 text-xs md:text-sm uppercase tracking-wider">Foto Bukti Barang</h2>
                </div>

                @if($refund->foto_bukti && $refund->foto_bukti !== 'refunds/cancel_order.png' && file_exists(public_path('storage/' . $refund->foto_bukti)))
                    <div class="rounded-2xl overflow-hidden border border-gray-150">
                        <a href="{{ asset('storage/' . $refund->foto_bukti) }}" target="_blank" class="block hover:opacity-95 transition">
                            <img src="{{ asset('storage/' . $refund->foto_bukti) }}" class="w-full h-auto object-cover">
                        </a>
                    </div>
                    <p class="text-[10px] text-gray-400 text-center font-bold">Klik gambar untuk memperbesar</p>
                @else
                    <p class="text-xs font-bold text-gray-500 bg-gray-50 border border-gray-100 p-4 rounded-2xl italic text-center">
                        Tidak ada foto
                    </p>
                @endif
            </div>
        </div>
    </div>
</div>

<x-modal id="modalSetuju" message="Apakah Anda yakin ingin menyetujui pengajuan refund ini?" confirmText="Iya" cancelText="Batal" confirmId="btnConfirmSetuju" cancelId="btnCancelSetuju" />
<x-modal id="modalTolak" message="Apakah Anda yakin ingin menolak pengajuan refund ini?" confirmText="Iya" cancelText="Batal" confirmId="btnConfirmTolak" cancelId="btnCancelTolak" />
<x-modal id="modalPeringatanCatatan" title="Catatan Wajib Diisi" message="Anda wajib memberikan catatan admin (alasan penolakan) jika ingin menolak pengajuan refund." confirmText="Oke" cancelText="Batal" confirmId="btnDismissPeringatan" cancelId="btnCancelPeringatan" />
<x-modal id="modalForceLocal" message="Apakah Anda yakin ingin menyetujui refund secara lokal saja? (Anda harus mentransfer dana secara manual ke rekening agen)." confirmText="Iya" cancelText="Batal" confirmId="btnConfirmForceLocal" cancelId="btnCancelForceLocal" />

<script>
function triggerModal(id) {
    if (typeof openModal === 'function') {
        openModal(id);
    } else {
        const modal = document.getElementById(id);
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
}

document.addEventListener('DOMContentLoaded', () => {
    // Custom warning modal icon override
    const warningModalContent = document.querySelector('#content-modalPeringatanCatatan');
    if (warningModalContent) {
        const iconContainer = warningModalContent.querySelector('div.w-20.h-20');
        if (iconContainer) {
            iconContainer.classList.remove('bg-green-100', 'text-[#58CC02]');
            iconContainer.classList.add('bg-red-100', 'text-red-500');
            const icon = iconContainer.querySelector('i');
            if (icon) {
                icon.classList.remove('fa-question');
                icon.classList.add('fa-exclamation');
            }
        }
    }

    // Custom force local warning modal icon override to show exclamation warning icon
    const forceLocalModalContent = document.querySelector('#content-modalForceLocal');
    if (forceLocalModalContent) {
        const iconContainer = forceLocalModalContent.querySelector('div.w-20.h-20');
        if (iconContainer) {
            iconContainer.classList.remove('bg-green-100', 'text-[#58CC02]');
            iconContainer.classList.add('bg-amber-100', 'text-amber-500');
            const icon = iconContainer.querySelector('i');
            if (icon) {
                icon.classList.remove('fa-question');
                icon.classList.add('fa-triangle-exclamation');
            }
        }
    }
});

function closeModalManual(id) {
    if (typeof closeModal === 'function') {
        closeModal(id);
    } else {
        const modal = document.getElementById(id);
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
}

function handleAction(actionType) {
    if (actionType === 'tolak') {
        const catatan = document.getElementById('catatan_admin').value.trim();
        if (!catatan) {
            triggerModal('modalPeringatanCatatan');
            return;
        }
    }

    if (actionType === 'force_local') {
        document.getElementById('refund_action').value = 'setuju';
        let forceInput = document.getElementById('force_local_input');
        if (!forceInput) {
            forceInput = document.createElement('input');
            forceInput.type = 'hidden';
            forceInput.name = 'force_local';
            forceInput.id = 'force_local_input';
            document.getElementById('refundActionForm').appendChild(forceInput);
        }
        forceInput.value = '1';
        triggerModal('modalForceLocal');
        return;
    }

    const forceInput = document.getElementById('force_local_input');
    if (forceInput) {
        forceInput.value = '0';
    }
    
    document.getElementById('refund_action').value = actionType;
    triggerModal(actionType === 'setuju' ? 'modalSetuju' : 'modalTolak');
}

document.addEventListener('DOMContentLoaded', function() {
    // Hide cancel button for warning modal
    const cancelBtn = document.getElementById('btnCancelPeringatan');
    if (cancelBtn) {
        cancelBtn.style.display = 'none';
    }

    document.getElementById('btnConfirmSetuju')?.addEventListener('click', () => {
        document.getElementById('refundActionForm').submit();
    });
    document.getElementById('btnCancelSetuju')?.addEventListener('click', () => closeModalManual('modalSetuju'));

    document.getElementById('btnConfirmTolak')?.addEventListener('click', () => {
        document.getElementById('refundActionForm').submit();
    });
    document.getElementById('btnCancelTolak')?.addEventListener('click', () => closeModalManual('modalTolak'));

    document.getElementById('btnDismissPeringatan')?.addEventListener('click', () => {
        closeModalManual('modalPeringatanCatatan');
    });

    document.getElementById('btnConfirmForceLocal')?.addEventListener('click', () => {
        document.getElementById('refundActionForm').submit();
    });
    document.getElementById('btnCancelForceLocal')?.addEventListener('click', () => closeModalManual('modalForceLocal'));
});
</script>
@endsection
