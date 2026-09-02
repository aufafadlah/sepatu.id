<?php

use App\Http\Controllers\CatalogController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\StockController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\SiteSettingController;
use Illuminate\Support\Facades\Route;

// =========================================================
// PUBLIK (tidak perlu login)
// =========================================================
Route::get('/', [CatalogController::class, 'index'])->name('catalog.index');
Route::get('/produk/{slug}', [CatalogController::class, 'show'])->name('catalog.show');

// =========================================================
// BUTUH LOGIN (Akses Akun & Profil)
// =========================================================
Route::middleware(['auth'])->group(function () {

    // Redirection Dashboard pintar berdasarkan Role (Admin vs User)
    Route::get('/dashboard', function () {
        if (auth()->user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('catalog.index');
    })->name('dashboard');

    // Profil Pengguna (Bisa diakses Admin maupun User)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // =========================================================
    // FITUR BELANJA PELANGGAN (Hanya untuk Role 'user', Admin tidak bisa belanja)
    // =========================================================
    Route::middleware(['role:user'])->group(function () {
        // Wishlist
        Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
        Route::post('/wishlist/{product}', [WishlistController::class, 'toggle'])->name('wishlist.toggle');

        // Keranjang Belanja
        Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
        Route::post('/cart', [CartController::class, 'store'])->name('cart.store');
        Route::patch('/cart/{cart}', [CartController::class, 'update'])->name('cart.update');
        Route::delete('/cart/{cart}', [CartController::class, 'destroy'])->name('cart.destroy');

        // Checkout
        Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
        Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
        Route::post('/checkout/apply-voucher', [CheckoutController::class, 'applyVoucher'])->name('checkout.apply-voucher');
        Route::post('/checkout/remove-voucher', [CheckoutController::class, 'removeVoucher'])->name('checkout.remove-voucher');

        // Pesanan Saya
        Route::get('/pesanan-saya', [OrderController::class, 'index'])->name('orders.index');
        Route::get('/pesanan-saya/{order}', [OrderController::class, 'show'])->name('orders.show');
        Route::post('/pesanan-saya/{order}/upload-bukti', [OrderController::class, 'uploadPaymentProof'])->name('orders.upload-proof');
        Route::post('/pesanan-saya/{order}/konfirmasi-terima', [OrderController::class, 'confirmReceived'])->name('orders.confirm-received');

        // Review Produk
        Route::post('/produk/{product}/review', [ReviewController::class, 'store'])->name('review.store');
    });
});

// =========================================================
// ADMIN — Sistem 2 Role sederhanan ('admin' & 'user')
// =========================================================
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        // Dashboard & Audit Trail & Report Export
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/laporan/export', [DashboardController::class, 'exportPdf'])->name('laporan.export');

        // Kelola Kategori & Produk
        Route::resource('/produk/kategori', \App\Http\Controllers\Admin\CategoryController::class)->names([
            'index' => 'produk.kategori.index',
            'create' => 'produk.kategori.create',
            'store' => 'produk.kategori.store',
            'edit' => 'produk.kategori.edit',
            'update' => 'produk.kategori.update',
            'destroy' => 'produk.kategori.destroy',
        ])->parameters(['kategori' => 'category']);
        Route::resource('/kategori', \App\Http\Controllers\Admin\CategoryController::class)->names([
            'index' => 'kategori.index',
            'create' => 'kategori.create',
            'store' => 'kategori.store',
            'edit' => 'kategori.edit',
            'update' => 'kategori.update',
            'destroy' => 'kategori.destroy',
        ])->parameters(['kategori' => 'category']);

        Route::resource('/produk', AdminProductController::class)->parameters(['produk' => 'product']);

        // Kelola Voucher Promo Diskon
        Route::resource('/vouchers', \App\Http\Controllers\Admin\VoucherController::class);
        Route::patch('/vouchers/{voucher}/toggle', [\App\Http\Controllers\Admin\VoucherController::class, 'toggleStatus'])->name('vouchers.toggle');

        // Kelola Stok Gudang
        Route::get('/gudang', [StockController::class, 'index'])->name('gudang.index');
        Route::patch('/gudang/{variant}', [StockController::class, 'update'])->name('gudang.update');

        // Kelola Pesanan
        Route::get('/pesanan', [AdminOrderController::class, 'index'])->name('pesanan.index');
        Route::get('/pesanan/{order}', [AdminOrderController::class, 'show'])->name('pesanan.show');
        Route::get('/pesanan/{order}/cetak-alamat', [AdminOrderController::class, 'printAddress'])->name('pesanan.print-address');
        Route::patch('/pesanan/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('pesanan.update-status');
        Route::patch('/pesanan/{order}/payment-status', [AdminOrderController::class, 'updatePaymentStatus'])->name('pesanan.update-payment-status');

        // Kelola User Accounts
        Route::resource('/users', AdminUserController::class)->except(['show']);

        // Edit Teks Landing Page (CMS)
        Route::get('/settings/landing', [SiteSettingController::class, 'landing'])->name('settings.landing');
        Route::put('/settings/landing', [SiteSettingController::class, 'updateLanding'])->name('settings.landing.update');
    });

require __DIR__.'/auth.php';