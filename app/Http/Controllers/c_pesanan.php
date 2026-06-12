<?php

namespace App\Http\Controllers;

use App\Events\OrderStatusUpdated;
use App\Models\Desa;
use App\Models\DetailPesanan;
use App\Models\Keranjang;
use App\Models\Pembayaran;
use App\Models\Pesanan;
use App\Models\Refund;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class c_pesanan extends Controller
{
    public static function cancelOldOrders()
    {
        $oneWeekAgo = now()->subDays(7);

        $expiredOrders = Pesanan::with('detailPesanans.produk')
            ->whereIn('status_pesanan', ['pending', 'diproses'])
            ->where('created_at', '<', $oneWeekAgo)
            ->get();

        foreach ($expiredOrders as $pesanan) {
            DB::transaction(function () use ($pesanan) {
                $pesanan->update([
                    'status_pesanan' => 'dibatalkan',
                    'deskripsi' => $pesanan->deskripsi.' | Batal otomatis oleh sistem karena tidak diproses dalam 1 minggu.',
                ]);

                if ($pesanan->pembayaran) {
                    $pesanan->pembayaran->update([
                        'statusPembayaran' => 'gagal',
                    ]);
                }

                foreach ($pesanan->detailPesanans as $detail) {
                    if ($detail->produk) {
                        $detail->produk->increment('stok', $detail->jumlahPesanan);
                    }
                }
            });
        }
    }

    public function index(Request $request)
    {
        self::cancelOldOrders();

        $activeTab = $request->input('tab', 'transaksi');

        if ($activeTab === 'keuangan') {
            $subTab = $request->input('sub', 'selesai');
            $pesanans = [];
            $refunds = [];

            if ($subTab === 'refund') {
                $refunds = Refund::whereHas('pesanan', function ($q) {
                    $q->where('userId', Auth::id());
                })
                    ->with(['pesanan', 'detailPesanan.produk'])
                    ->orderBy('created_at', 'desc')
                    ->get();
            } elseif ($subTab === 'batal') {
                $pesanans = Pesanan::with(['detailPesanans.produk', 'pembayaran'])
                    ->where('userId', Auth::id())
                    ->where('status_pesanan', 'dibatalkan')
                    ->orderBy('created_at', 'desc')
                    ->get();
            } else {
                $pesanans = Pesanan::with(['detailPesanans.produk', 'pembayaran'])
                    ->where('userId', Auth::id())
                    ->where('status_pesanan', 'selesai')
                    ->orderBy('created_at', 'desc')
                    ->get();
            }

            return view('agen.pesanan.index', compact('activeTab', 'subTab', 'pesanans', 'refunds'));
        }

        $status = $request->input('status', 'all');
        $query = Pesanan::with(['detailPesanans.produk', 'pembayaran'])
            ->where('userId', Auth::id())
            ->whereIn('status_pesanan', ['diproses', 'dikirim']);

        if ($status !== 'all') {
            $query->where('status_pesanan', $status);
        }

        $pesanans = $query->orderBy('created_at', 'desc')->get();

        return view('agen.pesanan.index', compact('activeTab', 'status', 'pesanans'));
    }

    public function show($id)
    {
        $pesanan = Pesanan::with(['detailPesanans.produk', 'pembayaran'])
            ->where('userId', Auth::id())
            ->findOrFail($id);

        if (request('status') === 'success' && $pesanan->status_pesanan === 'pending') {
            DB::transaction(function () use ($pesanan) {
                if ($pesanan->pembayaran) {
                    $pesanan->pembayaran->update([
                        'statusPembayaran' => 'berhasil',
                        'waktuDibayar' => now(),
                    ]);
                }
                $pesanan->update(['status_pesanan' => 'diproses']);

                foreach ($pesanan->detailPesanans as $detail) {
                    Keranjang::where('userId', $pesanan->userId)
                        ->where('produkId', $detail->produkId)
                        ->delete();
                }
            });
            $pesanan->refresh();
        }

        $trackingData = $this->getBiteshipTracking($pesanan->deskripsi);
        if ($trackingData) {
            $biteshipStatus = $trackingData['status'] ?? null;
            if ($biteshipStatus === 'delivered') {
                if ($pesanan->status_pesanan !== 'selesai') {
                    $pesanan->update(['status_pesanan' => 'selesai']);
                    $pesanan->refresh();
                    event(new OrderStatusUpdated($pesanan));
                }
            } else {
                $shippingStatuses = [
                    'confirmed', 'allocated', 'scheduled',
                    'pickingUp', 'picking_up',
                    'picked',
                    'inTransit', 'in_transit',
                    'droppingOff', 'dropping_off',
                ];
                if (in_array($biteshipStatus, $shippingStatuses) && $pesanan->status_pesanan === 'diproses') {
                    $pesanan->update(['status_pesanan' => 'dikirim']);
                    $pesanan->refresh();
                    event(new OrderStatusUpdated($pesanan));
                }
            }
        }

        return view('agen.pesanan.show', compact('pesanan', 'trackingData'));
    }

    public function checkoutForm(Request $request)
    {
        if (! $request->filled('items')) {
            return redirect()->route('agen.keranjang.index')->with('error', 'Silakan pilih produk yang ingin dicheckout.');
        }

        $itemIds = explode(',', $request->items);
        $keranjangs = Keranjang::with('produk.kategori')
            ->whereIn('id', $itemIds)
            ->where('userId', Auth::id())
            ->get();

        if ($keranjangs->isEmpty()) {
            return redirect()->route('agen.keranjang.index')->with('error', 'Produk tidak ditemukan di keranjang.');
        }

        $user = Auth::user();

        if (! $user->desaId) {
            return redirect()->route('agen.profile')->with('error', 'Silakan lengkapi wilayah alamat Anda di profil terlebih dahulu sebelum melakukan checkout.');
        }

        $totalPrice = 0;
        $totalWeight = 0;
        foreach ($keranjangs as $item) {
            $totalPrice += $item->produk->harga * $item->jumlah;
            $totalWeight += $item->produk->kategori->karung * $item->jumlah;
        }

        $admin = User::where('isAdmin', true)->first();
        $originAreaId = null;
        if ($admin && $admin->desaId) {
            $originAreaId = $this->getOrCreateBiteshipArea($admin->desaId);
        }
        if (! $originAreaId) {
            $originAreaId = 'IDNP6IDNC148IDND843IDZ12250';
        }

        $destinationAreaId = $this->getOrCreateBiteshipArea($user->desaId);

        $midtransClientKey = config('services.midtrans.client_key');

        return view('agen.pesanan.checkout', compact(
            'keranjangs',
            'totalPrice',
            'totalWeight',
            'user',
            'originAreaId',
            'destinationAreaId',
            'midtransClientKey'
        ));
    }

    public function cekOngkir(Request $request)
    {
        $request->validate([
            'origin_area_id' => 'required|string',
            'destination_area_id' => 'required|string',
            'weight' => 'required|numeric|min:0.1',
        ]);

        $originAreaId = $request->origin_area_id;
        $destinationAreaId = $request->destination_area_id;
        $weightKg = (float) $request->weight;
        $weightGrams = (int) ($weightKg * 1000);

        $cacheKey = 'biteship_rates_'.md5($originAreaId.'_'.$destinationAreaId.'_'.$weightGrams);

        if (Cache::has($cacheKey)) {
            $cachedRates = Cache::get($cacheKey);
            if (! empty($cachedRates)) {
                return response()->json($cachedRates);
            }
        }

        $apiKey = config('services.biteship.key');
        $baseUrl = $this->getBiteshipBaseUrl();

        try {
            $response = Http::withToken($apiKey)
                ->timeout(7)
                ->post("$baseUrl/rates/couriers", [
                    'origin_area_id' => $originAreaId,
                    'destination_area_id' => $destinationAreaId,
                    'couriers' => 'jne,sicepat,jnt,tiki,lion,ninja,anteraja',
                    'items' => [
                        [
                            'name' => 'Produk AGRIS',
                            'description' => 'Produk Agroindustri AGRIS',
                            'value' => 0,
                            'weight' => $weightGrams,
                        ],
                    ],
                ]);

            if ($response->successful()) {
                $rates = [];
                $biteshipRates = $response->json()['pricing'] ?? [];
                foreach ($biteshipRates as $rate) {
                    $rates[] = [
                        'courier_name' => strtoupper($rate['courier_code']),
                        'courier_service_code' => $rate['courier_service_code'],
                        'courier_service_name' => $rate['courier_service_name'],
                        'price' => $rate['price'],
                        'duration' => $rate['duration'],
                    ];
                }
                if (! empty($rates)) {

                    Cache::put($cacheKey, $rates, now()->addDays(7));

                    return response()->json($rates);
                }
            }
        } catch (\Exception $e) {
            Log::error('Biteship Cek Ongkir Error: '.$e->getMessage());
        }

        $fallbackRates = [
            [
                'courier_name' => 'JNE',
                'courier_service_code' => 'reg',
                'courier_service_name' => 'REG',
                'price' => round(12000 * $weightKg),
                'duration' => '2-3 Hari',
            ],
            [
                'courier_name' => 'SiCepat',
                'courier_service_code' => 'gokil',
                'courier_service_name' => 'GOKIL',
                'price' => round(9000 * $weightKg),
                'duration' => '3-5 Hari',
            ],
            [
                'courier_name' => 'J&T',
                'courier_service_code' => 'ez',
                'courier_service_name' => 'EZ',
                'price' => round(11000 * $weightKg),
                'duration' => '2-4 Hari',
            ],
        ];

        return response()->json($fallbackRates);
    }

    public function checkoutStore(Request $request)
    {
        $request->validate([
            'items' => 'required|string',
            'delivery_type' => 'required|in:kirim,ambil',
            'alamat_pengiriman' => 'required|string',
            'courier_name' => 'required_if:delivery_type,kirim|nullable|string',
            'courier_service' => 'required_if:delivery_type,kirim|nullable|string',
            'shipping_cost' => 'required|numeric|min:0',
        ]);

        $itemIds = explode(',', $request->items);
        $keranjangs = Keranjang::with('produk.kategori')
            ->whereIn('id', $itemIds)
            ->where('userId', Auth::id())
            ->get();

        if ($keranjangs->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'Produk tidak ditemukan di keranjang.'], 422);
        }

        foreach ($keranjangs as $item) {
            if ($item->produk->stok < $item->jumlah) {
                return response()->json(['success' => false, 'message' => "Stok produk '{$item->produk->namaProduk}' tidak mencukupi."], 422);
            }
        }

        $shippingCost = 0;
        $courierName = $request->delivery_type === 'ambil' ? 'Ambil di Tempat' : $request->courier_name;
        $courierService = $request->delivery_type === 'ambil' ? 'Ambil Sendiri' : $request->courier_service;

        if ($request->delivery_type === 'kirim') {

            $admin = User::where('isAdmin', true)->first();
            $originAreaId = ($admin && $admin->desaId) ? $this->getOrCreateBiteshipArea($admin->desaId) : null;
            if (! $originAreaId) {
                $originAreaId = 'IDNP6IDNC148IDND843IDZ12250';
            }
            $destinationAreaId = $this->getOrCreateBiteshipArea(Auth::user()->desaId);

            $totalWeight = 0;
            foreach ($keranjangs as $item) {
                $totalWeight += $item->produk->kategori->karung * $item->jumlah;
            }
            $weightGrams = (int) ($totalWeight * 1000);

            $apiKey = config('services.biteship.key');
            $baseUrl = $this->getBiteshipBaseUrl();

            try {
                $response = Http::withToken($apiKey)
                    ->timeout(8)
                    ->post("$baseUrl/rates/couriers", [
                        'origin_area_id' => $originAreaId,
                        'destination_area_id' => $destinationAreaId,
                        'couriers' => strtolower($request->courier_name),
                        'items' => [
                            [
                                'name' => 'Produk AGRIS',
                                'description' => 'Produk Agroindustri AGRIS',
                                'value' => 0,
                                'weight' => $weightGrams,
                            ],
                        ],
                    ]);

                if ($response->successful()) {
                    $biteshipRates = $response->json()['pricing'] ?? [];
                    foreach ($biteshipRates as $rate) {
                        if (strtolower($rate['courier_code']) === strtolower($request->courier_name) &&
                            strtolower($rate['courier_service_code']) === strtolower($request->courier_service)) {
                            $shippingCost = $rate['price'];
                            break;
                        }
                    }
                }
            } catch (\Exception $e) {
                Log::error('Biteship Cek Ongkir Verification Error: '.$e->getMessage());
            }

            if ($shippingCost === 0) {
                $shippingCost = $request->shipping_cost;
            }
        }

        DB::beginTransaction();
        try {

            $pesanan = Pesanan::create([
                'userId' => Auth::id(),
                'tanggal_pesanan' => now(),
                'alamat_pengiriman' => $request->delivery_type === 'ambil' ? 'Diambil di Gudang Utama AGRIS (Patrang, Jember)' : $request->alamat_pengiriman,
                'desaId' => Auth::user()->desaId,
                'status_pesanan' => 'pending',
                'deskripsi' => 'Opsi: '.($request->delivery_type === 'ambil' ? 'Ambil di Tempat' : 'Kirim via '.strtoupper($courierName)." ({$courierService})").' | Ongkir: Rp '.number_format($shippingCost, 0, ',', '.'),
            ]);

            $totalItemPrice = 0;

            foreach ($keranjangs as $item) {
                $subtotal = $item->produk->harga * $item->jumlah;
                $totalItemPrice += $subtotal;

                DetailPesanan::create([
                    'pesananId' => $pesanan->id,
                    'produkId' => $item->produkId,
                    'jumlahPesanan' => $item->jumlah,
                    'harga_satuan' => $item->produk->harga,
                    'subtotal' => $subtotal,
                ]);

                $item->produk->decrement('stok', $item->jumlah);
            }

            $grossAmount = $totalItemPrice + $shippingCost;

            $serverKey = config('services.midtrans.server_key');
            $isProduction = config('services.midtrans.is_production', false);
            $snapToken = null;

            if (! empty($serverKey)) {
                $snapApiUrl = $isProduction
                    ? 'https://app.midtrans.com/snap/v1/transactions'
                    : 'https://app.sandbox.midtrans.com/snap/v1/transactions';

                try {
                    $response = Http::withBasicAuth($serverKey, '')
                        ->timeout(10)
                        ->post($snapApiUrl, [
                            'transaction_details' => [
                                'order_id' => $pesanan->id,
                                'gross_amount' => (int) $grossAmount,
                            ],
                            'customer_details' => [
                                'first_name' => Auth::user()->namaLengkap,
                                'email' => Auth::user()->email,
                                'phone' => Auth::user()->noTelp,
                            ],
                            'expiry' => [
                                'duration' => 24,
                                'unit' => 'hours',
                            ],
                        ]);

                    if ($response->successful()) {
                        $snapToken = $response->json()['token'] ?? null;
                    } else {
                        Log::error('Midtrans Snap Token request failed: '.$response->body());
                    }
                } catch (\Exception $e) {
                    Log::error('Midtrans connection exception: '.$e->getMessage());
                }
            }

            if (! $snapToken) {
                throw new \Exception('Gagal terhubung dengan server Midtrans. Pastikan Server Key dan Client Key Sandbox di file .env Anda sudah terkonfigurasi dengan benar.');
            }

            Pembayaran::create([
                'pesananId' => $pesanan->id,
                'statusPembayaran' => 'pending',
                'totalPembayaran' => $grossAmount,
                'snapToken' => $snapToken,
                'transactionId' => null,
                'paymentType' => 'midtrans_snap',
                'payment_info' => json_encode([
                    'method' => 'midtrans',
                    'details' => [
                        'snap_token' => $snapToken,
                    ],
                ]),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'snap_token' => $snapToken,
                'order_id' => $pesanan->id,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Simpan Pesanan Gagal: '.$e->getMessage());

            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan internal saat memproses pesanan.'], 500);
        }
    }

    private function getOrCreateBiteshipArea(string $desaId)
    {
        $area = DB::table('biteship_areas')->where('desaId', $desaId)->first();
        if ($area) {
            return $area->biteship_area_id;
        }

        $desa = Desa::with('kecamatan')->find($desaId);
        if (! $desa || ! $desa->kecamatan) {
            return null;
        }

        $kecName = $desa->kecamatan->namaKecamatan;

        try {
            $apiKey = config('services.biteship.key');
            $baseUrl = $this->getBiteshipBaseUrl();

            $response = Http::withToken($apiKey)
                ->timeout(5)
                ->get("$baseUrl/maps/areas", [
                    'countries' => 'ID',
                    'input' => $kecName,
                    'type' => 'single',
                ]);

            if ($response->successful()) {
                $areas = $response->json()['areas'] ?? [];
                if (! empty($areas)) {
                    $areaData = $areas[0];
                    DB::table('biteship_areas')->updateOrInsert(
                        ['desaId' => $desaId],
                        [
                            'biteship_area_id' => $areaData['id'],
                            'biteship_name' => $areaData['name'],
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]
                    );

                    return $areaData['id'];
                }
            }
        } catch (\Exception $e) {
            Log::error('Get/Create Biteship Area ID Gagal: '.$e->getMessage());
        }

        return null;
    }

    public function cancelOrder($id)
    {
        $pesanan = Pesanan::where('userId', Auth::id())->findOrFail($id);

        if (! in_array($pesanan->status_pesanan, ['pending', 'diproses'])) {
            return redirect()->back()->with('error', 'Pesanan tidak dapat dibatalkan karena sudah dalam pengiriman atau selesai.');
        }

        DB::beginTransaction();
        try {
            $pesanan->update([
                'status_pesanan' => 'dibatalkan',
            ]);
            event(new OrderStatusUpdated($pesanan));

            $pembayaran = $pesanan->pembayaran;
            $hasPaid = $pembayaran && $pembayaran->statusPembayaran === 'berhasil';

            if ($pembayaran) {
                if (!$hasPaid) {
                    $pembayaran->update([
                        'statusPembayaran' => 'gagal',
                    ]);
                }
            }

            foreach ($pesanan->detailPesanans as $detail) {
                if ($detail->produk) {
                    $detail->produk->increment('stok', $detail->jumlahPesanan);
                }

                if ($hasPaid) {
                    Refund::create([
                        'pesananId' => $pesanan->id,
                        'detailPesananId' => $detail->id,
                        'jumlah' => $detail->jumlahPesanan,
                        'nominal' => $detail->subtotal,
                        'alasan' => 'Dibatalkan oleh Agen',
                        'foto_bukti' => 'refunds/cancel_order.png',
                        'status' => 'pending',
                        'catatan_admin' => 'Menunggu persetujuan admin.',
                    ]);
                }
            }

            DB::commit();

            return redirect()->back()->with('success', 'Pesanan Anda berhasil dibatalkan dan pengajuan refund sedang menunggu persetujuan admin.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Pembatalan Pesanan Gagal: '.$e->getMessage());

            return redirect()->back()->with('error', 'Gagal membatalkan pesanan. Silakan coba beberapa saat lagi.');
        }
    }

    public function lacakPengiriman($id)
    {
        $pesanan = Pesanan::findOrFail($id);

        $biteshipOrderId = null;
        $noResi = null;
        if ($pesanan->deskripsi) {
            $parts = explode('|', $pesanan->deskripsi);
            foreach ($parts as $part) {
                $part = trim($part);
                $lowerPart = strtolower($part);
                if (str_starts_with($lowerPart, 'biteship order id:')) {
                    $biteshipOrderId = trim(substr($part, 18));
                } elseif (str_starts_with($lowerPart, 'no resi:')) {
                    $noResi = trim(substr($part, 8));
                }
            }
        }

        $trackId = $biteshipOrderId ?: $noResi;

        if ($trackId && ! str_contains(strtoupper($trackId), 'AMBIL')) {
            return redirect("https://track.biteship.com/{$trackId}");
        }

        return redirect()->route('guest.track', ['q' => $id]);
    }

    public function markDiterima($id)
    {
        $pesanan = Pesanan::where('userId', Auth::id())->findOrFail($id);

        if ($pesanan->status_pesanan !== 'dikirim') {
            return redirect()->back()->with('error', 'Status pesanan tidak valid untuk diselesaikan.');
        }

        try {
            $pesanan->update([
                'status_pesanan' => 'selesai',
            ]);
            event(new OrderStatusUpdated($pesanan));

            return redirect()->back()->with('success', 'Terima kasih! Pesanan Anda telah ditandai sebagai selesai.');
        } catch (\Exception $e) {
            Log::error('Penyelesaian Pesanan Gagal: '.$e->getMessage());

            return redirect()->back()->with('error', 'Gagal memproses status pesanan.');
        }
    }

    public function adminIndex(Request $request)
    {
        self::cancelOldOrders();

        $activeTab = $request->input('tab', 'aktif');

        if ($activeTab === 'refund') {
            $refundStatus = $request->input('refund_status', 'all');
            $query = Refund::with(['pesanan.user', 'detailPesanan.produk']);

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('id', 'LIKE', "%{$search}%")
                        ->orWhere('pesananId', 'LIKE', "%{$search}%")
                        ->orWhereHas('pesanan.user', function ($uq) use ($search) {
                            $uq->where('namaLengkap', 'LIKE', "%{$search}%")
                                ->orWhere('username', 'LIKE', "%{$search}%");
                        });
                });
            }

            if ($refundStatus !== 'all') {
                $query->where('status', $refundStatus);
            }

            $refunds = $query->orderBy('created_at', 'desc')->paginate(10);

            return view('admin.pesanan.index', compact('activeTab', 'refunds', 'refundStatus'));
        }

        $query = Pesanan::with(['user', 'pembayaran', 'detailPesanans.produk']);

        if ($activeTab === 'aktif') {

            $query->whereIn('status_pesanan', ['diproses', 'dikirim']);
        } else {

            $query->whereIn('status_pesanan', ['selesai', 'dibatalkan']);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('id', 'LIKE', "%{$search}%")
                    ->orWhereHas('user', function ($uq) use ($search) {
                        $uq->where('namaLengkap', 'LIKE', "%{$search}%")
                            ->orWhere('username', 'LIKE', "%{$search}%");
                    });
            });
        }

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status_pesanan', $request->status);
        }

        $pesanans = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('admin.pesanan.index', compact('activeTab', 'pesanans'));
    }

    public function adminShow($id)
    {
        $pesanan = Pesanan::with(['user.desa.kecamatan.kabupaten.provinsi', 'pembayaran', 'detailPesanans.produk'])
            ->findOrFail($id);

        $trackingData = $this->getBiteshipTracking($pesanan->deskripsi);
        if ($trackingData) {
            $biteshipStatus = $trackingData['status'] ?? null;
            if ($biteshipStatus === 'delivered') {
                if ($pesanan->status_pesanan !== 'selesai') {
                    $pesanan->update(['status_pesanan' => 'selesai']);
                    $pesanan->refresh();
                    event(new OrderStatusUpdated($pesanan));
                }
            } else {
                $shippingStatuses = [
                    'confirmed', 'allocated', 'scheduled',
                    'pickingUp', 'picking_up',
                    'picked',
                    'inTransit', 'in_transit',
                    'droppingOff', 'dropping_off',
                ];
                if (in_array($biteshipStatus, $shippingStatuses) && $pesanan->status_pesanan === 'diproses') {
                    $pesanan->update(['status_pesanan' => 'dikirim']);
                    $pesanan->refresh();
                    event(new OrderStatusUpdated($pesanan));
                }
            }
        }

        return view('admin.pesanan.show', compact('pesanan', 'trackingData'));
    }

    public function adminAction(Request $request, $id)
    {
        $pesanan = Pesanan::findOrFail($id);
        $action = $request->input('action');

        DB::beginTransaction();
        try {
            if ($action === 'proses') {
                $pesanan->update(['status_pesanan' => 'diproses']);
                event(new OrderStatusUpdated($pesanan));
                if ($pesanan->pembayaran && $pesanan->pembayaran->statusPembayaran !== 'berhasil') {
                    $pesanan->pembayaran->update([
                        'statusPembayaran' => 'berhasil',
                        'waktuDibayar' => now(),
                    ]);
                }
                $msg = 'Pesanan berhasil diproses dan dikemas.';
            } elseif ($action === 'kirim') {
                $noResi = null;
                $biteshipOrderId = null;
                $isAmbil = false;

                if ($pesanan->deskripsi) {
                    $isAmbil = str_contains(strtolower($pesanan->deskripsi), 'ambil');
                }

                if ($isAmbil) {
                    $noResi = 'AMBIL-SENDIRI';
                } else {

                    $courierCompany = 'jne';
                    $courierService = 'reg';

                    if ($pesanan->deskripsi) {
                        if (preg_match('/Kirim via\s+([A-Za-z0-9&\s\-\+]+)\s*\(([^)]+)\)/i', $pesanan->deskripsi, $matches)) {
                            $courierCompany = strtolower(trim($matches[1]));
                            $courierService = strtolower(trim($matches[2]));

                            $courierCompany = str_replace(['j&t', 'jnt', ' '], ['jnt', 'jnt', ''], $courierCompany);
                        }
                    }

                    $admin = User::with('desa.kecamatan.kabupaten.provinsi')->where('isAdmin', true)->first();
                    $originAreaId = null;
                    if ($admin && $admin->desaId) {
                        $originAreaId = $this->getOrCreateBiteshipArea($admin->desaId);
                    }
                    if (! $originAreaId) {
                        $originAreaId = 'IDNP6IDNC148IDND843IDZ12250';
                    }

                    $destinationAreaId = null;
                    if ($pesanan->desaId) {
                        $destinationAreaId = $this->getOrCreateBiteshipArea($pesanan->desaId);
                    }

                    $items = [];
                    $totalItemValue = 0;
                    foreach ($pesanan->detailPesanans as $detail) {
                        $weightKg = 1;
                        if ($detail->produk && $detail->produk->kategori) {
                            $weightKg = (float) $detail->produk->kategori->karung;
                        }
                        $itemValue = (int) ($detail->harga_satuan);
                        $totalItemValue += ($itemValue * $detail->jumlahPesanan);
                        $items[] = [
                            'id' => $detail->produkId,
                            'name' => $detail->produk ? $detail->produk->namaProduk : 'Produk AGRIS',
                            'description' => $detail->produk ? ($detail->produk->deskripsi ?: 'Produk Agroindustri AGRIS') : 'Produk Agroindustri AGRIS',
                            'value' => $itemValue,
                            'quantity' => (int) $detail->jumlahPesanan,
                            'weight' => (int) max(1, $weightKg * 1000),
                            'height' => 0,
                            'length' => 0,
                            'width' => 0,
                        ];
                    }

                    $apiKey = config('services.biteship.key');
                    $baseUrl = $this->getBiteshipBaseUrl();

                    try {
                        $response = Http::withToken($apiKey)
                            ->timeout(12)
                            ->post("$baseUrl/orders", [
                                'shipper_contact_name' => $admin->namaLengkap ?? 'Admin AGRIS',
                                'shipper_contact_phone' => $admin->noTelp ?? '081234567890',
                                'shipper_contact_email' => $admin->email ?? 'admin@agris.com',
                                'shipper_organization' => 'AGRIS',
                                'origin_contact_name' => $admin->namaLengkap ?? 'Admin AGRIS',
                                'origin_contact_phone' => $admin->noTelp ?? '081234567890',
                                'origin_address' => $admin->alamatLengkap ?: 'Kawasan Bisnis Agris, Jl. Manyar Gg. Kelapa, Puring, Slawu, Patrang, Jember, Jawa Timur',
                                'origin_area_id' => $originAreaId,
                                'destination_contact_name' => $pesanan->user->namaLengkap,
                                'destination_contact_phone' => $pesanan->user->noTelp,
                                'destination_contact_email' => $pesanan->user->email,
                                'destination_address' => $pesanan->alamat_pengiriman,
                                'destination_area_id' => $destinationAreaId,
                                'courier_company' => $courierCompany,
                                'courier_type' => $courierService,
                                'courier_service' => $courierService,
                                'courier_insurance' => (int) $totalItemValue,
                                'delivery_type' => 'now',
                                'items' => $items,
                            ]);

                        if ($response->successful()) {
                            $responseData = $response->json();
                            $noResi = $responseData['courier']['waybill_id'] ?? null;
                            $biteshipOrderId = $responseData['id'] ?? null;
                            Log::info('Biteship Order Created. ID: '.($biteshipOrderId ?? 'N/A').', Resi: '.$noResi);
                        } else {
                            $errorMsg = $response->json()['error'] ?? 'Gagal membuat pesanan di Biteship.';
                            Log::error('Biteship Order Creation Failed: '.$response->body());
                            DB::rollBack();
                            return redirect()->back()->with('error', 'Gagal membuat pesanan Biteship: ' . $errorMsg);
                        }
                    } catch (\Exception $e) {
                        Log::error('Biteship Order Creation Exception: '.$e->getMessage());
                        DB::rollBack();
                        return redirect()->back()->with('error', 'Gagal menghubungi server Biteship: ' . $e->getMessage());
                    }
                }

                if (empty($noResi)) {
                    $courierCompany = isset($courierCompany) ? strtoupper($courierCompany) : 'EXP';
                    $noResi = $courierCompany.'-SBX-'.rand(10000000, 99999999);
                    Log::warning("Fallback mock waybill generated for order {$id}: {$noResi}");
                }

                $newDescription = $pesanan->deskripsi.' | No Resi: '.$noResi;
                if (! empty($biteshipOrderId)) {
                    $newDescription .= ' | Biteship Order ID: '.$biteshipOrderId;
                }
                $pesanan->update([
                    'status_pesanan' => 'dikirim',
                    'deskripsi' => $newDescription,
                ]);
                event(new OrderStatusUpdated($pesanan));

                $msg = $isAmbil
                    ? 'Pesanan siap diambil.'
                    : 'Pesanan berhasil dikirim dengan Nomor Resi otomatis Biteship: '.$noResi;
            } elseif ($action === 'selesai') {
                $pesanan->update(['status_pesanan' => 'selesai']);
                event(new OrderStatusUpdated($pesanan));
                $msg = 'Pesanan berhasil ditandai sebagai selesai.';
            } elseif ($action === 'batal') {
                $pesanan->update(['status_pesanan' => 'dibatalkan']);
                event(new OrderStatusUpdated($pesanan));

                $pembayaran = $pesanan->pembayaran;
                $hasPaid = $pembayaran && $pembayaran->statusPembayaran === 'berhasil';

                if ($pembayaran) {
                    if ($hasPaid) {
                        $pembayaran->update([
                            'jumlahRefund' => $pembayaran->totalPembayaran,
                        ]);
                    } else {
                        $pembayaran->update(['statusPembayaran' => 'gagal']);
                    }
                }

                foreach ($pesanan->detailPesanans as $detail) {
                    if ($detail->produk) {
                        $detail->produk->increment('stok', $detail->jumlahPesanan);
                    }

                    if ($hasPaid) {
                        Refund::create([
                            'pesananId' => $pesanan->id,
                            'detailPesananId' => $detail->id,
                            'jumlah' => $detail->jumlahPesanan,
                            'nominal' => $detail->subtotal,
                            'alasan' => 'Dibatalkan oleh Admin/Sistem',
                            'foto_bukti' => 'refunds/cancel_order.png',
                            'status' => 'disetujui',
                            'catatan_admin' => 'Transaksi dibatalkan oleh Admin/Sistem.',
                        ]);
                    }
                }
                $msg = 'Pesanan berhasil dibatalkan dan stok produk serta pembayaran refund telah diproses.';
            } else {
                return redirect()->back()->with('error', 'Aksi tidak valid.');
            }

            DB::commit();

            return redirect()->back()->with('success', $msg);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Admin Order Action Error: '.$e->getMessage());

            return redirect()->back()->with('error', 'Gagal memproses aksi pesanan.');
        }
    }

    public function trackForm()
    {
        return view('guest.track');
    }

    public function trackSearch(Request $request)
    {
        $request->validate([
            'query' => 'required|string|min:3|max:100',
        ]);

        $query = trim($request->input('query'));

        $pesanan = Pesanan::with(['detailPesanans.produk', 'pembayaran', 'user.desa.kecamatan.kabupaten.provinsi'])
            ->where('id', $query)
            ->first();

        if (! $pesanan) {
            $pesanan = Pesanan::with(['detailPesanans.produk', 'pembayaran', 'user.desa.kecamatan.kabupaten.provinsi'])
                ->where('deskripsi', 'LIKE', '%No Resi: '.$query.'%')
                ->first();
        }

        if (! $pesanan) {
            return response()->json([
                'success' => false,
                'message' => 'Pesanan dengan ID tersebut tidak ditemukan. Pastikan ID pesanan yang Anda masukkan sudah benar.',
            ], 404);
        }

        $deskripsi = $pesanan->deskripsi;
        $courierInfo = '';
        $noResi = '';
        $ongkirText = '';
        $biteshipOrderId = '';

        if ($deskripsi) {
            $parts = explode('|', $deskripsi);
            foreach ($parts as $part) {
                $part = trim($part);
                if (str_starts_with(strtolower($part), 'opsi:')) {
                    $courierInfo = trim(substr($part, 5));
                } elseif (str_starts_with(strtolower($part), 'ongkir:')) {
                    $ongkirText = trim(substr($part, 7));
                } elseif (str_starts_with(strtolower($part), 'no resi:')) {
                    $noResi = trim(substr($part, 8));
                } elseif (str_starts_with(strtolower($part), 'biteship order id:')) {
                    $biteshipOrderId = trim(substr($part, 18));
                }
            }
        }

        $trackingData = $this->getBiteshipTracking($pesanan->deskripsi);

        if ($trackingData) {
            $biteshipStatus = $trackingData['status'] ?? null;
            $shippingStatuses = [
                'confirmed', 'allocated', 'scheduled',
                'pickingUp', 'picking_up',
                'picked',
                'inTransit', 'in_transit',
                'droppingOff', 'dropping_off',
                'delivered',
            ];
            if (in_array($biteshipStatus, $shippingStatuses) && $pesanan->status_pesanan === 'diproses') {
                $pesanan->update(['status_pesanan' => 'dikirim']);
                $pesanan->refresh();
            }
        }

        $statusMap = [
            'pending' => ['label' => 'Menunggu Pembayaran', 'color' => 'amber', 'icon' => 'fa-clock'],
            'diproses' => ['label' => 'Sedang Dikemas', 'color' => 'blue', 'icon' => 'fa-box'],
            'dikirim' => ['label' => 'Dalam Pengiriman', 'color' => 'purple', 'icon' => 'fa-truck'],
            'selesai' => ['label' => 'Selesai', 'color' => 'green', 'icon' => 'fa-circle-check'],
            'dibatalkan' => ['label' => 'Dibatalkan', 'color' => 'red', 'icon' => 'fa-ban'],
        ];
        $currentStatus = $statusMap[$pesanan->status_pesanan] ?? ['label' => 'Tidak Diketahui', 'color' => 'gray', 'icon' => 'fa-question'];

        $items = [];
        foreach ($pesanan->detailPesanans as $detail) {
            $items[] = [
                'nama' => $detail->produk ? $detail->produk->namaProduk : 'Produk Tidak Tersedia',
                'jumlah' => $detail->jumlahPesanan,
                'harga_satuan' => $detail->harga_satuan,
                'subtotal' => $detail->subtotal,
                'foto' => ($detail->produk && $detail->produk->fotoProduk) ? asset('storage/'.$detail->produk->fotoProduk) : null,
            ];
        }

        $trackingHistory = [];
        if ($trackingData && ! empty($trackingData['history'])) {
            foreach (array_reverse($trackingData['history']) as $event) {
                $statusLabel = match ($event['status']) {
                    'confirmed' => 'Pesanan Dikonfirmasi',
                    'allocated' => 'Kurir Dialokasikan',
                    'pickingUp' => 'Proses Penjemputan',
                    'picked' => 'Paket Dijemput Kurir',
                    'inTransit' => 'Dalam Transit / Pengiriman',
                    'droppingOff' => 'Kurir Menuju Lokasi Anda',
                    'delivered' => 'Paket Diterima',
                    'rejected' => 'Paket Ditolak/Bermasalah',
                    'cancelled' => 'Pengiriman Dibatalkan',
                    'returned' => 'Paket Dikembalikan',
                    default => strtoupper($event['status']),
                };
                $trackingHistory[] = [
                    'status' => $event['status'],
                    'status_label' => $statusLabel,
                    'note' => $event['note'] ?? '',
                    'updated_at' => $event['updated_at'] ?? '',
                ];
            }
        }

        $isPickup = str_contains(strtolower($courierInfo), 'ambil');

        return response()->json([
            'success' => true,
            'data' => [
                'order_id' => $pesanan->id,
                'status_pesanan' => $pesanan->status_pesanan,
                'status_label' => $currentStatus['label'],
                'status_color' => $currentStatus['color'],
                'status_icon' => $currentStatus['icon'],
                'tanggal_pesanan' => $pesanan->created_at->timezone('Asia/Jakarta')->locale('id')->translatedFormat('d F Y, H:i'),
                'courier_info' => $courierInfo,
                'no_resi' => $noResi,
                'is_pickup' => $isPickup,
                'alamat_pengiriman' => $pesanan->alamat_pengiriman,
                'items' => $items,
                'total' => $pesanan->total_harga,
                'tracking_history' => $trackingHistory,
                'has_biteship_data' => ! empty($trackingHistory),
                'biteship_order_id' => $biteshipOrderId,
            ],
        ]);
    }

    private function getBiteshipTracking($deskripsi)
    {
        if (! $deskripsi) {
            return null;
        }

        $biteshipOrderId = null;

        $parts = explode('|', $deskripsi);
        foreach ($parts as $part) {
            $part = trim($part);
            if (str_starts_with(strtolower($part), 'biteship order id:')) {
                $biteshipOrderId = trim(substr($part, 18));
            }
        }

        if (! $biteshipOrderId) {
            return null;
        }

        $cacheKey = "biteship_tracking_{$biteshipOrderId}";
        if (Cache::has($cacheKey)) {
            $cached = Cache::get($cacheKey);
            if ($cached !== null) {
                return $cached;
            }
        }

        try {
            $apiKey = config('services.biteship.key');
            $baseUrl = $this->getBiteshipBaseUrl();

            $response = Http::withToken($apiKey)
                ->timeout(8)
                ->get("$baseUrl/orders/$biteshipOrderId");

            if ($response->successful()) {
                $orderData = $response->json();

                $data = [
                    'status' => $orderData['status'] ?? null,
                    'history' => $orderData['courier']['history'] ?? [],
                ];
                Cache::put($cacheKey, $data, now()->addSeconds(30));

                return $data;
            }
        } catch (\Exception $e) {
            Log::error('Biteship Tracking API Error: '.$e->getMessage());
        }

        return null;
    }

    public function biteshipWebhook(Request $request)
    {
        Log::info('Biteship Webhook Received: '.$request->getContent());

        $event = $request->input('event');
        if ($event === 'order.status') {
            $biteshipOrderId = $request->input('order_id');
            $status = $request->input('status');

            // Invalidate the cache when a webhook is received, so that subsequent calls get fresh data from Biteship
            Cache::forget("biteship_tracking_{$biteshipOrderId}");

            $pesanan = Pesanan::where('deskripsi', 'LIKE', "%Biteship Order ID: {$biteshipOrderId}%")->first();

            if ($pesanan) {
                if ($status === 'delivered') {
                    if ($pesanan->status_pesanan !== 'selesai') {
                        $pesanan->update(['status_pesanan' => 'selesai']);
                        Log::info("Biteship Webhook: Order {$pesanan->id} marked as selesai.");
                    }
                } else {
                    $shippingStatuses = [
                        'confirmed', 'allocated', 'scheduled',
                        'pickingUp', 'picking_up',
                        'picked',
                        'inTransit', 'in_transit',
                        'droppingOff', 'dropping_off',
                    ];
                    if (in_array($status, $shippingStatuses)) {
                        if ($pesanan->status_pesanan === 'diproses') {
                            $pesanan->update(['status_pesanan' => 'dikirim']);
                            Log::info("Biteship Webhook: Order {$pesanan->id} marked as dikirim.");
                        }
                    }
                }

                // ALWAYS broadcast the update event so the frontend (both index and show) updates in real-time!
                event(new OrderStatusUpdated($pesanan));
                Log::info("Biteship Webhook: Order {$pesanan->id} status updated to {$status}. Broadcasted OrderStatusUpdated event.");
            }
        }

        return response()->json(['success' => true]);
    }

    private function getBiteshipBaseUrl()
    {
        return config('services.biteship.url') ?: 'https://api.biteship.com/v1';
    }
}
