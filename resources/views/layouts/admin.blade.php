<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Admin Panel — Sepatu.id')</title>

    <!-- Fonts: Inter / Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="h-full bg-slate-50 text-slate-800 antialiased selection:bg-indigo-500 selection:text-white">

    <div x-data="{ sidebarOpen: false }" class="min-h-screen flex flex-col md:flex-row">

        <!-- Mobile Sidebar Overlay -->
        <div x-show="sidebarOpen" x-cloak @click="sidebarOpen = false" class="fixed inset-0 z-40 bg-slate-900/40 backdrop-blur-xs md:hidden"></div>

        <!-- Sticky Fixed White Sidebar -->
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
               class="fixed inset-y-0 left-0 z-50 w-64 bg-white border-r border-slate-200/80 flex flex-col justify-between transition-transform duration-300 md:translate-x-0 md:sticky md:top-0 md:h-screen md:overflow-y-auto shrink-0">
            <div>


                <!-- User Profile Card (Di Atas Sidebar) -->
                <div class="p-4 border-b border-slate-100 bg-slate-50/50">
                    <div class="p-3 rounded-2xl bg-white border border-slate-200/80 shadow-2xs flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full bg-slate-900 text-white font-black text-xs flex items-center justify-center shadow-xs">
                                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                            </div>
                            <div class="min-w-0">
                                <p class="text-xs font-extrabold text-slate-900 truncate max-w-[105px]">{{ auth()->user()->name }}</p>
                                <span class="inline-block text-[9px] font-bold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-md border border-indigo-100/80 uppercase">Admin System</span>
                            </div>
                        </div>
                        <a href="{{ route('profile.edit') }}" class="p-1.5 rounded-lg text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 transition" title="Pengaturan Akun Admin">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Navigation Sections -->
                <nav class="p-4 space-y-6 text-xs font-semibold">

                    <!-- Section: Utama -->
                    <div>
                        <span class="px-3 text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-2">UTAMA</span>
                        <div class="space-y-1">
                            <a href="{{ route('admin.dashboard') }}"
                               class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-50 text-indigo-600 font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                                <svg class="w-4 h-4 {{ request()->routeIs('admin.dashboard') ? 'text-indigo-600' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                                </svg>
                                <span>Dashboard</span>
                            </a>
                        </div>
                    </div>

                    <!-- Section: Katalogue & Stok -->
                    <div>
                        <span class="px-3 text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-2">PRODUK &amp; STOK</span>
                        <div class="space-y-1">
                            <a href="{{ route('admin.produk.kategori.index') }}"
                               class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition {{ request()->routeIs('admin.produk.kategori.*') ? 'bg-indigo-50 text-indigo-600 font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                                <svg class="w-4 h-4 {{ request()->routeIs('admin.produk.kategori.*') ? 'text-indigo-600' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 11h.01M7 15h.01M13 7h7M13 11h7M13 15h7" />
                                </svg>
                                <span>Kategori Sepatu</span>
                            </a>
                            <a href="{{ route('admin.produk.index') }}"
                               class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition {{ request()->routeIs('admin.produk.index', 'admin.produk.create', 'admin.produk.edit', 'admin.produk.show') ? 'bg-indigo-50 text-indigo-600 font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                                <svg class="w-4 h-4 {{ request()->routeIs('admin.produk.*') ? 'text-indigo-600' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m-8-10l-8-4m8 4v10l8 4" />
                                </svg>
                                <span>Katalog Produk</span>
                            </a>

                            <a href="{{ route('admin.gudang.index') }}"
                               class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition {{ request()->routeIs('admin.gudang.*') ? 'bg-indigo-50 text-indigo-600 font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                                <svg class="w-4 h-4 {{ request()->routeIs('admin.gudang.*') ? 'text-indigo-600' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 01-2-2V5a2 2 0 012-2h14a2 2 0 012 2v1a2 2 0 01-2 2M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                                </svg>
                                <span>Stok Gudang</span>
                            </a>
                        </div>
                    </div>

                    <!-- Section: Penjualan & Transaksi -->
                    <div>
                        <span class="px-3 text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-2">TRANSAKSI &amp; PROMO</span>
                        <div class="space-y-1">
                            @php
                                $sidebarUnreadOrders = \App\Models\Order::where('is_viewed_by_admin', false)->count();
                            @endphp
                            <a href="{{ route('admin.pesanan.index') }}"
                               class="flex items-center justify-between px-3 py-2.5 rounded-xl transition {{ request()->routeIs('admin.pesanan.*') ? 'bg-indigo-50 text-indigo-600 font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                                <div class="flex items-center gap-3">
                                    <svg class="w-4 h-4 {{ request()->routeIs('admin.pesanan.*') ? 'text-indigo-600' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                    </svg>
                                    <span>Pesanan Pelanggan</span>
                                </div>
                                <span id="sidebar-order-badge" class="{{ $sidebarUnreadOrders > 0 ? 'inline-flex' : 'hidden' }} items-center justify-center px-2 py-0.5 rounded-full text-[10px] font-black bg-rose-500 text-white animate-pulse">
                                    {{ $sidebarUnreadOrders }}
                                </span>
                            </a>

                            <!-- Filter Cepat Pesanan -->
                            <div class="pl-7 space-y-0.5 pb-1">
                                <a href="{{ route('admin.pesanan.index', ['tab' => 'unviewed']) }}"
                                   class="block py-1 px-2 text-[11px] font-medium rounded-lg transition {{ request('tab') == 'unviewed' ? 'text-rose-600 font-bold bg-rose-50' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-50' }}">
                                    • Belum Dilihat Admin
                                </a>
                                <a href="{{ route('admin.pesanan.index', ['payment_status' => 'pending_verification']) }}"
                                   class="block py-1 px-2 text-[11px] font-medium rounded-lg transition {{ request('payment_status') == 'pending_verification' ? 'text-amber-600 font-bold bg-amber-50' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-50' }}">
                                    • Perlu Verifikasi TF
                                </a>
                                <a href="{{ route('admin.pesanan.index', ['tab' => 'unprinted']) }}"
                                   class="block py-1 px-2 text-[11px] font-medium rounded-lg transition {{ request('tab') == 'unprinted' ? 'text-indigo-600 font-bold bg-indigo-50' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-50' }}">
                                    • Belum Cetak Struk
                                </a>
                            </div>

                            <a href="{{ route('admin.vouchers.index') }}"
                               class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition {{ request()->routeIs('admin.vouchers.*') ? 'bg-indigo-50 text-indigo-600 font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                                <svg class="w-4 h-4 {{ request()->routeIs('admin.vouchers.*') ? 'text-indigo-600' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                                </svg>
                                <span>Voucher &amp; Diskon</span>
                            </a>

                            <a href="{{ route('admin.laporan.index') }}"
                               class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition {{ request()->routeIs('admin.laporan.*') ? 'bg-indigo-50 text-indigo-600 font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                                <svg class="w-4 h-4 {{ request()->routeIs('admin.laporan.*') ? 'text-indigo-600' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <span>Laporan Penjualan</span>
                            </a>
                        </div>
                    </div>

                    <!-- Section: Pengaturan -->
                    <div>
                        <span class="px-3 text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-2">PENGATURAN &amp; CMS</span>
                        <div class="space-y-1">
                            <a href="{{ route('admin.settings.landing') }}"
                               class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition {{ request()->routeIs('admin.settings.landing') ? 'bg-indigo-50 text-indigo-600 font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                                <svg class="w-4 h-4 {{ request()->routeIs('admin.settings.landing') ? 'text-indigo-600' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                <span>Edit Landing Page</span>
                            </a>
                            <a href="{{ route('admin.users.index') }}"
                               class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition {{ request()->routeIs('admin.users.*') ? 'bg-indigo-50 text-indigo-600 font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                                <svg class="w-4 h-4 {{ request()->routeIs('admin.users.*') ? 'text-indigo-600' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                                <span>Pengguna &amp; Hak Akses</span>
                            </a>
                        </div>
                    </div>

                </nav>
            </div>

            <!-- Footer Action -->
            <div class="p-4 border-t border-slate-100">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center gap-2 p-2.5 rounded-xl bg-slate-50 hover:bg-rose-50 hover:text-rose-600 text-slate-600 text-xs font-bold transition border border-slate-200/60">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                        <span>Keluar Sesi Admin</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content Container -->
        <div class="flex-grow flex flex-col min-w-0">

            <!-- Clean Top Navbar -->
            <header class="h-16 bg-white border-b border-slate-200/80 px-6 flex items-center justify-between sticky top-0 z-30">
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = true" class="md:hidden p-2 text-slate-600 hover:text-slate-900">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                    <h1 class="text-lg sm:text-xl font-bold text-slate-900 tracking-tight">
                        @yield('page_title', 'Dashboard')
                    </h1>
                </div>

                <div class="flex items-center gap-3">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-100 text-slate-600 text-xs font-semibold">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        <span>Mode Khusus Admin</span>
                    </span>
                </div>
            </header>

            <!-- Flash Toast Alerts -->
            <div class="px-6 pt-6 space-y-3">
                @if (session('success'))
                    <div x-data="{ show: true }" x-show="show" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 transform scale-100" x-transition:leave-end="opacity-0 transform scale-95" class="p-4 rounded-2xl bg-emerald-50/90 border border-emerald-200/80 text-emerald-950 text-xs font-semibold flex items-center justify-between shadow-2xs">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" /></svg>
                            </div>
                            <span>{{ session('success') }}</span>
                        </div>
                        <button @click="show = false" class="p-1.5 rounded-lg text-emerald-600 hover:bg-emerald-100 transition ml-4 shrink-0" title="Tutup">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>
                @endif

                @if (session('error'))
                    <div x-data="{ show: true }" x-show="show" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 transform scale-100" x-transition:leave-end="opacity-0 transform scale-95" class="p-4 rounded-2xl bg-rose-50/90 border border-rose-200/80 text-rose-950 text-xs font-semibold flex items-center justify-between shadow-2xs">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-xl bg-rose-100 text-rose-600 flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            </div>
                            <span>{{ session('error') }}</span>
                        </div>
                        <button @click="show = false" class="p-1.5 rounded-lg text-rose-600 hover:bg-rose-100 transition ml-4 shrink-0" title="Tutup">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>
                @endif

                @if (session('warning'))
                    <div x-data="{ show: true }" x-show="show" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 transform scale-100" x-transition:leave-end="opacity-0 transform scale-95" class="p-4 rounded-2xl bg-amber-50/90 border border-amber-200/80 text-amber-950 text-xs font-semibold flex items-center justify-between shadow-2xs">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                            </div>
                            <span>{{ session('warning') }}</span>
                        </div>
                        <button @click="show = false" class="p-1.5 rounded-lg text-amber-600 hover:bg-amber-100 transition ml-4 shrink-0" title="Tutup">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>
                @endif

                @if (session('info') || session('status'))
                    <div x-data="{ show: true }" x-show="show" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 transform scale-100" x-transition:leave-end="opacity-0 transform scale-95" class="p-4 rounded-2xl bg-indigo-50/90 border border-indigo-200/80 text-indigo-950 text-xs font-semibold flex items-center justify-between shadow-2xs">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            </div>
                            <span>{{ session('info') ?? session('status') }}</span>
                        </div>
                        <button @click="show = false" class="p-1.5 rounded-lg text-indigo-600 hover:bg-indigo-100 transition ml-4 shrink-0" title="Tutup">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>
                @endif

                @if ($errors->any())
                    <div x-data="{ show: true }" x-show="show" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 transform scale-100" x-transition:leave-end="opacity-0 transform scale-95" class="p-4 rounded-2xl bg-rose-50/90 border border-rose-200/80 text-rose-950 text-xs font-semibold flex items-start justify-between shadow-2xs">
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-xl bg-rose-100 text-rose-600 flex items-center justify-center shrink-0 mt-0.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                            </div>
                            <div>
                                <p class="font-bold text-rose-900 mb-1">Terdapat beberapa kesalahan pengisian:</p>
                                <ul class="list-disc list-inside space-y-0.5 text-rose-700 font-medium">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        <button @click="show = false" class="p-1.5 rounded-lg text-rose-600 hover:bg-rose-100 transition ml-4 shrink-0" title="Tutup">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>
                @endif
            </div>

            <!-- Page Main Content -->
            <main class="p-6 flex-grow">
                @yield('content')
            </main>

        </div>
    </div>

    <!-- Global Floating Toast Notification Container -->
    <div id="toast-container" class="fixed bottom-5 right-5 z-50 flex flex-col gap-2.5 max-w-sm w-full pointer-events-none px-4"></div>

    <script>
        window.showToast = function(message, type = 'success') {
            const container = document.getElementById('toast-container');
            if (!container) return;

            const icons = {
                success: `<svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" /></svg>`,
                error: `<svg class="w-4 h-4 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>`,
                warning: `<svg class="w-4 h-4 text-amber-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>`,
                info: `<svg class="w-4 h-4 text-indigo-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>`
            };

            const colors = {
                success: 'bg-white border-emerald-200 text-emerald-950 shadow-emerald-500/10',
                error: 'bg-white border-rose-200 text-rose-950 shadow-rose-500/10',
                warning: 'bg-white border-amber-200 text-amber-950 shadow-amber-500/10',
                info: 'bg-white border-slate-200 text-slate-900 shadow-slate-500/10'
            };

            const toast = document.createElement('div');
            toast.className = `pointer-events-auto p-4 rounded-2xl border shadow-xl flex items-center justify-between gap-3 text-xs font-bold transition-all duration-300 transform translate-y-4 opacity-0 ${colors[type] || colors.success}`;
            toast.innerHTML = `
                <div class="flex items-center gap-2.5">
                    ${icons[type] || icons.success}
                    <span>${message}</span>
                </div>
                <button type="button" class="text-slate-400 hover:text-slate-700 transition shrink-0 ml-2" onclick="this.parentElement.remove()">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            `;

            container.appendChild(toast);

            requestAnimationFrame(() => {
                toast.classList.remove('translate-y-4', 'opacity-0');
                toast.classList.add('translate-y-0', 'opacity-100');
            });

            setTimeout(() => {
                toast.classList.remove('translate-y-0', 'opacity-100');
                toast.classList.add('translate-y-4', 'opacity-0');
                setTimeout(() => toast.remove(), 300);
            }, 3500);
        };

        // Audio Alert Chime untuk Pesanan Baru Masuk
        function playOrderAlertSound() {
            try {
                const ctx = new (window.AudioContext || window.webkitAudioContext)();
                const now = ctx.currentTime;
                
                const osc1 = ctx.createOscillator();
                const osc2 = ctx.createOscillator();
                const gain = ctx.createGain();

                osc1.type = 'sine';
                osc1.frequency.setValueAtTime(587.33, now); // D5
                osc1.frequency.setValueAtTime(880, now + 0.12); // A5

                osc2.type = 'triangle';
                osc2.frequency.setValueAtTime(440, now);
                osc2.frequency.setValueAtTime(659.25, now + 0.12);

                gain.gain.setValueAtTime(0.12, now);
                gain.gain.exponentialRampToValueAtTime(0.001, now + 0.6);

                osc1.connect(gain);
                osc2.connect(gain);
                gain.connect(ctx.destination);

                osc1.start(now);
                osc2.start(now);
                osc1.stop(now + 0.6);
                osc2.stop(now + 0.6);
            } catch (e) {
                // Audio context not allowed until interaction
            }
        }

        // Real-Time New Order Poller
        (function() {
            let lastKnownUnread = {{ \App\Models\Order::where('is_viewed_by_admin', false)->count() }};
            
            async function pollNewOrders() {
                try {
                    const res = await fetch('{{ route('admin.pesanan.check-new') }}', {
                        headers: { 'Accept': 'application/json' }
                    });
                    if (!res.ok) return;
                    const data = await res.json();

                    const badge = document.getElementById('sidebar-order-badge');
                    if (badge) {
                        if (data.unviewed_count > 0) {
                            badge.textContent = data.unviewed_count;
                            badge.classList.remove('hidden');
                            badge.classList.add('inline-flex');
                        } else {
                            badge.classList.add('hidden');
                            badge.classList.remove('inline-flex');
                        }
                    }

                    // Jika ada pesanan baru yang belum dilihat bertambah
                    if (data.unviewed_count > lastKnownUnread && data.latest) {
                        playOrderAlertSound();
                        window.showToast(`🔥 Pesanan Baru Masuk! #${data.latest.id} dari ${data.latest.customer} (${data.latest.total})`, 'info');
                    }

                    lastKnownUnread = data.unviewed_count;
                } catch (e) {}
            }

            // Jalankan polling setiap 12 detik
            setInterval(pollNewOrders, 12000);
        })();
    </script>

    @stack('scripts')
</body>
</html>
