<header class="fixed top-0 right-0 left-0 {{ !Route::is('admin.profile') ? 'md:left-64' : 'z-40' }} bg-[#58CC02] h-17" style="z-index: 50;">
    <div class="flex items-center justify-between h-full px-6">
        <div class="flex items-center gap-4">
            @if(Route::is('admin.profile'))
                <a href="{{ route('admin.produk.index') }}" class="flex items-center gap-2 group transition-transform hover:scale-105">
                    <img src="{{ asset('images/icon.png') }}" class="h-10 w-10">
                    <span class="text-2xl font-bold text-white uppercase tracking-wider">AGRIS</span>
                </a>
            @else
                <button id="hamburgerBtn" class="md:hidden p-2 text-white hover:bg-white/10 rounded-lg transition">
                    <i class="fa-solid fa-bars text-xl"></i>
                </button>
            @endif
        </div>

        <div class="flex-1 max-w-xl px-7 hidden md:block">
            <div class="relative flex items-center bg-[#46A302] rounded-full p-1 shadow-inner border border-white/10">
                <input type="text" id="navSearchInput" placeholder="Search...." class="w-full bg-white rounded-full py-2 px-5 text-sm text-gray-700 focus:outline-none border-none placeholder-gray-400">
                <button type="button" id="navSearchBtn" class="px-4 text-white hover:scale-110 transition-transform">
                    <i class="fa-solid fa-magnifying-glass text-lg"></i>
                </button>
            </div>
        </div>

        <div class="relative">
            <button id="adminDropdownBtn" type="button" class="group flex items-center gap-3 rounded-full bg-green-600/30 p-1.5 pr-4 transition-all hover:bg-green-600/60 focus:outline-none overflow-visible">
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

            <div id="adminDropdownMenu" class="hidden absolute right-0 mt-3 w-52 bg-white rounded-2xl shadow-2xl border border-gray-100 py-2 transition-all duration-200 z-[9999]">
                <a href="{{ route('admin.profile') }}" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 transition font-semibold">
                    <i class="fa-regular fa-id-card mr-3 text-[#58CC02] text-lg"></i>
                    Profil Saya
                </a>
                <div class="mx-4 border-t border-gray-100 my-1"></div>
                <button type="button" id="logoutBtnTrigger" class="flex items-center w-full px-4 py-3 text-sm text-red-600 hover:bg-red-50 transition font-bold text-left">
                    <i class="fa-solid fa-right-from-bracket mr-3 text-lg"></i>
                    Log Out
                </button>
            </div>
        </div>
    </div>
</header>

<x-modal id="logoutModal" title="Konfirmasi Logout" message="Apakah Anda yakin ingin keluar?" confirmText="Iya, Keluar" cancelText="Batal" confirmId="confirmLogoutBtn" cancelId="closeLogoutBtn" />

<form id="logoutFormReal" action="{{ route('logout') }}" method="POST" class="hidden">
    @csrf
</form>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const btn = document.getElementById('adminDropdownBtn');
        const menu = document.getElementById('adminDropdownMenu');
        const arrow = document.getElementById('dropdownArrow');
        const logoutTrigger = document.getElementById('logoutBtnTrigger');
        const logoutModal = document.getElementById('logoutModal');

        if (btn && menu) {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                if (menu.classList.contains('hidden')) {
                    menu.classList.remove('hidden');
                    if(arrow) arrow.style.transform = 'rotate(180deg)';
                } else {
                    menu.classList.add('hidden');
                    if(arrow) arrow.style.transform = 'rotate(0deg)';
                }
            });
        }

        document.addEventListener('click', function(e) {
            if (menu && !btn.contains(e.target) && !menu.contains(e.target)) {
                menu.classList.add('hidden');
                if(arrow) arrow.style.transform = 'rotate(0deg)';
            }
        });

        if (logoutTrigger) {
            logoutTrigger.addEventListener('click', function() {
                menu.classList.add('hidden');
                if(logoutModal) logoutModal.classList.remove('hidden');
            });
        }

        const closeLogout = document.getElementById('closeLogoutBtn');
        if (closeLogout) {
            closeLogout.addEventListener('click', function() {
                logoutModal.classList.add('hidden');
            });
        }

        const confirmLogout = document.getElementById('confirmLogoutBtn');
        if (confirmLogout) {
            confirmLogout.addEventListener('click', function() {
                document.getElementById('logoutFormReal').submit();
            });
        }

        const searchInput = document.getElementById('navSearchInput');
        const searchBtn = document.getElementById('navSearchBtn');

        const routes = {
            "dashboard": "{{ route('admin.produk.index') }}",
            "produk": "{{ route('admin.produk.index') }}",
            "profil": "{{ route('admin.profile') }}",
            "tambah": "{{ route('admin.produk.create') }}"
        };

        function performNav() {
            const query = searchInput.value.toLowerCase().trim();
            if (!query) return;

            if (routes[query]) {
                window.location.href = routes[query];
            } else {
                window.location.href = "{{ route('admin.produk.index') }}?search=" + encodeURIComponent(query);
            }
        }

        if(searchInput) {
            searchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    performNav();
                }
            });
        }

        if(searchBtn) {
            searchBtn.addEventListener('click', performNav);
        }
    });
</script>
