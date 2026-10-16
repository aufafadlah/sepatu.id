<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;

class StockController extends Controller
{
    /**
     * Tampilkan kelola stok fisik per produk & varian ukuran terkelompok (Panel Admin Gudang).
     */
    public function index(Request $request)
    {
        $query = Product::with(['category', 'variants' => function ($q) {
            $q->orderBy('size', 'asc');
        }]);

        if ($request->boolean('low_stock')) {
            $query->whereHas('variants', function ($q) {
                $q->where('stock', '<', 5);
            });
        }

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('name', 'like', "%{$search}%");
        }

        $products = $query->latest()->paginate(10)->withQueryString();
        $allProducts = Product::orderBy('name', 'asc')->get();

        return view('admin.stock.index', compact('products', 'allProducts'));
    }

    /**
     * Tambahkan atau restock varian ukuran baru untuk produk.
     */
    public function storeVariant(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'size' => 'required|integer|min:30|max:50',
            'stock' => 'required|integer|min:0',
        ]);

        $variant = ProductVariant::updateOrCreate(
            [
                'product_id' => $validated['product_id'],
                'size' => $validated['size'],
            ],
            [
                'stock' => $validated['stock'],
            ]
        );

        $productName = $variant->product->name ?? 'Produk';

        auth()->user()->logActivity(
            'add_variant_stock',
            "Menambah/memperbarui varian ukuran {$variant->size} untuk produk {$productName} (stok: {$variant->stock} pasang)"
        );

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Stok {$productName} ukuran {$variant->size} berhasil ditambahkan ({$variant->stock} pasang)!",
                'variant' => $variant,
            ]);
        }

        return back()->with('success', "Stok {$productName} ukuran {$variant->size} berhasil ditambahkan!");
    }

    /**
     * Update stok varian ukuran tertentu via AJAX / Form.
     */
    public function update(Request $request, ProductVariant $variant)
    {
        $validated = $request->validate([
            'stock' => 'required|integer|min:0',
        ]);

        $oldStock = $variant->stock;
        $newStock = $validated['stock'];

        $variant->update(['stock' => $newStock]);

        $productName = $variant->product->name ?? 'Produk';

        auth()->user()->logActivity(
            'update_stock',
            "Mengubah stok {$productName} (Ukuran {$variant->size}) dari {$oldStock} menjadi {$newStock} pasang"
        );

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Stok {$productName} EUR {$variant->size} berhasil diperbarui menjadi {$newStock} pasang.",
                'new_stock' => $newStock,
            ]);
        }

        return back()->with('success', "Stok {$productName} ukuran {$variant->size} berhasil diperbarui.");
    }
}
