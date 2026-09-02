<nav x-data="{ open: false }" class="bg-white border-b border-neutral-200">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 sm:h-20">
            <div class="flex items-center gap-8">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('catalog.index') }}" class="flex flex-col">
                        <span class="font-black text-xl tracking-tighter uppercase text-neutral-900 leading-none">
                            Sepatu<span class="text-orange-500">.id</span>
                        </span>
                        <span class="text-[9px] uppercase tracking-widest text-neutral-400 font-bold -mt-0.5">Footwear Brand</span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:flex text-xs font-extrabold uppercase tracking-widest">
                    <a href="{{ route('catalog.index') }}" class="inline-flex items-center px-1 pt-1 border-b-2 text-neutral-900 transition {{ request()->routeIs('catalog.index') ? 'border-neutral-900 font-black' : 'border-transparent text-neutral-500 hover:text-neutral-900' }}">
                        Katalog
                    </a>
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center px-3 py-1 text-[11px] font-bold rounded-full bg-neutral-900 text-white hover:bg-orange-500 transition my-auto">
                            Panel Admin
                        </a>
                    @endif
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <div x-data="{ openDropdown: false }" class="relative">
                    <button @click="openDropdown = !openDropdown" @click.away="openDropdown = false" class="flex items-center gap-2 px-3 py-2 text-xs font-extrabold uppercase tracking-wider text-neutral-900 hover:text-orange-500 transition">
                        <div>{{ Auth::user()->name }}</div>
                        <svg class="w-3.5 h-3.5 text-neutral-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <div x-show="openDropdown" x-cloak x-transition
                         class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-xl border border-neutral-100 py-1.5 z-50 text-xs font-semibold">
                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-neutral-800 hover:bg-neutral-50">
                            Pengaturan Profil
                        </a>
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-neutral-900 hover:bg-neutral-50 font-bold">
                                Dashboard Admin
                            </a>
                        @endif

                        <form method="POST" action="{{ route('logout') }}" class="border-t border-neutral-100 mt-1">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2 text-rose-600 hover:bg-rose-50 font-bold">
                                Keluar
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-neutral-800 hover:text-neutral-900 hover:bg-neutral-100 focus:outline-none transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-white border-b border-neutral-200">
        <div class="pt-2 pb-3 space-y-1 text-xs font-bold uppercase tracking-wider px-4">
            <a href="{{ route('catalog.index') }}" class="block py-2 text-neutral-900">Katalog</a>
            @if(auth()->user()->isAdmin())
                <a href="{{ route('admin.dashboard') }}" class="block py-2 text-neutral-900 font-black">Dashboard Admin</a>
            @endif
        </div>

        <div class="pt-4 pb-3 border-t border-neutral-100 px-4 space-y-2 text-xs">
            <div class="font-black text-neutral-900 uppercase">{{ Auth::user()->name }}</div>
            <div class="text-neutral-400 text-[10px]">{{ Auth::user()->email }}</div>

            <div class="mt-3 space-y-1 font-bold">
                <a href="{{ route('profile.edit') }}" class="block py-1.5 text-neutral-700">Pengaturan Profil</a>
                <form method="POST" action="{{ route('logout') }}" class="pt-1 border-t border-neutral-100">
                    @csrf
                    <button type="submit" class="w-full text-left py-1.5 text-rose-600">Keluar</button>
                </form>
            </div>
        </div>
    </div>
</nav>
