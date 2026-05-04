<nav class="fixed top-0 w-full z-50 shadow-md border-b border-white/10">
    <div class="bg-[#0f8629] py-3 px-6">
        <div class="max-w-7xl mx-auto flex justify-between items-center gap-4">
            <a href="{{ route('agen.produk.index') }}" class="flex items-center gap-2">
                <img src="{{ asset('images/icon.png') }}" class="w-15 h-auto" alt="Logo AGRIS">
                <span class="text-2xl font-bold text-white uppercase">AGRIS</span>
            </a>

            <div class="flex-1 max-w-xl hidden md:block px-4">
                <form action="{{ route('agen.produk.index') }}" method="GET" class="relative flex items-center bg-[#46A302] rounded-full p-1 shadow-inner border border-white/10 group">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari produk di AGRIS..." class="w-full bg-white rounded-full py-2 px-5 text-sm text-gray-700 focus:outline-none border-none placeholder-gray-400 transition-all">
                    <button type="submit" class="px-4 text-white hover:scale-110 transition-transform">
                        <i class="fa-solid fa-magnifying-glass text-lg"></i>
                    </button>
                </form>
            </div>

            <div class="flex items-center gap-3 shrink-0">
                <!-- Chat link untuk Mobile di sebelah Profil -->
                <a href="#" class="md:hidden flex items-center justify-center w-10 h-10 rounded-full bg-green-600/30 text-white hover:bg-green-600/60 transition-all">
                    <i class="fa-solid fa-comments text-lg"></i>
                </a>

                <div class="relative hidden md:block">
                    <button id="dropdownBtn" type="button" class="group flex items-center gap-3 rounded-full bg-green-600/30 p-1.5 pr-4 transition-all hover:bg-green-600/60 focus:outline-none">
                        <div class="h-10 w-10 overflow-hidden rounded-full border-2 border-white pointer-events-none">
                            <img src="{{ auth()->user()->fotoProfil ?? 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->username) }}" class="h-full w-full object-cover">
                        </div>
                        <div class="flex items-center gap-2 pointer-events-none text-white">
                            <div class="flex flex-col items-start leading-tight">
                                <span class="text-sm font-black">{{ auth()->user()->username }}</span>
                                <span class="text-sm font-bold">Profil</span>
                            </div>
                            <i class="fa-solid fa-chevron-down text-[10px] transition-transform duration-300" id="dropdownArrow"></i>
                        </div>
                    </button>

                    <div id="dropdownMenu" class="hidden absolute right-0 mt-3 w-52 bg-white rounded-2xl shadow-2xl border border-gray-100 py-2 z-60">
                        <a href="{{ route('agen.profile') }}" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 transition font-semibold">
                            <i class="fa-regular fa-id-card mr-3 text-[#0f8629] text-lg"></i> Profil Saya
                        </a>
                        <div class="mx-4 border-t border-gray-100 my-1"></div>
                        <button type="button" id="logoutBtnTrigger" class="w-full flex items-center px-4 py-3 text-sm text-red-600 hover:bg-red-50 transition font-bold text-left">
                            <i class="fa-solid fa-right-from-bracket mr-3 text-lg"></i> Logout
                        </button>
                    </div>
                </div>

                <button id="hamburger" class="md:hidden p-2 text-white hover:bg-white/10 rounded-lg transition">
                    <i class="fa-solid fa-bars text-xl"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Sub Navbar Desktop -->
    <div class="bg-[#46A302] hidden md:block border-t border-white/5">
        <div class="max-w-7xl mx-auto px-6">
            <div class="flex justify-center items-center gap-10 h-11 text-white/90 text-sm font-bold tracking-wide uppercase">
                <a href="{{ route('agen.blog.index') }}" class="hover:text-white transition-all py-1 border-b-2 {{ Route::is('agen.blog.*') ? 'border-white' : 'border-transparent' }} hover:border-white">Blog</a>
                <a href="{{ route('agen.produk.index') }}" class="hover:text-white transition-all py-1 border-b-2 {{ Route::is('agen.produk.*') ? 'border-white' : 'border-transparent' }} hover:border-white">Produk</a>
                <a href="#" class="hover:text-white transition-all py-1 border-b-2 border-transparent hover:border-white">Transaksi</a>
                <a href="{{ route('kemitraan.index') }}" class="hover:text-white transition-all py-1 border-b-2 {{ Route::is('agen.kemitraan.*') ? 'border-white' : 'border-transparent' }} hover:border-white">Kemitraan</a>
                <!-- Link Chat Desktop sementara # -->
                <a href="#" class="hover:text-white transition-all py-1 border-b-2 border-transparent hover:border-white">Chat</a>
                <a href="#" class="hover:text-white transition-all py-1 border-b-2 border-transparent hover:border-white">Konsultasi</a>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div id="mobileMenu" class="hidden md:hidden bg-white border-t border-gray-100 shadow-2xl absolute w-full left-0 animate-fade-in-down max-h-[calc(100vh-80px)] overflow-y-auto">
        <div class="px-6 py-6 space-y-4">
            <!-- User Info di Mobile -->
            <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-2xl border border-gray-100">
                <div class="h-14 w-14 overflow-hidden rounded-full border-2 border-[#0f8629]">
                    <img src="{{ auth()->user()->fotoProfil ?? 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->username) }}" class="h-full w-full object-cover">
                </div>
                <div>
                    <h4 class="font-black text-gray-900 text-lg">{{ auth()->user()->username }}</h4>
                    <a href="{{ route('agen.profile') }}" class="text-sm font-bold text-[#0f8629] hover:underline">Lihat Profil</a>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-2">
                <a href="{{ route('agen.blog.index') }}" class="flex items-center py-3 px-4 rounded-xl {{ Route::is('agen.blog.*') ? 'bg-green-50 text-[#0f8629]' : 'hover:bg-gray-50 text-gray-700' }} font-bold text-base">
                    <i class="fa-solid fa-newspaper mr-3 w-5 text-center"></i> Blog
                </a>
                <a href="{{ route('agen.produk.index') }}" class="flex items-center py-3 px-4 rounded-xl {{ Route::is('agen.produk.*') ? 'bg-green-50 text-[#0f8629]' : 'hover:bg-gray-50 text-gray-700' }} font-bold text-base">
                    <i class="fa-solid fa-box mr-3 w-5 text-center"></i> Produk
                </a>
                <a href="#" class="flex items-center py-3 px-4 rounded-xl hover:bg-gray-50 font-bold text-gray-700 text-base">
                    <i class="fa-solid fa-receipt mr-3 w-5 text-center"></i> Transaksi
                </a>
                <a href="{{ route('kemitraan.index') }}" class="flex items-center py-3 px-4 rounded-xl hover:bg-gray-50 font-bold text-gray-700 text-base">
                    <i class="fa-solid fa-handshake mr-3 w-5 text-center"></i> Kemitraan
                </a>
                <!-- Link Chat Mobile sementara # -->
                <a href="#" class="flex items-center py-3 px-4 rounded-xl hover:bg-gray-50 font-bold text-gray-700 text-base">
                    <i class="fa-solid fa-comments mr-3 w-5 text-center"></i> Chat
                </a>
                <a href="#" class="flex items-center py-3 px-4 rounded-xl hover:bg-gray-50 font-bold text-gray-700 text-base">
                    <i class="fa-solid fa-headset mr-3 w-5 text-center"></i> Konsultasi
                </a>
                <div class="my-2 border-t border-gray-100"></div>
                <button type="button" class="logoutMobileBtn w-full flex items-center py-4 px-4 rounded-xl hover:bg-red-50 font-black text-red-500 transition-all">
                    <i class="fa-solid fa-right-from-bracket mr-3 w-5 text-center"></i> Logout dari Akun
                </button>
            </div>
        </div>
    </div>
</nav>

<div class="h-20 md:h-28"></div>

<x-modal id="logoutModal" title="Konfirmasi Logout" message="Apakah Anda yakin ingin keluar dari AGRIS?" confirmText="Iya" cancelText="Batal" confirmId="confirmLogoutBtn" cancelId="closeLogoutBtn" />

<form id="logoutFormReal" action="{{ route('logout') }}" method="POST" class="hidden">
    @csrf
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const hamburger = document.getElementById('hamburger');
    const mobileMenu = document.getElementById('mobileMenu');
    const dropdownBtn = document.getElementById('dropdownBtn');
    const dropdownMenu = document.getElementById('dropdownMenu');
    const dropdownArrow = document.getElementById('dropdownArrow');

    const closeAllMenus = () => {
        dropdownMenu?.classList.add('hidden');
        mobileMenu?.classList.add('hidden');
        if (dropdownArrow) dropdownArrow.style.transform = 'rotate(0deg)';
    };

    dropdownBtn?.addEventListener('click', function(e) {
        e.stopPropagation();
        mobileMenu?.classList.add('hidden');
        const isHidden = dropdownMenu.classList.toggle('hidden');
        if (dropdownArrow) dropdownArrow.style.transform = isHidden ? 'rotate(0deg)' : 'rotate(180deg)';
    });

    hamburger?.addEventListener('click', function(e) {
        e.stopPropagation();
        dropdownMenu?.classList.add('hidden');
        mobileMenu.classList.toggle('hidden');
    });

    document.addEventListener('click', function(e) {
        if (!e.target.closest('#dropdownBtn') && !e.target.closest('#dropdownMenu')) {
            dropdownMenu?.classList.add('hidden');
            if (dropdownArrow) dropdownArrow.style.transform = 'rotate(0deg)';
        }
        if (!e.target.closest('#hamburger') && !e.target.closest('#mobileMenu')) {
            mobileMenu?.classList.add('hidden');
        }
    });

    document.getElementById('logoutBtnTrigger')?.addEventListener('click', () => {
        closeAllMenus();
        openModal('logoutModal');
    });

    document.querySelector('.logoutMobileBtn')?.addEventListener('click', () => {
        closeAllMenus();
        openModal('logoutModal');
    });

    document.getElementById('confirmLogoutBtn')?.addEventListener('click', function() {
        document.getElementById('logoutFormReal')?.submit();
    });

    document.getElementById('closeLogoutBtn')?.addEventListener('click', function() {
        closeModal('logoutModal');
    });
});
</script>
