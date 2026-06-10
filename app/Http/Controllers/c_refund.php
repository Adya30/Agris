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

class c_refund extends Controller
{
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

        $refundedQty = Refund::where('detailPesananId', $detail->id)
            ->whereIn('status', ['pending', 'disetujui'])
            ->sum('jumlah');

        $maxQty = $detail->jumlahPesanan - $refundedQty;

        if ($request->jumlah > $maxQty) {
            return back()->withErrors(['jumlah' => 'Jumlah barang yang diajukan refund melebihi batas pembelian.'])->withInput();
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

        DB::transaction(function () use ($request, $refund) {
            if ($request->action === 'setuju') {
                $refund->update([
                    'status' => 'disetujui',
                    'catatan_admin' => $request->catatan_admin,
                ]);

                $pembayaran = Pembayaran::where('pesananId', $refund->pesananId)->first();
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
