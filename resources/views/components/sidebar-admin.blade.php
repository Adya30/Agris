<aside id="sidebar" class="fixed top-0 left-0 z-50 w-64 h-screen transition-transform -translate-x-full md:translate-x-0 bg-[#58CC02]">
    <div class="h-full flex flex-col">
        {{-- Logo --}}
        <div class="pl-8 pt-5 pb-5">
            <a href="{{ route('admin.produk.index') }}" class="flex items-center gap-2 group">
                <div class="rounded-full">
                    <img src="{{ asset('images/icon.png') }}" class="h-13 w-13" alt="Logo">
                </div>
                <span class="text-3xl font-bold text-white tracking-tight">AGRIS</span>
            </a>
        </div>

        <nav class="flex-1 px-4 space-y-3">
            @php
                $menus = [
                    ['name' => 'Produk', 'url' => route('admin.produk.index'), 'active' => 'admin/produk*', 'icon' => 'fa-leaf'],
                    ['name' => 'Kemitraan', 'url' => '#', 'active' => 'admin/kemitraan*', 'icon' => 'fa-users'],
                    ['name' => 'Transaksi', 'url' => '#', 'active' => 'admin/transaksi*', 'icon' => 'fa-wallet'],
                    ['name' => 'Laporan', 'url' => '#', 'active' => 'admin/laporan*', 'icon' => 'fa-file-lines'],
                    ['name' => 'Blog', 'url' => '#', 'active' => 'admin/blog*', 'icon' => 'fa-book-open'],
                ];
            @endphp

            @foreach($menus as $menu)
                <a href="{{ $menu['url'] }}"
                   class="flex items-center gap-4 px-5 py-4 rounded-2xl font-bold transition-all duration-300 {{ request()->is($menu['active']) ? 'bg-[#46A302] text-white shadow-lg ring-1 ring-white/20' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
                    <i class="fa-solid {{ $menu['icon'] }} text-sm w-5"></i>
                    <span class="tracking-wide text-lg">{{ $menu['name'] }}</span>
                </a>
            @endforeach
        </nav>
    </div>
</aside>

<div id="sidebarOverlay" class="fixed inset-0 bg-black/50 z-40 hidden md:hidden"></div>

<script>
    (function() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');

        function toggleSidebar() {
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        }

        document.addEventListener('click', function(e) {
            if (e.target.closest('#hamburgerBtn')) {
                toggleSidebar();
            }
            if (e.target.id === 'sidebarOverlay') {
                toggleSidebar();
            }
        });
    })();
</script>
