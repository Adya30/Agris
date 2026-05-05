<aside id="sidebar" class="fixed top-0 right-0 w-64 h-screen transition-transform translate-x-full md:translate-x-0 md:left-0 md:right-auto bg-[#0f8629] z-60">
    <div class="h-full flex flex-col">
        <div class="pl-8 pt-5 pb-8 hidden md:block">
            <a href="{{ route('admin.produk.index') }}" class="flex items-center gap-2 group">
                <div class="rounded-full">
                    <img src="{{ asset('images/icon.png') }}" class="h-11 w-auto" alt="Logo">
                </div>
                <span class="text-2xl font-bold text-white tracking-tight">AGRIS</span>
            </a>
        </div>

        <nav class="flex-1 px-6 py-6 md:py-0 space-y-4">
            @php
                $menus = [
                    ['name' => 'Produk', 'url' => route('admin.produk.index'), 'active' => 'admin/produk*', 'icon' => 'fa-seedling'],
                    ['name' => 'Kemitraan', 'url' => route('admin.kemitraan.index'), 'active' => 'admin/kemitraan*', 'icon' => 'fa-users'],
                    ['name' => 'Transaksi', 'url' => '#', 'active' => 'admin/transaksi*', 'icon' => 'fa-wallet'],
                    ['name' => 'Laporan', 'url' => '#', 'active' => 'admin/laporan*', 'icon' => 'fa-file-lines'],
                    ['name' => 'Blog', 'url' => route('admin.blog.index'), 'active' => 'admin/blog*', 'icon' => 'fa-book-open'],
                ];
            @endphp

            @foreach($menus as $menu)
                <a href="{{ $menu['url'] }}" class="flex items-center gap-4 p-2 rounded-2xl font-bold transition-all duration-300 {{ request()->is($menu['active']) ? 'bg-[#46A302] text-white shadow-lg ring-1 ring-white/20' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
                    <i class="fa-solid {{ $menu['icon'] }} text-sm w-5 pl-2"></i>
                    <span class="tracking-wide text-mg">{{ $menu['name'] }}</span>
                </a>
            @endforeach

            <div class="pt-4 mt-4 border-t border-white/20 md:hidden">
                <a href="{{ route('admin.profile') }}" class="flex items-center gap-4 p-2 rounded-2xl font-bold text-white/80 hover:bg-white/10 hover:text-white transition-all duration-300">
                    <i class="fa-solid fa-id-card text-sm w-5 pl-2"></i>
                    <span class="tracking-wide text-mg">Profil Saya</span>
                </a>
                <button type="button" onclick="openModal('logoutModal')" class="flex items-center w-full gap-4 p-2 mt-2 rounded-2xl font-bold text-red-200 hover:bg-red-600 hover:text-white transition-all duration-300">
                    <i class="fa-solid fa-right-from-bracket text-sm w-5 pl-2"></i>
                    <span class="tracking-wide text-mg">Log Out</span>
                </button>
            </div>
        </nav>

        <div class="p-6 mt-auto border-t border-white/10 hidden md:block">
            <div class="flex items-center gap-3">
                <div class="h-10 w-10 overflow-hidden rounded-full border-2 border-white">
                    <img src="{{ auth()->user()->fotoProfil ?? 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->username) }}" class="h-full w-full object-cover">
                </div>
                <div class="flex flex-col leading-tight">
                    <span class="text-sm font-black text-white truncate w-32">{{ auth()->user()->username }}</span>
                    <span class="text-xs text-white/70 uppercase font-bold">Admin</span>
                </div>
            </div>
        </div>
    </div>
</aside>

<div id="sidebarOverlay" class="fixed inset-0 bg-black/60 z-55 hidden md:hidden"></div>

<script>
    (function() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');

        function toggleSidebar() {
            if (!sidebar || !overlay) return;
            sidebar.classList.toggle('translate-x-full');
            overlay.classList.toggle('hidden');
            document.body.classList.toggle('overflow-hidden');
        }

        document.addEventListener('click', function(e) {
            if (e.target.closest('#hamburgerBtn') || e.target.id === 'sidebarOverlay') {
                toggleSidebar();
            }
        });
    })();
</script>
