<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class c_blog extends Controller
{
    public function index()
    {
        $blogs = Blog::query()->latest()->paginate(9);
        return view('admin.blog.index', compact('blogs'));
    }

    public function indexAgen()
    {
        $blogs = Blog::query()->latest()->paginate(9);
        return view('agen.blog.index', compact('blogs'));
    }

    public function indexGuest()
    {
        $blogs = Blog::query()->latest()->paginate(9);
        return view('guest.blog.index', compact('blogs'));
    }

    public function create()
    {
        return view('admin.blog.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'judulBlog' => 'required|max:200',
            'isiBlog' => 'required',
            'fotoBlog' => 'nullable|image|mimes:jpg,jpeg,png|max:10120',
        ]);

        $blog = new Blog();
        $blog->userId = Auth::id();
        $blog->judulBlog = $request->judulBlog;
        $blog->isiBlog = $request->isiBlog;
        $blog->tanggalBlog = now();

        if ($request->hasFile('fotoBlog')) {
            $blog->fotoBlog = $request->file('fotoBlog')->store('blog', 'public');
        }

        $blog->save();

        return redirect()->route('admin.blog.index')->with('success', 'Artikel berhasil diterbitkan');
    }

    public function show(string $id)
    {
        $blog = Blog::with('user')->findOrFail($id);
        return view('admin.blog.show', compact('blog'));
    }

    public function showAgen(string $id)
    {
        $blog = Blog::with('user')->findOrFail($id);
        return view('agen.blog.show', compact('blog'));
    }

    public function showGuest(string $id)
    {
        $blog = Blog::with('user')->findOrFail($id);
        return view('guest.blog.show', compact('blog'));
    }

    public function edit(string $id)
    {
        $blog = Blog::findOrFail($id);
        return view('admin.blog.edit', compact('blog'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'judulBlog' => 'required|max:200',
            'isiBlog' => 'required',
            'fotoBlog' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
        ]);

        $blog = Blog::findOrFail($id);
        $blog->judulBlog = $request->judulBlog;
        $blog->isiBlog = $request->isiBlog;

        if ($request->hasFile('fotoBlog')) {
            if ($blog->fotoBlog) {
                Storage::disk('public')->delete($blog->fotoBlog);
            }
            $blog->fotoBlog = $request->file('fotoBlog')->store('blog', 'public');
        }

        $blog->save();

        return redirect()->route('admin.blog.index')->with('success', 'Artikel berhasil diperbarui');
    }

    public function destroy(string $id)
    {
        $blog = Blog::findOrFail($id);
        if ($blog->fotoBlog) {
            Storage::disk('public')->delete($blog->fotoBlog);
        }
        $blog->delete();
        return back()->with('success', 'Artikel berhasil dihapus');
    }
}
