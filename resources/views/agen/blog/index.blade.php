@extends('layouts.agen')

@section('content')
<div class="max-w-7xl mx-auto pt-4 pb-12">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-12 gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Blog Kami</h1>
            <p class="text-gray-500 font-medium pt-2">Temukan informasi dan seputar AGRIS</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
        @forelse($blogs as $blog)
        <div class="group relative bg-white rounded-[40px] overflow-hidden shadow-sm">
            <a href="{{ route('agen.blog.show', $blog->id) }}" class="absolute inset-0 z-20"></a>

            <div class="relative h-72 w-full overflow-hidden">
                @if($blog->fotoBlog)
                    <img src="{{ $blog->fotoBlog }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                @else
                    <div class="w-full h-full bg-gray-100 flex items-center justify-center text-gray-300">
                        <i class="fa-solid fa-image text-5xl"></i>
                    </div>
                @endif
            </div>

            <div class="p-10 flex flex-col flex-1">
                <h3 class="text-3xl font-bold text-gray-900 mb-5 leading-tight">
                    {{ $blog->judulBlog }}
                </h3>

                <div class="text-gray-500 text-base leading-relaxed line-clamp-3 mb-10">
                    {{ Str::limit(strip_tags($blog->isiBlog), 150, '...') }}
                </div>

                <div class="mt-auto pt-8 flex items-center justify-between border-t border-gray-50">
                    <div class="flex items-center gap-4">
                        <div class="h-12 w-12 overflow-hidden rounded-full border-2 border-white shadow-sm bg-gray-100">
                            <img src="{{ $blog->user->fotoProfil ?? 'https://ui-avatars.com/api/?name='.urlencode($blog->user->username ?? 'Admin') }}" class="h-full w-full object-cover">
                        </div>
                        <div>
                            <p class="text-base font-bold text-gray-900 leading-none mb-1">{{ $blog->user->username ?? 'Admin' }}</p>
                            <p class="text-xs font-bold text-gray-400">{{ $blog->tanggalBlog->diffForHumans() }}</p>
                        </div>
                    </div>

                    <div class="w-12 h-12 rounded-2xl bg-gray-50 flex items-center justify-center text-gray-300">
                        <i class="fa-solid fa-arrow-right"></i>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full py-24 text-center bg-gray-50 rounded-[50px] border-2 border-dashed border-gray-200">
            <div class="mb-4">
                <i class="fa-solid fa-box-open text-5xl text-gray-200"></i>
            </div>
            <p class="text-gray-400 text-lg font-bold">Belum ada artikel yang tersedia.</p>
        </div>
        @endforelse
    </div>

    <div class="mt-16">
        {{ $blogs->links() }}
    </div>
</div>
@endsection
