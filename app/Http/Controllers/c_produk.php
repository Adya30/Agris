<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\KategoriProduk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class c_produk extends Controller
{
    /**
     * Menampilkan Produk yang tersedia (Stok > 0)
     */
    public function index(Request $request)
    {
        $query = Produk::with('kategori')->where('stok', '>', 0);

        // Fitur Pencarian Nama
        if ($request->search) {
            $query->where('namaProduk', 'like', '%' . $request->search . '%');
        }

        // Fitur Filter Kategori
        if ($request->kategori) {
            $query->where('kategoriId', $request->kategori);
        }

        $produks = $query->latest()->paginate(10)->withQueryString();
        $kategoris = KategoriProduk::orderBy('jenisKategori', 'asc')->get();

        return view('admin.produk.index', compact('produks', 'kategoris'));
    }

    /**
     * Form Tambah Produk
     */
    public function create()
    {
        $kategoris = KategoriProduk::orderBy('jenisKategori', 'asc')->get();
        return view('admin.produk.create', compact('kategoris'));
    }

    /**
     * Simpan Produk Baru
     */
    public function store(Request $request)
    {
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
                $data['fotoProduk'] = $request->file('fotoProduk')->store('produk', 'public');
            }

            Produk::create($data);

            return redirect()->route('admin.produk.index')
                ->with('success', 'Produk berhasil ditambahkan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Simpan Kategori Baru (Fitur Tambahan)
     */
    public function storeKategori(Request $request)
    {
        $request->validate([
            'jenisKategori' => 'required|in:padi,jagung,beras,gabah',
            'mutu'          => 'required|in:A,B,C',
            'deskripsi'     => 'nullable|string',
        ]);

        try {
            KategoriProduk::create($request->all());
            return back()->with('success', 'Kategori dan Mutu baru berhasil ditambahkan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal! Kombinasi jenis dan mutu tersebut sudah ada.');
        }
    }

    /**
     * Edit Produk
     */
    public function edit($id)
    {
        $produk = Produk::findOrFail($id);
        $kategoris = KategoriProduk::all();
        return view('admin.produk.edit', compact('produk', 'kategoris'));
    }

    /**
     * Update Produk
     */
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

    /**
     * Soft Delete (Pindah ke Arsip, bukan Trash stok 0)
     */
    public function destroy($id)
    {
        $produk = Produk::findOrFail($id);
        $produk->delete();

        return redirect()->route('admin.produk.index')
            ->with('success', 'Produk berhasil diarsipkan.');
    }

    /**
     * Halaman Trash: Menampilkan Produk Stok 0
     */
    public function trash()
    {
        // Menampilkan produk yang stoknya habis (0)
        // Kita tidak menggunakan onlyTrashed() di sini karena
        // fungsinya dialihkan untuk manajemen stok kosong.
        $produks = Produk::with('kategori')
            ->where('stok', '<=', 0)
            ->latest()
            ->paginate(10);

        return view('admin.produk.trash', compact('produks'));
    }

    /**
     * Hapus Permanen (Termasuk File Foto)
     */
    public function forceDelete($id)
    {
        // Mencari di data aktif maupun soft-deleted
        $produk = Produk::withTrashed()->findOrFail($id);

        if ($produk->fotoProduk) {
            Storage::disk('public')->delete($produk->fotoProduk);
        }

        $produk->forceDelete();

        return back()->with('success', 'Produk berhasil dihapus permanen dari sistem.');
    }
}
