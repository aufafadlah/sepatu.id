@extends('layouts.main')

@section('title', 'Sepatu.id — SNEAKER & FOOTWEAR BRAND')

@section('content')

    <!-- Hero Showcase Section -->
    <section class="relative bg-[#f5f5f5] text-neutral-900 py-12 lg:py-20 overflow-hidden border-b border-neutral-200/60">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">

                <!-- Left Content -->
                <div class="lg:col-span-6 space-y-4 z-10">
                    <span class="inline-block text-[11px] font-extrabold uppercase tracking-widest text-orange-500">
                        {{ \App\Models\SiteSetting::get('hero_badge', 'New Concept Collection 2026') }}
                    </span>

                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black tracking-tighter uppercase leading-none text-neutral-900">
                        @php
                            $title = \App\Models\SiteSetting::get('hero_title', 'sepatu.id');
                            if (str_contains(strtolower($title), '.id')) {
                                $parts = explode('.id', $title, 2);
                                echo e($parts[0]) . '<span class="text-orange-500">.id</span>' . e($parts[1]);
                            } else {
                                echo e($title);
                            }
                        @endphp
                    </h1>

                    <p class="text-xs sm:text-sm text-neutral-600 font-medium leading-relaxed max-w-md uppercase tracking-wider">
                        {{ \App\Models\SiteSetting::get('hero_subtitle', 'Koleksi Sepatu Sneaker & Streetwear Lokal dengan siluet modern, bantalan empuk, dan kenyamanan aktivitas harian.') }}
                    </p>

                    <!-- Search Input -->
                    <form action="{{ route('catalog.index') }}" method="GET" id="search-form" class="pt-2 max-w-md">
                        <div class="relative flex items-center">
                            <input type="text"
                                   name="search"
                                   id="search-input"
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

                <!-- Right Hero Showcase (Vector SVG illustration - fixed broken image) -->
                <div class="lg:col-span-6 relative flex items-center justify-center">
                    <!-- Giant Watermark Background Text -->
                    <span class="absolute inset-0 flex items-center justify-center font-black text-7xl sm:text-8xl lg:text-9xl text-neutral-300/40 uppercase tracking-tighter pointer-events-none select-none">
                        SNEAKER
                    </span>

                    <div class="relative z-10 h-72 sm:h-80 lg:h-96 flex items-center justify-center p-4">
                        <svg class="max-h-full max-w-full drop-shadow-2xl hover:scale-105 transition duration-500 cursor-pointer text-neutral-900" viewBox="0 0 512 512" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M480 352C480 369.7 465.7 384 448 384H64C28.7 384 0 355.3 0 320V240C0 231.2 7.2 224 16 224H48C56.8 224 64 216.8 64 208V176C64 140.7 92.7 112 128 112H200C222.1 112 242.7 123.4 254.4 142.1L288 196H384C437 196 480 239 480 292V352Z" fill="#18181b"/>
                            <path d="M384 196H288L254.4 142.1C242.7 123.4 222.1 112 200 112H128C92.7 112 64 140.7 64 176V208C64 216.8 56.8 224 48 224H16C7.2 224 0 231.2 0 240V320C0 355.3 28.7 384 64 384H448C465.7 384 480 369.7 480 352V292C480 239 437 196 384 196Z" stroke="#f97316" stroke-width="12" stroke-linejoin="round"/>
                            <circle cx="160" cy="272" r="20" fill="#f97316"/>
                            <circle cx="240" cy="272" r="20" fill="#f97316"/>
                            <circle cx="320" cy="272" r="20" fill="#f97316"/>
                            <path d="M64 384H448C465.7 384 480 369.7 480 352V336H32C32 362.5 46.3 384 64 384Z" fill="#f97316"/>
                        </svg>
                    </div> 
                </div>

            </div>
        </div>
    </section>

    <!-- Featured Products Section -->
    <section id="katalog" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 relative">

        <!-- Watermark Header -->
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

            <!-- Category Pills (AJAX Intercept) -->
            <div id="kategori" class="flex items-center justify-center flex-wrap gap-2">
                <a href="{{ route('catalog.index') }}"
                   data-category=""
                   class="category-pill px-5 py-2 rounded-full text-xs font-extrabold uppercase tracking-wider transition {{ !request('category') ? 'bg-neutral-900 text-white shadow-xs' : 'bg-neutral-100 text-neutral-700 hover:bg-neutral-200 border border-neutral-200' }}">
                    Semua Produk
                </a>

                @foreach($categories as $cat)
                    <a href="{{ route('catalog.index', array_merge(request()->query(), ['category' => $cat->id])) }}"
                       data-category="{{ $cat->id }}"
                       class="category-pill px-5 py-2 rounded-full text-xs font-extrabold uppercase tracking-wider transition flex items-center gap-1.5 {{ request('category') == $cat->id ? 'bg-neutral-900 text-white shadow-xs' : 'bg-neutral-100 text-neutral-700 hover:bg-neutral-200 border border-neutral-200' }}">
                        <span>{{ $cat->name }}</span>
                        <span class="text-[10px] opacity-75">({{ $cat->products_count }})</span>
                    </a>
                @endforeach
            </div>

            <!-- Size Filter Pills & Sorting -->
            <div class="bg-[#f8f8f8] p-4 rounded-2xl border border-neutral-200 flex flex-wrap items-center justify-between gap-4 text-xs">
                <div class="flex flex-wrap items-center gap-1.5" id="size-filters">
                    <span class="font-bold text-neutral-700 uppercase tracking-wider mr-1 text-[11px]">Ukuran:</span>
                    <a href="{{ route('catalog.index', request()->except('size')) }}"
                       data-size=""
                       class="size-pill px-3 py-1 rounded-full text-xs font-bold transition {{ !request('size') ? 'bg-neutral-900 text-white' : 'bg-white border border-neutral-300 text-neutral-700 hover:bg-neutral-100' }}">
                        Semua
                    </a>
                    @foreach($availableSizes as $sz)
                        <a href="{{ route('catalog.index', array_merge(request()->query(), ['size' => $sz])) }}"
                           data-size="{{ $sz }}"
                           class="size-pill px-3 py-1 rounded-full text-xs font-bold transition {{ request('size') == $sz ? 'bg-neutral-900 text-white' : 'bg-white border border-neutral-300 text-neutral-700 hover:bg-neutral-100' }}">
                            {{ $sz }}
                        </a>
                    @endforeach
                </div>

                <div class="flex items-center gap-3">
                    <form action="{{ route('catalog.index') }}" method="GET" id="sort-form" class="flex items-center gap-2">
                        <select name="sort" id="sort-select" class="text-xs font-bold uppercase tracking-wider border-neutral-300 rounded-full bg-white py-1.5 px-4 focus:ring-neutral-900">
                            <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Terbaru</option>
                            <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Harga: Terendah</option>
                            <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Harga: Tertinggi</option>
                            <option value="rating_desc" {{ request('sort') == 'rating_desc' ? 'selected' : '' }}>Rating Tertinggi</option>
                        </select>
                    </form>

                    <a href="{{ route('catalog.index') }}" id="reset-filter-btn" class="{{ request()->hasAny(['search', 'category', 'size', 'sort']) ? '' : 'hidden' }} text-xs font-bold text-rose-600 hover:text-rose-700 uppercase tracking-wider">
                        Reset
                    </a>
                </div>
            </div>

        </div>

        <!-- Product Grid Wrapper for AJAX -->
        <div class="relative">
            <!-- Loading Overlay -->
            <div id="grid-loading" class="hidden absolute inset-0 bg-white/70 backdrop-blur-xs z-20 flex items-center justify-center rounded-2xl">
                <div class="flex items-center gap-2 text-xs font-extrabold uppercase text-neutral-900">
                    <svg class="animate-spin h-5 w-5 text-orange-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Memuat Produk...
                </div>
            </div>

            <!-- Product Grid Container -->
            <div id="product-grid-container" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
                @include('catalog._product-grid', ['products' => $products])
            </div>
        </div>

        <!-- Pagination Container -->
        <div id="pagination-container" class="mt-14 text-center">
            {{ $products->links() }}
        </div>

    </section>

    <!-- Best Seller Watermark Section (CMS Editable) -->
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
                    <h4 class="font-black text-neutral-900 uppercase tracking-wider text-xs">{{ \App\Models\SiteSetting::get('feature_1_title', '100% Original Quality') }}</h4>
                    <p class="text-neutral-500 leading-relaxed">{{ \App\Models\SiteSetting::get('feature_1_desc', 'Pilihan material sepatu kelas atas dengan durabilitas tinggi.') }}</p>
                </div>
                <div class="p-6 bg-white rounded-2xl border border-neutral-200 shadow-2xs space-y-2">
                    <h4 class="font-black text-neutral-900 uppercase tracking-wider text-xs">{{ \App\Models\SiteSetting::get('feature_2_title', 'Garansi Tukar Ukuran') }}</h4>
                    <p class="text-neutral-500 leading-relaxed">{{ \App\Models\SiteSetting::get('feature_2_desc', 'Penukaran varian ukuran tanpa ribet hingga 7 hari.') }}</p>
                </div>
                <div class="p-6 bg-white rounded-2xl border border-neutral-200 shadow-2xs space-y-2">
                    <h4 class="font-black text-neutral-900 uppercase tracking-wider text-xs">{{ \App\Models\SiteSetting::get('feature_3_title', 'Ulasan Terverifikasi') }}</h4>
                    <p class="text-neutral-500 leading-relaxed">{{ \App\Models\SiteSetting::get('feature_3_desc', 'Setiap ulasan berasal dari pembeli nyata yang menyelesaikan pesanan.') }}</p>
                </div>
                <div class="p-6 bg-white rounded-2xl border border-neutral-200 shadow-2xs space-y-2">
                    <h4 class="font-black text-neutral-900 uppercase tracking-wider text-xs">{{ \App\Models\SiteSetting::get('feature_4_title', 'Pengiriman Cepat') }}</h4>
                    <p class="text-neutral-500 leading-relaxed">{{ \App\Models\SiteSetting::get('feature_4_desc', 'Pengiriman aman & efisien ke seluruh kota di Indonesia.') }}</p>
                </div>
            </div>
        </div>
    </section>

    <!-- AJAX Script for Category Filter, Real-time Live Search & Pagination without reload -->
    <script>
        let currentFilterState = {
            category: "{{ request('category', '') }}",
            size: "{{ request('size', '') }}",
            sort: "{{ request('sort', 'latest') }}",
            search: "{{ request('search', '') }}",
            page: "{{ request('page', 1) }}"
        };

        let searchDebounceTimer = null;

        // Function Toggle Wishlist AJAX (Tanpa Reload)
        function toggleWishlist(productId, btnElement) {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            fetch(`/wishlist/${productId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            })
            .then(res => {
                if (res.status === 401) {
                    window.location.href = '/login';
                    return;
                }
                return res.json();
            })
            .then(data => {
                if (data && data.success) {
                    const svg = btnElement.querySelector('svg');
                    if (data.added) {
                        svg.classList.remove('text-neutral-400', 'hover:text-rose-500');
                        svg.classList.add('text-rose-500', 'fill-rose-500');
                        svg.setAttribute('fill', 'currentColor');
                    } else {
                        svg.classList.remove('text-rose-500', 'fill-rose-500');
                        svg.classList.add('text-neutral-400', 'hover:text-rose-500');
                        svg.setAttribute('fill', 'none');
                    }
                    if (window.showToast) {
                        window.showToast(data.message, 'success');
                    }
                }
            })
            .catch(err => console.error(err));
        }

        // AJAX Filter & Search Loader
        function fetchFilteredProducts(resetPage = false, scrollToGrid = false) {
            const loading = document.getElementById('grid-loading');
            if (loading) loading.classList.remove('hidden');

            if (resetPage) {
                currentFilterState.page = 1;
            }

            const params = new URLSearchParams();
            if (currentFilterState.category) params.set('category', currentFilterState.category);
            if (currentFilterState.size) params.set('size', currentFilterState.size);
            if (currentFilterState.sort && currentFilterState.sort !== 'latest') params.set('sort', currentFilterState.sort);
            if (currentFilterState.search) params.set('search', currentFilterState.search);
            if (currentFilterState.page && currentFilterState.page > 1) params.set('page', currentFilterState.page);

            const queryString = params.toString();
            const fetchUrl = `{{ route('catalog.index') }}${queryString ? '?' + queryString : ''}`;

            fetch(fetchUrl, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                const grid = document.getElementById('product-grid-container');
                const pagination = document.getElementById('pagination-container');
                if (grid) grid.innerHTML = data.html;
                if (pagination) pagination.innerHTML = data.pagination;
                
                // Update URL browser tanpa reload
                window.history.pushState(currentFilterState, '', fetchUrl);

                // Show/hide reset button
                const resetBtn = document.getElementById('reset-filter-btn');
                if (resetBtn) {
                    if (currentFilterState.category || currentFilterState.size || currentFilterState.search || (currentFilterState.sort && currentFilterState.sort !== 'latest')) {
                        resetBtn.classList.remove('hidden');
                    } else {
                        resetBtn.classList.add('hidden');
                    }
                }

                if (scrollToGrid) {
                    const katalogSec = document.getElementById('katalog');
                    if (katalogSec) {
                        katalogSec.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    }
                }
            })
            .catch(err => console.error(err))
            .finally(() => {
                if (loading) loading.classList.add('hidden');
            });
        }

        document.addEventListener('DOMContentLoaded', function () {
            // 1. Real-time Live Search as user types
            const searchInput = document.getElementById('search-input');
            const searchForm = document.getElementById('search-form');

            if (searchInput) {
                searchInput.addEventListener('input', function () {
                    clearTimeout(searchDebounceTimer);
                    currentFilterState.search = this.value.trim();
                    searchDebounceTimer = setTimeout(() => {
                        fetchFilteredProducts(true);
                    }, 250);
                });
            }

            if (searchForm) {
                searchForm.addEventListener('submit', function (e) {
                    e.preventDefault();
                    clearTimeout(searchDebounceTimer);
                    if (searchInput) currentFilterState.search = searchInput.value.trim();
                    fetchFilteredProducts(true, true);
                });
            }

            // 2. Seamless AJAX Pagination Intercept
            const paginationContainer = document.getElementById('pagination-container');
            if (paginationContainer) {
                paginationContainer.addEventListener('click', function (e) {
                    const link = e.target.closest('a');
                    if (!link) return;

                    const href = link.getAttribute('href');
                    if (href && href !== '#' && !href.startsWith('javascript:')) {
                        e.preventDefault();
                        try {
                            const urlObj = new URL(href, window.location.origin);
                            const pageNum = urlObj.searchParams.get('page');
                            currentFilterState.page = pageNum || 1;
                            fetchFilteredProducts(false, true);
                        } catch (err) {
                            console.error(err);
                        }
                    }
                });
            }

            // 3. Category pills click handler
            document.querySelectorAll('.category-pill').forEach(pill => {
                pill.addEventListener('click', function (e) {
                    e.preventDefault();
                    currentFilterState.category = this.dataset.category;

                    document.querySelectorAll('.category-pill').forEach(p => {
                        p.className = 'category-pill px-5 py-2 rounded-full text-xs font-extrabold uppercase tracking-wider transition bg-neutral-100 text-neutral-700 hover:bg-neutral-200 border border-neutral-200 flex items-center gap-1.5';
                    });
                    this.className = 'category-pill px-5 py-2 rounded-full text-xs font-extrabold uppercase tracking-wider transition bg-neutral-900 text-white shadow-xs flex items-center gap-1.5';

                    fetchFilteredProducts(true);
                });
            });

            // 4. Size pills click handler
            document.querySelectorAll('.size-pill').forEach(pill => {
                pill.addEventListener('click', function (e) {
                    e.preventDefault();
                    currentFilterState.size = this.dataset.size;

                    document.querySelectorAll('.size-pill').forEach(p => {
                        p.className = 'size-pill px-3 py-1 rounded-full text-xs font-bold transition bg-white border border-neutral-300 text-neutral-700 hover:bg-neutral-100';
                    });
                    this.className = 'size-pill px-3 py-1 rounded-full text-xs font-bold transition bg-neutral-900 text-white';

                    fetchFilteredProducts(true);
                });
            });

            // 5. Sort dropdown change handler
            const sortSelect = document.getElementById('sort-select');
            if (sortSelect) {
                sortSelect.addEventListener('change', function () {
                    currentFilterState.sort = this.value;
                    fetchFilteredProducts(true);
                });
            }

            // 6. Reset filters click handler
            const resetBtn = document.getElementById('reset-filter-btn');
            if (resetBtn) {
                resetBtn.addEventListener('click', function (e) {
                    e.preventDefault();
                    currentFilterState = {
                        category: '',
                        size: '',
                        sort: 'latest',
                        search: '',
                        page: 1
                    };
                    if (searchInput) searchInput.value = '';
                    if (sortSelect) sortSelect.value = 'latest';

                    document.querySelectorAll('.category-pill').forEach((p, idx) => {
                        p.className = (idx === 0)
                            ? 'category-pill px-5 py-2 rounded-full text-xs font-extrabold uppercase tracking-wider transition bg-neutral-900 text-white shadow-xs flex items-center gap-1.5'
                            : 'category-pill px-5 py-2 rounded-full text-xs font-extrabold uppercase tracking-wider transition bg-neutral-100 text-neutral-700 hover:bg-neutral-200 border border-neutral-200 flex items-center gap-1.5';
                    });

                    document.querySelectorAll('.size-pill').forEach((p, idx) => {
                        p.className = (idx === 0)
                            ? 'size-pill px-3 py-1 rounded-full text-xs font-bold transition bg-neutral-900 text-white'
                            : 'size-pill px-3 py-1 rounded-full text-xs font-bold transition bg-white border border-neutral-300 text-neutral-700 hover:bg-neutral-100';
                    });

                    fetchFilteredProducts(true);
                });
            }

            // 7. Browser Back / Forward handler
            window.addEventListener('popstate', function (e) {
                if (e.state) {
                    currentFilterState = e.state;
                    fetchFilteredProducts(false);
                }
            });
        });
    </script>

@endsection
