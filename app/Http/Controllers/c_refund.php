<?php

namespace App\Http\Controllers;

use App\Models\Refund;
use App\Models\Pesanan;
use App\Models\DetailPesanan;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class c_refund extends Controller
{
    public function create(Request $request)
    {
        $pesanan = Pesanan::where('userId', Auth::id())
            ->where('status_pesanan', 'selesai')
            ->with('detailPesanans.produk')
            ->findOrFail($request->input('pesananId'));

        $detail = $pesanan->detailPesanans->firstWhere('id', $request->input('detailPesananId'));

        if (! $detail) {
            abort(404, 'Item pesanan tidak ditemukan.');
        }

        // Jumlah refund bebas, berdasarkan jumlah satuan produk yang dibeli
        $maxQty = $detail->jumlahPesanan;

        $existingRefunds = Refund::where('detailPesananId', $detail->id)->get();

        return view('agen.refund.create', compact('pesanan', 'detail', 'maxQty', 'existingRefunds'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pesananId' => 'required',
            'detailPesananId' => 'required',
            'jumlah' => 'required|integer|min:1',
            'alasan' => 'required|string',
            'foto_bukti' => 'required|image|max:5120',
        ]);

        $pesanan = Pesanan::where('userId', Auth::id())
            ->where('status_pesanan', 'selesai')
            ->findOrFail($request->pesananId);

        $detail = DetailPesanan::where('pesananId', $pesanan->id)
            ->findOrFail($request->detailPesananId);

        // Batas jumlah refund = total unit yang dibeli, tanpa memperhitungkan refund sebelumnya
        if ($request->jumlah > $detail->jumlahPesanan) {
            return back()->withErrors(['jumlah' => 'Jumlah tidak boleh melebihi jumlah produk yang dibeli (' . $detail->jumlahPesanan . ' unit).'])->withInput();
        }

        $fotoPath = $request->file('foto_bukti')->store('refunds', 'public');

        Refund::create([
            'pesananId' => $pesanan->id,
            'detailPesananId' => $detail->id,
            'jumlah' => $request->jumlah,
            'nominal' => $request->jumlah * $detail->harga_satuan,
            'alasan' => $request->alasan,
            'foto_bukti' => $fotoPath,
            'status' => 'pending',
        ]);

        return redirect()->route('agen.pesanan.show', $pesanan->id)->with('success', 'Pengajuan refund berhasil dikirim.');
    }

    public function adminIndex(Request $request)
    {
        $status = $request->input('status', 'all');

        $query = Refund::with(['pesanan.user', 'detailPesanan.produk']);

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $refunds = $query->orderBy('created_at', 'desc')->get();

        return view('admin.refund.index', compact('refunds', 'status'));
    }

    public function adminShow($id)
    {
        $refund = Refund::with(['pesanan.user', 'detailPesanan.produk'])->findOrFail($id);

        return view('admin.refund.show', compact('refund'));
    }

    public function adminAction(Request $request, $id)
    {
        $request->validate([
            'action' => 'required|in:setuju,tolak',
            'catatan_admin' => 'nullable|string',
        ]);

        $refund = Refund::findOrFail($id);

        if ($refund->status !== 'pending') {
            return back()->with('error', 'Pengajuan refund sudah diproses sebelumnya.');
        }

        $pembayaran = Pembayaran::where('pesananId', $refund->pesananId)->first();
        $forceLocal = $request->input('force_local') === '1';

        if ($request->action === 'setuju') {
            $serverKey = config('services.midtrans.server_key');
            $isProduction = config('services.midtrans.is_production', false);

            $isRealMidtrans = $pembayaran
                && $pembayaran->paymentType !== 'simulasi_midtrans'
                && !empty($serverKey)
                && !$forceLocal;

            if ($isRealMidtrans) {
                $transactionOrOrderId = $pembayaran->transactionId ?? $refund->pesananId;

                // Determine exact payment type from Midtrans if it is still generic
                $paymentType = $pembayaran->paymentType;
                if (empty($paymentType) || $paymentType === 'midtrans_snap') {
                    $statusUrl = $isProduction
                        ? "https://api.midtrans.com/v2/{$transactionOrOrderId}/status"
                        : "https://api.sandbox.midtrans.com/v2/{$transactionOrOrderId}/status";
                    try {
                        $statusResponse = Http::withBasicAuth($serverKey, '')
                            ->timeout(10)
                            ->get($statusUrl);
                        if ($statusResponse->successful()) {
                            $statusData = $statusResponse->json();
                            $paymentType = $statusData['payment_type'] ?? $paymentType;
                            $pembayaran->update(['paymentType' => $paymentType]);
                        }
                    } catch (\Exception $e) {
                        Log::error("Midtrans Status Check during Refund failed: " . $e->getMessage());
                    }
                }

                // Check if payment method is unsupported for online API refund
                $unsupportedPaymentTypes = ['bank_transfer', 'echannel', 'cstore'];
                $isUnsupportedType = in_array($paymentType, $unsupportedPaymentTypes);

                if ($isUnsupportedType) {
                    $methodName = str_replace('_', ' ', $paymentType);
                    $warningMessage = 'Refund disetujui secara lokal. Karena pembayaran menggunakan ' . strtoupper($methodName) . ' yang tidak mendukung refund otomatis, harap lakukan transfer manual ke rekening agen.';

                    DB::transaction(function () use ($request, $refund, $pembayaran, $paymentType) {
                        $refund->update([
                            'status' => 'disetujui',
                            'catatan_admin' => $request->catatan_admin . "\n(Catatan: Refund diproses manual karena metode pembayaran " . strtoupper(str_replace('_', ' ', $paymentType)) . " tidak didukung oleh refund otomatis Midtrans)",
                        ]);

                        if ($pembayaran) {
                            $pembayaran->update([
                                'jumlahRefund' => ($pembayaran->jumlahRefund ?? 0) + $refund->nominal,
                            ]);
                        }
                    });

                    return redirect()->route('admin.pesanan.index', ['tab' => 'refund'])->with('success', $warningMessage);
                }

                $baseUrl = $isProduction
                    ? 'https://api.midtrans.com'
                    : 'https://api.sandbox.midtrans.com';
                $refundUrl = "{$baseUrl}/v2/{$transactionOrOrderId}/refund";

                try {
                    $response = Http::withBasicAuth($serverKey, '')
                        ->timeout(12)
                        ->post($refundUrl, [
                            'refund_key' => 'REF-' . $refund->id,
                            'amount' => (int) $refund->nominal,
                            'reason' => $refund->alasan ?? 'Refund disetujui oleh admin',
                        ]);

                    $resData = $response->json();
                    $statusCode = $resData['status_code'] ?? null;

                    if (!$response->successful() || ($statusCode && !str_starts_with($statusCode, '2'))) {
                        $msg = $resData['status_message'] ?? 'Terjadi kesalahan pada server Midtrans.';
                        Log::error("Midtrans Refund Error: " . $response->body());
                        return back()->withInput()->with([
                            'error' => 'Gagal memproses refund di Midtrans: ' . $msg,
                            'show_force_local' => true
                        ]);
                    }
                } catch (\Exception $e) {
                    Log::error("Midtrans Refund Exception: " . $e->getMessage());
                    return back()->withInput()->with([
                        'error' => 'Gagal memproses refund: Terjadi kesalahan koneksi ke server Midtrans.',
                        'show_force_local' => true
                    ]);
                }
            }
        }

        DB::transaction(function () use ($request, $refund, $pembayaran) {
            if ($request->action === 'setuju') {
                $refund->update([
                    'status' => 'disetujui',
                    'catatan_admin' => $request->catatan_admin,
                ]);

                if ($pembayaran) {
                    $pembayaran->update([
                        'jumlahRefund' => ($pembayaran->jumlahRefund ?? 0) + $refund->nominal,
                    ]);
                }
            } else {
                $refund->update([
                    'status' => 'ditolak',
                    'catatan_admin' => $request->catatan_admin,
                ]);
            }
        });

        return redirect()->route('admin.pesanan.index', ['tab' => 'refund'])->with('success', 'Pengajuan refund berhasil diperbarui.');
    }
}
