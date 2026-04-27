<header class="fixed top-0 right-0 left-0 {{ !Route::is('admin.profile') ? 'md:left-64' : 'z-40' }} bg-[#58CC02] h-17" style="z-index: 50;">
    <div class="flex items-center justify-between h-full px-6">

        <div class="flex items-center gap-4">
            @if(Route::is('admin.profile'))
                <a href="{{ route('admin.produk.index') }}" class="flex items-center gap-2 group">
                    <img src="{{ asset('images/icon.png') }}" class="h-10 w-10">
                    <span class="text-2xl font-bold text-white uppercase tracking-wider">AGRIS</span>
                </a>
            @else
                <button id="hamburgerBtn" class="md:hidden p-2 text-white hover:bg-white/10 rounded-lg transition">
                    <i class="fa-solid fa-bars text-xl"></i>
                </button>
            @endif
        </div>

        <div class="flex-1 max-w-xl px-10 hidden md:block">
            <div class="relative flex items-center bg-[#46A302] rounded-full p-1.5 shadow-inner">
                <input type="text" id="navSearchInput" placeholder="Cari Menu...." class="w-full bg-white rounded-full py-2 px-6 text-sm text-gray-700 focus:outline-none border-none">
                <button type="button" id="navSearchBtn" class="px-4 text-white hover:scale-110 transition-transform">
                    <i class="fa-solid fa-magnifying-glass text-lg"></i>
                </button>
            </div>
        </div>

        <div class="relative flex items-center">
            <button id="adminDropdownBtn" type="button" class="group flex items-center gap-3 rounded-full bg-green-600/30 p-1.5 pr-4 transition-all hover:bg-green-600/60 focus:outline-none overflow-visible">
                <div class="h-10 w-10 overflow-hidden rounded-full border-2 border-white pointer-events-none">
                    <img src="{{ auth()->user()->fotoProfil ?? 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->username) }}" class="h-full w-full object-cover">
                </div>
                <div class="flex items-center gap-2 pointer-events-none">
                    <div class="flex flex-col items-start leading-tight">
                        <span class="text-sm font-black text-white">{{ auth()->user()->username }}</span>
                        <span class="text-md font-bold text-white">Profil</span>
                    </div>
                    <i class="fa-solid fa-chevron-down text-[10px] text-white/80 transition-transform group-focus:rotate-180"></i>
                </div>
            </button>

            <div id="adminDropdownMenu" class="hidden fixed right-6 top-16 w-52 bg-white rounded-2xl shadow-2xl border border-gray-100 py-2 transition-all duration-200" style="z-index: 9999 !important; display: none;">
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

<x-modal id="logoutModal" title="Konfirmasi Logout" message="Apakah Anda yakin ingin keluar?" confirmText="Iya" cancelText="Batal" confirmId="confirmLogoutBtn" cancelId="closeLogoutBtn" />

<form id="logoutFormReal" action="{{ route('logout') }}" method="POST" class="hidden">
    @csrf
</form>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const btn = document.getElementById('adminDropdownBtn');
        const menu = document.getElementById('adminDropdownMenu');
        const logoutTrigger = document.getElementById('logoutBtnTrigger');

        // Fungsi Toggle Dropdown
        if (btn && menu) {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();

                // Cek status hidden
                if (menu.style.display === 'none' || menu.classList.contains('hidden')) {
                    menu.classList.remove('hidden');
                    menu.style.display = 'block';
                } else {
                    menu.classList.add('hidden');
                    menu.style.display = 'none';
                }
            });
        }

        document.addEventListener('click', function(e) {
            if (menu && !btn.contains(e.target) && !menu.contains(e.target)) {
                menu.classList.add('hidden');
                menu.style.display = 'none';
            }
        });

        if (logoutTrigger) {
            logoutTrigger.addEventListener('click', function(e) {
                e.stopPropagation();
                menu.classList.add('hidden');
                menu.style.display = 'none';
                document.getElementById('logoutModal').classList.remove('hidden');
            });
        }

        document.getElementById('closeLogoutBtn').addEventListener('click', function() {
            document.getElementById('logoutModal').classList.add('hidden');
        });

        document.getElementById('confirmLogoutBtn').addEventListener('click', function() {
            document.getElementById('logoutFormReal').submit();
        });

        const searchInput = document.getElementById('navSearchInput');
        const searchBtn = document.getElementById('navSearchBtn');
        const routes = { "dashboard": "{{ route('admin.produk.index') }}", "profil": "{{ route('admin.profile') }}" };

        function nav() {
            const v = searchInput.value.toLowerCase().trim();
            if (routes[v]) window.location.href = routes[v];
            else if (v) window.location.href = "{{ route('admin.produk.index') }}?search=" + v;
        }

        if(searchInput) searchInput.addEventListener('keypress', (e) => e.key === 'Enter' && nav());
        if(searchBtn) searchBtn.addEventListener('click', nav);
    });
</script>
