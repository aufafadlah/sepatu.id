@extends('layouts.main')

@section('title', $product->name . ' — Sepatu.id')

@section('content')

    <!-- Breadcrumb -->
    <div class="border-b border-neutral-200 py-3.5 text-xs text-neutral-500 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center gap-2 font-medium">
            <a href="{{ route('catalog.index') }}" class="hover:text-neutral-900 transition">Katalog</a>
            <span class="text-neutral-300">/</span>
            <span class="text-neutral-600 font-semibold">{{ $product->category->name ?? 'Sepatu' }}</span>
            <span class="text-neutral-300">/</span>
            <span class="text-neutral-900 font-bold truncate uppercase">{{ $product->name }}</span>
        </div>
    </div>

    <!-- Product Detail Section -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-12">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-12 items-start">

            <!-- Gallery (Left Column - Sticky) -->
            <div class="lg:col-span-6 lg:sticky lg:top-28">
                <div class="bg-[#f9fafb] rounded-3xl overflow-hidden aspect-square flex items-center justify-center border border-neutral-200/80 p-8 relative shadow-xs">
                    
                    <!-- Floating Discount Tag -->
                    @if($product->hasDiscount())
                        <div class="absolute top-5 left-5 z-10 flex items-center gap-1.5 bg-orange-500 text-white font-black text-xs uppercase px-3 py-1.5 rounded-full shadow-md">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M12.395 2.553a1 1 0 00-1.45-.385c-.345.23-.614.558-.822.88-.316.492-.633.991-1.002 1.49-.39.529-.81 1.057-1.284 1.584C6.88 7.158 5.74 8.448 5.09 9.878c-.65 1.43-.89 2.977-.62 4.498.3 1.688 1.25 3.197 2.62 4.144 1.37.947 3.09 1.282 4.74 1.041 1.65-.24 3.17-1.11 4.19-2.387 1.02-1.277 1.48-2.915 1.34-4.57-.15-1.786-.96-3.414-2.18-4.717-.46-.49-.97-.936-1.5-1.34-.33-.25-.66-.48-.99-.68a9.42 9.42 0 00-.7-.417l-.07-.037-.02-.01-.01-.005z" clip-rule="evenodd" />
                            </svg>
                            <span>Diskon {{ $product->discountPercent() }}%</span>
                        </div>
                    @endif

                    <!-- Verified Original Pill -->
                    <div class="absolute bottom-5 left-5 z-10 hidden sm:flex items-center gap-1.5 bg-white/90 backdrop-blur-xs text-neutral-800 font-extrabold text-[11px] uppercase tracking-wider px-3 py-1.5 rounded-full border border-neutral-200 shadow-2xs">
                        <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" /></svg>
                        <span>100% Produk Asli</span>
                    </div>

                    @if($product->image)
                        <img src="{{ Str::startsWith($product->image, ['http://', 'https://']) ? $product->image : asset('storage/' . $product->image) }}"
                             alt="{{ $product->name }}"
                             class="max-h-full max-w-full object-contain filter drop-shadow-xl hover:scale-105 transition-transform duration-500 ease-out">
                    @else
                        <div class="w-full h-full flex items-center justify-center bg-neutral-100">
                            <svg class="w-24 h-24 text-neutral-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                    @endif

                    @if($product->isOutOfStock())
                        <div class="absolute inset-0 bg-neutral-950/70 backdrop-blur-xs flex items-center justify-center">
                            <span class="px-5 py-2.5 rounded-full bg-rose-600 text-white font-black text-xs uppercase tracking-widest shadow-xl">
                                Stok Habis
                            </span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Product Specs & Purchase Box (Right Column) -->
            <div class="lg:col-span-6 space-y-6"
                 x-data="{
                     selectedVariant: null,
                     selectedSize: null,
                     selectedStock: 0,
                     qty: 1,
                     chooseVariant(id, size, stock) {
                         if (stock <= 0) return;
                         this.selectedVariant = id;
                         this.selectedSize = size;
                         this.selectedStock = stock;
                         if (this.qty > stock) this.qty = 1;
                     }
                 }">

                <!-- Brand / Category / Rating -->
                <div class="space-y-3">
                    <div class="flex items-center justify-between gap-2 flex-wrap">
                        <span class="px-3 py-1 rounded-full bg-orange-50 border border-orange-200 text-orange-600 font-black uppercase tracking-widest text-[11px]">
                            {{ $product->category->name ?? 'Sepatu' }}
                        </span>
                        
                        <!-- Dynamic Rating Stars -->
                        @php
                            $avgRating = $product->averageRating() ?? 0;
                            $reviewCount = $product->reviews->count();
                        @endphp
                        <a href="#ulasan-pembeli" class="inline-flex items-center gap-1.5 text-xs font-bold hover:text-orange-600 transition">
                            <div class="flex items-center text-amber-400 text-sm">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= floor($avgRating))
                                        ★
                                    @else
                                        <span class="text-neutral-300">★</span>
                                    @endif
                                @endfor
                            </div>
                            <span class="text-neutral-900 font-extrabold">{{ $reviewCount > 0 ? number_format($avgRating, 1) : '5.0' }}</span>
                            <span class="text-neutral-400 font-medium underline">({{ $reviewCount }} Ulasan)</span>
                        </a>
                    </div>

                    <h1 class="text-3xl sm:text-4xl font-black text-neutral-900 uppercase tracking-tight leading-tight">
                        {{ $product->name }}
                    </h1>
                </div>

                <!-- Price Block -->
                <div class="bg-white rounded-2xl p-5 border border-neutral-200/80 shadow-2xs space-y-3">
                    <div class="flex flex-wrap items-baseline gap-3">
                        @if($product->hasDiscount())
                            <span class="text-3xl sm:text-4xl font-black text-orange-600">
                                Rp {{ number_format($product->finalPrice(), 0, ',', '.') }}
                            </span>
                            <span class="text-sm text-neutral-400 line-through font-semibold">
                                Rp {{ number_format($product->price, 0, ',', '.') }}
                            </span>
                            <span class="text-xs font-extrabold text-white bg-orange-500 px-2.5 py-1 rounded-full uppercase tracking-wider">
                                Hemat Rp {{ number_format($product->price - $product->finalPrice(), 0, ',', '.') }}
                            </span>
                        @else
                            <span class="text-3xl sm:text-4xl font-black text-neutral-900">
                                Rp {{ number_format($product->price, 0, ',', '.') }}
                            </span>
                        @endif
                    </div>

                    <div class="flex items-center gap-2 pt-2 border-t border-neutral-100 text-xs">
                        <span class="flex h-2.5 w-2.5 relative">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
                        </span>
                        <span class="font-bold text-neutral-700">
                            Stok Toko: <strong class="text-neutral-900">{{ $product->totalStock() }} pasang</strong> siap kirim
                        </span>
                    </div>
                </div>

                <!-- Size & Order Form -->
                <form action="{{ route('cart.store') }}" method="POST" class="bg-white rounded-2xl p-6 border border-neutral-200 shadow-2xs space-y-5">
                    @csrf
                    <input type="hidden" name="product_variant_id" :value="selectedVariant" required>

                    <!-- Size Selection -->
                    <div>
                        <div class="flex items-center justify-between mb-2.5">
                            <label class="text-xs font-black uppercase tracking-wider text-neutral-900">
                                1. Pilih Ukuran Sepatu (EUR): <span class="text-rose-500">*</span>
                            </label>
                            <span class="text-[11px] text-neutral-400 font-semibold uppercase">Standard EUR</span>
                        </div>

                        <div class="grid grid-cols-4 sm:grid-cols-7 gap-2">
                            @forelse($product->variants as $variant)
                                @php $isOut = $variant->stock <= 0; @endphp
                                <button type="button"
                                        @click="chooseVariant({{ $variant->id }}, '{{ $variant->size }}', {{ $variant->stock }})"
                                        :class="{
                                            'bg-neutral-900 text-white border-neutral-900 ring-2 ring-neutral-900 shadow-sm': selectedVariant === {{ $variant->id }},
                                            'bg-white text-neutral-800 border-neutral-300 hover:border-neutral-900 hover:bg-neutral-50': selectedVariant !== {{ $variant->id }} && {{ $isOut ? 'false' : 'true' }},
                                            'opacity-30 bg-neutral-100 text-neutral-400 border-neutral-200 cursor-not-allowed line-through': {{ $isOut ? 'true' : 'false' }}
                                        }"
                                        class="py-3 rounded-xl border text-xs font-black transition text-center relative"
                                        {{ $isOut ? 'disabled' : '' }}>
                                    <span>{{ $variant->size }}</span>
                                </button>
                            @empty
                                <p class="text-xs text-rose-500 col-span-4">Belum ada varian ukuran tersedia.</p>
                            @endforelse
                        </div>

                        <!-- Feedback Ukuran Terpilih -->
                        <div class="mt-2.5 min-h-[20px]">
                            <template x-if="selectedVariant">
                                <p class="text-xs font-semibold text-neutral-700">
                                    Ukuran <strong class="text-neutral-900 font-black">EUR <span x-text="selectedSize"></span></strong> dipilih —
                                    <span :class="selectedStock <= 3 ? 'text-rose-600 font-extrabold' : 'text-emerald-700 font-bold'">
                                        tersisa <span x-text="selectedStock"></span> pasang
                                    </span>
                                </p>
                            </template>
                            <template x-if="!selectedVariant">
                                <p class="text-xs text-neutral-400 font-medium italic">
                                    Silakan klik salah satu ukuran di atas untuk melanjutkan belanja.
                                </p>
                            </template>
                        </div>
                    </div>

                    <!-- Quantity Selection -->
                    <div class="pt-4 border-t border-neutral-100 flex items-center justify-between">
                        <label class="text-xs font-black uppercase tracking-wider text-neutral-900">
                            2. Jumlah Pasang:
                        </label>
                        <div class="flex items-center border border-neutral-300 rounded-full bg-[#f8f8f8] p-1">
                            <button type="button"
                                    @click="if(qty > 1) qty--"
                                    class="w-8 h-8 rounded-full flex items-center justify-center text-neutral-800 font-bold hover:bg-neutral-200 transition">-</button>
                            <input type="number" name="qty" x-model="qty" min="1" :max="selectedStock || 99"
                                   class="w-12 text-center text-xs font-extrabold border-none bg-transparent focus:ring-0 p-0">
                            <button type="button"
                                    @click="if(!selectedStock || qty < selectedStock) qty++"
                                    class="w-8 h-8 rounded-full flex items-center justify-center text-neutral-800 font-bold hover:bg-neutral-200 transition">+</button>
                        </div>
                    </div>

                    <!-- Action Buttons Row -->
                    <div class="pt-4 border-t border-neutral-100 space-y-3">
                        <div class="flex flex-col sm:flex-row items-center gap-3">
                            {{-- Button 1: Tambah ke Keranjang (AJAX) --}}
                            <button type="button" id="btn-add-cart"
                                    :disabled="!selectedVariant || {{ $product->isOutOfStock() ? 'true' : 'false' }}"
                                    :class="selectedVariant ? 'bg-white border-neutral-900 text-neutral-900 hover:bg-neutral-900 hover:text-white cursor-pointer shadow-2xs' : 'bg-neutral-100 border-neutral-200 text-neutral-400 cursor-not-allowed'"
                                    class="flex-1 w-full py-4 rounded-full font-black text-xs uppercase tracking-widest transition flex items-center justify-center gap-2 border-2"
                                    @click="addToCartAjax($event, {{ $product->id }}, selectedVariant, qty)">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                </svg>
                                <span x-text="selectedVariant ? '+ Keranjang' : 'Pilih Ukuran Dulu'"></span>
                            </button>

                            {{-- Button 2: Beli Langsung (Redirect Checkout) --}}
                            <button type="submit" name="buy_now" value="1"
                                    :disabled="!selectedVariant || {{ $product->isOutOfStock() ? 'true' : 'false' }}"
                                    :class="selectedVariant ? 'bg-orange-500 hover:bg-orange-600 text-white cursor-pointer shadow-md' : 'bg-neutral-200 text-neutral-400 cursor-not-allowed'"
                                    class="flex-1 w-full py-4 rounded-full font-black text-xs uppercase tracking-widest transition flex items-center justify-center gap-2">
                                <span x-text="selectedVariant ? 'Beli Langsung →' : 'Pilih Ukuran Dulu'"></span>
                            </button>

                            {{-- Wishlist Heart Button --}}
                            @auth
                                @php $inWishlist = auth()->user()->wishlists()->where('product_id', $product->id)->exists(); @endphp
                                <button type="button" id="detail-wishlist-btn"
                                        onclick="toggleWishlistDetail({{ $product->id }}, this)"
                                        data-in-wishlist="{{ $inWishlist ? 'true' : 'false' }}"
                                        title="{{ $inWishlist ? 'Hapus dari Wishlist' : 'Simpan ke Wishlist' }}"
                                        class="p-4 rounded-full border transition shrink-0 {{ $inWishlist ? 'bg-rose-50 border-rose-300 text-rose-600' : 'bg-white border-neutral-300 text-neutral-500 hover:border-rose-300 hover:text-rose-500' }}">
                                    <svg id="detail-wishlist-icon" class="w-5 h-5" viewBox="0 0 24 24" fill="{{ $inWishlist ? 'currentColor' : 'none' }}" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                    </svg>
                                </button>
                            @else
                                <a href="{{ route('login') }}"
                                   title="Login untuk Wishlist"
                                   class="p-4 rounded-full border border-neutral-300 bg-white text-neutral-400 hover:border-rose-300 hover:text-rose-500 transition shrink-0">
                                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                    </svg>
                                </a>
                            @endauth
                        </div>
                    </div>
                </form>

                <!-- Value Proposition Perks -->
                <div class="grid grid-cols-2 gap-3 pt-2">
                    <div class="p-3.5 rounded-2xl bg-neutral-50 border border-neutral-200/80 flex items-center gap-3">
                        <div class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" /></svg>
                        </div>
                        <div class="text-[11px]">
                            <p class="font-extrabold text-neutral-900 uppercase">100% Original</p>
                            <p class="text-neutral-500">Garansi uang kembali</p>
                        </div>
                    </div>

                    <div class="p-3.5 rounded-2xl bg-neutral-50 border border-neutral-200/80 flex items-center gap-3">
                        <div class="w-8 h-8 rounded-xl bg-indigo-100 text-indigo-700 flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>
                        </div>
                        <div class="text-[11px]">
                            <p class="font-extrabold text-neutral-900 uppercase">Tukar Ukuran</p>
                            <p class="text-neutral-500">Garansi 7 hari penukaran</p>
                        </div>
                    </div>

                    <div class="p-3.5 rounded-2xl bg-neutral-50 border border-neutral-200/80 flex items-center gap-3">
                        <div class="w-8 h-8 rounded-xl bg-orange-100 text-orange-700 flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" /></svg>
                        </div>
                        <div class="text-[11px]">
                            <p class="font-extrabold text-neutral-900 uppercase">Bebas Ongkir</p>
                            <p class="text-neutral-500">Promo seluruh Indonesia</p>
                        </div>
                    </div>

                    <div class="p-3.5 rounded-2xl bg-neutral-50 border border-neutral-200/80 flex items-center gap-3">
                        <div class="w-8 h-8 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                        </div>
                        <div class="text-[11px]">
                            <p class="font-extrabold text-neutral-900 uppercase">Kirim Cepat</p>
                            <p class="text-neutral-500">Proses kirim hari ini</p>
                        </div>
                    </div>
                </div>

                <!-- Product Description Card -->
                <div class="bg-white rounded-2xl p-6 border border-neutral-200 shadow-2xs space-y-3">
                    <h3 class="text-xs font-black uppercase tracking-wider text-neutral-900 border-b border-neutral-100 pb-3">
                        Deskripsi &amp; Spesifikasi Produk
                    </h3>
                    <p class="text-xs sm:text-sm text-neutral-600 leading-relaxed font-medium whitespace-pre-line">
                        {{ $product->description }}
                    </p>
                </div>

            </div>

        </div>

        <!-- Reviews Section -->
        <div id="ulasan-pembeli" class="mt-16 bg-white rounded-3xl p-6 sm:p-10 border border-neutral-200 shadow-2xs space-y-8">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-neutral-100 pb-6">
                <div>
                    <h2 class="text-xl font-black text-neutral-900 uppercase tracking-tight">Ulasan &amp; Penilaian Pembeli</h2>
                    <p class="text-xs text-neutral-500 mt-0.5">Ulasan autentik dari pelanggan yang telah melakukan pembelian</p>
                </div>
                <div class="flex items-center gap-3 bg-[#f8f8f8] px-4 py-2.5 rounded-2xl border border-neutral-200">
                    <span class="text-2xl font-black text-neutral-900">{{ $reviewCount > 0 ? number_format($avgRating, 1) : '5.0' }}</span>
                    <div class="text-left">
                        <div class="flex items-center text-amber-400 text-xs">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= floor($avgRating)) ★ @else <span class="text-neutral-300">★</span> @endif
                            @endfor
                        </div>
                        <span class="text-[11px] text-neutral-500 font-semibold">{{ $reviewCount }} total ulasan</span>
                    </div>
                </div>
            </div>

            <!-- Review Submission Form / Status Notice -->
            @auth
                @if($canReview)
                    <div class="bg-[#f8f8f8] p-6 sm:p-8 rounded-3xl border border-neutral-200 space-y-5" x-data="{ rating: {{ $existingReview ? $existingReview->rating : 5 }} }">
                        <div class="flex items-center justify-between border-b border-neutral-200/80 pb-3">
                            <h4 class="text-xs font-black text-neutral-900 uppercase tracking-wider">
                                {{ $existingReview ? 'Perbarui Ulasan Anda' : 'Tulis Ulasan & Lampirkan Foto/Video' }}
                            </h4>
                            <span class="text-[11px] font-bold text-emerald-700 bg-emerald-100 px-2.5 py-0.5 rounded-full">Pembeli Terverifikasi ✓</span>
                        </div>

                        <form action="{{ route('review.store', $product) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                            @csrf
                            
                            <!-- Interactive Star Picker -->
                            <div>
                                <label class="block text-xs font-black uppercase tracking-wider text-neutral-800 mb-1.5">Rating Kepuasan:</label>
                                <div class="flex items-center gap-1.5 text-3xl text-amber-400 cursor-pointer select-none">
                                    <template x-for="i in 5">
                                        <span @click="rating = i" :class="i <= rating ? 'text-amber-500 scale-105' : 'text-neutral-300'" class="transition hover:scale-125">★</span>
                                    </template>
                                    <span class="text-xs font-extrabold text-neutral-700 ml-2" x-text="rating + ' / 5 Bintang'"></span>
                                </div>
                                <input type="hidden" name="rating" :value="rating" required>
                            </div>

                            <div>
                                <label class="block text-xs font-black uppercase tracking-wider text-neutral-800 mb-1.5">Komentar Ulasan:</label>
                                <textarea name="comment" rows="3" required placeholder="Ceritakan pengalaman Anda mengenai ukuran, kenyamanan bantalan, dan keaslian produk..." class="w-full text-xs rounded-2xl border-neutral-300 p-3.5 focus:ring-neutral-900 font-medium bg-white">{{ $existingReview ? $existingReview->comment : '' }}</textarea>
                            </div>

                            <!-- Photo & Video Upload Grid -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-1">
                                <div class="space-y-2">
                                    <label class="block text-[11px] font-black uppercase tracking-wider text-neutral-800">
                                        Foto Produk (JPG/PNG, Max 5MB):
                                    </label>
                                    <div class="flex flex-wrap items-center gap-2">
                                        <input type="file" id="review-photo-input" name="image" accept="image/*" class="w-full text-xs text-neutral-500 file:mr-3 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-neutral-900 file:text-white hover:file:bg-orange-500 transition cursor-pointer">
                                        <button type="button" onclick="openCameraModal()"
                                                class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full bg-indigo-50 hover:bg-indigo-100 text-indigo-800 font-extrabold text-[11px] uppercase tracking-wider transition border border-indigo-200">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                            Ambil Foto Kamera Langsung
                                        </button>
                                    </div>
                                    <div id="camera-preview-container" class="hidden mt-2">
                                        <img id="camera-captured-preview" src="" alt="Captured" class="w-24 h-24 object-cover rounded-xl border border-indigo-300 shadow-2xs">
                                        <p class="text-[10px] text-indigo-700 font-semibold mt-1">Foto kamera terlampir ✓</p>
                                    </div>
                                    @if($existingReview && $existingReview->image)
                                        <p class="text-[10px] text-emerald-600 font-semibold">Foto saat ini telah terpasang</p>
                                    @endif
                                </div>

                                <div class="space-y-2">
                                    <label class="block text-[11px] font-black uppercase tracking-wider text-neutral-800">
                                        Video Unboxing (Opsional, MP4/WebM, Max 20MB):
                                    </label>
                                    <input type="file" name="video" accept="video/*" class="w-full text-xs text-neutral-500 file:mr-3 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-neutral-900 file:text-white hover:file:bg-orange-500 transition cursor-pointer">
                                    @if($existingReview && $existingReview->video)
                                        <p class="text-[10px] text-emerald-600 font-semibold">Video saat ini telah terpasang</p>
                                    @endif
                                </div>
                            </div>

                            <div class="pt-3 border-t border-neutral-200">
                                <button type="submit" class="px-8 py-3.5 bg-neutral-900 hover:bg-orange-500 text-white text-xs font-extrabold uppercase tracking-widest rounded-full transition shadow-md">
                                    {{ $existingReview ? 'Simpan Perubahan Ulasan' : 'Publikasikan Ulasan & Media' }}
                                </button>
                            </div>
                        </form>
                    </div>
                @else
                    <div class="bg-amber-50/80 border border-amber-200 rounded-2xl p-4 sm:p-5 text-amber-900 text-xs font-medium flex items-center justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-amber-200 flex items-center justify-center shrink-0 text-amber-800">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            </div>
                            <span>Hanya pelanggan yang telah membeli produk ini dan pesanan telah dikonfirmasi selesai yang dapat memberikan ulasan &amp; melampirkan media.</span>
                        </div>
                        <a href="{{ route('orders.index') }}" class="px-4 py-2 rounded-full bg-neutral-900 hover:bg-orange-500 text-white font-black text-[11px] uppercase tracking-wider shrink-0 transition">
                            Cek Pesanan
                        </a>
                    </div>
                @endif
            @else
                <div class="bg-neutral-50 border border-neutral-200 rounded-2xl p-5 text-neutral-700 text-xs font-medium text-center">
                    Ingin memberikan ulasan untuk produk ini? Silakan <a href="{{ route('login') }}" class="font-extrabold underline text-neutral-900 hover:text-orange-600">Masuk ke Akun Anda</a> terlebih dahulu.
                </div>
            @endauth

            <!-- Customer Reviews List -->
            <div class="space-y-4" x-data="{ mediaModal: false, mediaType: '', mediaSrc: '' }">
                @forelse($product->reviews as $review)
                    <div class="p-5 sm:p-6 rounded-2xl border border-neutral-200/90 bg-[#fafafa] space-y-3">
                        <div class="flex items-center justify-between text-xs">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-neutral-900 text-white font-black text-xs flex items-center justify-center shadow-xs">
                                    {{ strtoupper(substr($review->user->name ?? 'P', 0, 1)) }}
                                </div>
                                <div>
                                    <div class="flex items-center gap-2">
                                        <span class="font-black text-neutral-900 uppercase text-xs">{{ $review->user->name ?? 'Pelanggan' }}</span>
                                        <span class="text-[10px] font-bold text-emerald-700 bg-emerald-100 px-2 py-0.2 rounded-full">Terverifikasi ✓</span>
                                    </div>
                                    <span class="text-[10px] text-neutral-400 font-semibold">{{ $review->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                            <div class="flex items-center text-amber-500 text-sm">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $review->rating) ★ @else <span class="text-neutral-300">★</span> @endif
                                @endfor
                            </div>
                        </div>

                        <p class="text-xs text-neutral-700 font-medium leading-relaxed">
                            "{{ $review->comment }}"
                        </p>

                        <!-- Attached Media: Photo & Video -->
                        @if($review->image || $review->video)
                            <div class="flex flex-wrap items-center gap-3 pt-2">
                                @if($review->image)
                                    <div class="relative group cursor-pointer w-20 h-20 rounded-xl overflow-hidden border border-neutral-300 bg-white shadow-2xs"
                                         @click="mediaModal = true; mediaType = 'image'; mediaSrc = '{{ asset('storage/' . $review->image) }}'">
                                        <img src="{{ asset('storage/' . $review->image) }}" alt="Foto Ulasan" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                        <div class="absolute inset-0 bg-neutral-950/40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center text-white text-[10px] font-bold">
                                            🔍 Lihat
                                        </div>
                                    </div>
                                @endif

                                @if($review->video)
                                    <div class="relative group cursor-pointer w-20 h-20 rounded-xl overflow-hidden border border-neutral-300 bg-neutral-900 flex items-center justify-center shadow-2xs"
                                         @click="mediaModal = true; mediaType = 'video'; mediaSrc = '{{ asset('storage/' . $review->video) }}'">
                                        <video src="{{ asset('storage/' . $review->video) }}" class="w-full h-full object-cover opacity-70"></video>
                                        <div class="absolute inset-0 flex items-center justify-center">
                                            <div class="w-7 h-7 rounded-full bg-white/90 text-neutral-900 flex items-center justify-center shadow-md">
                                                <svg class="w-3.5 h-3.5 fill-current ml-0.5" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="text-center py-10 border border-dashed border-neutral-200 rounded-3xl space-y-2">
                        <p class="text-xs text-neutral-400 uppercase font-black tracking-wider">Belum ada ulasan untuk sepatu ini</p>
                        <p class="text-[11px] text-neutral-500">Jadilah pelanggan pertama yang mencoba dan memberikan penilaian!</p>
                    </div>
                @endforelse

                <!-- Media Preview Lightbox Modal -->
                <div x-show="mediaModal" x-cloak class="fixed inset-0 z-50 bg-neutral-950/80 backdrop-blur-xs flex items-center justify-center p-4" @click.self="mediaModal = false">
                    <div class="relative max-w-2xl max-h-[85vh] bg-white rounded-3xl overflow-hidden p-4 shadow-2xl space-y-3">
                        <div class="flex items-center justify-between border-b border-neutral-200 pb-2.5">
                            <span class="text-xs font-black uppercase tracking-wider text-neutral-900">Lampiran Media Ulasan</span>
                            <button @click="mediaModal = false" class="p-1 rounded-lg text-neutral-400 hover:text-neutral-900 hover:bg-neutral-100 transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                            </button>
                        </div>
                        <div class="flex items-center justify-center max-h-[70vh] overflow-auto">
                            <template x-if="mediaType === 'image'">
                                <img :src="mediaSrc" alt="Foto Ulasan" class="max-h-[70vh] max-w-full rounded-2xl object-contain">
                            </template>
                            <template x-if="mediaType === 'video'">
                                <video :src="mediaSrc" controls autoplay class="max-h-[70vh] max-w-full rounded-2xl"></video>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>

@endsection

@push('scripts')
{{-- ================================================================== --}}
{{-- CAMERA MODAL (WebRTC) untuk Foto Ulasan Produk                     --}}
{{-- ================================================================== --}}
<div id="camera-modal" class="hidden fixed inset-0 z-[60] bg-neutral-950/80 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl p-6 w-full max-w-md shadow-2xl space-y-4">
        <div class="flex items-center justify-between">
            <h3 class="font-black text-sm uppercase tracking-tight text-neutral-900 flex items-center gap-2">
                <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                Ambil Foto Ulasan
            </h3>
            <button onclick="closeCameraModal()" class="p-1.5 rounded-lg text-neutral-400 hover:text-neutral-900 hover:bg-neutral-100 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        </div>

        <!-- Live Camera Preview -->
        <div id="camera-live-view" class="space-y-3">
            <video id="camera-stream" class="w-full rounded-2xl bg-neutral-900 aspect-square object-cover" autoplay muted playsinline></video>
            <button type="button" onclick="capturePhoto()"
                    class="w-full py-3.5 rounded-full bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold text-xs uppercase tracking-widest transition flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                Jepret Foto
            </button>
        </div>

        <!-- Captured Preview -->
        <div id="camera-result-view" class="hidden space-y-3">
            <canvas id="camera-canvas" class="w-full rounded-2xl border border-neutral-200 aspect-square object-cover"></canvas>
            <div class="flex gap-2">
                <button type="button" onclick="retakePhoto()"
                        class="flex-1 py-3 rounded-full border border-neutral-300 text-neutral-700 font-bold text-xs uppercase tracking-wider hover:bg-neutral-100 transition">
                    Ulangi Foto
                </button>
                <button type="button" onclick="usePhoto()"
                        class="flex-1 py-3 rounded-full bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-xs uppercase tracking-wider transition">
                    ✓ Pakai Foto Ini
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// =====================================================================
// 1. WebRTC Camera Capture untuk Foto Ulasan
// =====================================================================
let cameraStream = null;

function openCameraModal() {
    const modal = document.getElementById('camera-modal');
    modal.classList.remove('hidden');
    document.getElementById('camera-live-view').classList.remove('hidden');
    document.getElementById('camera-result-view').classList.add('hidden');

    navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' }, audio: false })
        .then(stream => {
            cameraStream = stream;
            document.getElementById('camera-stream').srcObject = stream;
        })
        .catch(err => {
            closeCameraModal();
            if (window.showToast) window.showToast('Tidak dapat mengakses kamera: ' + err.message, 'error');
        });
}

function closeCameraModal() {
    if (cameraStream) {
        cameraStream.getTracks().forEach(t => t.stop());
        cameraStream = null;
    }
    document.getElementById('camera-modal').classList.add('hidden');
}

function capturePhoto() {
    const video = document.getElementById('camera-stream');
    const canvas = document.getElementById('camera-canvas');
    const ctx = canvas.getContext('2d');
    canvas.width = video.videoWidth || 640;
    canvas.height = video.videoHeight || 640;
    ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
    document.getElementById('camera-live-view').classList.add('hidden');
    document.getElementById('camera-result-view').classList.remove('hidden');
}

function retakePhoto() {
    document.getElementById('camera-live-view').classList.remove('hidden');
    document.getElementById('camera-result-view').classList.add('hidden');
}

function usePhoto() {
    const canvas = document.getElementById('camera-canvas');
    canvas.toBlob(blob => {
        const file = new File([blob], 'camera-review-' + Date.now() + '.jpg', { type: 'image/jpeg' });
        const dt = new DataTransfer();
        dt.items.add(file);
        const input = document.getElementById('review-photo-input');
        if (input) input.files = dt.files;

        // Show preview
        const previewImg = document.getElementById('camera-captured-preview');
        const previewContainer = document.getElementById('camera-preview-container');
        if (previewImg && previewContainer) {
            previewImg.src = URL.createObjectURL(blob);
            previewContainer.classList.remove('hidden');
        }

        closeCameraModal();
        if (window.showToast) window.showToast('Foto berhasil dilampirkan ke formulir ulasan!', 'success');
    }, 'image/jpeg', 0.9);
}

// =====================================================================
// 2. AJAX "Tambah ke Keranjang" (tanpa redirect halaman)
// =====================================================================
async function addToCartAjax(event, productId, variantId, qty) {
    if (!variantId) return;
    const btn = event.currentTarget;
    const span = btn.querySelector('span');
    const originalText = span ? span.textContent : '';
    btn.disabled = true;
    if (span) span.textContent = 'Menambahkan...';

    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    try {
        const resp = await fetch('{{ route('cart.store') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ product_variant_id: variantId, qty: qty })
        });
        const data = await resp.json();
        if (resp.ok && data.success !== false) {
            // Update cart badge in navbar if exists
            const badges = document.querySelectorAll('.cart-count-badge');
            badges.forEach(b => {
                const cur = parseInt(b.textContent || '0');
                b.textContent = cur + parseInt(qty);
                b.classList.remove('hidden');
            });
            if (window.showToast) window.showToast(data.message || 'Produk berhasil ditambahkan ke keranjang!', 'success');
        } else {
            if (window.showToast) window.showToast(data.message || 'Gagal menambahkan ke keranjang.', 'error');
        }
    } catch (e) {
        if (window.showToast) window.showToast('Terjadi kesalahan koneksi.', 'error');
    } finally {
        btn.disabled = false;
        if (span) span.textContent = originalText;
    }
}

// =====================================================================
// 3. AJAX Wishlist Toggle (halaman detail produk)
// =====================================================================
async function toggleWishlistDetail(productId, btn) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    const inWishlist = btn.getAttribute('data-in-wishlist') === 'true';
    const icon = document.getElementById('detail-wishlist-icon');

    try {
        const resp = await fetch(`/wishlist/${productId}`, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        });
        const data = await resp.json();
        if (resp.ok) {
            const nowIn = data.wishlisted ?? !inWishlist;
            btn.setAttribute('data-in-wishlist', nowIn ? 'true' : 'false');
            if (nowIn) {
                btn.className = 'p-4 rounded-full border transition shrink-0 bg-rose-50 border-rose-300 text-rose-600';
            } else {
                btn.className = 'p-4 rounded-full border transition shrink-0 bg-white border-neutral-300 text-neutral-500 hover:border-rose-300 hover:text-rose-500';
            }
            if (icon) {
                icon.setAttribute('fill', nowIn ? 'currentColor' : 'none');
            }
            if (window.showToast) window.showToast(data.message || (nowIn ? 'Ditambahkan ke wishlist!' : 'Dihapus dari wishlist.'), 'success');
        }
    } catch (e) {
        if (window.showToast) window.showToast('Gagal memperbarui wishlist.', 'error');
    }
}
</script>
@endpush
