@extends('layouts.app')

@section('title', 'Kontak Kami - PT Surya Kencana Agrifarm')

@section('content')
<x-navbar />

<section class="pt-32 pb-20 bg-white px-6">
    <div class="max-w-7xl mx-auto">
        <div class="grid md:grid-cols-2 gap-16 items-start">
            <div>
                <h1 class="text-4xl font-bold text-gray-800 mb-6">Hubungi Kami</h1>
                <p class="text-gray-500 mb-10 text-lg">Ada pertanyaan atau ingin bekerja sama sebagai mitra? Tim kami siap membantu Anda.</p>

                <div class="space-y-8">
                    <div class="flex items-start gap-6">
                        <div class="w-12 h-12 bg-[#58CC02]/10 rounded-xl flex items-center justify-center text-[#58CC02]">
                            <i class="fa-solid fa-location-dot text-xl"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-800">Kantor Pusat</h4>
                            <p class="text-gray-500">Jl. Raya Pertanian No. 123, Kabupaten Jember, Jawa Timur, Indonesia.</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-6">
                        <div class="w-12 h-12 bg-[#58CC02]/10 rounded-xl flex items-center justify-center text-[#58CC02]">
                            <i class="fa-solid fa-phone text-xl"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-800">Telepon & WhatsApp</h4>
                            <p class="text-gray-500">+62 812-3456-7890</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-6">
                        <div class="w-12 h-12 bg-[#58CC02]/10 rounded-xl flex items-center justify-center text-[#58CC02]">
                            <i class="fa-solid fa-envelope text-xl"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-800">Email Resmi</h4>
                            <p class="text-gray-500">agrisagroindustri@agris.co.id</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-gray-50 p-8 md:p-12 rounded-3xl border border-gray-100 shadow-sm">
                <h3 class="text-2xl font-bold text-gray-800 mb-6">Kirim Pesan</h3>
                <form action="#" class="space-y-4">
                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Nama Lengkap</label>
                            <input type="text" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#58CC02] focus:border-transparent outline-none transition" placeholder="Contoh: Budi Santoso">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">No. WhatsApp</label>
                            <input type="text" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#58CC02] focus:border-transparent outline-none transition" placeholder="0812...">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Subjek</label>
                        <select class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#58CC02] outline-none">
                            <option>Kemitraan Petani</option>
                            <option>Pemesanan Benih</option>
                            <option>Keluhan Pelanggan</option>
                            <option>Lainnya</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Pesan Anda</label>
                        <textarea rows="4" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#58CC02] outline-none" placeholder="Tuliskan pesan Anda secara detail..."></textarea>
                    </div>
                    <button type="submit" class="w-full py-4 bg-[#58CC02] text-white font-bold rounded-xl shadow-lg shadow-[#58CC02]/30 hover:bg-[#4aad02] transition duration-300">
                        Kirim Sekarang
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>

<x-footer />
@endsection
