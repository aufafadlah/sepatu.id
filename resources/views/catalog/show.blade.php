@extends('layouts.main')

@section('title', $product->name . ' — Sepatu.id')

@section('content')

    <!-- Breadcrumb -->
    <div class="border-b border-neutral-200 py-3 text-xs text-neutral-500 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center gap-2 font-medium">
            <a href="{{ route('catalog.index') }}" class="hover:text-neutral-900 transition">Katalog</a>
            <span>/</span>
            <span>{{ $product->category->name ?? 'Sepatu' }}</span>
            <span>/</span>
            <span class="text-neutral-900 font-bold truncate uppercase">{{ $product->name }}</span>
        </div>
    </div>

    <!-- Product Detail Section -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 bg-white p-6 sm:p-10 rounded-2xl border border-neutral-200 shadow-2xs">

            <!-- Gallery (Left) -->
            <div class="lg:col-span-6">
                <div class="bg-[#f8f8f8] rounded-xl overflow-hidden aspect-square flex items-center justify-center border border-neutral-200 p-6 relative">
                    @if($product->image)
                        <img src="{{ Str::startsWith($product->image, ['http://', 'https://']) ? $product->image : asset('storage/' . $product->image) }}"
                             alt="{{ $product->name }}"
                             class="max-h-full max-w-full object-contain filter drop-shadow-xl">
                    @else
                        <img src="https://images.unsplash.com/photo-1542291026-7eec264c27ff?auto=format&fit=crop&w=800&q=80"
                             alt="{{ $product->name }}"
                             class="max-h-full max-w-full object-contain filter drop-shadow-xl">
                    @endif

                    @if($product->isOutOfStock())
                        <div class="absolute inset-0 bg-neutral-950/60 backdrop-blur-xs flex items-center justify-center">
                            <span class="px-4 py-2 rounded-full bg-neutral-900 text-white font-black text-xs uppercase tracking-widest">
                                Stok Habis
                            </span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Product Specs (Right) -->
            <div class="lg:col-span-6 space-y-6">
                <div>
                    <div class="flex items-center justify-between text-xs mb-2">
                        <span class="font-extrabold text-orange-500 uppercase tracking-widest text-[11px]">{{ $product->category->name ?? 'Sepatu' }}</span>
                        <span class="font-bold text-amber-500 text-sm">★★★★☆ <span class="text-neutral-400 font-normal text-xs">({{ number_format($product->averageRating() ?? 5.0, 1) }} / {{ $product->reviews->count() }} ulasan)</span></span>
                    </div>

                    <h1 class="text-2xl sm:text-4xl font-black text-neutral-900 uppercase tracking-tight leading-tight">
                        {{ $product->name }}
                    </h1>

                    <div class="flex items-baseline gap-3 mt-3">
                        <span class="text-3xl font-black text-neutral-900">
                            Rp {{ number_format($product->price, 0, ',', '.') }}
                        </span>
                        <span class="text-xs font-bold text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-full border border-emerald-200 uppercase">
                            Stok Tersedia: {{ $product->totalStock() }} pasang
                        </span>
                    </div>
                </div>

                <div class="border-t border-neutral-100 pt-4">
                    <h4 class="text-xs font-extrabold text-neutral-900 uppercase tracking-widest mb-2">Deskripsi Produk</h4>
                    <p class="text-xs sm:text-sm text-neutral-600 leading-relaxed font-medium">
                        {{ $product->description }}
                    </p>
                </div>

                <!-- Form Size & Cart -->
                <form action="{{ route('cart.store') }}" method="POST" x-data="{ selectedVariant: null, qty: 1 }" class="border-t border-neutral-100 pt-4 space-y-4">
                    @csrf

                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label class="text-xs font-extrabold text-neutral-900 uppercase tracking-widest">Pilih Ukuran (EUR):</label>
                            <span class="text-[11px] text-neutral-400 font-semibold uppercase">Varian Resmi</span>
                        </div>

                        <div class="grid grid-cols-4 sm:grid-cols-6 gap-2">
                            @forelse($product->variants as $variant)
                                <button type="button"
                                        @click="selectedVariant = {{ $variant->id }}"
                                        :class="selectedVariant === {{ $variant->id }} ? 'bg-neutral-900 text-white border-neutral-900' : 'bg-white text-neutral-800 border-neutral-200 hover:border-neutral-900'"
                                        class="py-2.5 rounded-xl border text-xs font-extrabold transition text-center {{ $variant->stock <= 0 ? 'opacity-30 cursor-not-allowed bg-neutral-100' : '' }}"
                                        {{ $variant->stock <= 0 ? 'disabled' : '' }}>
                                    {{ $variant->size }}
                                </button>
                            @empty
                                <p class="text-xs text-rose-500 col-span-4">Belum ada varian ukuran.</p>
                            @endforelse
                        </div>

                        <input type="hidden" name="product_variant_id" :value="selectedVariant" required>
                    </div>

                    <div class="flex items-center gap-3">
                        <label class="text-xs font-extrabold text-neutral-900 uppercase tracking-widest">Jumlah:</label>
                        <div class="flex items-center border border-neutral-300 rounded-full bg-[#f8f8f8]">
                            <button type="button" @click="if(qty > 1) qty--" class="px-3.5 py-1 text-neutral-800 font-bold hover:bg-neutral-200 rounded-l-full transition">-</button>
                            <input type="number" name="qty" x-model="qty" min="1" class="w-12 text-center text-xs font-bold border-none bg-transparent focus:ring-0">
                            <button type="button" @click="qty++" class="px-3.5 py-1 text-neutral-800 font-bold hover:bg-neutral-200 rounded-r-full transition">+</button>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 pt-2">
                        <button type="submit"
                                :disabled="!selectedVariant || {{ $product->isOutOfStock() ? 'true' : 'false' }}"
                                :class="selectedVariant ? 'bg-neutral-900 hover:bg-orange-500 text-white shadow-md' : 'bg-neutral-200 text-neutral-400 cursor-not-allowed'"
                                class="flex-1 py-4 rounded-full font-black text-xs uppercase tracking-widest transition text-center">
                            <span x-text="selectedVariant ? 'Tambah ke Keranjang' : 'Pilih Ukuran Dulu'"></span>
                        </button>
                    </div>
                </form>

                <div class="border-t border-neutral-100 pt-4 grid grid-cols-2 gap-2 text-xs text-neutral-500 font-semibold uppercase">
                    <p>✓ 100% Produk Original</p>
                    <p>✓ Garansi Tukar Size 7 Hari</p>
                    <p>✓ Gratis Ongkir Pembelian > 500k</p>
                    <p>✓ Ulasan Terverifikasi Pembeli</p>
                </div>
            </div>

        </div>

        <!-- Reviews -->
        <div class="mt-12 bg-white rounded-2xl p-6 sm:p-10 border border-neutral-200 shadow-2xs space-y-6">
            <div class="flex items-center justify-between border-b border-neutral-100 pb-4">
                <div>
                    <h3 class="text-lg font-black text-neutral-900 uppercase tracking-tight">Ulasan Pembeli</h3>
                    <p class="text-xs text-neutral-500">Ulasan asli dari pembeli terverifikasi</p>
                </div>
                <span class="text-sm font-bold text-amber-500">★★★★☆ {{ number_format($product->averageRating() ?? 5.0, 1) }} / 5.0</span>
            </div>

            @auth
                <div class="bg-[#f8f8f8] p-4 rounded-xl border border-neutral-200 space-y-3">
                    <h4 class="text-xs font-bold text-neutral-900 uppercase tracking-wider">Tulis Ulasan</h4>
                    <form action="{{ route('review.store', $product) }}" method="POST" class="space-y-3">
                        @csrf
                        <div>
                            <select name="rating" required class="text-xs border-neutral-300 rounded-lg bg-white font-bold uppercase">
                                <option value="5">★ 5 - Sangat Puas</option>
                                <option value="4">★ 4 - Bagus</option>
                                <option value="3">★ 3 - Cukup</option>
                                <option value="2">★ 2 - Kurang</option>
                                <option value="1">★ 1 - Kecewa</option>
                            </select>
                        </div>
                        <textarea name="comment" rows="3" required placeholder="Tuliskan ulasan Anda mengenai produk ini..." class="w-full text-xs rounded-xl border-neutral-300 p-3 focus:ring-neutral-900"></textarea>
                        <button type="submit" class="px-5 py-2.5 bg-neutral-900 hover:bg-orange-500 text-white text-xs font-bold uppercase tracking-wider rounded-full transition">
                            Kirim Ulasan
                        </button>
                    </form>
                </div>
            @endauth

            <div class="space-y-3">
                @forelse($product->reviews as $review)
                    <div class="p-4 rounded-xl border border-neutral-200 bg-[#f9f9f9] space-y-1">
                        <div class="flex items-center justify-between text-xs">
                            <span class="font-extrabold text-neutral-900 uppercase">{{ $review->user->name ?? 'Pelanggan' }}</span>
                            <span class="font-bold text-amber-500">★★★★☆ {{ $review->rating }}.0</span>
                        </div>
                        <p class="text-xs text-neutral-600 font-medium">"{{ $review->comment }}"</p>
                        <p class="text-[10px] text-neutral-400 font-semibold">{{ $review->created_at->diffForHumans() }}</p>
                    </div>
                @empty
                    <p class="text-xs text-neutral-400 text-center py-4 uppercase font-semibold">Belum ada ulasan untuk produk ini.</p>
                @endforelse
            </div>
        </div>

    </section>

@endsection
