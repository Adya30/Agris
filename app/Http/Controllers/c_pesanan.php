<?php

namespace App\Http\Controllers;

use App\Models\Keranjang;
use App\Models\Pesanan;
use App\Models\DetailPesanan;
use App\Models\Pembayaran;
use App\Models\User;
use App\Models\Desa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class c_pesanan extends Controller
{
    public function index()
    {
        $pesanans = Pesanan::with(['detailPesanans.produk', 'pembayaran'])
            ->where('userId', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('agen.pesanan.index', compact('pesanans'));
    }

    public function show($id)
    {
        $pesanan = Pesanan::with(['detailPesanans.produk', 'pembayaran'])
            ->where('userId', Auth::id())
            ->findOrFail($id);

        return view('agen.pesanan.show', compact('pesanan'));
    }

    public function checkoutForm(Request $request)
    {
        if (!$request->filled('items')) {
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

        // Check if user has updated their region/address
        if (!$user->desaId) {
            return redirect()->route('agen.profile')->with('error', 'Silakan lengkapi wilayah alamat Anda di profil terlebih dahulu sebelum melakukan checkout.');
        }

        $totalPrice = 0;
        $totalWeight = 0;
        foreach ($keranjangs as $item) {
            $totalPrice += $item->produk->harga * $item->jumlah;
            $totalWeight += $item->produk->kategori->karung * $item->jumlah;
        }

        // Get origin Biteship area ID (from Admin address or fallback)
        $admin = User::where('isAdmin', true)->first();
        $originAreaId = null;
        if ($admin && $admin->desaId) {
            $originAreaId = $this->getOrCreateBiteshipArea($admin->desaId);
        }
        if (!$originAreaId) {
            $originAreaId = 'IDNP6IDNC148IDND843IDZ12250'; // Default Patrang
        }

        // Get destination Biteship area ID
        $destinationAreaId = $this->getOrCreateBiteshipArea($user->desaId);

        return view('agen.pesanan.checkout', compact(
            'keranjangs',
            'totalPrice',
            'totalWeight',
            'user',
            'originAreaId',
            'destinationAreaId'
        ));
    }

    public function cekOngkir(Request $request)
    {
        $request->validate([
            'origin_area_id' => 'required|string',
            'destination_area_id' => 'required|string',
            'weight' => 'required|numeric|min:0.1',
        ]);

        $apiKey = config('services.biteship.key');
        $baseUrl = config('services.biteship.url', 'https://api-sandbox.biteship.com/v1');

        try {
            $response = Http::withToken($apiKey)
                ->timeout(7)
                ->post("$baseUrl/rates/couriers", [
                    'origin_area_id' => $request->origin_area_id,
                    'destination_area_id' => $request->destination_area_id,
                    'couriers' => 'jne,sicepat,jnt,tiki,lion,ninja,anteraja',
                    'items' => [
                        [
                            'name' => 'Produk AGRIS',
                            'description' => 'Produk Agroindustri AGRIS',
                            'value' => 150000,
                            'weight' => $request->weight,
                        ]
                    ]
                ]);

            if ($response->successful()) {
                $rates = [];
                $biteshipRates = $response->json()['pricing'] ?? [];
                foreach ($biteshipRates as $rate) {
                    $rates[] = [
                        'courier_name' => strtoupper($rate['courier_code']),
                        'courier_service_name' => $rate['courier_service_name'],
                        'price' => $rate['price'],
                        'duration' => $rate['duration'],
                    ];
                }
                return response()->json($rates);
            }
        } catch (\Exception $e) {
            Log::error("Biteship Cek Ongkir Error: " . $e->getMessage());
        }

        // Offline / Error Fallback
        $weight = $request->weight;
        $fallbackRates = [
            [
                'courier_name' => 'JNE',
                'courier_service_name' => 'REG',
                'price' => round(12000 * $weight),
                'duration' => '2-3 Hari',
            ],
            [
                'courier_name' => 'SiCepat',
                'courier_service_name' => 'GOKIL',
                'price' => round(9000 * $weight),
                'duration' => '3-5 Hari',
            ],
            [
                'courier_name' => 'J&T',
                'courier_service_name' => 'EZ',
                'price' => round(11000 * $weight),
                'duration' => '2-4 Hari',
            ],
        ];

        return response()->json($fallbackRates);
    }

    public function checkoutStore(Request $request)
    {
        $request->validate([
            'items' => 'required|string',
            'alamat_pengiriman' => 'required|string',
            'courier_name' => 'required|string',
            'courier_service' => 'required|string',
            'shipping_cost' => 'required|numeric|min:0',
        ]);

        $itemIds = explode(',', $request->items);
        $keranjangs = Keranjang::with('produk.kategori')
            ->whereIn('id', $itemIds)
            ->where('userId', Auth::id())
            ->get();

        if ($keranjangs->isEmpty()) {
            return redirect()->route('agen.keranjang.index')->with('error', 'Produk tidak ditemukan di keranjang.');
        }

        // Check stock availability
        foreach ($keranjangs as $item) {
            if ($item->produk->stok < $item->jumlah) {
                return redirect()->back()->with('error', "Stok produk '{$item->produk->namaProduk}' tidak mencukupi untuk checkout.");
            }
        }

        DB::beginTransaction();
        try {
            // Create Pesanan
            $pesanan = Pesanan::create([
                'userId' => Auth::id(),
                'tanggal_pesanan' => now(),
                'alamat_pengiriman' => $request->alamat_pengiriman,
                'desaId' => Auth::user()->desaId,
                'status_pesanan' => 'pending',
                'deskripsi' => "Kurir: " . strtoupper($request->courier_name) . " ({$request->courier_service}) | Ongkir: Rp " . number_format($request->shipping_cost, 0, ',', '.'),
            ]);

            $totalItemPrice = 0;

            // Create DetailPesanan records and deduct stocks
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

            // Create Pembayaran
            Pembayaran::create([
                'pesananId' => $pesanan->id,
                'statusPembayaran' => 'pending',
                'totalPembayaran' => $totalItemPrice + $request->shipping_cost,
            ]);

            // Clear selected items from cart
            Keranjang::whereIn('id', $itemIds)->delete();

            DB::commit();

            return redirect()->route('agen.pesanan.show', $pesanan->id)->with('success', 'Pesanan berhasil dibuat, silakan lakukan pembayaran.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Simpan Pesanan Gagal: " . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat membuat pesanan. Silakan coba lagi.')->withInput();
        }
    }

    private function getOrCreateBiteshipArea(string $desaId)
    {
        $area = DB::table('biteship_areas')->where('desaId', $desaId)->first();
        if ($area) {
            return $area->biteship_area_id;
        }

        // Fetch from Desa details
        $desa = Desa::with('kecamatan')->find($desaId);
        if (!$desa || !$desa->kecamatan) {
            return null;
        }

        $kecName = $desa->kecamatan->namaKecamatan;

        try {
            $apiKey = config('services.biteship.key');
            $baseUrl = config('services.biteship.url', 'https://api-sandbox.biteship.com/v1');

            $response = Http::withToken($apiKey)
                ->timeout(5)
                ->get("$baseUrl/maps/areas", [
                    'countries' => 'ID',
                    'input' => $kecName,
                    'type' => 'single'
                ]);

            if ($response->successful()) {
                $areas = $response->json()['areas'] ?? [];
                if (!empty($areas)) {
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
            Log::error("Get/Create Biteship Area ID Gagal: " . $e->getMessage());
        }

        return null;
    }
}
