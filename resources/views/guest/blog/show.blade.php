@extends('layouts.app')

@section('title', $blog->judulBlog . ' - AGRIS')

@section('content')
<x-navbar/>

<section class="relative pt-32 pb-24 bg-white px-6">
    <div class="max-w-4xl mx-auto">
        <a href="{{ route('guest.blog.index') }}" class="inline-flex items-center gap-3 text-gray-400 hover:text-green-600 font-bold mb-10 transition-colors group">
            <div class="w-10 h-10 rounded-xl border border-gray-100 flex items-center justify-center group-hover:border-green-600 transition-all">
                <i class="fa-solid fa-arrow-left text-xs"></i>
            </div>
            Kembali ke Blog
        </a>

        <div class="mb-12">
            <span class="text-green-600 font-black tracking-widest uppercase text-xs mb-4 block">Artikel Detail</span>
            <h1 class="text-3xl md:text-5xl font-bold text-slate-900 leading-tight mb-8">{{ $blog->judulBlog }}</h1>

            <div class="flex items-center gap-6 p-6 bg-gray-50 rounded-3xl border border-gray-100">
                <div class="flex items-center gap-3">
                    <img src="{{ $blog->user->fotoProfil ?? 'https://ui-avatars.com/api/?name='.urlencode($blog->user->username ?? 'Admin').'&background=ffffff&color=166534' }}" class="h-12 w-12 rounded-full object-cover border-2 border-white shadow-sm">
                    <div>
                        <p class="text-sm font-black text-slate-900 leading-none mb-1">{{ $blog->user->name ?? 'Admin Agris' }}</p>
                        <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest">Penulis Artikel</p>
                    </div>
                </div>
                <div class="h-8 w-px bg-gray-200 hidden sm:block"></div>
                <div class="hidden sm:block">
                    <p class="text-sm font-black text-slate-900 leading-none mb-1">{{ $blog->tanggalBlog->format('d F Y') }}</p>
                    <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest">Tanggal Terbit</p>
                </div>
            </div>
        </div>

        @if($blog->fotoBlog)
        <div class="relative w-full h-75 md:h-125 rounded-[40px] overflow-hidden mb-12 shadow-2xl">
            <img src="{{ asset('storage/' . $blog->fotoBlog) }}" class="w-full h-full object-cover">
        </div>
        @endif

        <div class="prose prose-lg prose-slate max-w-none">
            <div class="text-gray-600 leading-relaxed text-lg">
                @php
                    $urlPattern = '/(https?:\/\/[^\s]+)/';
                    $contentWithLinks = preg_replace(
                        $urlPattern,
                        '<a href="$1" target="_blank" class="text-green-600 hover:text-green-700 font-bold decoration-2 underline-offset-4 transition-all">$1</a>',
                        e($blog->isiBlog)
                    );
                @endphp
                {!! nl2br($contentWithLinks) !!}
            </div>
        </div>

        <div class="mt-16 pt-10 border-t border-gray-100">
            <div class="bg-slate-900 rounded-4xl p-8 md:p-12 text-center">
                <h3 class="text-2xl font-bold text-white mb-4">Butuh Benih Berkualitas?</h3>
                <p class="text-gray-400 mb-8 max-w-lg mx-auto">Bergabunglah bersama ribuan mitra AGRIS lainnya untuk mendapatkan hasil panen yang maksimal.</p>
                <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-8 py-4 bg-green-600 text-white font-bold rounded-2xl hover:bg-green-500 transition-all shadow-lg shadow-green-900/20">
                    Daftar Sekarang
                </a>
            </div>
        </div>
    </div>
</section>

<x-footer/>
@endsection
