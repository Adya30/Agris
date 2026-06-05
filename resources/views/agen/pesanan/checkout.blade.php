@extends('layouts.agen')

@section('title', 'Checkout - AGRIS')

@section('content')
<div class="max-w-5xl mx-auto pt-5 pb-12 px-6">
    <div class="mb-8">
        <a href="{{ route('agen.keranjang.index') }}" class="text-xs font-bold text-gray-400 hover:text-gray-600 flex items-center gap-1.5 mb-2 uppercase">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Keranjang
        </a>
        <h1 class="text-2xl font-bold text-gray-800">Checkout Pesanan</h1>
        <p class="text-gray-500 text-sm">Selesaikan pesanan Anda dengan memilih kurir pengiriman</p>
    </div>

    @if(session('error'))
        <div class="bg-red-100 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6 text-sm font-bold">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Form & Items -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Alamat Pengiriman -->
            <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm">
                <div class="flex items-center gap-2.5 mb-4 pb-3 border-b border-gray-100">
                    <i class="fa-solid fa-location-dot text-lg text-[#58CC02]"></i>
                    <h2 class="font-bold text-gray-800 text-base">Alamat Pengiriman</h2>
                </div>
                <div class="text-sm font-medium text-gray-800 space-y-1">
                    <p class="font-bold">{{ $user->namaLengkap }}</p>
                    <p class="text-gray-500">{{ $user->noTelp }}</p>
                    <p class="text-gray-600 mt-2 leading-relaxed">
                        {{ $user->alamatLengkap }}
                    </p>
                </div>
            </div>

            <!-- Ringkasan Produk -->
            <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm">
                <div class="flex items-center gap-2.5 mb-4 pb-3 border-b border-gray-100">
                    <i class="fa-solid fa-box-open text-lg text-[#58CC02]"></i>
                    <h2 class="font-bold text-gray-800 text-base">Produk yang Dibeli</h2>
                </div>
                <div class="divide-y divide-gray-100">
                    @foreach($keranjangs as $item)
                        <div class="flex items-center gap-4 py-4 first:pt-0 last:pb-0">
                            <div class="w-16 h-16 bg-gray-50 rounded-xl overflow-hidden border border-gray-100 flex items-center justify-center p-1.5 shrink-0">
                                @if($item->produk->fotoProduk)
                                    <img src="{{ asset('storage/' . $item->produk->fotoProduk) }}" class="w-full h-full object-cover rounded-lg">
                                @else
                                    <i class="fa-solid fa-image text-xl text-gray-300"></i>
                                @endif
                            </div>
                            <div class="grow min-w-0">
                                <h4 class="font-bold text-gray-800 text-sm truncate">{{ $item->produk->namaProduk }}</h4>
                                <p class="text-xs text-gray-400 mt-0.5">
                                    {{ $item->jumlah }} barang x Rp {{ number_format($item->produk->harga, 0, ',', '.') }}
                                </p>
                                <span class="inline-block text-[9px] font-bold uppercase bg-gray-100 text-gray-500 px-1.5 py-0.5 rounded mt-1">
                                    {{ $item->produk->kategori->karung }} Kg
                                </span>
                            </div>
                            <div class="text-right shrink-0">
                                <span class="font-bold text-gray-800 text-sm">
                                    Rp {{ number_format($item->produk->harga * $item->jumlah, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Sidebar Summary & Shipping Selection -->
        <div class="space-y-6">
            <!-- Kurir Selection -->
            <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm">
                <div class="flex items-center gap-2 mb-4 pb-3 border-b border-gray-50">
                    <i class="fa-solid fa-truck-fast text-base text-[#58CC02]"></i>
                    <h2 class="font-bold text-gray-800 text-sm">Opsi Pengiriman</h2>
                </div>

                <!-- Rates Selector -->
                <div class="space-y-3">
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider">Pilih Opsi Pengiriman</label>
                    
                    <!-- Loading Spinner -->
                    <div id="shipping-loading" class="flex flex-col items-center justify-center py-6 text-gray-400">
                        <i class="fa-solid fa-circle-notch fa-spin text-2xl mb-2 text-[#58CC02]"></i>
                        <span class="text-xs font-bold">Mengambil ongkos kirim...</span>
                    </div>

                    <!-- No shipping available error -->
                    <div id="shipping-error" class="hidden p-3 bg-red-50 text-red-600 rounded-xl text-xs font-bold">
                        Gagal mendapatkan opsi kurir. Silakan coba beberapa saat lagi.
                    </div>

                    <!-- Dropdown for rates -->
                    <select id="shipping_service" class="hidden w-full rounded-xl border-gray-200 bg-white py-2.5 px-3 text-sm focus:ring-0">
                        <option value="">-- Pilih Kurir / Layanan --</option>
                    </select>
                </div>
            </div>

            <!-- Ringkasan Pembayaran -->
            <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm">
                <h2 class="font-bold text-gray-800 text-sm mb-4 pb-3 border-b border-gray-55 uppercase tracking-wider text-gray-400">Ringkasan Pembayaran</h2>

                <div class="space-y-3 text-sm pb-4 border-b border-gray-100">
                    <div class="flex justify-between text-gray-500 font-medium">
                        <span>Total Harga ({{ $keranjangs->sum('jumlah') }} barang)</span>
                        <span class="text-gray-800 font-bold">Rp {{ number_format($totalPrice, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-gray-500 font-medium">
                        <span>Total Berat</span>
                        <span class="text-gray-800 font-bold">{{ number_format($totalWeight, 0, ',', '.') }} Kg</span>
                    </div>
                    <div class="flex justify-between text-gray-500 font-medium">
                        <span>Ongkos Kirim</span>
                        <span id="shipping-cost-display" class="text-gray-800 font-bold">Pilih Kurir</span>
                    </div>
                </div>

                <div class="flex justify-between items-center my-4">
                    <span class="text-gray-800 font-bold text-sm">Total Tagihan</span>
                    <span id="total-payment-display" class="text-xl font-bold text-[#58CC02]">
                        Rp {{ number_format($totalPrice, 0, ',', '.') }}
                    </span>
                </div>

                <!-- Final Form -->
                <form action="{{ route('agen.checkout.store') }}" method="POST" id="formCheckout">
                    @csrf
                    <input type="hidden" name="items" value="{{ request('items') }}">
                    <input type="hidden" name="alamat_pengiriman" value="{{ $user->alamatLengkap }}">
                    <input type="hidden" name="courier_name" id="hidden_courier_name">
                    <input type="hidden" name="courier_service" id="hidden_courier_service">
                    <input type="hidden" name="shipping_cost" id="hidden_shipping_cost" value="0">

                    <button type="submit" id="btnSubmitOrder" disabled class="w-full bg-gray-300 text-gray-500 py-3 rounded-xl font-bold text-sm flex items-center justify-center gap-2 cursor-not-allowed transition duration-200">
                        <i class="fa-solid fa-wallet"></i> Buat Pesanan
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', async function() {
    const originAreaId = @js($originAreaId);
    const destinationAreaId = @js($destinationAreaId);
    const weight = @js($totalWeight);
    const itemTotal = @js($totalPrice);

    const selectEl = document.getElementById('shipping_service');
    const loadingEl = document.getElementById('shipping-loading');
    const errorEl = document.getElementById('shipping-error');
    
    const shippingDisplay = document.getElementById('shipping-cost-display');
    const totalDisplay = document.getElementById('total-payment-display');
    const btnSubmit = document.getElementById('btnSubmitOrder');

    const hiddenCourier = document.getElementById('hidden_courier_name');
    const hiddenService = document.getElementById('hidden_courier_service');
    const hiddenCost = document.getElementById('hidden_shipping_cost');

    let availableRates = [];

    // Fetch shipping rates
    try {
        const response = await fetch("{{ route('agen.checkout.cek-ongkir') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                origin_area_id: originAreaId,
                destination_area_id: destinationAreaId,
                weight: weight
            })
        });

        if (response.ok) {
            availableRates = await response.json();
            loadingEl.classList.add('hidden');
            selectEl.classList.remove('hidden');

            availableRates.forEach((rate, index) => {
                const text = `${rate.courier_name} (${rate.courier_service_name}) - Rp ${rate.price.toLocaleString('id-ID')} [${rate.duration}]`;
                const option = new Option(text, index);
                selectEl.add(option);
            });
        } else {
            throw new Error('Gagal memuat ongkir');
        }
    } catch (e) {
        console.error(e);
        loadingEl.classList.add('hidden');
        errorEl.classList.remove('hidden');
    }

    // Handle courier rate selection
    selectEl.addEventListener('change', function() {
        const val = this.value;
        if (val === "") {
            shippingDisplay.textContent = 'Pilih Kurir';
            totalDisplay.textContent = 'Rp ' + itemTotal.toLocaleString('id-ID');
            btnSubmit.disabled = true;
            btnSubmit.className = 'w-full bg-gray-300 text-gray-500 py-3 rounded-xl font-bold text-sm flex items-center justify-center gap-2 cursor-not-allowed transition duration-200';
            
            hiddenCourier.value = "";
            hiddenService.value = "";
            hiddenCost.value = "0";
            return;
        }

        const selectedRate = availableRates[val];
        const cost = selectedRate.price;
        const total = itemTotal + cost;

        shippingDisplay.textContent = 'Rp ' + cost.toLocaleString('id-ID');
        totalDisplay.textContent = 'Rp ' + total.toLocaleString('id-ID');
        btnSubmit.disabled = false;
        btnSubmit.className = 'w-full bg-[#58CC02] hover:bg-[#46A302] text-white py-3 rounded-xl font-bold text-sm flex items-center justify-center gap-2 shadow-sm transition duration-200';

        hiddenCourier.value = selectedRate.courier_name;
        hiddenService.value = selectedRate.courier_service_name;
        hiddenCost.value = cost;
    });
});
</script>
@endsection
