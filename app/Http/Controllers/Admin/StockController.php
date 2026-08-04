<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductVariant;
use Illuminate\Http\Request;

class StockController extends Controller
{
    /**
     * Tampilkan kelola stok fisik per varian ukuran (Panel Admin Gudang).
     */
    public function index(Request $request)
    {
        $query = ProductVariant::with(['product.category']);

        if ($request->boolean('low_stock')) {
            $query->where('stock', '<', 5);
        }

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->whereHas('product', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        $variants = $query->orderBy('stock', 'asc')->paginate(15)->withQueryString();

        return view('admin.stock.index', compact('variants'));
    }

    /**
     * Update stok varian ukuran tertentu.
     */
    public function update(Request $request, ProductVariant $variant)
    {
        $validated = $request->validate([
            'stock' => 'required|integer|min:0',
        ]);

        $oldStock = $variant->stock;
        $newStock = $validated['stock'];

        $variant->update(['stock' => $newStock]);

        auth()->user()->logActivity(
            'update_stock',
            "Mengubah stok {$variant->product->name} (Ukuran {$variant->size}) dari {$oldStock} menjadi {$newStock} pasang"
        );

        return back()->with('success', "Stok {$variant->product->name} ukuran {$variant->size} berhasil diperbarui.");
    }
}
