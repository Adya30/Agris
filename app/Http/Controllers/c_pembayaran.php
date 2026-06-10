<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\Pembayaran;
use App\Models\DetailPesanan;
use App\Models\Keranjang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class c_pembayaran extends Controller
{
    public function paymentCallback(Request $request)
    {
        $serverKey = config('services.midtrans.server_key');
        $statusCode = $request->status_code;
        $grossAmount = $request->gross_amount;
        $orderId = $request->order_id;
        $signatureKey = $request->signature_key;

        $hashed = hash("sha512", $orderId . $statusCode . $grossAmount . $serverKey);

        if ($hashed !== $signatureKey) {
            Log::warning("Midtrans Webhook: Signature verification failed for order " . $orderId);
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        $transactionStatus = $request->transaction_status;
        $paymentType = $request->payment_type;
        $transactionId = $request->transaction_id;

        $success = $this->updatePaymentAndOrderStatus($orderId, $transactionStatus, $paymentType, $transactionId, $request->all());

        if ($success) {
            return response()->json(['message' => 'Success']);
        }

        return response()->json(['message' => 'Order/Payment not found'], 404);
    }

    private function updatePaymentAndOrderStatus($orderId, $transactionStatus, $paymentType, $transactionId, $rawData)
    {
        $pesanan = Pesanan::find($orderId);
        if (!$pesanan) {
            return false;
        }

        $pembayaran = Pembayaran::where('pesananId', $orderId)->first();
        if (!$pembayaran) {
            return false;
        }

        $statusPembayaran = 'pending';
        $statusPesanan = $pesanan->status_pesanan;

        if ($transactionStatus == 'capture') {
            if (isset($rawData['fraud_status']) && $rawData['fraud_status'] == 'challenge') {
                $statusPembayaran = 'challenge';
            } else {
                $statusPembayaran = 'berhasil';
                $statusPesanan = 'diproses';
                $pembayaran->waktuDibayar = now();
            }
        } else if ($transactionStatus == 'settlement') {
            $statusPembayaran = 'berhasil';
            $statusPesanan = 'diproses';
            $pembayaran->waktuDibayar = now();
        } else if ($transactionStatus == 'pending') {
            $statusPembayaran = 'pending';
        } else if (in_array($transactionStatus, ['deny', 'expire', 'cancel'])) {
            $statusPembayaran = 'gagal';
            $statusPesanan = 'dibatalkan';

            $detailPesanans = DetailPesanan::where('pesananId', $orderId)->get();
            foreach ($detailPesanans as $detail) {
                if ($detail->produk) {
                    $detail->produk->increment('stok', $detail->jumlahPesanan);
                }
            }
        }

        if ($statusPembayaran === 'berhasil') {
            $detailPesanans = DetailPesanan::where('pesananId', $orderId)->get();
            foreach ($detailPesanans as $detail) {
                Keranjang::where('userId', $pesanan->userId)
                    ->where('produkId', $detail->produkId)
                    ->delete();
            }
        }

        $pembayaran->update([
            'statusPembayaran' => $statusPembayaran,
            'transactionId' => $transactionId,
            'paymentType' => $paymentType,
        ]);

        $pesanan->update([
            'status_pesanan' => $statusPesanan,
        ]);

        return true;
    }

    public function cekStatus($id)
    {
        $pesanan = Pesanan::where('userId', Auth::id())->findOrFail($id);
        $pembayaran = Pembayaran::where('pesananId', $id)->first();

        if (!$pembayaran) {
            return redirect()->back()->with('error', 'Data pembayaran tidak ditemukan.');
        }

        if ($pembayaran->statusPembayaran === 'berhasil') {
            return redirect()->back()->with('success', 'Pembayaran sudah lunas dan pesanan sedang diproses.');
        }

        $serverKey = config('services.midtrans.server_key');
        $isProduction = config('services.midtrans.is_production', false);

        if (empty($serverKey)) {
            return redirect()->back()->with('info', 'Aplikasi dalam mode simulasi offline. Silakan klik tombol "Simulasikan Pembayaran" untuk mencoba.');
        }

        $statusUrl = $isProduction
            ? "https://api.midtrans.com/v2/{$id}/status"
            : "https://api.sandbox.midtrans.com/v2/{$id}/status";

        try {
            $response = Http::withBasicAuth($serverKey, '')
                ->timeout(10)
                ->get($statusUrl);

            if ($response->successful()) {
                $data = $response->json();
                $transactionStatus = $data['transaction_status'] ?? null;
                $paymentType = $data['payment_type'] ?? null;
                $transactionId = $data['transaction_id'] ?? null;

                if ($transactionStatus) {
                    $this->updatePaymentAndOrderStatus($id, $transactionStatus, $paymentType, $transactionId, $data);

                    $pesanan->refresh();
                    $pembayaran->refresh();

                    if ($pembayaran->statusPembayaran === 'berhasil') {
                        return redirect()->back()->with('success', 'Pembayaran berhasil terverifikasi! Status pesanan kini: ' . strtoupper($pesanan->status_pesanan));
                    } else {
                        return redirect()->back()->with('info', 'Status pembayaran di Midtrans: ' . strtoupper($transactionStatus));
                    }
                }
            } else {
                Log::error("Midtrans Status Check Failed: " . $response->body());
                return redirect()->back()->with('error', 'Gagal memeriksa status ke Midtrans: ' . ($response->json()['status_message'] ?? 'Unknown Error'));
            }
        } catch (\Exception $e) {
            Log::error("Midtrans Status Check Exception: " . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghubungi server Midtrans.');
        }

        return redirect()->back();
    }

    public function bayarSimulasi($id)
    {
        $pesanan = Pesanan::where('userId', Auth::id())->findOrFail($id);

        $pembayaran = Pembayaran::where('pesananId', $id)->first();
        if (!$pembayaran) {
            return response()->json(['success' => false, 'message' => 'Data pembayaran tidak ditemukan.'], 404);
        }

        DB::beginTransaction();
        try {
            $pembayaran->update([
                'statusPembayaran' => 'berhasil',
                'waktuDibayar' => now(),
                'paymentType' => 'simulasi_midtrans',
                'transactionId' => 'SIM-' . strtoupper(Str::random(12)),
            ]);

            $pesanan->update([
                'status_pesanan' => 'diproses',
            ]);

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Simulasi pembayaran berhasil diproses.']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Simulasi Pembayaran Gagal: " . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan sistem saat memproses simulasi pembayaran.'], 500);
        }
    }

    public function batalCheckout($id)
    {
        $pesanan = Pesanan::with('detailPesanans')->where('userId', Auth::id())->where('status_pesanan', 'pending')->findOrFail($id);

        DB::beginTransaction();
        try {
            foreach ($pesanan->detailPesanans as $detail) {

                if ($detail->produk) {
                    $detail->produk->increment('stok', $detail->jumlahPesanan);
                }

                Keranjang::create([
                    'userId' => Auth::id(),
                    'produkId' => $detail->produkId,
                    'jumlah' => $detail->jumlahPesanan,
                ]);
            }

            if ($pesanan->pembayaran) {
                $pesanan->pembayaran->delete();
            }
            $pesanan->detailPesanans()->delete();
            $pesanan->delete();

            DB::commit();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Batal Checkout Gagal: " . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Gagal membatalkan transaksi.'], 500);
        }
    }
}
