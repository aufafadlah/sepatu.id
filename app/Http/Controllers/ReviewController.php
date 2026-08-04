<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Simpan atau perbarui ulasan produk (hanya untuk pembeli terverifikasi).
     */
    public function store(Request $request, Product $product)
    {
        $user = auth()->user();

        // Verifikasi apakah user pernah membeli produk ini dan pesanan sudah selesai
        $hasPurchased = Order::where('user_id', $user->id)
            ->where('status', Order::STATUS_SELESAI)
            ->whereHas('items.productVariant', function ($query) use ($product) {
                $query->where('product_id', $product->id);
            })
            ->exists();

        if (! $hasPurchased) {
            return back()->with('error', 'Hanya pembeli terverifikasi yang dapat memberikan ulasan untuk produk ini (pesanan harus berstatus selesai).');
        }

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
        ]);

        Review::updateOrCreate(
            [
                'user_id' => $user->id,
                'product_id' => $product->id,
            ],
            [
                'rating' => $validated['rating'],
                'comment' => $validated['comment'],
            ]
        );

        return back()->with('success', 'Ulasan Anda berhasil disimpan. Terima kasih atas makluman dan tanggapan Anda!');
    }
}
