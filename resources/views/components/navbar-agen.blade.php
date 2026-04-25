<nav class="fixed top-0 w-full z-50 bg-white/90 backdrop-blur shadow-sm border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-6">
        <div class="flex justify-between items-center h-16">

            <a href="#" class="flex items-center gap-2 text-2xl text-[#58CC02]">
                <img src="{{ asset('images/icon.png') }}" class="w-8">
                AGRIS
            </a>

            <div class="hidden md:flex items-center gap-8 text-gray-700 text-sm font-medium">

                <a href="{{ route('agen.profile') }}"
                   class="relative group py-2 xl:hidden">
                    Profil
                    <span class="absolute left-0 -bottom-1 w-0 h-0.5 bg-[#58CC02] transition-all duration-300 group-hover:w-full"></span>
                </a>

                <a href="#"
                   class="relative group py-2">
                    Produk
                    <span class="absolute left-0 -bottom-1 w-0 h-0.5 bg-[#58CC02] transition-all duration-300 group-hover:w-full"></span>
                </a>

                <a href="#"
                   class="relative group py-2">
                    Transaksi
                    <span class="absolute left-0 -bottom-1 w-0 h-0.5 bg-[#58CC02] transition-all duration-300 group-hover:w-full"></span>
                </a>

                <a href="#"
                   class="relative group py-2">
                    Blog
                    <span class="absolute left-0 -bottom-1 w-0 h-0.5 bg-[#58CC02] transition-all duration-300 group-hover:w-full"></span>
                </a>

                <a href="#"
                   class="relative group py-2">
                    Chat
                    <span class="absolute left-0 -bottom-1 w-0 h-0.5 bg-[#58CC02] transition-all duration-300 group-hover:w-full"></span>
                </a>

                <a href="#"
                   class="relative group py-2">
                    Konsultasi
                    <span class="absolute left-0 -bottom-1 w-0 h-0.5 bg-[#58CC02] transition-all duration-300 group-hover:w-full"></span>
                </a>

                <a href="#"
                   class="relative group py-2">
                    Kemitraan
                    <span class="absolute left-0 -bottom-1 w-0 h-0.5 bg-[#58CC02] transition-all duration-300 group-hover:w-full"></span>
                </a>

            </div>

            <div class="hidden md:flex items-center gap-4 relative">

                <button id="dropdownBtn" class="flex items-center gap-2 px-3 py-2 rounded-full bg-gray-100 hover:bg-gray-300 cursor-pointer transition">

                    @if(auth()->user()->fotoProfil)
                        <img src="{{ asset('storage/'.auth()->user()->fotoProfil) }}"
                             class="w-8 h-8 rounded-full object-cover shadow">
                    @else
                        <div class="w-8 h-8 rounded-full bg-[#58CC02] text-white flex items-center justify-center">
                            <i class="fa-solid fa-user"></i>
                        </div>
                    @endif

                    <span class="text-sm font-semibold text-gray-800">
                        {{ auth()->user()->username }}
                    </span>


                    <i id="dropdownIcon" class="fa-solid fa-chevron-down text-xs transition-transform duration-200"></i>
                </button>

                <div id="dropdownMenu" class="hidden absolute right-0 top-14 w-48 bg-white rounded-xl shadow-lg border border-gray-100">

                    <a href="{{ route('agen.profile') }}"
                       class="block px-4 py-3 hover:bg-gray-50 transition">
                        <i class="fa-regular fa-user mr-2 text-[#58CC02]"></i>
                        Profil Saya
                    </a>

                    <div class="border-t border-gray-100"></div>

                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit"
                                class="w-full text-left px-4 py-3 text-red-600 hover:bg-red-50 transition">
                            <i class="fa-solid fa-right-from-bracket mr-2"></i>
                            Logout
                        </button>
                    </form>

                </div>
            </div>

            <button id="hamburger" class="md:hidden text-gray-700 cursor-pointer focus:outline-none">
                <i class="fa-solid fa-bars text-2xl"></i>
            </button>

        </div>
    </div>

    <div id="mobileMenu"
         class="hidden md:hidden bg-white border-t border-gray-100 shadow-lg">

        <div class="px-6 py-6 space-y-4 text-sm font-medium text-gray-700">

            <a href="{{ route('agen.profile') }}" class="block hover:text-[#58CC02]">Profil</a>
            <a href="#" class="block hover:text-[#58CC02]">Produk</a>
            <a href="#" class="block hover:text-[#58CC02]">Transaksi</a>
            <a href="#" class="block hover:text-[#58CC02]">Blog</a>
            <a href="#" class="block hover:text-[#58CC02]">Chat</a>
            <a href="#" class="block hover:text-[#58CC02]">Konsultasi</a>
            <a href="#" class="block hover:text-[#58CC02]">Kemitraan</a>

            <div class="border-t border-gray-200 pt-4">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="w-full bg-red-500 text-white py-2 rounded-lg">
                        Logout
                    </button>
                </form>
            </div>

        </div>
    </div>
</nav>

<script>
document.addEventListener('DOMContentLoaded', function() {

    const hamburger = document.getElementById('hamburger');
    const mobileMenu = document.getElementById('mobileMenu');

    hamburger.addEventListener('click', function() {
        mobileMenu.classList.toggle('hidden');
    });

    const dropdownBtn = document.getElementById('dropdownBtn');
    const dropdownMenu = document.getElementById('dropdownMenu');
    const dropdownIcon = document.getElementById('dropdownIcon');

    dropdownBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        dropdownMenu.classList.toggle('hidden');
        dropdownIcon.classList.toggle('rotate-180');
    });

    document.addEventListener('click', function(e) {
        if (!dropdownBtn.contains(e.target) && !dropdownMenu.contains(e.target)) {
            dropdownMenu.classList.add('hidden');
            dropdownIcon.classList.remove('rotate-180');
        }
    });

});
</script>
