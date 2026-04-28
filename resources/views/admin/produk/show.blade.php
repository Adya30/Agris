@extends('layouts.admin')

@section('title', 'Detail Produk - AGRIS')

@section('content')
<div class="max-w-5xl mx-auto pt-4 pb-12">
    <div class="flex items-center justify-between mb-6 px-4 md:px-0">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.produk.index') }}" class="w-10 h-10 flex items-center justify-center rounded-full bg-white border border-gray-200 text-gray-600 hover:bg-gray-50 transition shadow-sm">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <h1 class="text-xl font-bold text-gray-800">Detail Produk</h1>
        </div>

        <span class="{{ $item->stok > 0 ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }} px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wide">
            {{ $item->stok > 0 ? 'Tersedia' : 'Stok Habis' }}
        </span>
    </div>

    <div class="bg-white rounded-3xl border border-gray-200 overflow-hidden mx-4 md:mx-0 shadow-sm">
        <div class="flex flex-col lg:flex-row">
            <div class="lg:w-2/5 bg-gray-50 p-8 flex flex-col items-center justify-center border-b lg:border-b-0 lg:border-r border-gray-100">
                <div class="w-full aspect-square rounded-2xl overflow-hidden shadow-inner bg-white">
                    @if($item->fotoProduk)
                        <img src="{{ asset('storage/'.$item->fotoProduk) }}" class="w-full h-full object-cover">
                    @else
                        <div class="flex flex-col items-center justify-center h-full text-gray-300">
                            <i class="fa-solid fa-image text-6xl mb-2"></i>
                            <p class="text-xs font-medium">Tidak ada foto</p>
                        </div>
                    @endif
                </div>
            </div>

            <div class="lg:w-3/5 p-8 md:p-10">
                <div class="flex gap-2 mb-4">
                    <span class="text-[10px] font-black uppercase text-[#58CC02] bg-[#58CC02]/10 px-3 py-1 rounded-lg">
                        {{ $item->kategori->jenisKategori }}
                    </span>
                    <span class="text-[10px] font-black uppercase text-gray-500 bg-gray-100 px-3 py-1 rounded-lg">
                        Mutu {{ $item->kategori->mutu }}
                    </span>
                </div>

                <h2 class="text-3xl font-bold text-gray-800 mb-2">{{ $item->namaProduk }}</h2>

                <div class="flex items-baseline gap-1 mb-8">
                    <span class="text-3xl font-black text-[#58CC02]">Rp {{ number_format($item->harga, 0, ',', '.') }}</span>
                    <span class="text-gray-400 font-medium">/kg</span>
                </div>

                <div class="grid grid-cols-2 gap-6 mb-8">
                    <div class="p-4 rounded-2xl bg-gray-50 border border-gray-100">
                        <p class="text-[10px] text-gray-400 uppercase font-bold tracking-widest mb-1">Stok Tersedia</p>
                        <p class="text-xl font-black {{ $item->stok > 0 ? 'text-gray-700' : 'text-red-500' }}">
                            {{ $item->stok }} <span class="text-sm font-medium">Kg</span>
                        </p>
                    </div>
                    <div class="p-4 rounded-2xl bg-gray-50 border border-gray-100">
                        <p class="text-[10px] text-gray-400 uppercase font-bold tracking-widest mb-1">Terakhir Update</p>
                        <p class="text-sm font-bold text-gray-700">{{ $item->updated_at->format('d M Y') }}</p>
                    </div>
                </div>

                <div class="mb-8">
                    <h4 class="text-sm font-bold text-gray-800 mb-3 flex items-center gap-2">
                        Deskripsi Produk
                    </h4>
                    <p class="text-gray-600 leading-relaxed text-sm">
                        {{ $item->deskripsi ?? 'Tidak ada deskripsi untuk produk ini.' }}
                    </p>
                </div>

                <div class="flex gap-3">
                    <a href="{{ route('admin.produk.edit', $item->id) }}" class="flex-1 py-3 flex items-center justify-center rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold transition shadow-sm">
                        <i class="fa-solid fa-pen-to-square mr-2"></i> Ubah data produk
                    </a>

                    <button type="button" onclick="confirmDelete()" class="px-6 py-3 flex items-center justify-center rounded-xl bg-red-50 text-red-600 hover:bg-red-600 hover:text-white font-bold transition border border-red-100 shadow-sm">
                        <i class="fa-solid fa-trash-can mr-2"></i> Hapus
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="modalHapus" class="hidden fixed inset-0 z-100 items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="closeDeleteModal()"></div>

    <div class="relative bg-white rounded-[2.5rem] p-10 max-w-sm w-full shadow-2xl text-center border border-gray-100 transition-all transform">
        <div class="w-20 h-20 bg-green-100 text-[#58CC02] rounded-full flex items-center justify-center mx-auto mb-6 text-4xl">
            <i class="fa-solid fa-question"></i>
        </div>

        <h3 class="text-2xl font-black text-gray-800 mb-2">Hapus Produk</h3>
        <p class="text-gray-500 font-medium mb-8">Apakah Anda yakin ingin menghapus produk ini? Stok akan otomatis menjadi 0 dan produk diarsipkan.</p>

        <div class="flex gap-3">
            <button type="button" onclick="closeDeleteModal()"
                class="flex-1 py-4 bg-red-500 hover:bg-red-400 text-white font-bold rounded-2xl transition">
                Batal
            </button>
            <button type="button" onclick="submitDelete(this)"
                class="flex-1 py-4 bg-[#58CC02] hover:bg-[#4fb802] text-white font-bold rounded-2xl transition shadow-lg shadow-green-100">
                Hapus
            </button>
        </div>
    </div>
</div>

<form id="delete-form" action="{{ route('admin.produk.destroy', $item->id) }}" method="POST" class="hidden">
    @csrf
    @method('DELETE')
</form>

<script>
    function confirmDelete() {
        const modal = document.getElementById('modalHapus');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function closeDeleteModal() {
        const modal = document.getElementById('modalHapus');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = 'auto';
    }

    function submitDelete(btn) {
        btn.disabled = true;
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i>';
        document.getElementById('delete-form').submit();
    }
</script>
@endsection
