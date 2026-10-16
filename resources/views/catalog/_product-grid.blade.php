{{-- Partial: Product Grid (untuk AJAX reload) --}}
@forelse($products as $product)
    <div class="flex flex-col text-center group">

        <!-- Image Card Backdrop -->
        <div class="relative bg-[#f8f8f8] rounded-xl overflow-hidden aspect-square flex items-center justify-center p-6 mb-4 border border-neutral-200/60 group-hover:border-neutral-400 transition duration-300">
            
            <!-- Sale / Discount / Out of Stock Badge -->
            <div class="absolute top-3 left-3 z-10 flex flex-col gap-1">
                @if($product->isOutOfStock())
                    <span class="text-[11px] italic font-serif text-neutral-900 font-bold bg-white/90 px-2 py-0.5 rounded shadow-2xs">
                        habis!
                    </span>
                @elseif($product->hasDiscount())
                    <span class="text-[10px] font-black text-white bg-orange-500 px-2 py-0.5 rounded shadow-2xs uppercase tracking-wide">
                        -{{ $product->discountPercent() }}%
                    </span>
                @elseif($product->isNew())
                    <span class="text-[11px] italic font-serif text-orange-600 font-bold bg-white/90 px-2 py-0.5 rounded shadow-2xs">
                        baru!
                    </span>
                @endif
            </div>

            <!-- Wishlist Toggle (AJAX) -->
            @auth
                @php
                    $inWishlist = auth()->user()->wishlists()->where('product_id', $product->id)->exists();
                @endphp
                <button type="button"
                        onclick="toggleWishlist({{ $product->id }}, this)"
                        class="wishlist-btn absolute top-3 right-3 z-10 w-7 h-7 rounded-full bg-white/90 hover:bg-white flex items-center justify-center shadow-2xs transition active:scale-95"
                        data-in-wishlist="{{ $inWishlist ? 'true' : 'false' }}">
                    <svg class="w-3.5 h-3.5 {{ $inWishlist ? 'text-rose-500 fill-rose-500' : 'text-neutral-400 hover:text-rose-500' }}" viewBox="0 0 24 24" fill="{{ $inWishlist ? 'currentColor' : 'none' }}" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                </button>
            @else
                <a href="{{ route('login') }}"
                   title="Login untuk tambah wishlist"
                   class="wishlist-btn absolute top-3 right-3 z-10 w-7 h-7 rounded-full bg-white/90 hover:bg-white flex items-center justify-center shadow-2xs transition active:scale-95">
                    <svg class="w-3.5 h-3.5 text-neutral-300 hover:text-rose-400" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                </a>
            @endauth

            <!-- Product Image -->
            <a href="{{ route('catalog.show', $product->slug) }}" class="w-full h-full flex items-center justify-center">
                @if($product->image)
                    <img src="{{ Str::startsWith($product->image, ['http://', 'https://']) ? $product->image : asset('storage/' . $product->image) }}"
                         alt="{{ $product->name }}"
                         class="max-h-full max-w-full object-contain group-hover:scale-105 transition duration-500">
                @else
                    <div class="w-full h-full flex items-center justify-center bg-neutral-100">
                        <svg class="w-16 h-16 text-neutral-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
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

            <!-- Dynamic Rating Stars -->
            @php
                $avgRating = $product->averageRating() ?? 0;
                $reviewCount = $product->reviews->count();
            @endphp
            <div class="text-[11px] text-amber-500 font-bold tracking-widest">
                @for($i = 1; $i <= 5; $i++)
                    @if($i <= floor($avgRating))
                        ★
                    @elseif($i - $avgRating < 1 && $i - $avgRating > 0)
                        ★
                    @else
                        <span class="text-neutral-300">★</span>
                    @endif
                @endfor
                <span class="text-neutral-400 font-normal text-[10px]">({{ $reviewCount > 0 ? number_format($avgRating, 1) : 'Belum ada' }})</span>
            </div>

            <!-- Price with Discount -->
            <div class="pt-1">
                @if($product->hasDiscount())
                    <p class="text-[10px] text-neutral-400 line-through font-semibold leading-none">
                        Rp {{ number_format($product->price, 0, ',', '.') }}
                    </p>
                    <p class="text-xs font-extrabold text-orange-600 leading-tight">
                        Rp {{ number_format($product->finalPrice(), 0, ',', '.') }}
                    </p>
                @else
                    <p class="text-xs font-extrabold text-neutral-900">
                        Rp {{ number_format($product->price, 0, ',', '.') }}
                    </p>
                @endif
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
@empty
    <div class="col-span-full">
        <div class="bg-[#f8f8f8] rounded-2xl p-12 text-center max-w-md mx-auto my-8 border border-neutral-200">
            <h3 class="text-base font-bold text-neutral-900 uppercase tracking-wider">Produk Tidak Ditemukan</h3>
            <p class="text-xs text-neutral-500 mt-1">Tidak ada produk sepatu yang sesuai dengan kriteria pencarian Anda.</p>
            <a href="{{ route('catalog.index') }}" class="inline-block mt-4 px-6 py-2.5 rounded-full bg-neutral-900 text-white text-xs font-bold uppercase tracking-wider hover:bg-orange-500 transition">
                Lihat Semua Produk
            </a>
        </div>
    </div>
@endforelse
