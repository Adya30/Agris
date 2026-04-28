<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\KategoriProduk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class c_produk extends Controller
{
    public function index(Request $request)
    {
        $query = Produk::with('kategori')->where('stok', '>', 0);
        if ($request->kategori) {
            $query->where('kategoriId', $request->kategori);
        }

        if ($request->search) {
            $query->where('namaProduk', 'like', '%' . $request->search . '%');
        }

        $produks = $query->latest()->paginate(12)->withQueryString();
        $kategoris = KategoriProduk::orderBy('jenisKategori', 'asc')->get();

        return view('admin.produk.index', compact('produks', 'kategoris'));
    }

    public function create()
    {
        $kategoris = KategoriProduk::orderBy('jenisKategori', 'asc')->get();
        return view('admin.produk.create', compact('kategoris'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kategoriId' => 'required|exists:kategori_produks,id',
            'namaProduk' => 'required|string|max:150',
            'stok'       => 'required|numeric|min:0',
            'harga'      => 'required|numeric|min:0',
            'fotoProduk' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'deskripsi'  => 'nullable|string',
        ],[
            'required' => 'Data wajib diisi!'
        ]);

        try {
            $data = $request->all();
            if ($request->hasFile('fotoProduk')) {
                $data['fotoProduk'] = $request->file('fotoProduk')->store('produk', 'public');
            }
            Produk::create($data);
            return redirect()->route('admin.produk.index')->with('success', 'Produk benih berhasil ditambahkan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal: ' . $e->getMessage())->withInput();
        }
    }

    public function show($id)
    {
        $item = Produk::with('kategori')->withTrashed()->findOrFail($id);
        return view('admin.produk.show', compact('item'));
    }

    public function edit($id)
    {
        $produk = Produk::withTrashed()->findOrFail($id);
        $kategoris = KategoriProduk::orderBy('jenisKategori', 'asc')->get();
        return view('admin.produk.edit', compact('produk', 'kategoris'));
    }

    public function update(Request $request, $id)
    {
        $produk = Produk::withTrashed()->findOrFail($id);

        $request->validate([
            'kategoriId' => 'required|exists:kategori_produks,id',
            'namaProduk' => 'required|string|max:150',
            'stok'       => 'required|numeric|min:0',
            'harga'      => 'required|numeric|min:0',
            'fotoProduk' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'deskripsi'  => 'nullable|string',
        ],[
            'required' => 'Data wajib diisi!'
        ]);

        try {
            $data = $request->all();

            if ($request->hasFile('fotoProduk')) {
                if ($produk->fotoProduk) { Storage::disk('public')->delete($produk->fotoProduk); }
                $data['fotoProduk'] = $request->file('fotoProduk')->store('produk', 'public');
            }

            $produk->update($data);

            if ($produk->trashed() && $request->stok > 0) {
                $produk->restore();
            }

            return redirect()->route('admin.produk.index')->with('success', 'Data produk berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui data.')->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $produk = Produk::findOrFail($id);
            $produk->update(['stok' => 0]);
            $produk->delete();

            return redirect()->route('admin.produk.index')
                ->with('success', 'Produk berhasil dihapus');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus produk.');
        }
    }

    public function trash()
    {
        $produks = Produk::onlyTrashed()->with('kategori')->latest()->paginate(12);
        return view('admin.produk.trash', compact('produks'));
    }

    public function restore($id)
    {
        try {
            $produk = Produk::onlyTrashed()->findOrFail($id);
            $produk->restore();
            return redirect()->route('admin.produk.index')->with('success', 'Produk diaktifkan kembali.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengembalikan produk.');
        }
    }

    public function forceDelete($id)
    {
        try {
            $produk = Produk::onlyTrashed()->findOrFail($id);
            if ($produk->fotoProduk) { Storage::disk('public')->delete($produk->fotoProduk); }
            $produk->forceDelete();
            return back()->with('success', 'Produk dihapus permanen.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus permanen.');
        }
    }
}
