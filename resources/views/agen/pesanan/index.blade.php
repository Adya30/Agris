@extends('layouts.agen')

@section('title', 'Keranjang Belanja - AGRIS')

@section('content')
<div class="max-w-5xl mx-auto pb-10 px-4">
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-800">Keranjang Belanja</h1>
        <p class="text-gray-500 text-sm">Kelola produk pilihan Anda sebelum melakukan pemesanan</p>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-6 text-sm font-bold">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6 text-sm font-bold">
            {{ session('error') }}
        </div>
    @endif

    @if(count($cart) > 0)
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-4">
                @php $total = 0; @endphp
                @foreach($cart as $id => $details)
                    @php $total += $details['harga'] * $details['quantity']; @endphp
                    <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm flex flex-col sm:flex-row items-center gap-4">
                        <div class="w-20 h-20 bg-gray-50 rounded-xl overflow-hidden flex items-center justify-center p-2 shrink-0">
                            @if($details['foto'])
                                <img src="{{ asset('storage/' . $details['foto']) }}" class="w-full h-full object-contain">
                            @else
                                <i class="fa-solid fa-image text-2xl text-gray-300"></i>
                            @endif
                        </div>

                        <div class="grow text-center sm:text-left w-full sm:w-auto">
                            <div class="flex flex-wrap justify-center sm:justify-start gap-1 mb-1">
                                <span class="text-[8px] font-bold uppercase bg-[#58CC02]/10 text-[#58CC02] px-1.5 py-0.5 rounded">
                                    {{ $details['jenis'] }}
                                </span>
                                <span class="text-[8px] font-bold uppercase bg-blue-50 text-blue-500 px-1.5 py-0.5 rounded">
                                    {{ $details['karung'] }} Kg
                                </span>
                                <span class="text-[8px] font-bold uppercase bg-orange-50 text-orange-500 px-1.5 py-0.5 rounded">
                                    {{ $details['mutu'] }}
                                </span>
                            </div>
                            <h3 class="font-bold text-gray-800 text-sm line-clamp-1">{{ $details['nama'] }}</h3>
                            <p class="text-[#58CC02] font-bold text-sm mt-1">
                                Rp {{ number_format($details['harga'], 0, ',', '.') }}
                            </p>
                        </div>

                        <div class="flex items-center gap-4 justify-between w-full sm:w-auto shrink-0">
                            <form action="{{ route('agen.keranjang.update', $id) }}" method="POST" class="flex items-center bg-gray-50 border border-gray-100 rounded-xl px-2 py-1">
                                @csrf
                                @method('PATCH')
                                <input type="number" name="quantity" value="{{ $details['quantity'] }}" min="1" max="{{ $details['stok_maksimal'] }}" onchange="this.form.submit()" class="w-14 text-center bg-transparent text-sm font-bold outline-none text-gray-800 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">
                            </form>

                            <div class="text-right hidden sm:block min-w-[100px]">
                                <span class="text-[10px] font-bold text-gray-400 block uppercase">Subtotal</span>
                                <span class="font-bold text-gray-800 text-sm">
                                    Rp {{ number_format($details['harga'] * $details['quantity'], 0, ',', '.') }}
                                </span>
                            </div>

                            <form action="{{ route('agen.keranjang.destroy', $id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-9 h-9 text-red-500 bg-red-50 hover:bg-red-100 transition rounded-xl flex items-center justify-center text-sm">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm h-fit">
                <h2 class="font-bold text-gray-800 text-base mb-4 pb-3 border-b border-gray-50">Ringkasan Pesanan</h2>

                <div class="flex justify-between items-center mb-6">
                    <span class="text-gray-500 text-sm">Total Harga</span>
                    <span class="text-xl font-bold text-[#58CC02]">
                        Rp {{ number_format($total, 0, ',', '.') }}
                    </span>
                </div>

                <form action="{{ route('agen.pesanan.checkout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full bg-[#58CC02] hover:bg-[#46A302] text-white py-3 rounded-xl transition font-bold text-sm flex items-center justify-center gap-2 shadow-sm">
                        <i class="fa-solid fa-bag-shopping"></i> Checkout Sekarang
                    </button>
                </form>
            </div>
        </div>
    @else
        <div class="py-20 text-center bg-white rounded-3xl border border-gray-100 shadow-sm">
            <i class="fa-solid fa-cart-shopping text-5xl text-gray-200 mb-4"></i>
            <p class="text-gray-400 font-bold mb-4">Keranjang belanja Anda kosong.</p>
            <a href="{{ route('agen.produk.index') }}" class="inline-flex bg-gray-800 hover:bg-black text-white px-6 py-2.5 rounded-xl text-xs font-bold transition">
                Lihat Produk
            </a>
        </div>
    @endif
</div>
@endsection
