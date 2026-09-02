@extends($user->isAdmin() ? 'layouts.admin' : 'layouts.main')

@section('title', $user->isAdmin() ? 'Pengaturan Profil Admin — Sepatu.id' : 'Profil Saya — Sepatu.id')
@section('page_title', 'Pengaturan Akun Admin')

@section('content')

@if($user->isAdmin())
    {{-- ================= TAMPILAN KHUSUS ADMIN ================= --}}
    <div class="space-y-6 max-w-5xl">
        <!-- Top Title Card -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-2xs flex flex-col sm:flex-row items-center justify-between gap-6">
            <div class="flex items-center gap-5">
                <div class="w-16 h-16 rounded-2xl bg-indigo-600 text-white font-black text-2xl flex items-center justify-center shadow-md">
                    {{ strtoupper(substr($user->name, 0, 2)) }}
                </div>
                <div class="space-y-1">
                    <div class="flex items-center gap-2">
                        <h2 class="text-xl font-bold text-slate-900 tracking-tight">{{ $user->name }}</h2>
                        <span class="px-2.5 py-0.5 rounded-full bg-indigo-50 text-indigo-600 text-[10px] font-bold uppercase border border-indigo-100">
                            Administrator
                        </span>
                    </div>
                    <p class="text-xs text-slate-500 font-medium">{{ $user->email }}</p>
                    <p class="text-[10px] text-slate-400 font-semibold uppercase tracking-wider">Terdaftar Sejak: {{ $user->created_at->format('d M Y') }}</p>
                </div>
            </div>
            <div>
                <span class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-slate-50 text-slate-600 border border-slate-200/80 text-xs font-semibold">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    <span>Hak Akses Penuh Sistem</span>
                </span>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Form 1: Edit Informasi Akun Admin -->
            <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-2xs space-y-5">
                <div class="border-b border-slate-100 pb-3">
                    <h3 class="text-sm font-bold text-slate-900">Informasi Pribadi Admin</h3>
                    <p class="text-xs text-slate-500 font-medium mt-0.5">Perbarui nama dan kontak administrator.</p>
                </div>

                @if (session('status') === 'profile-updated')
                    <div class="p-3.5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-900 text-xs font-semibold flex items-center gap-2">
                        <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" /></svg>
                        <span>Profil admin berhasil diperbarui!</span>
                    </div>
                @endif

                <form method="post" action="{{ route('profile.update') }}" class="space-y-4">
                    @csrf
                    @method('patch')

                    <div>
                        <label for="name" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Nama Lengkap <span class="text-rose-500">*</span></label>
                        <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required autocomplete="name"
                               class="w-full rounded-xl border border-slate-300 text-xs font-semibold p-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-slate-50">
                        @error('name')
                            <p class="text-rose-500 text-[11px] font-bold mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Alamat Email <span class="text-rose-500">*</span></label>
                        <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required autocomplete="username"
                               class="w-full rounded-xl border border-slate-300 text-xs font-semibold p-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-slate-50">
                        @error('email')
                            <p class="text-rose-500 text-[11px] font-bold mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="phone" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Nomor WhatsApp / Kontak</label>
                        <input id="phone" name="phone" type="text" value="{{ old('phone', $user->phone) }}" placeholder="Contoh: 081234567890"
                               class="w-full rounded-xl border border-slate-300 text-xs font-semibold p-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-slate-50">
                        @error('phone')
                            <p class="text-rose-500 text-[11px] font-bold mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="w-full sm:w-auto px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs transition shadow-2xs">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>

            <!-- Form 2: Ganti Password Admin -->
            <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-2xs space-y-5">
                <div class="border-b border-slate-100 pb-3">
                    <h3 class="text-sm font-bold text-slate-900">Keamanan &amp; Kata Sandi</h3>
                    <p class="text-xs text-slate-500 font-medium mt-0.5">Ganti kata sandi login admin secara berkala.</p>
                </div>

                @if (session('status') === 'password-updated')
                    <div class="p-3.5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-900 text-xs font-semibold flex items-center gap-2">
                        <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" /></svg>
                        <span>Kata sandi berhasil diperbarui!</span>
                    </div>
                @endif

                <form method="post" action="{{ route('password.update') }}" class="space-y-4">
                    @csrf
                    @method('put')

                    <div>
                        <label for="update_password_current_password" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Password Saat Ini</label>
                        <input id="update_password_current_password" name="current_password" type="password" autocomplete="current-password"
                               class="w-full rounded-xl border border-slate-300 text-xs font-semibold p-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-slate-50">
                        @error('current_password', 'updatePassword')
                            <p class="text-rose-500 text-[11px] font-bold mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="update_password_password" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Password Baru</label>
                        <input id="update_password_password" name="password" type="password" autocomplete="new-password"
                               class="w-full rounded-xl border border-slate-300 text-xs font-semibold p-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-slate-50">
                        @error('password', 'updatePassword')
                            <p class="text-rose-500 text-[11px] font-bold mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="update_password_password_confirmation" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Konfirmasi Password Baru</label>
                        <input id="update_password_password_confirmation" name="password_confirmation" type="password" autocomplete="new-password"
                               class="w-full rounded-xl border border-slate-300 text-xs font-semibold p-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-slate-50">
                        @error('password_confirmation', 'updatePassword')
                            <p class="text-rose-500 text-[11px] font-bold mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="w-full sm:w-auto px-5 py-2.5 rounded-xl bg-slate-900 hover:bg-indigo-600 text-white font-bold text-xs transition shadow-2xs">
                            Perbarui Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@else
    {{-- ================= TAMPILAN KHUSUS PELANGGAN ================= --}}
    <div class="bg-[#f8f8f8] py-10 lg:py-14 border-b border-neutral-200">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            <!-- Profile Header Card -->
            <div class="bg-white rounded-2xl p-6 sm:p-8 border border-neutral-200 shadow-2xs flex flex-col sm:flex-row items-center justify-between gap-6">
                <div class="flex flex-col sm:flex-row items-center gap-5 text-center sm:text-left">
                    <div class="w-20 h-20 rounded-full bg-neutral-900 text-white font-black text-2xl flex items-center justify-center shadow-md">
                        {{ strtoupper(substr($user->name, 0, 2)) }}
                    </div>
                    <div class="space-y-1">
                        <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2">
                            <h1 class="text-xl sm:text-2xl font-black text-neutral-900 uppercase tracking-tight">{{ $user->name }}</h1>
                            <span class="px-2.5 py-0.5 rounded-full bg-neutral-100 text-neutral-800 text-[10px] font-extrabold uppercase border border-neutral-200">
                                Member Pelanggan
                            </span>
                        </div>
                        <p class="text-xs text-neutral-500 font-semibold">{{ $user->email }}</p>
                        <p class="text-[10px] text-neutral-400 font-bold uppercase tracking-wider">Terdaftar Sejak: {{ $user->created_at->format('d M Y') }}</p>
                    </div>
                </div>

                <!-- Account Quick Stats -->
                <div class="flex items-center gap-6 border-t sm:border-t-0 sm:border-l border-neutral-200 pt-4 sm:pt-0 sm:pl-8 text-center">
                    <div>
                        <span class="block text-xl font-black text-neutral-900">{{ $user->orders()->count() }}</span>
                        <span class="text-[10px] font-extrabold uppercase tracking-wider text-neutral-400">Total Pesanan</span>
                    </div>
                    <div>
                        <span class="block text-xl font-black text-neutral-900">{{ $user->wishlists()->count() }}</span>
                        <span class="text-[10px] font-extrabold uppercase tracking-wider text-neutral-400">Wishlist</span>
                    </div>
                    <div>
                        <span class="block text-xl font-black text-neutral-900">{{ $user->carts()->sum('qty') }}</span>
                        <span class="text-[10px] font-extrabold uppercase tracking-wider text-neutral-400">Di Keranjang</span>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

                <!-- Left Column: Navigation Sidebar Tabs -->
                <div class="lg:col-span-4 space-y-4">
                    <div class="bg-white rounded-2xl p-4 border border-neutral-200 shadow-2xs space-y-2 text-xs font-extrabold uppercase tracking-wider">
                        <a href="{{ route('orders.index', ['tab' => 'active']) }}" class="flex items-center justify-between p-3 rounded-xl hover:bg-neutral-50 text-neutral-700 transition">
                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
                                <span>Pesanan Aktif</span>
                            </span>
                            <svg class="w-4 h-4 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                        </a>
                        <a href="{{ route('orders.index', ['tab' => 'history']) }}" class="flex items-center justify-between p-3 rounded-xl hover:bg-neutral-50 text-neutral-700 transition">
                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                <span>Riwayat Pesanan</span>
                            </span>
                            <svg class="w-4 h-4 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                        </a>
                        <a href="{{ route('wishlist.index') }}" class="flex items-center justify-between p-3 rounded-xl hover:bg-neutral-50 text-neutral-700 transition">
                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" /></svg>
                                <span>Wishlist Saya</span>
                            </span>
                            <svg class="w-4 h-4 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                        </a>
                        <a href="{{ route('cart.index') }}" class="flex items-center justify-between p-3 rounded-xl hover:bg-neutral-50 text-neutral-700 transition">
                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-neutral-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z" /></svg>
                                <span>Keranjang Belanja</span>
                            </span>
                            <svg class="w-4 h-4 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                        </a>
                    </div>
                </div>

                <!-- Right Column: Profile Edit Forms -->
                <div class="lg:col-span-8 space-y-6">

                    <!-- Form 1: Edit Informasi Akun & Alamat -->
                    <div class="bg-white rounded-2xl p-6 sm:p-8 border border-neutral-200 shadow-2xs space-y-6">
                        <div class="border-b border-neutral-100 pb-4">
                            <h2 class="text-base font-black text-neutral-900 uppercase tracking-tight">Informasi Pribadi &amp; Pengiriman</h2>
                            <p class="text-xs text-neutral-500 font-medium mt-0.5">Perbarui nama, nomor telepon WhatsApp, dan alamat pengiriman default Anda.</p>
                        </div>

                        @if (session('status') === 'profile-updated')
                            <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-900 text-xs font-semibold flex items-center gap-2">
                                <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" /></svg>
                                <span>Profil Anda berhasil diperbarui!</span>
                            </div>
                        @endif

                        <form method="post" action="{{ route('profile.update') }}" class="space-y-4">
                            @csrf
                            @method('patch')

                            <div>
                                <label for="name" class="block text-xs font-extrabold uppercase tracking-wider text-neutral-900 mb-1">Nama Lengkap <span class="text-rose-500">*</span></label>
                                <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required autocomplete="name"
                                       class="w-full rounded-xl border-neutral-300 text-xs font-semibold p-3 focus:ring-neutral-900 bg-[#f8f8f8]">
                                @error('name')
                                    <p class="text-rose-500 text-[11px] font-bold mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="email" class="block text-xs font-extrabold uppercase tracking-wider text-neutral-900 mb-1">Alamat Email <span class="text-rose-500">*</span></label>
                                <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required autocomplete="username"
                                       class="w-full rounded-xl border-neutral-300 text-xs font-semibold p-3 focus:ring-neutral-900 bg-[#f8f8f8]">
                                @error('email')
                                    <p class="text-rose-500 text-[11px] font-bold mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="phone" class="block text-xs font-extrabold uppercase tracking-wider text-neutral-900 mb-1">Nomor WhatsApp / HP</label>
                                <input id="phone" name="phone" type="text" value="{{ old('phone', $user->phone) }}" placeholder="Contoh: 081234567890"
                                       class="w-full rounded-xl border-neutral-300 text-xs font-semibold p-3 focus:ring-neutral-900 bg-[#f8f8f8]">
                                @error('phone')
                                    <p class="text-rose-500 text-[11px] font-bold mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="address" class="block text-xs font-extrabold uppercase tracking-wider text-neutral-900 mb-1">Alamat Pengiriman Utama</label>
                                <textarea id="address" name="address" rows="3" placeholder="Jalan, Nomor Rumah, RT/RW, Kecamatan, Kota, Kode Pos..."
                                          class="w-full rounded-xl border-neutral-300 text-xs font-semibold p-3 focus:ring-neutral-900 bg-[#f8f8f8]">{{ old('address', $user->address) }}</textarea>
                                @error('address')
                                    <p class="text-rose-500 text-[11px] font-bold mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="pt-2">
                                <button type="submit" class="px-6 py-3 rounded-full bg-neutral-900 hover:bg-orange-500 text-white font-extrabold text-xs uppercase tracking-widest transition shadow-xs">
                                    Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Form 2: Ganti Password -->
                    <div class="bg-white rounded-2xl p-6 sm:p-8 border border-neutral-200 shadow-2xs space-y-6">
                        <div class="border-b border-neutral-100 pb-4">
                            <h2 class="text-base font-black text-neutral-900 uppercase tracking-tight">Keamanan Kata Sandi</h2>
                            <p class="text-xs text-neutral-500 font-medium mt-0.5">Ganti kata sandi akun Anda secara berkala untuk keamanan ekstra.</p>
                        </div>

                        @if (session('status') === 'password-updated')
                            <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-900 text-xs font-semibold flex items-center gap-2">
                                <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" /></svg>
                                <span>Password Anda berhasil diperbarui!</span>
                            </div>
                        @endif

                        <form method="post" action="{{ route('password.update') }}" class="space-y-4">
                            @csrf
                            @method('put')

                            <div>
                                <label for="update_password_current_password" class="block text-xs font-extrabold uppercase tracking-wider text-neutral-900 mb-1">Password Saat Ini</label>
                                <input id="update_password_current_password" name="current_password" type="password" autocomplete="current-password"
                                       class="w-full rounded-xl border-neutral-300 text-xs font-semibold p-3 focus:ring-neutral-900 bg-[#f8f8f8]">
                                @error('current_password', 'updatePassword')
                                    <p class="text-rose-500 text-[11px] font-bold mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="update_password_password" class="block text-xs font-extrabold uppercase tracking-wider text-neutral-900 mb-1">Password Baru</label>
                                <input id="update_password_password" name="password" type="password" autocomplete="new-password"
                                       class="w-full rounded-xl border-neutral-300 text-xs font-semibold p-3 focus:ring-neutral-900 bg-[#f8f8f8]">
                                @error('password', 'updatePassword')
                                    <p class="text-rose-500 text-[11px] font-bold mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="update_password_password_confirmation" class="block text-xs font-extrabold uppercase tracking-wider text-neutral-900 mb-1">Konfirmasi Password Baru</label>
                                <input id="update_password_password_confirmation" name="password_confirmation" type="password" autocomplete="new-password"
                                       class="w-full rounded-xl border-neutral-300 text-xs font-semibold p-3 focus:ring-neutral-900 bg-[#f8f8f8]">
                                @error('password_confirmation', 'updatePassword')
                                    <p class="text-rose-500 text-[11px] font-bold mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="pt-2">
                                <button type="submit" class="px-6 py-3 rounded-full bg-neutral-900 hover:bg-orange-500 text-white font-extrabold text-xs uppercase tracking-widest transition shadow-xs">
                                    Perbarui Password
                                </button>
                            </div>
                        </form>
                    </div>

                </div>

            </div>

        </div>
    </div>
@endif

@endsection
