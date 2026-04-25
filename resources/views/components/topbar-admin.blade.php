<header class="fixed top-0 right-0 left-0 {{ !Route::is('admin.profile') ? 'md:left-64' : 'z-40' }} bg-[#58CC02] h-17 transition-all duration-300 shadow-sm border-b border-white/10">
    <div class="flex items-center justify-between h-full px-6">

        {{-- BAGIAN KIRI: Logo & Brand --}}
        <div class="flex items-center gap-4">
            @if(Route::is('admin.profile'))
                {{-- Logo HANYA tampil di halaman Profile --}}
                <a href="{{ route('admin.produk.index') }}" class="flex items-center gap-2 group">
                    <div class="rounded-full">
                        <img src="{{ asset('images/icon.png') }}" class="h-10 w-10" alt="Logo">
                    </div>
                    <span class="text-2xl font-bold text-white tracking-tight">AGRIS</span>
                </a>
            @else
                <button id="hamburgerBtn" class="md:hidden p-2 text-white hover:bg-white/10 rounded-lg transition">
                    <i class="fa-solid fa-bars-staggered text-xl"></i>
                </button>
            @endif
        </div>

        {{-- BAGIAN TENGAH: Search Bar Berfungsi --}}
        <div class="flex-1 max-w-xl px-10 hidden md:block">
            <div class="relative flex items-center bg-[#46A302] rounded-full p-1.5 shadow-inner group transition-all focus-within:bg-[#3d8d02]">
                {{-- Input dengan list --}}
                <input type="text"
                       id="navSearchInput"
                       list="menuList"
                       placeholder="Cari menu..."
                       class="w-full bg-white rounded-full py-2 px-6 text-sm text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-0 border-none transition-all">

                <button type="button" class="px-4 text-white hover:scale-110 transition-transform">
                    <i class="fa-solid fa-magnifying-glass text-lg"></i>
                </button>

            </div>
        </div>

        {{-- BAGIAN KANAN: Profile Dropdown --}}
        <div class="relative">
            <button id="adminDropdownBtn" class="flex items-center gap-3 py-1.5 px-2 rounded-full hover:bg-white/10 transition group">
                <div class="w-10 h-10 rounded-full overflow-hidden border-2 border-white shadow-sm transition-transform group-hover:scale-105">
                    <img src="{{ auth()->user()->fotoProfil ?? 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->username) }}"
                         class="w-full h-full object-cover"
                         alt="Foto Profil">
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-sm font-black text-white hidden sm:block tracking-tight">{{ auth()->user()->username }}</span>
                    <i class="fa-solid fa-chevron-down text-[10px] text-white/80 transition-transform group-focus:rotate-180"></i>
                </div>
            </button>

            {{-- Dropdown Menu --}}
            <div id="adminDropdownMenu" class="hidden absolute right-0 top-14 w-52 bg-white rounded-2xl shadow-xl border border-gray-100 py-2 z-50 animate-in fade-in slide-in-from-top-2 duration-200">
                <a href="{{ route('admin.profile') }}" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 transition font-bold">
                    <i class="fa-regular fa-id-card mr-3 text-[#58CC02] text-lg"></i> Profil Saya
                </a>
                <div class="mx-4 border-t border-gray-100 my-1"></div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="flex items-center w-full px-4 py-3 text-sm text-red-600 hover:bg-red-50 transition font-black uppercase tracking-tighter">
                        <i class="fa-solid fa-right-from-bracket mr-3 text-lg"></i> Log Out
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('navSearchInput');
        const searchBtn = document.getElementById('navSearchBtn');

        const routes = {
            "dashboard": "{{ route('admin.produk.index') }}",
            "daftar produk": "{{ route('admin.produk.index') }}",
            "produk": "{{ route('admin.produk.index') }}",
            "profil saya": "{{ route('admin.profile') }}",
            "profil": "{{ route('admin.profile') }}",
            "tambah produk": "/admin/produk/create"
        };

        function handleNavigation() {
            const val = searchInput.value.toLowerCase().trim();
            if (routes[val]) {
                window.location.href = routes[val];
            } else if (val !== "") {
                window.location.href = "{{ route('admin.produk.index') }}?search=" + val;
            }
        }

        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') handleNavigation();
        });

        searchBtn.addEventListener('click', handleNavigation);

        const dropBtn = document.getElementById('adminDropdownBtn');
        const dropMenu = document.getElementById('adminDropdownMenu');

        dropBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            dropMenu.classList.toggle('hidden');
        });

        document.addEventListener('click', () => dropMenu.classList.add('hidden'));
    });
</script>
