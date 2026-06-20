@extends('layouts.app')

@section('title', 'Lacak Pesanan - AGRIS')

@section('content')

<x-navbar/>

<section class="relative min-h-[70vh] flex items-center overflow-hidden pt-20 pb-16">
    <div class="absolute inset-0 z-0">
        <img src="{{ asset('images/hero.svg') }}" class="w-full h-full object-cover" alt="Background">
        <div class="absolute inset-0 bg-gradient-to-b from-[#0f8629]/80 via-[#0f8629]/70 to-[#0f8629]/90"></div>
    </div>

    <div class="relative z-10 max-w-4xl mx-auto px-6 w-full text-center" data-aos="fade-up">
        <div class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-sm border border-white/20 text-white px-4 py-2 rounded-full text-xs font-bold mb-6">
            <i class="fa-solid fa-truck-fast"></i>
            Lacak Pesanan Anda
        </div>
        <h1 class="text-4xl md:text-5xl font-extrabold text-white mb-4 leading-tight">
            Pantau Status<br>
            <span class="text-green-300">Pengiriman</span> Pesanan
        </h1>
        <p class="text-white/80 text-sm md:text-base max-w-xl mx-auto mb-10 leading-relaxed">
            Masukkan ID Pesanan Anda untuk melacak status pengiriman secara real-time melalui integrasi Biteship.
        </p>

        <form id="trackForm" class="max-w-2xl mx-auto">
            <div class="relative flex flex-col sm:flex-row gap-3">
                <div class="relative flex-1">
                    <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                        <i class="fa-solid fa-magnifying-glass text-gray-400"></i>
                    </div>
                    <input
                        type="text"
                        id="trackInput"
                        name="query"
                        placeholder="Masukkan ID Pesanan (contoh: 6862ad20...)"
                        required
                        class="w-full pl-12 pr-4 py-4 rounded-2xl bg-white/95 backdrop-blur-sm border-2 border-transparent focus:border-green-400 focus:bg-white text-gray-800 placeholder-gray-400 text-sm font-semibold outline-none shadow-xl transition-all duration-300"
                        autocomplete="off"
                    >
                </div>
                <button
                    type="submit"
                    id="trackBtn"
                    class="px-8 py-4 bg-[#58CC02] hover:bg-[#46a302] text-white font-extrabold rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-300 text-sm flex items-center justify-center gap-2 cursor-pointer whitespace-nowrap"
                >
                    <i class="fa-solid fa-location-crosshairs"></i>
                    Lacak Sekarang
                </button>
            </div>
        </form>
    </div>
</section>

<section id="resultSection" class="hidden py-12 px-6 bg-gray-50 min-h-[50vh]">
    <div class="max-w-4xl mx-auto">

        <div id="errorState" class="hidden">
            <div class="bg-white rounded-3xl border border-red-100 shadow-sm p-8 text-center">
                <div class="w-16 h-16 bg-red-50 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fa-solid fa-triangle-exclamation text-red-400 text-2xl"></i>
                </div>
                <h3 class="text-lg font-extrabold text-gray-800 mb-2">Pesanan Tidak Ditemukan</h3>
                <p id="errorMessage" class="text-sm text-gray-500 leading-relaxed"></p>
            </div>
        </div>

        <div id="successState" class="hidden space-y-6">

            <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6 md:p-8">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6 pb-6 border-b border-gray-100">
                    <div>
                        <div class="flex items-center gap-3 mb-2">
                            <div class="w-10 h-10 rounded-2xl bg-[#58CC02]/10 flex items-center justify-center text-[#58CC02]">
                                <i class="fa-solid fa-box-open text-lg"></i>
                            </div>
                            <div>
                                <h2 class="font-extrabold text-gray-800 text-base">Pesanan <span id="orderId" class="font-mono"></span></h2>
                                <p id="orderDate" class="text-xs text-gray-400 font-semibold"></p>
                            </div>
                        </div>
                    </div>
                    <div id="statusBadge" class="inline-flex items-center gap-2 px-4 py-2 rounded-full font-bold text-xs uppercase tracking-wider">
                        <i id="statusIcon" class="fa-solid"></i>
                        <span id="statusLabel"></span>
                    </div>
                </div>

                <div class="flex items-center gap-3 mb-4 pb-3 border-b border-gray-100">
                    <div class="w-9 h-9 rounded-2xl bg-blue-50 flex items-center justify-center text-blue-600">
                        <i class="fa-solid fa-truck-fast text-base"></i>
                    </div>
                    <div>
                        <h3 class="font-extrabold text-gray-800 text-sm uppercase tracking-wider">Info Pengiriman</h3>
                        <p class="text-xs text-gray-400">Detail kurir dan pengiriman pesanan</p>
                    </div>
                </div>
                <div id="shippingInfoGrid" class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                </div>

                <div class="mt-4 bg-gray-50 rounded-2xl p-4 border border-gray-100">
                    <div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Alamat Pengiriman</div>
                    <p id="alamatPengiriman" class="text-sm text-gray-700 font-medium leading-relaxed"></p>
                </div>
            </div>

            <div id="trackingTimelineCard" class="hidden bg-white rounded-3xl border border-gray-100 shadow-sm p-6 md:p-8">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6 pb-4 border-b border-gray-100">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-2xl bg-purple-50 flex items-center justify-center text-purple-600">
                            <i class="fa-solid fa-map-location-dot text-base"></i>
                        </div>
                        <div>
                            <h3 class="font-extrabold text-gray-800 text-sm uppercase tracking-wider">Tracking Pengiriman</h3>
                            <p class="text-xs text-gray-400">Data real-time dari Biteship</p>
                        </div>
                    </div>
                    <div id="biteshipTrackButtonContainer"></div>
                </div>
                <div id="trackingTimeline" class="relative border-l-2 border-dashed border-gray-200 pl-6 ml-2.5 space-y-5">
                </div>
            </div>

            <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6 md:p-8">
                <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-100">
                    <div class="w-9 h-9 rounded-2xl bg-blue-50 flex items-center justify-center text-blue-600">
                        <i class="fa-solid fa-bag-shopping text-base"></i>
                    </div>
                    <h3 class="font-extrabold text-gray-800 text-xs uppercase tracking-wider text-gray-400">Daftar Produk</h3>
                </div>
                <div id="orderItems" class="divide-y divide-gray-100">
                </div>
                <div class="flex justify-between items-center pt-4 mt-4 border-t border-gray-100">
                    <span class="font-extrabold text-gray-800 text-sm">Total Pesanan</span>
                    <span id="orderTotal" class="text-xl font-black text-[#58CC02]"></span>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-16 px-6 bg-white">
    <div class="max-w-5xl mx-auto">
        <div class="text-center max-w-2xl mx-auto mb-12" data-aos="fade-up">
            <h2 class="text-2xl md:text-3xl font-extrabold text-gray-800 mb-3">Cara Melacak Pesanan</h2>
            <p class="text-gray-500 text-sm">Ikuti langkah-langkah mudah berikut untuk memantau status pengiriman Anda</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-gray-50 rounded-3xl p-6 border border-gray-100 text-center group hover:shadow-lg hover:border-green-200 transition-all duration-300" data-aos="zoom-in">
                <div class="w-14 h-14 rounded-2xl bg-green-50 flex items-center justify-center text-[#58CC02] text-2xl mx-auto mb-4 group-hover:bg-[#58CC02] group-hover:text-white transition-all duration-300">
                    <i class="fa-solid fa-hashtag"></i>
                </div>
                <h3 class="font-extrabold text-gray-800 text-sm mb-2">1. Siapkan ID Pesanan</h3>
                <p class="text-xs text-gray-500 leading-relaxed">Salin ID Pesanan dari halaman riwayat pesanan atau email konfirmasi Anda.</p>
            </div>
            <div class="bg-gray-50 rounded-3xl p-6 border border-gray-100 text-center group hover:shadow-lg hover:border-green-200 transition-all duration-300" data-aos="zoom-in" data-aos-delay="100">
                <div class="w-14 h-14 rounded-2xl bg-green-50 flex items-center justify-center text-[#58CC02] text-2xl mx-auto mb-4 group-hover:bg-[#58CC02] group-hover:text-white transition-all duration-300">
                    <i class="fa-solid fa-keyboard"></i>
                </div>
                <h3 class="font-extrabold text-gray-800 text-sm mb-2">2. Masukkan di Kolom Pencarian</h3>
                <p class="text-xs text-gray-500 leading-relaxed">Tempelkan ID Pesanan Anda pada kolom pencarian di atas, lalu klik "Lacak Sekarang".</p>
            </div>
            <div class="bg-gray-50 rounded-3xl p-6 border border-gray-100 text-center group hover:shadow-lg hover:border-green-200 transition-all duration-300" data-aos="zoom-in" data-aos-delay="200">
                <div class="w-14 h-14 rounded-2xl bg-green-50 flex items-center justify-center text-[#58CC02] text-2xl mx-auto mb-4 group-hover:bg-[#58CC02] group-hover:text-white transition-all duration-300">
                    <i class="fa-solid fa-map-location-dot"></i>
                </div>
                <h3 class="font-extrabold text-gray-800 text-sm mb-2">3. Pantau Real-Time</h3>
                <p class="text-xs text-gray-500 leading-relaxed">Lihat status pengiriman secara real-time yang terintegrasi langsung dengan Biteship.</p>
            </div>
        </div>
    </div>
</section>

<x-footer/>

@push('scripts')
<script>
let currentResi = null;

document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('trackForm');
    const input = document.getElementById('trackInput');
    const btn = document.getElementById('trackBtn');
    const resultSection = document.getElementById('resultSection');
    const errorState = document.getElementById('errorState');
    const successState = document.getElementById('successState');

    const urlParams = new URLSearchParams(window.location.search);
    const queryParam = urlParams.get('q');
    if (queryParam) {
        input.value = queryParam;
        doSearch(queryParam);
    }

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        const query = input.value.trim();
        if (query.length < 3) return;
        doSearch(query);
    });

    function doSearch(query) {

        btn.disabled = true;
        btn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin"></i> Mencari...';
        btn.classList.add('opacity-70');
        input.disabled = true;

        resultSection.classList.remove('hidden');
        errorState.classList.add('hidden');
        successState.classList.add('hidden');

        fetch('{{ route("guest.track.search") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || document.querySelector('input[name="_token"]')?.value,
                'Accept': 'application/json',
            },
            body: JSON.stringify({ query: query })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                renderResult(data.data);
            } else {
                showError(data.message || 'Terjadi kesalahan saat mencari pesanan.');
            }
        })
        .catch(err => {
            showError('Gagal terhubung ke server. Pastikan koneksi internet Anda stabil dan coba lagi.');
        })
        .finally(() => {
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-location-crosshairs"></i> Lacak Sekarang';
            btn.classList.remove('opacity-70');
            input.disabled = false;

            resultSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
        });
    }

    function showError(message) {
        errorState.classList.remove('hidden');
        successState.classList.add('hidden');
        document.getElementById('errorMessage').textContent = message;
    }

    function renderResult(data) {
        errorState.classList.add('hidden');
        successState.classList.remove('hidden');

        document.getElementById('orderId').textContent = data.order_id;
        document.getElementById('orderDate').textContent = data.tanggal_pesanan + ' WIB';

        const badge = document.getElementById('statusBadge');
        const colorMap = {
            amber: 'bg-amber-50 text-amber-600 border border-amber-100',
            blue: 'bg-blue-50 text-blue-600 border border-blue-100',
            purple: 'bg-purple-50 text-purple-600 border border-purple-100',
            green: 'bg-green-50 text-green-600 border border-green-100',
            red: 'bg-red-50 text-red-600 border border-red-100',
            gray: 'bg-gray-50 text-gray-600 border border-gray-100',
        };
        badge.className = 'inline-flex items-center gap-2 px-4 py-2 rounded-full font-bold text-xs uppercase tracking-wider ' + (colorMap[data.status_color] || colorMap.gray);
        document.getElementById('statusIcon').className = 'fa-solid ' + data.status_icon;
        document.getElementById('statusLabel').textContent = data.status_label;

        const grid = document.getElementById('shippingInfoGrid');
        grid.innerHTML = '';

        function addInfoCard(label, value, icon, extraHtml) {
            const card = document.createElement('div');
            card.className = 'bg-gray-50 rounded-2xl p-4 border border-gray-100';
            card.innerHTML = `
                <div class="flex items-center gap-2 mb-1.5">
                    <div class="w-6 h-6 rounded-lg bg-white flex items-center justify-center text-gray-400 shadow-sm">
                        <i class="fa-solid ${icon} text-[10px]"></i>
                    </div>
                    <div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">${label}</div>
                </div>
                <div class="text-sm font-extrabold text-gray-800">${value}</div>
                ${extraHtml || ''}
            `;
            grid.appendChild(card);
        }

        if (data.courier_info) {
            addInfoCard('Kurir Pengiriman', data.courier_info.toUpperCase(), 'fa-truck');
        } else if (data.is_pickup) {
            addInfoCard('Metode Pengiriman', 'Ambil di Tempat', 'fa-warehouse');
        } else {
            addInfoCard('Kurir Pengiriman', 'Belum ditentukan', 'fa-truck');
        }

        if (data.no_resi) {
            currentResi = data.no_resi;
            addInfoCard('Nomor Resi', data.no_resi, 'fa-barcode',
                `<button onclick="copyResi(this)" class="mt-2 text-[10px] font-bold text-[#58CC02] hover:text-green-700 transition cursor-pointer flex items-center gap-1">
                    <i class="fa-regular fa-copy"></i> Salin Resi
                </button>`
            );
        } else {
            currentResi = null;
            if (['dikirim', 'selesai'].includes(data.status_pesanan)) {
                addInfoCard('Nomor Resi', 'Belum tersedia', 'fa-barcode');
            }
        }

        if (data.is_pickup) {
            addInfoCard('Tipe', 'Pengambilan Mandiri', 'fa-store');
        } else if (data.courier_info) {
            addInfoCard('Tipe', 'Pengiriman Kurir', 'fa-route');
        }

        addInfoCard('Tanggal Pesanan', data.tanggal_pesanan + ' WIB', 'fa-calendar');

        document.getElementById('alamatPengiriman').textContent = data.alamat_pengiriman;

        const biteshipBtnContainer = document.getElementById('biteshipTrackButtonContainer');
        biteshipBtnContainer.innerHTML = '';
        const trackId = data.biteship_order_id || data.no_resi;
        if (trackId && !data.is_pickup) {
            biteshipBtnContainer.innerHTML = `
                <a href="https://track-sandbox.biteship.com/${trackId}" target="_blank" class="inline-flex items-center gap-1.5 bg-blue-50 border border-blue-100 px-3.5 py-2 rounded-xl text-blue-600 font-extrabold text-xs hover:bg-blue-100 transition shadow-xs">
                    <i class="fa-solid fa-arrow-up-right-from-square"></i> Lacak via Biteship
                </a>
            `;
        }

        const timelineCard = document.getElementById('trackingTimelineCard');
        const timeline = document.getElementById('trackingTimeline');

        if (data.has_biteship_data && data.tracking_history.length > 0) {
            timelineCard.classList.remove('hidden');
            timeline.innerHTML = '';

            data.tracking_history.forEach((event, index) => {
                const isLatest = index === 0;
                const statusColors = {
                    confirmed: 'blue',
                    allocated: 'blue',
                    pickingUp: 'amber',
                    picking_up: 'amber',
                    picked: 'amber',
                    inTransit: 'purple',
                    in_transit: 'purple',
                    droppingOff: 'purple',
                    dropping_off: 'purple',
                    delivered: 'green',
                    rejected: 'red',
                    cancelled: 'red',
                    returned: 'red',
                };
                const dotColor = isLatest ? 'bg-[#58CC02]' : 'bg-gray-300';
                const labelColor = isLatest ? 'text-[#58CC02]' : 'text-gray-700';

                const dateFormatted = formatDate(event.updated_at);

                const div = document.createElement('div');
                div.className = 'relative';
                div.innerHTML = `
                    <span class="absolute -left-8.5 top-0.5 w-3.5 h-3.5 rounded-full ${dotColor} border-2 border-white shadow-sm"></span>
                    <div class="text-xs">
                        <span class="text-gray-400 font-bold block mb-0.5">${dateFormatted}</span>
                        <p class="font-extrabold ${labelColor}">${event.status_label}</p>
                        <p class="text-gray-500 mt-0.5 font-medium leading-relaxed">${event.note}</p>
                    </div>
                `;
                timeline.appendChild(div);
            });
        } else {

            timelineCard.classList.remove('hidden');
            timeline.innerHTML = '';
            renderOrderSteps(timeline, data);
        }

        const itemsContainer = document.getElementById('orderItems');
        itemsContainer.innerHTML = '';
        data.items.forEach(item => {
            const div = document.createElement('div');
            div.className = 'flex items-center gap-4 py-4 first:pt-0 last:pb-0';
            div.innerHTML = `
                <div class="w-12 h-12 bg-gray-50 rounded-xl overflow-hidden border border-gray-100 flex items-center justify-center p-1 shrink-0">
                    ${item.foto
                        ? `<img src="${item.foto}" class="w-full h-full object-cover">`
                        : `<i class="fa-solid fa-image text-xl text-gray-300"></i>`
                    }
                </div>
                <div class="grow min-w-0">
                    <h4 class="font-bold text-gray-800 text-xs truncate">${item.nama}</h4>
                    <p class="text-[11px] text-gray-400 font-semibold mt-0.5">${item.jumlah} barang x Rp ${formatRupiah(item.harga_satuan)}</p>
                </div>
                <div class="text-right shrink-0">
                    <span class="font-bold text-gray-800 text-xs">Rp ${formatRupiah(item.subtotal)}</span>
                </div>
            `;
            itemsContainer.appendChild(div);
        });

        document.getElementById('orderTotal').textContent = 'Rp ' + formatRupiah(data.total);
    }

    function renderOrderSteps(timeline, data) {
        const steps = [
            { label: 'Pesanan Dibuat', desc: 'Pesanan berhasil masuk ke dalam sistem.', icon: 'fa-check', active: true },
            { label: 'Pembayaran Diterima', desc: 'Pembayaran berhasil diverifikasi.', icon: 'fa-credit-card', active: ['diproses', 'dikirim', 'selesai'].includes(data.status_pesanan) },
            { label: data.is_pickup ? 'Siap Diambil' : 'Dalam Pengiriman', desc: data.is_pickup ? 'Pesanan siap diambil di gudang AGRIS.' : 'Paket sedang dalam proses pengiriman.', icon: data.is_pickup ? 'fa-warehouse' : 'fa-truck', active: ['dikirim', 'selesai'].includes(data.status_pesanan) },
            { label: 'Pesanan Selesai', desc: 'Pesanan telah diterima oleh penerima.', icon: 'fa-circle-check', active: data.status_pesanan === 'selesai' },
        ];

        steps.forEach((step) => {
            const div = document.createElement('div');
            div.className = 'relative';
            div.innerHTML = `
                <span class="absolute -left-12 top-0.5 flex h-8 w-8 items-center justify-center rounded-full ${step.active ? 'bg-[#58CC02] text-white' : 'bg-gray-100 text-gray-400'} shadow-sm ring-4 ring-white">
                    <i class="fa-solid ${step.icon} text-xs"></i>
                </span>
                <div>
                    <h3 class="text-sm font-extrabold ${step.active ? 'text-gray-800' : 'text-gray-400'}">${step.label}</h3>
                    <p class="text-xs ${step.active ? 'text-gray-500' : 'text-gray-400'} mt-0.5">${step.desc}</p>
                </div>
            `;
            timeline.appendChild(div);
        });
    }

    function formatDate(dateStr) {
        if (!dateStr) return '';
        try {
            const d = new Date(dateStr);
            const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
            const day = d.getDate();
            const month = months[d.getMonth()];
            const year = d.getFullYear();
            const hours = String(d.getHours()).padStart(2, '0');
            const mins = String(d.getMinutes()).padStart(2, '0');
            return `${day} ${month} ${year}, ${hours}:${mins}`;
        } catch(e) {
            return dateStr;
        }
    }

    function formatRupiah(num) {
        return new Intl.NumberFormat('id-ID').format(num);
    }
});

function copyResi(btn) {
    const resi = currentResi;
    if (resi) {
        navigator.clipboard.writeText(resi).then(() => {
            const originalText = btn.innerHTML;
            btn.innerHTML = '<i class="fa-solid fa-check text-xs"></i> Tersalin!';
            setTimeout(() => {
                btn.innerHTML = originalText;
            }, 1500);
        });
    }
}
</script>
@endpush
@endsection
