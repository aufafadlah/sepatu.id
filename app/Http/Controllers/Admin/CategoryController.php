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
    public function index(Request $request)
    {
        $query = Category::withCount('products');

        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where('name', 'like', "%{$search}%");
        }

        $categories = $query->latest()->paginate(10)->withQueryString();
        $totalCategories = Category::count();

        return view('admin.categories.index', compact('categories', 'totalCategories'));
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
    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Perbarui data kategori.
     */
    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:categories,name,' . $category->id,
        ]);

        $category->update($validated);

        auth()->user()->logActivity('update_category', "Mengubah nama kategori menjadi: {$category->name}");

        return redirect()->route('admin.produk.kategori.index')->with('success', 'Kategori berhasil diperbarui!');
    }

    /**
     * Hapus kategori.
     */
    public function destroy(Category $category)
    {
        if ($category->products()->count() > 0) {
            return back()->with('error', 'Kategori tidak dapat dihapus karena masih memiliki produk terkait.');
        }

        $name = $category->name;
        $category->delete();

        auth()->user()->logActivity('delete_category', "Menghapus kategori: {$name}");

        return redirect()->route('admin.produk.kategori.index')->with('success', 'Kategori berhasil dihapus!');
    }
}
