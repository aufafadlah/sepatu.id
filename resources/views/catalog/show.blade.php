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
                        <div class="w-full h-full flex items-center justify-center bg-neutral-100">
                            <svg class="w-24 h-24 text-neutral-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
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
                        
                        <!-- Dynamic Rating Stars -->
                        @php
                            $avgRating = $product->averageRating() ?? 0;
                            $reviewCount = $product->reviews->count();
                        @endphp
                        <span class="font-bold text-amber-500 text-sm">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= floor($avgRating))
                                    ★
                                @else
                                    <span class="text-neutral-300">★</span>
                                @endif
                            @endfor
                            <span class="text-neutral-400 font-normal text-xs">({{ $reviewCount > 0 ? number_format($avgRating, 1) : '0' }} / {{ $reviewCount }} ulasan)</span>
                        </span>
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

                <div class="border-t border-neutral-100 pt-4 grid grid-cols-2 gap-3 text-xs text-neutral-600 font-semibold uppercase">
                    <p class="flex items-center gap-1.5"><svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" /></svg> 100% Produk Original</p>
                    <p class="flex items-center gap-1.5"><svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" /></svg> Garansi Tukar Size 7 Hari</p>
                    <p class="flex items-center gap-1.5"><svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" /></svg> Gratis Ongkir > 500k</p>
                    <p class="flex items-center gap-1.5"><svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" /></svg> Ulasan Terverifikasi</p>
                </div>
            </div>

        </div>

        <!-- Reviews Section -->
        <div class="mt-12 bg-white rounded-2xl p-6 sm:p-10 border border-neutral-200 shadow-2xs space-y-6">
            <div class="flex items-center justify-between border-b border-neutral-100 pb-4">
                <div>
                    <h3 class="text-lg font-black text-neutral-900 uppercase tracking-tight">Ulasan Pembeli</h3>
                    <p class="text-xs text-neutral-500">Ulasan asli dari pembeli terverifikasi</p>
                </div>
                <span class="text-sm font-bold text-amber-500">
                    @for($i = 1; $i <= 5; $i++)
                        @if($i <= floor($avgRating)) ★ @else <span class="text-neutral-300">★</span> @endif
                    @endfor
                    {{ $reviewCount > 0 ? number_format($avgRating, 1) : '0' }} / 5.0
                </span>
            </div>

            <!-- Interactive Review Form / Notice -->
            @auth
                @if($canReview)
                    <div class="bg-[#f8f8f8] p-6 rounded-2xl border border-neutral-200 space-y-4" x-data="{ rating: {{ $existingReview ? $existingReview->rating : 5 }} }">
                        <h4 class="text-xs font-black text-neutral-900 uppercase tracking-wider">
                            {{ $existingReview ? 'Perbarui Ulasan Anda' : 'Tulis Ulasan & Upload Foto/Video' }}
                        </h4>
                        <form action="{{ route('review.store', $product) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                            @csrf
                            
                            <!-- Interactive Star Picker -->
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-neutral-700 mb-1">Pilih Rating Bintang:</label>
                                <div class="flex items-center gap-1 text-2xl text-amber-400 cursor-pointer select-none">
                                    <template x-for="i in 5">
                                        <span @click="rating = i" :class="i <= rating ? 'text-amber-500' : 'text-neutral-300'" class="transition hover:scale-110">★</span>
                                    </template>
                                </div>
                                <input type="hidden" name="rating" :value="rating" required>
                            </div>

                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-neutral-700 mb-1">Komentar &amp; Ulasan Pengalaman:</label>
                                <textarea name="comment" rows="3" required placeholder="Tuliskan ulasan jujur Anda mengenai kenyamanan, kualitas material, dan ukuran sepatu ini..." class="w-full text-xs rounded-xl border-neutral-300 p-3 focus:ring-neutral-900 font-medium bg-white">{{ $existingReview ? $existingReview->comment : '' }}</textarea>
                            </div>

                            <!-- Photo & Video Upload Grid -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-1">
                                <div class="space-y-1.5">
                                    <label class="block text-[11px] font-bold uppercase tracking-wider text-neutral-700">
                                        Foto Produk (Opsional, JPG/PNG, Max 5MB):
                                    </label>
                                    <input type="file" name="image" accept="image/*" class="w-full text-xs text-neutral-500 file:mr-3 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-neutral-900 file:text-white hover:file:bg-orange-500 transition cursor-pointer">
                                    @if($existingReview && $existingReview->image)
                                        <p class="text-[10px] text-emerald-600 font-semibold">Foto saat ini terpasang</p>
                                    @endif
                                </div>

                                <div class="space-y-1.5">
                                    <label class="block text-[11px] font-bold uppercase tracking-wider text-neutral-700">
                                        Video Unboxing/Review (Opsional, MP4/WebM, Max 20MB):
                                    </label>
                                    <input type="file" name="video" accept="video/*" class="w-full text-xs text-neutral-500 file:mr-3 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-neutral-900 file:text-white hover:file:bg-orange-500 transition cursor-pointer">
                                    @if($existingReview && $existingReview->video)
                                        <p class="text-[10px] text-emerald-600 font-semibold">Video saat ini terpasang</p>
                                    @endif
                                </div>
                            </div>

                            <div class="pt-2">
                                <button type="submit" class="px-7 py-3 bg-neutral-900 hover:bg-orange-500 text-white text-xs font-extrabold uppercase tracking-widest rounded-full transition shadow-xs">
                                    {{ $existingReview ? 'Simpan Perubahan Ulasan' : 'Kirim Ulasan & Media' }}
                                </button>
                            </div>
                        </form>
                    </div>
                @else
                    <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 text-amber-900 text-xs font-medium flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-amber-700 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                            <span>Hanya pelanggan yang telah membeli produk ini dan pesanan berstatus selesai yang dapat memberikan ulasan &amp; upload foto/video.</span>
                        </div>
                        <a href="{{ route('orders.index') }}" class="px-4 py-2 rounded-full bg-amber-900 hover:bg-amber-950 text-white font-bold text-[11px] uppercase tracking-wider shrink-0 transition">
                            Cek Pesanan
                        </a>
                    </div>
                @endif
            @else
                <div class="bg-neutral-50 border border-neutral-200 rounded-xl p-4 text-neutral-700 text-xs font-medium text-center">
                    Silakan <a href="{{ route('login') }}" class="font-bold underline text-neutral-900">Masuk / Login</a> terlebih dahulu untuk memberikan ulasan.
                </div>
            @endauth

            <!-- Review List with Photos & Videos -->
            <div class="space-y-4" x-data="{ mediaModal: false, mediaType: '', mediaSrc: '' }">
                @forelse($product->reviews as $review)
                    <div class="p-5 rounded-2xl border border-neutral-200 bg-[#f9f9f9] space-y-3">
                        <div class="flex items-center justify-between text-xs">
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 rounded-full bg-neutral-900 text-white font-bold text-[11px] flex items-center justify-center">
                                    {{ strtoupper(substr($review->user->name ?? 'P', 0, 1)) }}
                                </div>
                                <div>
                                    <span class="font-extrabold text-neutral-900 uppercase block">{{ $review->user->name ?? 'Pelanggan' }}</span>
                                    <span class="text-[10px] text-neutral-400 font-semibold">{{ $review->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                            <span class="font-bold text-amber-500 text-sm">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $review->rating) ★ @else <span class="text-neutral-300">★</span> @endif
                                @endfor
                            </span>
                        </div>

                        <p class="text-xs text-neutral-700 font-medium leading-relaxed">"{{ $review->comment }}"</p>

                        <!-- Attached Media: Photo & Video -->
                        @if($review->image || $review->video)
                            <div class="flex flex-wrap items-center gap-3 pt-1">
                                @if($review->image)
                                    <div class="relative group cursor-pointer w-20 h-20 rounded-xl overflow-hidden border border-neutral-300 bg-white"
                                         @click="mediaModal = true; mediaType = 'image'; mediaSrc = '{{ asset('storage/' . $review->image) }}'">
                                        <img src="{{ asset('storage/' . $review->image) }}" alt="Foto Ulasan" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                        <div class="absolute inset-0 bg-neutral-950/30 opacity-0 group-hover:opacity-100 transition flex items-center justify-center text-white text-[10px] font-bold">
                                            🔍 Lihat
                                        </div>
                                    </div>
                                @endif

                                @if($review->video)
                                    <div class="relative group cursor-pointer w-20 h-20 rounded-xl overflow-hidden border border-neutral-300 bg-neutral-900 flex items-center justify-center"
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
                    <p class="text-xs text-neutral-400 text-center py-6 uppercase font-semibold">Belum ada ulasan untuk produk ini. Jadilah yang pertama memberikan ulasan!</p>
                @endforelse

                <!-- Media Preview Lightbox Modal -->
                <div x-show="mediaModal" x-cloak class="fixed inset-0 z-50 bg-neutral-950/80 backdrop-blur-xs flex items-center justify-center p-4" @click.self="mediaModal = false">
                    <div class="relative max-w-2xl max-h-[85vh] bg-white rounded-2xl overflow-hidden p-3 shadow-2xl space-y-3">
                        <div class="flex items-center justify-between border-b border-neutral-200 pb-2">
                            <span class="text-xs font-bold uppercase tracking-wider text-neutral-900">Media Ulasan Pembeli</span>
                            <button @click="mediaModal = false" class="p-1 rounded-lg text-neutral-400 hover:text-neutral-900 hover:bg-neutral-100 transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                            </button>
                        </div>
                        <div class="flex items-center justify-center max-h-[70vh] overflow-auto">
                            <template x-if="mediaType === 'image'">
                                <img :src="mediaSrc" alt="Foto Ulasan" class="max-h-[70vh] max-w-full rounded-xl object-contain">
                            </template>
                            <template x-if="mediaType === 'video'">
                                <video :src="mediaSrc" controls autoplay class="max-h-[70vh] max-w-full rounded-xl"></video>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>

@endsection
