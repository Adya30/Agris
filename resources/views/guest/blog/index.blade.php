@extends('layouts.app')

@section('title', 'Wawasan Pertanian - AGRIS')

@section('content')
<x-navbar/>

<section class="relative pt-32 pb-20 px-6 overflow-hidden">
    <div class="absolute inset-0 z-0">
        <img src="{{ asset('images/hero.jpg') }}" class="w-full h-full object-cover" alt="Background">
        <div class="absolute inset-0 bg-black/60 backdrop-blur-[2px]"></div>
    </div>

    <div class="relative z-10 max-w-7xl mx-auto text-center">
        <span class="text-[#58CC02] font-bold tracking-widest uppercase text-sm mb-4 block">
            Blog
        </span>
        <h1 class="text-4xl md:text-5xl font-extrabold text-white mb-6 leading-tight">
            Informasi <br> Seputar Pertanian
        </h1>
        <p class="text-gray-200 max-w-2xl mx-auto text-lg leading-relaxed">
           Informasi seputar pertanian PT Surya Kencana Agrifarm Sejahtera
        </p>
    </div>
</section>

<section class="relative pt-10 pb-20 bg-gray-50 px-6">

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($blogs as $blog)
            <div class="group bg-white rounded-4xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-500 border border-gray-100 flex flex-col">
                <div class="relative h-64 overflow-hidden">
                    @if($blog->fotoBlog)
                        <img src="{{ asset('storage/' . $blog->fotoBlog) }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                    @else
                        <div class="w-full h-full bg-green-50 flex items-center justify-center text-green-200">
                            <i class="fa-solid fa-image text-5xl"></i>
                        </div>
                    @endif
                    <div class="absolute top-6 left-6">
                        <span class="px-4 py-2 bg-white/90 backdrop-blur-md text-slate-900 text-xs font-bold rounded-xl shadow-sm">
                            {{ $blog->tanggalBlog->format('d M Y') }}
                        </span>
                    </div>
                </div>

                <div class="p-8 flex flex-col flex-1">
                    <h3 class="text-xl font-bold text-slate-900 mb-4 leading-tight group-hover:text-green-600 transition-colors">
                        {{ $blog->judulBlog }}
                    </h3>

                    <div class="text-gray-500 text-base leading-relaxed line-clamp-3 mb-10">
                        {{ Str::limit(strip_tags($blog->isiBlog), 90, '...') }}
                    </div>

                    <div class="mt-auto pt-6 flex items-center justify-between border-t border-gray-50">
                        <div class="flex items-center gap-3">
                            <img src="{{ $blog->user->fotoProfil ?? 'https://ui-avatars.com/api/?name='.urlencode($blog->user->username ?? 'Admin').'&background=f0fdf4&color=166534' }}" class="h-10 w-10 rounded-full object-cover border border-gray-100">
                            <span class="text-sm font-bold text-slate-700">{{ $blog->user->username ?? 'Admin' }}</span>
                        </div>
                        <a href="{{ route('guest.blog.show', $blog->id) }}" class="w-10 h-10 rounded-xl bg-gray-50 flex items-center justify-center text-slate-400 group-hover:bg-green-600 group-hover:text-white transition-all">
                            <i class="fa-solid fa-arrow-right text-xs"></i>
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full py-24 text-center bg-white rounded-[40px] border-2 border-dashed border-gray-200">
                <i class="fa-solid fa-box-open text-6xl text-gray-200 mb-6"></i>
                <p class="text-gray-400 text-lg font-bold">Belum ada artikel yang diterbitkan.</p>
            </div>
            @endforelse
        </div>

        <div class="mt-16">
            {{ $blogs->links() }}
        </div>
    </div>
</section>

<x-footer/>
@endsection
