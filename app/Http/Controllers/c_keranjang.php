<?php

namespace App\Http\Controllers;

use App\Models\Keranjang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class c_keranjang extends Controller
{
    public function index()
    {
        $items = Keranjang::with('produk')
            ->where('userId', Auth::id())
            ->get();

        return view('agen.keranjang.index', compact('items'));
    }

    public function tambah(Request $request)
    {
        $request->validate([
            'produkId' => 'required|exists:produks,id',
            'jumlah' => 'required|integer|min:1',
        ]);

        $userId = Auth::id();
        $produkId = $request->produkId;

        $keranjang = Keranjang::where('userId', $userId)
            ->where('produkId', $produkId)
            ->first();

        if ($keranjang) {
            $keranjang->jumlah += $request->jumlah;
            $keranjang->save();
        } else {
            Keranjang::create([
                'userId' => $userId,
                'produkId' => $produkId,
                'jumlah' => $request->jumlah,
            ]);
        }

        return response()->json([
            'message' => 'Produk berhasil ditambahkan ke keranjang.'
        ]);
    }

    public function kurang($id)
    {
        $item = Keranjang::where('id', $id)
            ->where('userId', Auth::id())
            ->firstOrFail();

        if ($item->jumlah > 1) {
            $item->decrement('jumlah');
        } else {
            $item->delete();
        }

        return redirect()->back()->with('success', 'Jumlah produk dikurangi.');
    }

    public function destroy($id)
    {
        $item = Keranjang::where('id', $id)
            ->where('userId', Auth::id())
            ->firstOrFail();

        $item->delete();

        return redirect()->back()->with('success', 'Produk berhasil dihapus dari keranjang.');
    }
}
