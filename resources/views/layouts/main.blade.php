<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Sepatu.id — Official Footwear Store')</title>
    <meta name="description" content="Katalog e-commerce sepatu original lokal dengan kualitas premium dan desain minimalis.">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="h-full bg-white text-neutral-900 antialiased flex flex-col selection:bg-neutral-900 selection:text-white">

    <!-- Top Announcement Bar -->
    <div class="bg-neutral-100 border-b border-neutral-200 text-neutral-800 text-[11px] py-2 px-4 text-center font-medium tracking-wide">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <span class="hidden sm:inline-block text-neutral-500">Garansi 100% Original &amp; Bebas Tukar Ukuran</span>
            <span class="mx-auto sm:mx-0 font-semibold text-neutral-900">Gratis Ongkir Seluruh Indonesia Pembelian &gt; Rp 500.000</span>
            <span class="hidden md:inline-block text-neutral-500">CS WhatsApp: 0812-3456-789</span>
        </div>
    </div>

    <!-- Navigation Header -->
    <header x-data="{ mobileMenu: false, scrolled: false }"
            @scroll.window="scrolled = (window.pageYOffset > 10)"
            :class="scrolled ? 'bg-white/95 backdrop-blur-md shadow-xs border-neutral-200' : 'bg-white border-neutral-100'"
            class="sticky top-0 z-40 border-b transition-all duration-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 sm:h-20">

                <!-- Brand Logo (Inspired by Sneaker Flare) -->
                <a href="{{ route('catalog.index') }}" class="flex flex-col items-center group">
                    <span class="font-black text-xl sm:text-2xl tracking-tighter uppercase text-neutral-900 leading-none">
                        Sepatu<span class="text-orange-500">.id</span>
                    </span>
                    <span class="text-[9px] uppercase tracking-widest text-neutral-400 font-bold -mt-0.5">Footwear Brand</span>
                </a>

                <!-- Navigation Links -->
                <nav class="hidden md:flex items-center gap-8 text-xs font-bold uppercase tracking-widest text-neutral-600">
                    <a href="{{ route('catalog.index') }}" class="hover:text-neutral-900 transition {{ request()->routeIs('catalog.index') ? 'text-neutral-900 font-extrabold border-b-2 border-neutral-900 pb-1' : '' }}">
                        Katalog
                    </a>
                    <a href="{{ route('catalog.index') }}#kategori" class="hover:text-neutral-900 transition">
                        Kategori
                    </a>
                    @auth
                        <a href="{{ route('orders.index') }}" class="hover:text-neutral-900 transition {{ request()->routeIs('orders.*') ? 'text-neutral-900 font-extrabold border-b-2 border-neutral-900 pb-1' : '' }}">
                            Pesanan Saya
                        </a>
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="px-3 py-1 rounded bg-neutral-900 text-white text-[11px] font-bold hover:bg-neutral-800 transition">
                                Panel Admin
                            </a>
                        @endif
                    @endauth
                </nav>

                <!-- Right Action Icons -->
                <div class="flex items-center gap-4">

                    <!-- Wishlist Icon -->
                    <a href="{{ route('wishlist.index') }}" class="relative p-2 text-neutral-800 hover:text-orange-500 transition" title="Wishlist">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                        @auth
                            @php $wishlistCount = auth()->user()->wishlists()->count(); @endphp
                            @if($wishlistCount > 0)
                                <span class="absolute top-0 right-0 bg-orange-500 text-white text-[9px] font-bold w-4 h-4 rounded-full flex items-center justify-center">
                                    {{ $wishlistCount }}
                                </span>
                            @endif
                        @endauth
                    </a>

                    <!-- Cart Icon -->
                    <a href="{{ route('cart.index') }}" class="relative p-2 text-neutral-800 hover:text-orange-500 transition" title="Keranjang Belanja">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                        @auth
                            @php $cartCount = auth()->user()->carts()->sum('qty'); @endphp
                            @if($cartCount > 0)
                                <span class="absolute top-0 right-0 bg-neutral-900 text-white text-[9px] font-bold w-4 h-4 rounded-full flex items-center justify-center">
                                    {{ $cartCount }}
                                </span>
                            @endif
                        @endauth
                    </a>

                    <!-- User Account / Auth -->
                    @auth
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" @click.away="open = false" class="flex items-center gap-1.5 p-1 rounded-full text-neutral-900 focus:outline-none">
                                <div class="w-8 h-8 rounded-full bg-neutral-900 text-white font-bold text-xs flex items-center justify-center">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                                </div>
                                <svg class="w-3.5 h-3.5 text-neutral-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <div x-show="open" x-cloak x-transition
                                 class="absolute right-0 mt-2 w-52 bg-white rounded-xl shadow-xl border border-neutral-100 py-1.5 z-50 text-xs">
                                <div class="px-4 py-2 border-b border-neutral-100">
                                    <p class="font-bold text-neutral-900 truncate">{{ auth()->user()->name }}</p>
                                    <p class="text-[10px] text-neutral-400 truncate">{{ auth()->user()->email }}</p>
                                </div>

                                @if(auth()->user()->isAdmin())
                                    <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-neutral-900 hover:bg-neutral-50 font-bold">
                                        Dashboard Admin
                                    </a>
                                @endif
                                <a href="{{ route('orders.index') }}" class="block px-4 py-2 text-neutral-700 hover:bg-neutral-50 font-medium">
                                    Pesanan Saya
                                </a>
                                <a href="{{ route('wishlist.index') }}" class="block px-4 py-2 text-neutral-700 hover:bg-neutral-50 font-medium">
                                    Wishlist Saya
                                </a>
                                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-neutral-700 hover:bg-neutral-50 font-medium">
                                    Pengaturan Profil
                                </a>

                                <form method="POST" action="{{ route('logout') }}" class="border-t border-neutral-100 mt-1">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-rose-600 hover:bg-rose-50 font-semibold">
                                        Keluar
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <div class="hidden sm:flex items-center gap-2 text-xs">
                            <a href="{{ route('login') }}" class="px-3 py-2 font-bold uppercase tracking-wider text-neutral-800 hover:text-orange-500 transition">
                                Masuk
                            </a>
                            <a href="{{ route('register') }}" class="px-4 py-2 rounded-full bg-neutral-900 text-white font-bold uppercase tracking-wider hover:bg-orange-500 transition">
                                Daftar
                            </a>
                        </div>
                    @endauth

                    <!-- Mobile Menu Button -->
                    <button @click="mobileMenu = !mobileMenu" class="md:hidden p-2 text-neutral-800">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path x-show="!mobileMenu" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            <path x-show="mobileMenu" x-cloak stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Navigation Menu -->
        <div x-show="mobileMenu" x-cloak x-transition class="md:hidden bg-white border-b border-neutral-200 px-4 py-4 space-y-2 text-xs font-bold uppercase tracking-widest">
            <a href="{{ route('catalog.index') }}" class="block py-2 text-neutral-900">Katalog</a>
            @auth
                <a href="{{ route('orders.index') }}" class="block py-2 text-neutral-700">Pesanan Saya</a>
                <a href="{{ route('wishlist.index') }}" class="block py-2 text-neutral-700">Wishlist</a>
                <a href="{{ route('cart.index') }}" class="block py-2 text-neutral-700">Keranjang Belanja</a>
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="block py-2 text-neutral-900 font-extrabold">Dashboard Admin</a>
                @endif
                <form method="POST" action="{{ route('logout') }}" class="pt-2 border-t border-neutral-100">
                    @csrf
                    <button type="submit" class="w-full text-left py-2 text-rose-600">Keluar</button>
                </form>
            @else
                <div class="flex flex-col gap-2 pt-2 border-t border-neutral-100">
                    <a href="{{ route('login') }}" class="w-full text-center py-2 rounded-full border border-neutral-300 font-bold text-neutral-800">Masuk</a>
                    <a href="{{ route('register') }}" class="w-full text-center py-2 rounded-full bg-neutral-900 font-bold text-white">Daftar</a>
                </div>
            @endauth
        </div>
    </header>

    <!-- Flash Messages -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
        @if (session('success'))
            <div x-data="{ show: true }" x-show="show" class="mb-4 p-4 rounded-xl bg-neutral-100 border border-neutral-300 text-neutral-900 text-xs font-medium flex items-center justify-between">
                <span>{{ session('success') }}</span>
                <button @click="show = false" class="text-neutral-500 hover:text-neutral-900 ml-4">✕</button>
            </div>
        @endif

        @if (session('error'))
            <div x-data="{ show: true }" x-show="show" class="mb-4 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-900 text-xs font-medium flex items-center justify-between">
                <span>{{ session('error') }}</span>
                <button @click="show = false" class="text-rose-500 hover:text-rose-900 ml-4">✕</button>
            </div>
        @endif
    </div>

    <!-- Main Content -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Minimalist Footer (Inspired by Sneaker Flare) -->
    <footer class="bg-neutral-950 text-neutral-400 mt-20 border-t border-neutral-900 text-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">

                <!-- Brand Column -->
                <div class="space-y-3">
                    <span class="font-black text-xl text-white tracking-tighter uppercase block">
                        Sepatu<span class="text-orange-500">.id</span>
                    </span>
                    <p class="text-neutral-400 text-xs leading-relaxed">
                        Koleksi sepatu sneaker &amp; streetwear original buatan Indonesia dengan material premium dan kenyamanan tingkat tinggi.
                    </p>
                </div>

                <!-- Nav -->
                <div>
                    <h4 class="text-white text-xs font-extrabold uppercase tracking-widest mb-3">Navigasi</h4>
                    <ul class="space-y-2 text-neutral-400 font-medium">
                        <li><a href="{{ route('catalog.index') }}" class="hover:text-white transition">Katalog Utama</a></li>
                        <li><a href="{{ route('cart.index') }}" class="hover:text-white transition">Keranjang Belanja</a></li>
                        <li><a href="{{ route('wishlist.index') }}" class="hover:text-white transition">Wishlist Saya</a></li>
                        <li><a href="{{ route('orders.index') }}" class="hover:text-white transition">Lacak Pesanan</a></li>
                    </ul>
                </div>

                <!-- Kategori -->
                <div>
                    <h4 class="text-white text-xs font-extrabold uppercase tracking-widest mb-3">Kategori</h4>
                    <ul class="space-y-2 text-neutral-400 font-medium">
                        <li><a href="{{ route('catalog.index') }}?search=sneakers" class="hover:text-white transition">Sneakers</a></li>
                        <li><a href="{{ route('catalog.index') }}?search=running" class="hover:text-white transition">Running</a></li>
                        <li><a href="{{ route('catalog.index') }}?search=casual" class="hover:text-white transition">Casual</a></li>
                        <li><a href="{{ route('catalog.index') }}?search=slip" class="hover:text-white transition">Slip-On</a></li>
                    </ul>
                </div>

                <!-- CS -->
                <div>
                    <h4 class="text-white text-xs font-extrabold uppercase tracking-widest mb-3">Layanan CS</h4>
                    <div class="space-y-2 text-neutral-400 font-medium">
                        <p>Jakarta, Indonesia</p>
                        <p>WhatsApp: 0812-3456-789</p>
                        <p>Email: support@sepatu.id</p>
                    </div>
                </div>

            </div>

            <div class="mt-12 pt-6 border-t border-neutral-900 flex flex-col sm:flex-row items-center justify-between text-[11px] text-neutral-500 gap-4">
                <p>&copy; {{ date('Y') }} Sepatu.id — All rights reserved.</p>
                <div class="flex items-center gap-4 uppercase font-bold tracking-wider text-[10px]">
                    <a href="#" class="hover:text-white transition">Kebijakan Privasi</a>
                    <a href="#" class="hover:text-white transition">Syarat &amp; Ketentuan</a>
                </div>
            </div>
        </div>
    </footer>

</body>
</html>
