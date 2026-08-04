<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Daftar produk (Panel Admin Produk).
     */
    public function index(Request $request)
    {
        $query = Product::with(['category', 'variants']);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('name', 'like', "%{$search}%");
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->input('category_id'));
        }

        $products = $query->latest()->paginate(10)->withQueryString();
        $categories = Category::all();

        return view('admin.products.index', compact('products', 'categories'));
    }

    /**
     * Form tambah produk baru.
     */
    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    /**
     * Simpan produk beserta varian ukurannya.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'image_url' => 'nullable|string',
            'variants' => 'required|array|min:1',
            'variants.*.size' => 'required|integer|min:30|max:50',
            'variants.*.stock' => 'required|integer|min:0',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        } elseif ($request->filled('image_url')) {
            $imagePath = $request->input('image_url');
        }

        $product = Product::create([
            'category_id' => $validated['category_id'],
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']) . '-' . Str::random(4),
            'description' => $validated['description'],
            'price' => $validated['price'],
            'image' => $imagePath,
        ]);

        foreach ($validated['variants'] as $variant) {
            ProductVariant::create([
                'product_id' => $product->id,
                'size' => $variant['size'],
                'stock' => $variant['stock'],
            ]);
        }

        auth()->user()->logActivity('create_product', "Menambah produk baru: {$product->name}");

        return redirect()->route('admin.produk.index')->with('success', 'Produk berhasil ditambahkan!');
    }

    /**
     * Detail produk.
     */
    public function show(Product $product)
    {
        $product->load(['category', 'variants', 'reviews.user']);
        return view('admin.products.show', compact('product'));
    }

    /**
     * Form edit produk.
     */
    public function edit(Product $product)
    {
        $product->load('variants');
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    /**
     * Perbarui data produk dan varian ukuran.
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'image_url' => 'nullable|string',
            'variants' => 'nullable|array',
            'variants.*.size' => 'required|integer|min:30|max:50',
            'variants.*.stock' => 'required|integer|min:0',
        ]);

        if ($request->hasFile('image')) {
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
            $product->image = $request->file('image')->store('products', 'public');
        } elseif ($request->filled('image_url')) {
            $product->image = $request->input('image_url');
        }

        $product->update([
            'category_id' => $validated['category_id'],
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']) . '-' . Str::random(4),
            'description' => $validated['description'],
            'price' => $validated['price'],
        ]);

        if (! empty($validated['variants'])) {
            // Perbarui atau buat varian ukuran baru
            foreach ($validated['variants'] as $v) {
                ProductVariant::updateOrCreate(
                    [
                        'product_id' => $product->id,
                        'size' => $v['size'],
                    ],
                    [
                        'stock' => $v['stock'],
                    ]
                );
            }
        }

        auth()->user()->logActivity('update_product', "Mengubah data produk: {$product->name}");

        return redirect()->route('admin.produk.index')->with('success', 'Data produk berhasil diperbarui!');
    }

    /**
     * Hapus produk.
     */
    public function destroy(Product $product)
    {
        $name = $product->name;
        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        auth()->user()->logActivity('delete_product', "Menghapus produk: {$name}");

        return redirect()->route('admin.produk.index')->with('success', 'Produk berhasil dihapus!');
    }
}
