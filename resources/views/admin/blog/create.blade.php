@extends('layouts.admin')

@section('content')
<div class="max-w-5xl mx-auto pt-4 pb-12">
    <div class="flex items-center gap-6 mb-12">
        <a href="{{ route('admin.blog.index') }}" class="w-12 h-12 rounded-2xl border border-gray-100 flex items-center justify-center text-gray-400 bg-white shadow-sm">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Tambah Blog Baru</h1>
        </div>
    </div>

    <form action="{{ route('admin.blog.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
        @csrf
        <div class="bg-white p-10 rounded-[48px] border border-gray-100 shadow-sm space-y-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
                <div class="lg:col-span-2 space-y-8">
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-[0.2em] text-gray-400 mb-3 ml-1">Judul</label>
                        <input type="text" name="judulBlog" value="{{ old('judulBlog') }}" required placeholder="Apa yang ingin Anda bagikan?" class="w-full px-8 py-5 rounded-3xl border border-gray-50 bg-gray-50 outline-none focus:ring-2 focus:ring-[#58CC02] focus:bg-white transition-all font-bold text-xl text-gray-800 placeholder:font-bold placeholder:text-gray-300">
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-[0.2em] text-gray-400 mb-3 ml-1">Isi Blog</label>
                        <textarea name="isiBlog" id="editor" rows="15" placeholder="Tuliskan cerita lengkapnya di sini..." class="w-full px-8 py-6 rounded-3xl border border-gray-50 bg-gray-50 outline-none focus:ring-2 focus:ring-[#58CC02] focus:bg-white transition-all text-gray-600 leading-relaxed">{{ old('isiBlog') }}</textarea>
                    </div>
                </div>

                <div class="space-y-8">
                    <div class="bg-gray-50 p-8 rounded-4xl border border-gray-100">
                        <label class="block text-[10px] font-bold uppercase tracking-[0.2em] text-gray-400 mb-4 ml-1 text-center">Foto Blog</label>
                        <div class="relative group cursor-pointer">
                            <input type="file" name="fotoBlog" id="fotoInput" class="hidden" accept="image/*" required>
                            <div onclick="document.getElementById('fotoInput').click()" class="aspect-square bg-white rounded-2xl border-2 border-dashed border-gray-200 flex flex-col items-center justify-center text-center overflow-hidden">

                                <img id="preview" src="" class="hidden w-full h-full object-cover rounded-xl mb-0">

                                <div id="placeholder" class="">
                                    <i class="fa-solid fa-cloud-arrow-up text-3xl text-gray-300 mb-3 group-hover:text-[#58CC02]"></i>
                                    <p class="text-xs font-bold text-gray-400 group-hover:text-[#58CC02]">Unggah Gambar</p>
                                </div>
                            </div>
                        </div>
                        <p class="mt-4 text-[10px] font-bold text-blue-400 text-center">Besar file maks 10mb <br> Ektensi file: .PNG, .JPG, .JPEG</p>
                    </div>

                    <div class="bg-[#58CC02]/5 p-8 rounded-4xl border border-[#58CC02]/10">
                        <h4 class="text-xs font-bold text-[#58CC02] uppercase mb-4 tracking-wider">Informasi</h4>
                        <div class="space-y-3">
                            <div class="flex items-center gap-3 text-[11px] font-bold text-gray-500">
                                <i class="fa-solid fa-calendar text-[#58CC02]"></i>
                                Tanggal: {{ date('d/m/Y') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex justify-end pt-4">
            <button type="submit" class="bg-[#58CC02] hover:bg-[#46A302] text-white px-12 py-5 rounded-3xl font-bold shadow-2xl shadow-green-200 transition-all transform hover:-translate-y-1 flex items-center gap-4">
                Terbitkan Blog <i class="fa-solid fa-paper-plane"></i>
            </button>
        </div>
    </form>
</div>

<script>
    document.getElementById('fotoInput').onchange = evt => {
        const [file] = document.getElementById('fotoInput').files;
        if (file) {
            document.getElementById('preview').src = URL.createObjectURL(file);
            document.getElementById('preview').classList.remove('hidden');
            document.getElementById('placeholder').classList.add('hidden');
        }
    }
</script>
@endsection
