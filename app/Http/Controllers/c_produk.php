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
            'stok'       => 'required|numeric|min:0.01',
            'harga'      => 'required|numeric|min:0',
            'fotoProduk' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'deskripsi'  => 'nullable|string',
        ],[
            'kategoriId.required' => 'Data harus diisi!',
            'namaProduk.required' => 'Data harus diisi!',
            'stok.required'       => 'Data harus diisi!',
            'harga.required'      => 'Data harus diisi',
            'fotoProduk.image'    => 'File yang diunggah harus berupa gambar.',
            'fotoProduk.mimes'    => 'Format foto harus jpeg, png, atau jpg.',
            'fotoProduk.max'      => 'Ukuran foto maksimal adalah 2MB.',
        ]);

        try {
            $data = $request->all();

            if ($request->hasFile('fotoProduk')) {
                $data['fotoProduk'] = $request->file('fotoProduk')->store('produk', 'public');
            }

            Produk::create($data);

            return redirect()->route('admin.produk.index')
                ->with('success', 'Produk benih berhasil ditambahkan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal: ' . $e->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        $produk = Produk::findOrFail($id);
        $kategoris = KategoriProduk::orderBy('jenisKategori', 'asc')->get();
        return view('admin.produk.edit', compact('produk', 'kategoris'));
    }

    public function update(Request $request, $id)
    {
        $produk = Produk::findOrFail($id);

        $request->validate([
            'kategoriId' => 'required|exists:kategori_produks,id',
            'namaProduk' => 'required|string|max:150',
            'stok'       => 'required|numeric|min:0',
            'harga'      => 'required|numeric|min:0',
            'fotoProduk' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'deskripsi'  => 'nullable|string',
        ]);

        try {
            $data = $request->all();

            if ($request->hasFile('fotoProduk')) {
                if ($produk->fotoProduk) {
                    Storage::disk('public')->delete($produk->fotoProduk);
                }
                $data['fotoProduk'] = $request->file('fotoProduk')->store('produk', 'public');
            }

            $produk->update($data);

            return redirect()->route('admin.produk.index')
                ->with('success', 'Data produk berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui data.')->withInput();
        }
    }

    public function destroy($id)
    {
        $produk = Produk::findOrFail($id);
        $produk->delete();

        return redirect()->route('admin.produk.index')
            ->with('success', 'Produk berhasil dipindahkan ke stok kosong.');
    }

    public function trash()
    {
        $produks = Produk::with('kategori')
            ->where('stok', '<=', 0)
            ->latest()
            ->paginate(12);

        return view('admin.produk.trash', compact('produks'));
    }

    public function forceDelete($id)
    {
        // Menggunakan withTrashed() jika model Produk menggunakan SoftDeletes
        $produk = Produk::withTrashed()->findOrFail($id);

        if ($produk->fotoProduk) {
            Storage::disk('public')->delete($produk->fotoProduk);
        }

        $produk->forceDelete();

        return back()->with('success', 'Produk berhasil dihapus permanen.');
    }
}
