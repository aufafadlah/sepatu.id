@extends('layouts.main')

@section('title', 'Sepatu.id — SNEAKER & FOOTWEAR BRAND')

@section('content')

    <!-- Hero Showcase Section (Inspired by Sneaker Flare) -->
    <section class="relative bg-[#f5f5f5] text-neutral-900 py-12 lg:py-20 overflow-hidden border-b border-neutral-200/60">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">

                <!-- Left Content -->
                <div class="lg:col-span-6 space-y-4 z-10">
                    <span class="inline-block text-[11px] font-extrabold uppercase tracking-widest text-orange-500">
                        New Concept Collection 2026
                    </span>

                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black tracking-tighter uppercase leading-none text-neutral-900">
                        snea<span class="text-orange-500">.</span>ker
                    </h1>

                    <p class="text-xs sm:text-sm text-neutral-600 font-medium leading-relaxed max-w-md uppercase tracking-wider">
                        Koleksi Sepatu Sneaker &amp; Streetwear Lokal dengan siluet modern, bantalan empuk, dan kenyamanan aktivitas harian.
                    </p>

                    <!-- Search Input -->
                    <form action="{{ route('catalog.index') }}" method="GET" class="pt-2 max-w-md">
                        <div class="relative flex items-center">
                            <input type="text"
                                   name="search"
                                   value="{{ request('search') }}"
                                   placeholder="Cari nama sepatu, warna, atau seri..."
                                   class="w-full pl-4 pr-24 py-3 rounded-full bg-white text-neutral-900 placeholder-neutral-400 text-xs font-medium border border-neutral-300 focus:outline-none focus:ring-2 focus:ring-neutral-900 shadow-2xs">
                            <button type="submit"
                                    class="absolute right-1 px-5 py-2 rounded-full bg-neutral-900 hover:bg-orange-500 text-white font-extrabold text-[11px] uppercase tracking-wider transition">
                                Cari
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Right Hero Image Showcase -->
                <div class="lg:col-span-6 relative flex items-center justify-center">
                    <!-- Giant Watermark Background Text -->
                    <span class="absolute inset-0 flex items-center justify-center font-black text-7xl sm:text-8xl lg:text-9xl text-neutral-300/40 uppercase tracking-tighter pointer-events-none select-none">
                        SNEAKER
                    </span>

                    <div class="relative z-10 h-72 sm:h-80 lg:h-96 flex items-center justify-center p-4">
                        <img src="https://images.unsplash.com/photo-1552346154-21d32810aba3?auto=format&fit=crop&w=900&q=80"
                             alt="Sneaker Showcase"
                             class="max-h-full object-contain filter drop-shadow-2xl hover:scale-105 transition duration-500 cursor-pointer">
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Featured Products Section with Watermark Header -->
    <section id="katalog" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 relative">

        <!-- Watermark Header Component -->
        <div class="relative text-center mb-12 select-none overflow-hidden py-4">
            <span class="absolute left-1/2 -translate-x-1/2 top-1/2 -translate-y-1/2 font-black text-6xl sm:text-7xl lg:text-9xl text-neutral-200/50 uppercase tracking-widest whitespace-nowrap pointer-events-none">
                SNEAKER
            </span>
            <div class="relative z-10">
                <h2 class="text-2xl sm:text-3xl font-black text-neutral-900 uppercase tracking-tight">
                    featured <span class="text-xs font-semibold block text-neutral-500 uppercase tracking-widest mt-1">PRODUCTS</span>
                </h2>
            </div>
        </div>

        <!-- Filter Controls (Category & Size Pills) -->
        <div class="space-y-4 mb-10">

            <!-- Category Pills -->
            <div id="kategori" class="flex items-center justify-center flex-wrap gap-2">
                <a href="{{ route('catalog.index') }}"
                   class="px-5 py-2 rounded-full text-xs font-extrabold uppercase tracking-wider transition {{ !request('category') ? 'bg-neutral-900 text-white shadow-xs' : 'bg-neutral-100 text-neutral-700 hover:bg-neutral-200 border border-neutral-200' }}">
                    Semua Produk
                </a>

                @foreach($categories as $cat)
                    <a href="{{ route('catalog.index', array_merge(request()->query(), ['category' => $cat->id])) }}"
                       class="px-5 py-2 rounded-full text-xs font-extrabold uppercase tracking-wider transition flex items-center gap-1.5 {{ request('category') == $cat->id ? 'bg-neutral-900 text-white shadow-xs' : 'bg-neutral-100 text-neutral-700 hover:bg-neutral-200 border border-neutral-200' }}">
                        <span>{{ $cat->name }}</span>
                        <span class="text-[10px] opacity-75">({{ $cat->products_count }})</span>
                    </a>
                @endforeach
            </div>

            <!-- Size Filter Pills & Sorting -->
            <div class="bg-[#f8f8f8] p-4 rounded-2xl border border-neutral-200 flex flex-wrap items-center justify-between gap-4 text-xs">
                <div class="flex flex-wrap items-center gap-1.5">
                    <span class="font-bold text-neutral-700 uppercase tracking-wider mr-1 text-[11px]">Ukuran:</span>
                    <a href="{{ route('catalog.index', request()->except('size')) }}"
                       class="px-3 py-1 rounded-full text-xs font-bold transition {{ !request('size') ? 'bg-neutral-900 text-white' : 'bg-white border border-neutral-300 text-neutral-700 hover:bg-neutral-100' }}">
                        Semua
                    </a>
                    @foreach($availableSizes as $sz)
                        <a href="{{ route('catalog.index', array_merge(request()->query(), ['size' => $sz])) }}"
                           class="px-3 py-1 rounded-full text-xs font-bold transition {{ request('size') == $sz ? 'bg-neutral-900 text-white' : 'bg-white border border-neutral-300 text-neutral-700 hover:bg-neutral-100' }}">
                            {{ $sz }}
                        </a>
                    @endforeach
                </div>

                <div class="flex items-center gap-3">
                    <form action="{{ route('catalog.index') }}" method="GET" class="flex items-center gap-2">
                        @if(request('search'))
                            <input type="hidden" name="search" value="{{ request('search') }}">
                        @endif
                        @if(request('category'))
                            <input type="hidden" name="category" value="{{ request('category') }}">
                        @endif
                        @if(request('size'))
                            <input type="hidden" name="size" value="{{ request('size') }}">
                        @endif

                        <select name="sort" onchange="this.form.submit()" class="text-xs font-bold uppercase tracking-wider border-neutral-300 rounded-full bg-white py-1.5 px-4 focus:ring-neutral-900">
                            <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Terbaru</option>
                            <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Harga: Terendah</option>
                            <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Harga: Tertinggi</option>
                            <option value="rating_desc" {{ request('sort') == 'rating_desc' ? 'selected' : '' }}>Rating Tertinggi</option>
                        </select>
                    </form>

                    @if(request()->hasAny(['search', 'category', 'size', 'sort']))
                        <a href="{{ route('catalog.index') }}" class="text-xs font-bold text-rose-600 hover:text-rose-700 uppercase tracking-wider">
                            Reset
                        </a>
                    @endif
                </div>
            </div>

        </div>

        <!-- Product Grid (Exact Sneaker Flare Style) -->
        @if($products->isEmpty())
            <div class="bg-[#f8f8f8] rounded-2xl p-12 text-center max-w-md mx-auto my-8 border border-neutral-200">
                <h3 class="text-base font-bold text-neutral-900 uppercase tracking-wider">Produk Tidak Ditemukan</h3>
                <p class="text-xs text-neutral-500 mt-1">Tidak ada produk sepatu yang sesuai dengan kriteria pencarian Anda.</p>
                <a href="{{ route('catalog.index') }}" class="inline-block mt-4 px-6 py-2.5 rounded-full bg-neutral-900 text-white text-xs font-bold uppercase tracking-wider hover:bg-orange-500 transition">
                    Lihat Semua Produk
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
                @foreach($products as $product)
                    <div class="flex flex-col text-center group">

                        <!-- Image Card Backdrop (Clean Light Grey #f8f8f8) -->
                        <div class="relative bg-[#f8f8f8] rounded-xl overflow-hidden aspect-square flex items-center justify-center p-6 mb-4 border border-neutral-200/60 group-hover:border-neutral-400 transition duration-300">
                            
                            <!-- Small Sale Badge (Italic Serif Style from Sneaker Flare) -->
                            <div class="absolute top-3 left-3 z-10">
                                @if($product->isOutOfStock())
                                    <span class="text-[11px] italic font-serif text-neutral-900 font-bold bg-white/90 px-2 py-0.5 rounded shadow-2xs">
                                        habis!
                                    </span>
                                @elseif($product->isNew())
                                    <span class="text-[11px] italic font-serif text-orange-600 font-bold bg-white/90 px-2 py-0.5 rounded shadow-2xs">
                                        sale!
                                    </span>
                                @endif
                            </div>

                            <!-- Wishlist Toggle -->
                            @auth
                                <form action="{{ route('wishlist.toggle', $product) }}" method="POST" class="absolute top-3 right-3 z-10">
                                    @csrf
                                    @php
                                        $inWishlist = auth()->user()->wishlists()->where('product_id', $product->id)->exists();
                                    @endphp
                                    <button type="submit" class="w-7 h-7 rounded-full bg-white/90 hover:bg-white flex items-center justify-center shadow-2xs transition active:scale-95">
                                        <svg class="w-3.5 h-3.5 {{ $inWishlist ? 'text-rose-500 fill-rose-500' : 'text-neutral-400 hover:text-rose-500' }}" viewBox="0 0 24 24" fill="{{ $inWishlist ? 'currentColor' : 'none' }}" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                        </svg>
                                    </button>
                                </form>
                            @endauth

                            <!-- Product Image -->
                            <a href="{{ route('catalog.show', $product->slug) }}" class="w-full h-full flex items-center justify-center">
                                @if($product->image)
                                    <img src="{{ Str::startsWith($product->image, ['http://', 'https://']) ? $product->image : asset('storage/' . $product->image) }}"
                                         alt="{{ $product->name }}"
                                         class="max-h-full max-w-full object-contain group-hover:scale-105 transition duration-500">
                                @else
                                    <img src="https://images.unsplash.com/photo-1542291026-7eec264c27ff?auto=format&fit=crop&w=600&q=80"
                                         alt="{{ $product->name }}"
                                         class="max-h-full max-w-full object-contain group-hover:scale-105 transition duration-500">
                                @endif
                            </a>
                        </div>

                        <!-- Card Info -->
                        <div class="space-y-1.5 px-2">
                            <h3 class="font-bold text-neutral-900 text-xs sm:text-sm uppercase tracking-tight hover:text-orange-500 transition line-clamp-1">
                                <a href="{{ route('catalog.show', $product->slug) }}">
                                    {{ $product->name }}
                                </a>
                            </h3>

                            <!-- Rating Stars (★★★★☆) -->
                            <div class="text-[11px] text-amber-500 font-bold tracking-widest">
                                ★★★★☆ <span class="text-neutral-400 font-normal text-[10px]">({{ number_format($product->averageRating() ?? 5.0, 1) }})</span>
                            </div>

                            <!-- Price -->
                            <div class="pt-1">
                                <p class="text-xs font-extrabold text-neutral-900">
                                    Rp {{ number_format($product->price, 0, ',', '.') }}
                                </p>
                            </div>

                            <!-- Size Indicators -->
                            <div class="pt-1 flex flex-wrap justify-center gap-1">
                                @foreach($product->variants->where('stock', '>', 0)->take(5) as $variant)
                                    <span class="px-1.5 py-0.5 rounded bg-neutral-100 text-neutral-600 font-bold text-[9px] border border-neutral-200">
                                        {{ $variant->size }}
                                    </span>
                                @endforeach
                            </div>
                        </div>

                    </div>
                @endforeach
            </div>

            <!-- Outlined Pill Button (Inspired by Sneaker Flare "VIEW ALL PRODUCTS") -->
            <div class="mt-14 text-center">
                {{ $products->links() }}
            </div>
        @endif

    </section>

    <!-- Best Seller Watermark Section -->
    <section class="bg-[#f9f9f9] border-t border-neutral-200 py-16 relative overflow-hidden">
        <!-- Giant Background Watermark -->
        <span class="absolute left-1/2 -translate-x-1/2 top-1/2 -translate-y-1/2 font-black text-6xl sm:text-7xl lg:text-9xl text-neutral-200/60 uppercase tracking-widest whitespace-nowrap pointer-events-none select-none">
            PRODUCT
        </span>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
            <h2 class="text-2xl sm:text-3xl font-black text-neutral-900 uppercase tracking-tight mb-8">
                best seller <span class="text-xs font-semibold block text-neutral-500 uppercase tracking-widest mt-1">PRODUCTS</span>
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 text-xs text-neutral-600 font-medium">
                <div class="p-6 bg-white rounded-2xl border border-neutral-200 shadow-2xs space-y-2">
                    <h4 class="font-black text-neutral-900 uppercase tracking-wider text-xs">100% Original Quality</h4>
                    <p class="text-neutral-500 leading-relaxed">Pilihan material sepatu kelas atas dengan durabilitas tinggi.</p>
                </div>
                <div class="p-6 bg-white rounded-2xl border border-neutral-200 shadow-2xs space-y-2">
                    <h4 class="font-black text-neutral-900 uppercase tracking-wider text-xs">Garansi Tukar Ukuran</h4>
                    <p class="text-neutral-500 leading-relaxed">Penukaran varian ukuran tanpa ribet hingga 7 hari.</p>
                </div>
                <div class="p-6 bg-white rounded-2xl border border-neutral-200 shadow-2xs space-y-2">
                    <h4 class="font-black text-neutral-900 uppercase tracking-wider text-xs">Ulasan Terverifikasi</h4>
                    <p class="text-neutral-500 leading-relaxed">Setiap ulasan berasal dari pembeli nyata yang menyelesaikan pesanan.</p>
                </div>
                <div class="p-6 bg-white rounded-2xl border border-neutral-200 shadow-2xs space-y-2">
                    <h4 class="font-black text-neutral-900 uppercase tracking-wider text-xs">Pengiriman Cepat</h4>
                    <p class="text-neutral-500 leading-relaxed">Pengiriman aman &amp; efisien ke seluruh kota di Indonesia.</p>
                </div>
            </div>
        </div>
    </section>

@endsection
