<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Tampilkan daftar kategori produk.
     */
    public function index()
    {
        $categories = Category::withCount('products')->latest()->get();
        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Form tambah kategori.
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * Simpan kategori baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:categories,name',
        ]);

        $category = Category::create($validated);

        auth()->user()->logActivity('create_category', "Menambah kategori baru: {$category->name}");

        return redirect()->route('admin.produk.kategori.index')->with('success', 'Kategori berhasil ditambahkan!');
    }

    /**
     * Form edit kategori.
     */
    public function edit(Category $kategori)
    {
        return view('admin.categories.edit', ['category' => $kategori]);
    }

    /**
     * Perbarui data kategori.
     */
    public function update(Request $request, Category $kategori)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:categories,name,' . $kategori->id,
        ]);

        $kategori->update($validated);

        auth()->user()->logActivity('update_category', "Mengubah nama kategori menjadi: {$kategori->name}");

        return redirect()->route('admin.produk.kategori.index')->with('success', 'Kategori berhasil diperbarui!');
    }

    /**
     * Hapus kategori.
     */
    public function destroy(Category $kategori)
    {
        if ($kategori->products()->count() > 0) {
            return back()->with('error', 'Kategori tidak dapat dihapus karena masih memiliki produk terkait.');
        }

        $name = $kategori->name;
        $kategori->delete();

        auth()->user()->logActivity('delete_category', "Menghapus kategori: {$name}");

        return redirect()->route('admin.produk.kategori.index')->with('success', 'Kategori berhasil dihapus!');
    }
}
