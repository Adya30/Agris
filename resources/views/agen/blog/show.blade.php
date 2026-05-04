@extends('layouts.agen')

@section('title', 'Detail Blog - AGRIS')

@section('content')
<div class="max-w-4xl mx-auto pt-4 pb-12">
    <div class="mb-10 flex items-center justify-between">
        <a href="{{ route('agen.blog.index') }}" class="w-12 h-12 rounded-2xl border border-gray-100 flex items-center justify-center text-gray-400 hover:text-[#58CC02] bg-white shadow-sm transition-all">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
    </div>

    <div class="bg-white rounded-[48px] border border-gray-100 shadow-sm overflow-hidden">
        @if($blog->fotoBlog)
            <div class="w-full h-100 overflow-hidden">
                <img src="{{ $blog->fotoBlog }}" class="w-full h-full object-cover">
            </div>
        @endif

        <div class="p-12">
            <div class="flex items-center gap-4 mb-8">
                <div class="w-12 h-12 rounded-full bg-[#0f8629] flex items-center justify-center text-white font-bold">
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
                        '<a href="$1" target="_blank" class="text-[#58CC02] hover:underline font-bold transition-all">$1</a>',
                        e($blog->isiBlog)
                    );
                @endphp
                {!! nl2br($contentWithLinks) !!}
            </div>
        </div>
    </div>
</div>
@endsection
