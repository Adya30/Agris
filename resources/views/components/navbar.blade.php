<nav class="fixed w-full z-50 bg-white/90 backdrop-blur shadow-sm px-6">
    <div class="max-w-7xl mx-auto">
        <div class="flex justify-between items-center h-16">

            <a href="#" class="flex items-center gap-2 text-2xl font-bold text-[#58CC02]">
                <img src="{{ asset('images/icon.png') }}" class="w-10">
                AGRIS
            </a>

            <div class="hidden md:flex items-center gap-8 font-medium text-gray-700">
                <a href="#home" class="relative group py-2">
                    Beranda
                    <span class="absolute left-0 -bottom-1 w-0 h-0.5 bg-[#58CC02] transition-all duration-300 group-hover:w-full"></span>
                </a>
                <a href="#kategori" class="relative group py-2">
                    Tentang
                    <span class="absolute left-0 -bottom-1 w-0 h-0.5 bg-[#58CC02] transition-all duration-300 group-hover:w-full"></span>
                </a>
                <a href="#produk" class="relative group py-2">
                    Blog
                    <span class="absolute left-0 -bottom-1 w-0 h-0.5 bg-[#58CC02] transition-all duration-300 group-hover:w-full"></span>
                </a>

                <a href="#kontak" class="relative group py-2">
                    Kontak
                    <span class="absolute left-0 -bottom-1 w-0 h-0.5 bg-[#58CC02] transition-all duration-300 group-hover:w-full"></span>
                </a>

            </div>

            <div class="hidden md:flex gap-4">
                <a href="{{ route('login') }}" class="px-6 py-2 rounded-full bg-[#58CC02] text-white shadow-md hover:shadow-lg hover:scale-105 transition duration-300">
                    Login
                </a>
            </div>

            <div class="md:hidden flex items-center">
                <button id="menu-btn" class="text-gray-600 hover:text-[#58CC02] focus:outline-none transition-all duration-300">
                    <i id="menu-icon" class="fas fa-bars text-2xl"></i>
                </button>
            </div>
        </div>
    </div>

    <div id="mobile-menu" class="hidden md:hidden bg-white border-t border-gray-100 absolute w-full left-0 shadow-lg">
        <div class="px-6 pt-4 pb-6 space-y-4 font-medium">
            <a href="#home" class="mobile-link block hover:text-[#58CC02] transition border-b border-gray-50 pb-2">Beranda</a>
            <a href="#kategori" class="mobile-link block hover:text-[#58CC02] transition border-b border-gray-50 pb-2">Layanan</a>
            <a href="#produk" class="mobile-link block hover:text-[#58CC02] transition border-b border-gray-50 pb-2">Tentang Kami</a>
            <a href="#kontak" class="mobile-link block hover:text-[#58CC02] transition border-b border-gray-50 pb-2">Kontak</a>
            <div class="pt-2">
                <a href="{{ route('login') }}" class="block w-full text-center py-3 rounded-xl bg-[#58CC02] text-white font-bold">
                    Login
                </a>
            </div>
        </div>
    </div>
</nav>

<script>
    const menuBtn = document.getElementById('menu-btn');
    const mobileMenu = document.getElementById('mobile-menu');
    const menuIcon = document.getElementById('menu-icon');
    const mobileLinks = document.querySelectorAll('.mobile-link');

    menuBtn.addEventListener('click', () => {
        mobileMenu.classList.toggle('hidden');

        if (mobileMenu.classList.contains('hidden')) {
            menuIcon.classList.remove('fa-times');
            menuIcon.classList.add('fa-bars');
        } else {
            menuIcon.classList.remove('fa-bars');
            menuIcon.classList.add('fa-times');
        }
    });

    mobileLinks.forEach(link => {
        link.addEventListener('click', () => {
            mobileMenu.classList.add('hidden');
            menuIcon.classList.remove('fa-times');
            menuIcon.classList.add('fa-bars');
        });
    });
</script>
