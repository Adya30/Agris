@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-10 px-6">
    <div class="mb-10">
        <h1 class="text-3xl font-black text-slate-900">Pusat Bantuan</h1>
        <p class="text-gray-500">Hubungi admin untuk konsultasi atau bantuan layanan.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($users as $user)
        <a href="{{ route('chat.show', $user->id) }}" class="group bg-white p-6 rounded-[32px] border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-300 flex items-center gap-4">
            <div class="relative">
                <img src="{{ $user->fotoProfil ?? 'https://ui-avatars.com/api/?name='.urlencode($user->username) }}" class="h-16 w-16 rounded-full object-cover border-2 border-green-500 p-0.5">
                <div class="absolute bottom-0 right-0 w-4 h-4 bg-green-500 border-2 border-white rounded-full"></div>
            </div>
            <div class="flex-1">
                <h3 class="font-bold text-slate-900 group-hover:text-green-600 transition-colors">{{ $user->username }}</h3>
                <p class="text-xs text-gray-400 font-bold uppercase tracking-widest">Admin Agris</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-gray-50 flex items-center justify-center text-gray-300 group-hover:bg-green-600 group-hover:text-white transition-all">
                <i class="fa-solid fa-comment-dots text-sm"></i>
            </div>
        </a>
        @endforeach
    </div>
</div>
@endsection
