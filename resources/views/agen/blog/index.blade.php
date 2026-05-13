@extends('layouts.agen')

@section('title', 'Blog - AGRIS')

@section('content')
<div class="max-w-7xl mx-auto pt-8 pb-12 px-4 sm:px-6 lg:px-8">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 md:mb-12 gap-4">
        <div>
            <h1 class="text-2xl md:text-3xl lg:text-4xl font-bold text-gray-900 tracking-tight">Blog Kami</h1>
            <p class="text-gray-500 text-sm md:text-base font-medium pt-1 md:pt-2">Temukan informasi dan seputar AGRIS</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6 md:gap-8 lg:gap-10">
        @forelse($blogs as $blog)
        <div class="group relative bg-white rounded-3xl md:rounded-[40px] overflow-hidden shadow-sm flex flex-col border border-gray-100 hover:shadow-xl hover:shadow-gray-200/50 transition-all duration-300">
            <a href="{{ route('agen.blog.show', $blog->id) }}" class="absolute inset-0 z-20" aria-label="Baca {{ $blog->judulBlog }}"></a>

            <div class="relative h-48 sm:h-64 md:h-72 w-full overflow-hidden">
                @if($blog->fotoBlog)
                    <img src="{{ $blog->fotoBlog }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                @else
                    <div class="w-full h-full bg-gray-100 flex items-center justify-center text-gray-300">
                        <i class="fa-solid fa-image text-4xl md:text-5xl"></i>
                    </div>
                @endif
                <div class="absolute inset-0 bg-linear-to-t from-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
            </div>

            <div class="p-6 md:p-8 lg:p-10 flex flex-col flex-1">
                <h3 class="text-xl md:text-2xl lg:text-3xl font-bold text-gray-900 mb-3 md:mb-5 leading-tight group-hover:text-[#58CC02] transition-colors duration-300">
                    {{ $blog->judulBlog }}
                </h3>

                <div class="text-gray-500 text-sm md:text-base leading-relaxed line-clamp-2 md:line-clamp-3 mb-6 md:mb-10">
                    {{ Str::limit(strip_tags($blog->isiBlog), 150, '...') }}
                </div>

                <div class="mt-auto pt-6 md:pt-8 flex items-center justify-between border-t border-gray-100">
                    <div class="flex items-center gap-3 md:gap-4">
                        <div class="h-10 w-10 md:h-12 md:w-12 overflow-hidden rounded-full border-2 border-white shadow-sm bg-gray-100">
                            <img src="{{ $blog->user->fotoProfil ?? 'https://ui-avatars.com/api/?name='.urlencode($blog->user->username ?? 'Admin') }}" class="h-full w-full object-cover">
                        </div>
                        <div class="min-w-0">
                            <p class="text-sm md:text-base font-bold text-gray-900 mb-2">Tanggal Upload</p>
                            <p class="text-[10px] md:text-xs font-bold text-gray-400 uppercase tracking-wider">{{ $blog->tanggalBlog->format('d F Y') }}</p>
                        </div>
                    </div>

                    <div class="w-10 h-10 md:w-12 md:h-12 rounded-xl md:rounded-2xl bg-gray-50 flex items-center justify-center text-gray-300 group-hover:bg-[#58CC02] group-hover:text-white transition-all duration-300">
                        <i class="fa-solid fa-arrow-right transition-transform duration-300 group-hover:translate-x-1"></i>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full py-16 md:py-24 text-center bg-gray-50 rounded-[30px] md:rounded-[50px] border-2 border-dashed border-gray-200 px-6">
            <div class="mb-4">
                <i class="fa-solid fa-box-open text-4xl md:text-5xl text-gray-200"></i>
            </div>
            <p class="text-gray-400 text-base md:text-lg font-bold">Belum ada artikel yang tersedia.</p>
        </div>
        @endforelse
    </div>

    <div class="mt-12 md:mt-16 overflow-x-auto">
        {{ $blogs->links() }}
    </div>
</div>
@endsection
