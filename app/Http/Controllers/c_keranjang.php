<?php

namespace App\Http\Controllers;

use App\Models\Keranjang;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class c_keranjang extends Controller
{
    private function cartCount(): int
    {
        return Keranjang::where('userId', Auth::id())->distinct('produkId')->count('produkId');
    }

    public function index()
    {
        $keranjangs = Keranjang::with(['produk.kategori'])->where('userId', Auth::id())->get();
        $total = 0;

        return view('agen.keranjang.index', compact('keranjangs', 'total'));
    }

    public function tambah(Request $request)
    {
        if (Auth::user()->isActive != 1) {
            return response()->json(['message' => 'Anda harus bermitra terlebih dahulu untuk melakukan transaksi.'], 403);
        }

        $request->validate([
            'produkId' => 'required|exists:produks,id',
            'jumlah'   => 'required|integer|min:1',
        ]);

        $produk = Produk::findOrFail($request->produkId);

        if ($produk->stok <= 0) {
            return response()->json(['message' => 'Stok produk habis.'], 422);
        }

        $keranjang = Keranjang::where('userId', Auth::id())
            ->where('produkId', $request->produkId)
            ->first();

        if ($keranjang) {
            $newJumlah = $keranjang->jumlah + $request->jumlah;

            if ($newJumlah > $produk->stok) {
                return response()->json(['message' => 'Jumlah melebihi stok yang tersedia.'], 422);
            }

            $keranjang->update(['jumlah' => $newJumlah]);
        } else {
            if ($request->jumlah > $produk->stok) {
                return response()->json(['message' => 'Jumlah melebihi stok yang tersedia.'], 422);
            }

            Keranjang::create([
                'userId'   => Auth::id(),
                'produkId' => $request->produkId,
                'jumlah'   => $request->jumlah,
            ]);
        }

        return response()->json([
            'message'   => 'Produk berhasil ditambahkan ke keranjang.',
            'cartCount' => $this->cartCount(),
        ]);
    }

    public function tambahJumlah(Request $request, $id)
    {
        $keranjang = Keranjang::where('id', $id)
            ->where('userId', Auth::id())
            ->firstOrFail();

        $produk = $keranjang->produk;

        if ($keranjang->jumlah >= $produk->stok) {
            return response()->json([
                'message'  => 'Jumlah sudah mencapai batas stok.',
                'jumlah'   => $keranjang->jumlah,
                'subtotal' => $keranjang->jumlah * $produk->harga,
            ], 422);
        }

        $keranjang->increment('jumlah');

        return response()->json([
            'message'   => 'Jumlah berhasil ditambah.',
            'jumlah'    => $keranjang->jumlah,
            'subtotal'  => $keranjang->jumlah * $produk->harga,
            'cartCount' => $this->cartCount(),
        ]);
    }

    public function kurang(Request $request, $id)
    {
        $keranjang = Keranjang::where('id', $id)->where('userId', Auth::id())->firstOrFail();

        $produk = $keranjang->produk;

        if ($keranjang->jumlah > 1) {
            $keranjang->decrement('jumlah');
        }

        return response()->json([
            'message'   => 'Jumlah berhasil dikurangi.',
            'jumlah'    => $keranjang->jumlah,
            'subtotal'  => $keranjang->jumlah * $produk->harga,
            'cartCount' => $this->cartCount(),
        ]);
    }

    public function updateJumlah(Request $request, $id)
    {
        $request->validate([
            'jumlah' => 'required|integer|min:1',
        ]);

        $keranjang = Keranjang::where('id', $id)
            ->where('userId', Auth::id())
            ->firstOrFail();

        $produk = $keranjang->produk;

        $jumlah = $request->jumlah;
        if ($jumlah > $produk->stok) {
            return response()->json([
                'message' => 'Jumlah melebihi stok yang tersedia (' . $produk->stok . ').',
                'jumlah' => $keranjang->jumlah,
                'subtotal' => $keranjang->jumlah * $produk->harga,
            ], 422);
        }

        $keranjang->update(['jumlah' => $jumlah]);

        return response()->json([
            'message' => 'Jumlah berhasil diperbarui.',
            'jumlah' => $keranjang->jumlah,
            'subtotal' => $keranjang->jumlah * $produk->harga,
            'cartCount' => $this->cartCount(),
        ]);
    }

    public function destroy($id)
    {
        $keranjang = Keranjang::where('id', $id)->where('userId', Auth::id())->firstOrFail();

        $keranjang->delete();

        return response()->json([
            'message'   => 'Produk berhasil dihapus dari keranjang.',
            'cartCount' => $this->cartCount(),
        ]);
    }
}
