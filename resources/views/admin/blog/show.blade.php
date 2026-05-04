@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto pt-4 pb-12">
    <div class="mb-10 flex items-center justify-between">
        <a href="{{ route('admin.blog.index') }}" class="w-12 h-12 rounded-2xl border border-gray-100 flex items-center justify-center text-gray-400 hover:text-[#58CC02] bg-white shadow-sm transition-all">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <div class="flex gap-3">
            <a href="{{ route('admin.blog.edit', $blog->id) }}" class="bg-blue-500 text-white px-6 py-3 rounded-2xl font-bold flex items-center gap-2 hover:bg-blue-600 transition-all shadow-lg shadow-blue-100">
                <i class="fa-solid fa-pen-to-square"></i> Edit
            </a>
            <button type="button" onclick="openModal('modalHapus')" class="bg-red-500 text-white px-6 py-3 rounded-2xl font-bold flex items-center gap-2 hover:bg-red-600 transition-all shadow-lg shadow-red-100">
                <i class="fa-solid fa-trash"></i> Hapus
            </button>
        </div>
    </div>

    <div class="bg-white rounded-[48px] border border-gray-100 shadow-sm overflow-hidden">
        @if($blog->fotoBlog)
            <div class="w-full h-100 overflow-hidden">
                <img src="{{ $blog->fotoBlog }}" class="w-full h-full object-cover">
            </div>
        @endif

        <div class="p-12">
            <div class="flex items-center gap-4 mb-8">
                <div class="w-12 h-12 rounded-full bg-[#0f8629] flex items-center justify-center text-white font-bold overflow-hidden">
                    <img src="{{ $blog->user->fotoProfil ?? 'https://ui-avatars.com/api/?name='.urlencode($blog->user->username ?? 'Admin') }}" class="h-full w-full object-cover rounded-full">
                </div>
                <div>
                    <h4 class="font-bold text-gray-900">{{ $blog->user->name ?? 'Admin Agris' }}</h4>
                    <p class="text-xs text-gray-400 font-bold uppercase tracking-widest">{{ $blog->tanggalBlog->format('d F Y') }}</p>
                </div>
            </div>

            <h1 class="text-4xl font-bold text-gray-900 leading-tight mb-8">{{ $blog->judulBlog }}</h1>

            <div class="prose prose-lg prose-green max-w-none text-gray-600 leading-relaxed">
                @php
                    $urlPattern = '/(https?:\/\/[^\s]+)/';
                    $contentWithLinks = preg_replace(
                        $urlPattern,
                        '<a href="$1" target="_blank" class="text-blue-400 hover:underline font-bold transition-all">$1</a>',
                        e($blog->isiBlog)
                    );
                @endphp
                {!! nl2br($contentWithLinks) !!}
            </div>
        </div>
    </div>
</div>

<x-modal id="modalHapus" title="Hapus Artikel?" message="Artikel akan dihapus secara permanen dari sistem dan tidak dapat dikembalikan." confirmText="Iya" cancelText="Batal" confirmId="btnConfirmDelete" cancelId="btnCancelDelete" />

<form id="delete-form" action="{{ route('admin.blog.destroy', $blog->id) }}" method="POST" class="hidden">
    @csrf
    @method('DELETE')
</form>

<script>
    document.getElementById('btnConfirmDelete').addEventListener('click', function() {
        this.disabled = true;
        document.getElementById('delete-form').submit();
    });

    document.getElementById('btnCancelDelete').addEventListener('click', function() {
        closeModal('modalHapus');
    });
</script>
@endsection
