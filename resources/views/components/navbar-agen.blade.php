<nav class="fixed top-0 w-full z-50 shadow-md border-b border-white/10">
    <div class="bg-[#58CC02] py-3 px-6">
        <div class="max-w-7xl mx-auto flex justify-between items-center gap-4">
            <a href="{{ route('landing') }}" class="flex items-center gap-2">
                <img src="{{ asset('images/icon.png') }}" class="w-15 h-auto">
                <span class="text-2xl font-bold text-white uppercase tracking-wider">AGRIS</span>
            </a>

            <div class="flex-1 max-w-xl hidden md:block px-4">
                <form action="{{ route('agen.produk.index') }}" method="GET" class="relative flex items-center bg-[#46A302] rounded-full p-1 shadow-inner border border-white/10 group">
                    <input type="text"
                           name="search"
                           value="{{ request('search') }}"
                           placeholder="Search....."
                           class="w-full bg-white rounded-full py-2 px-5 text-sm text-gray-700 focus:outline-none border-none placeholder-gray-400 transition-all">
                    <button type="submit" class="px-4 text-white hover:scale-110 transition-transform">
                        <i class="fa-solid fa-magnifying-glass text-lg"></i>
                    </button>
                </form>
            </div>

            <div class="flex items-center gap-3 shrink-0">
                <button id="dropdownBtn" class="group flex items-center gap-3 rounded-full bg-green-600/30 p-1.5 pr-4 transition-all hover:bg-green-600/60 focus:outline-none border border-white/10">
                    <div class="h-9 w-9 overflow-hidden rounded-full border-2 border-white shadow-sm pointer-events-none">
                        <img src="{{ auth()->user()->fotoProfil ?? 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->username) }}" class="h-full w-full object-cover">
                    </div>
                    <div class="flex flex-col items-start leading-tight text-white hidden sm:flex pointer-events-none">
                        <span class="text-[11px] font-bold uppercase opacity-80">Profil</span>
                    </div>
                    <i id="dropdownIcon" class="fa-solid fa-chevron-down text-[10px] text-white transition-transform duration-300"></i>
                </button>

                <button id="hamburger" class="md:hidden p-2 text-white hover:bg-white/10 rounded-lg transition">
                    <i class="fa-solid fa-bars text-xl"></i>
                </button>

                <div id="dropdownMenu" class="hidden absolute right-6 top-16 w-52 bg-white rounded-2xl shadow-2xl border border-gray-100 py-2 z-[60]">

                    <a href="{{ route('agen.profile') }}" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 transition font-semibold">
                        <i class="fa-regular fa-id-card mr-3 text-[#58CC02] text-lg"></i> Profil Saya
                    </a>

                    <div class="mx-4 border-t border-gray-100 my-1"></div>

                    <button type="button" onclick="document.getElementById('logoutForm').submit()" class="w-full flex items-center px-4 py-3 text-sm text-red-600 hover:bg-red-50 transition font-bold">
                        <i class="fa-solid fa-right-from-bracket mr-3 text-lg"></i> Logout
                    </button>
                    <form action="{{ route('logout') }}" method="POST" id="logoutForm" class="hidden">@csrf</form>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-[#2D5A01] hidden md:block border-t border-white/5">
        <div class="max-w-7xl mx-auto px-6">
            <div class="flex justify-center items-center gap-10 h-11 text-white/90 text-sm font-bold tracking-wide uppercase">
                <a href="#" class="hover:text-white transition-all py-1 border-b-2 border-transparent hover:border-[#58CC02]">Blog</a>
                <a href="{{ route('agen.produk.index') }}" class="hover:text-white transition-all py-1 border-b-2 {{ Route::is('agen.produk.*') ? 'border-white' : 'border-transparent' }} hover:border-white">Produk</a>
                <a href="#" class="hover:text-white transition-all py-1 border-b-2 border-transparent hover:border-white">Transaksi</a>
                <a href="#" class="hover:text-white transition-all py-1 border-b-2 border-transparent hover:border-white">Kemitraan</a>
                <a href="#" class="hover:text-white transition-all py-1 border-b-2 border-transparent hover:border-white">Chat</a>
                <a href="#" class="hover:text-white transition-all py-1 border-b-2 border-transparent hover:border-white">Konsultasi</a>
            </div>
        </div>
    </div>

    <div id="mobileMenu" class="hidden md:hidden bg-white border-t border-gray-100 shadow-2xl absolute w-full left-0 animate-fade-in-down">
        <div class="px-6 py-4 space-y-2">
            <form action="{{ route('agen.produk.index') }}" method="GET" class="mb-4">
                <div class="relative flex items-center bg-gray-100 rounded-xl px-4 py-2">
                    <i class="fa-solid fa-magnifying-glass text-gray-400 mr-2"></i>
                    <input type="text" name="search" placeholder="Cari produk..." class="bg-transparent w-full focus:outline-none text-sm">
                </div>
            </form>

            <a href="#" class="flex items-center py-3 px-4 rounded-xl hover:bg-gray-50 font-bold text-gray-700">Blog</a>
            <a href="{{ route('agen.produk.index') }}" class="flex items-center py-3 px-4 rounded-xl {{ Route::is('agen.produk.*') ? 'bg-green-50 text-[#58CC02]' : 'hover:bg-gray-50 text-gray-700' }} font-bold">Produk</a>
            <a href="#" class="flex items-center py-3 px-4 rounded-xl hover:bg-gray-50 font-bold text-gray-700">Transaksi</a>
            <a href="#" class="flex items-center py-3 px-4 rounded-xl hover:bg-gray-50 font-bold text-gray-700 text-red-500" onclick="document.getElementById('logoutForm').submit()">Logout</a>
        </div>
    </div>
</nav>

<div class="h-20 md:h-28"></div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const hamburger = document.getElementById('hamburger');
    const mobileMenu = document.getElementById('mobileMenu');
    const dropdownBtn = document.getElementById('dropdownBtn');
    const dropdownMenu = document.getElementById('dropdownMenu');
    const dropdownIcon = document.getElementById('dropdownIcon');

    hamburger.addEventListener('click', function(e) {
        e.stopPropagation();
        mobileMenu.classList.toggle('hidden');
        dropdownMenu.classList.add('hidden');
    });

    dropdownBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        dropdownMenu.classList.toggle('hidden');
        if(dropdownIcon) dropdownIcon.style.transform = dropdownMenu.classList.contains('hidden') ? 'rotate(0deg)' : 'rotate(180deg)';
        mobileMenu.classList.add('hidden');
    });

    document.addEventListener('click', function(e) {
        if (!dropdownBtn.contains(e.target) && !dropdownMenu.contains(e.target)) {
            dropdownMenu.classList.add('hidden');
            if(dropdownIcon) dropdownIcon.style.transform = 'rotate(0deg)';
        }
        if (!hamburger.contains(e.target) && !mobileMenu.contains(e.target)) {
            mobileMenu.classList.add('hidden');
        }
    });
});
</script>
