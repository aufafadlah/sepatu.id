<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    /**
     * Tampilkan katalog produk publik dengan pencarian, filter, dan pengurutan.
     */
    public function index(Request $request)
    {
        if (auth()->check() && auth()->user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        $query = Product::with(['category', 'variants', 'reviews']);

        // Filter Pencarian Keyword
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter Kategori
        if ($request->filled('category')) {
            $query->where('category_id', $request->input('category'));
        }

        // Filter Ukuran (per varian)
        if ($request->filled('size')) {
            $size = $request->input('size');
            $query->whereHas('variants', function ($q) use ($size) {
                $q->where('size', $size)->where('stock', '>', 0);
            });
        }

        // Filter Rentang Harga
        if ($request->filled('min_price')) {
            $query->where('price', '>=', (float) $request->input('min_price'));
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', (float) $request->input('max_price'));
        }

        // Pengurutan (Sorting)
        switch ($request->input('sort')) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'rating_desc':
                $query->withAvg('reviews', 'rating')->orderByDesc('reviews_avg_rating');
                break;
            case 'latest':
            default:
                $query->latest();
                break;
        }

        $products = $query->paginate(12)->withQueryString();
        $categories = Category::withCount('products')->get();
        $availableSizes = ProductVariant::distinct()->pluck('size')->sort()->values();

        // AJAX request → return partial HTML
        if ($request->wantsJson() || $request->ajax()) {
            $html = view('catalog._product-grid', compact('products'))->render();
            $pagination = $products->links()->toHtml();
            return response()->json([
                'html' => $html,
                'pagination' => $pagination,
                'total' => $products->total(),
            ]);
        }

        return view('catalog.index', compact('products', 'categories', 'availableSizes'));
    }

    /**
     * Tampilkan detail produk berdasarkan slug.
     */
    public function show(string $slug)
    {
        if (auth()->check() && auth()->user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        $product = Product::with(['category', 'variants', 'reviews.user'])
            ->where('slug', $slug)
            ->firstOrFail();

        $relatedProducts = Product::with(['category', 'variants'])
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->take(4)
            ->get();

        $inWishlist = auth()->check()
            ? auth()->user()->wishlists()->where('product_id', $product->id)->exists()
            : false;

        // Cek apakah user sudah pernah membeli produk ini (untuk ulasan)
        $canReview = false;
        $existingReview = null;
        if (auth()->check()) {
            $canReview = Order::where('user_id', auth()->id())
                ->where('status', Order::STATUS_SELESAI)
                ->whereHas('items.productVariant', function ($query) use ($product) {
                    $query->where('product_id', $product->id);
                })
                ->exists();

            $existingReview = $product->reviews()->where('user_id', auth()->id())->first();
        }

        return view('catalog.show', compact('product', 'relatedProducts', 'inWishlist', 'canReview', 'existingReview'));
    }
}
