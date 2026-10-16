@extends('layouts.main')

@section('title', 'Checkout Pesanan — Sepatu.id')

@section('content')

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <h1 class="text-3xl font-black uppercase tracking-tight text-neutral-900 mb-8">
            Checkout Pesanan
        </h1>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8" x-data="{ paymentMethod: 'transfer' }">

            <!-- Form Alamat & Kontak Pengiriman (Left) -->
            <div class="lg:col-span-7 space-y-6">

                <form id="checkout-form" action="{{ route('checkout.store') }}" method="POST" class="bg-white rounded-2xl p-6 sm:p-8 border border-neutral-200 shadow-2xs space-y-6">
                    @csrf
                    @if($appliedVoucher)
                        <input type="hidden" name="voucher_code" value="{{ $appliedVoucher->code }}">
                    @endif
                    @foreach($carts as $c)
                        <input type="hidden" name="selected[]" value="{{ $c->id }}">
                    @endforeach

                    <div class="border-b border-neutral-100 pb-4 flex items-center justify-between">
                        <div>
                            <h3 class="text-base font-black uppercase tracking-wider text-neutral-900">
                                1. Kontak &amp; Alamat Penerima
                            </h3>
                            <p class="text-xs text-neutral-500 font-medium mt-0.5">Informasi penerima paket dapat disesuaikan tanpa mengubah data akun profil Anda.</p>
                        </div>
                    </div>

                    <div class="space-y-4 text-xs font-semibold">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <!-- Nama Penerima -->
                            <div>
                                <label for="recipient_name" class="block uppercase tracking-wider text-neutral-900 font-extrabold mb-1">
                                    Nama Penerima <span class="text-rose-500">*</span>
                                </label>
                                <input type="text" id="recipient_name" name="recipient_name" required
                                       value="{{ old('recipient_name', auth()->user()->name) }}"
                                       placeholder="Nama lengkap penerima..."
                                       class="w-full rounded-xl border-neutral-300 focus:ring-neutral-900 focus:border-neutral-900 p-3 font-bold bg-[#fcfcfc]">
                                @error('recipient_name')
                                    <p class="text-rose-500 text-[11px] font-bold mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Nomor Telepon / WA Penerima -->
                            <div>
                                <label for="recipient_phone" class="block uppercase tracking-wider text-neutral-900 font-extrabold mb-1">
                                    No. HP / WhatsApp <span class="text-rose-500">*</span>
                                </label>
                                <input type="text" id="recipient_phone" name="recipient_phone" required
                                       value="{{ old('recipient_phone', auth()->user()->phone) }}"
                                       placeholder="Contoh: 081234567890"
                                       maxlength="13"
                                       oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 13)"
                                       class="w-full rounded-xl border-neutral-300 focus:ring-neutral-900 focus:border-neutral-900 p-3 font-bold bg-[#fcfcfc]">
                                @error('recipient_phone')
                                    <p class="text-rose-500 text-[11px] font-bold mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Email Akun Pengorder -->
                        <div>
                            <label class="block uppercase tracking-wider text-neutral-500 mb-1">Email Akun Pengorder:</label>
                            <input type="email" value="{{ auth()->user()->email }}" disabled class="w-full rounded-xl border-neutral-200 bg-[#f4f4f4] text-neutral-500 font-medium p-3">
                        </div>

                        <!-- Alamat Lengkap Pengiriman -->
                        <div>
                            <x-address-input name="shipping_address" :value="old('shipping_address', auth()->user()->address)" />
                        </div>

                        <!-- Catatan Tambahan -->
                        <div>
                            <label for="notes" class="block uppercase tracking-wider text-neutral-900 font-extrabold mb-1">
                                Pesan / Catatan Tambahan <span class="text-neutral-400 font-normal">(Opsional)</span>
                            </label>
                            <textarea id="notes" name="notes" rows="2"
                                      placeholder="Contoh: Titipkan di pos satpam, atau warna tali alternatif..."
                                      class="w-full rounded-xl border-neutral-300 focus:ring-neutral-900 focus:border-neutral-900 p-3 font-medium text-xs">{{ old('notes') }}</textarea>
                        </div>
                    </div>

                    <!-- Metode Pembayaran -->
                    <div class="pt-6 border-t border-neutral-100 space-y-4">
                        <h3 class="text-base font-black uppercase tracking-wider text-neutral-900">
                            2. Metode Pembayaran <span class="text-rose-500">*</span>
                        </h3>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs font-bold">
                            <!-- Transfer Bank -->
                            <label @click="paymentMethod = 'transfer'"
                                   :class="paymentMethod === 'transfer' ? 'border-neutral-900 bg-neutral-900 text-white' : 'border-neutral-200 bg-white text-neutral-800 hover:border-neutral-400'"
                                   class="p-4 rounded-xl border cursor-pointer transition flex items-center gap-3">
                                <input type="radio" name="payment_method" value="transfer" x-model="paymentMethod" class="sr-only">
                                <div class="w-4 h-4 rounded-full border-2 flex items-center justify-center shrink-0" :class="paymentMethod === 'transfer' ? 'border-white' : 'border-neutral-400'">
                                    <div x-show="paymentMethod === 'transfer'" class="w-2 h-2 rounded-full bg-orange-500"></div>
                                </div>
                                <div>
                                    <p class="uppercase tracking-wider">Transfer Bank</p>
                                    <p class="text-[10px] font-normal opacity-80 mt-0.5">BCA / Mandiri / BNI / BRI</p>
                                </div>
                            </label>

                            <!-- COD -->
                            <label @click="paymentMethod = 'cod'"
                                   :class="paymentMethod === 'cod' ? 'border-neutral-900 bg-neutral-900 text-white' : 'border-neutral-200 bg-white text-neutral-800 hover:border-neutral-400'"
                                   class="p-4 rounded-xl border cursor-pointer transition flex items-center gap-3">
                                <input type="radio" name="payment_method" value="cod" x-model="paymentMethod" class="sr-only">
                                <div class="w-4 h-4 rounded-full border-2 flex items-center justify-center shrink-0" :class="paymentMethod === 'cod' ? 'border-white' : 'border-neutral-400'">
                                    <div x-show="paymentMethod === 'cod'" class="w-2 h-2 rounded-full bg-orange-500"></div>
                                </div>
                                <div>
                                    <p class="uppercase tracking-wider">Bayar di Tempat (COD)</p>
                                    <p class="text-[10px] font-normal opacity-80 mt-0.5">Bayar saat kurir mengantar</p>
                                </div>
                            </label>
                        </div>

                        <!-- Payment Info Box -->
                        <div x-show="paymentMethod === 'transfer'" class="p-4 rounded-xl bg-[#f8f8f8] border border-neutral-200 text-neutral-800 text-xs space-y-2">
                            <p class="font-bold uppercase tracking-wider text-orange-600 flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-orange-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                <span>Rekening Pembayaran Resmi Sepatu.id:</span>
                            </p>
                            <div class="space-y-1 font-mono text-[11px] text-neutral-700 bg-white p-3 rounded-lg border border-neutral-200">
                                <p><strong>Bank BCA:</strong> 8830-1234-5678 (a.n. PT Sepatu Indonesia)</p>
                                <p><strong>Bank Mandiri:</strong> 137-000-9876-543 (a.n. PT Sepatu Indonesia)</p>
                            </div>
                            <p class="text-neutral-500 text-[11px]">Setelah pesanan dibuat, Anda dapat mengunggah bukti transfer di halaman riwayat pesanan.</p>
                        </div>

                        <div x-show="paymentMethod === 'cod'" class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-900 text-xs space-y-1">
                            <p class="font-bold uppercase tracking-wider flex items-center gap-1.5 text-emerald-800">
                                <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                <span>Metode COD Dipilih</span>
                            </p>
                            <p class="text-neutral-600 text-[11px]">Siapkan uang pas sesuai total tagihan saat kurir tiba di alamat Anda.</p>
                        </div>
                    </div>
                </form>

            </div>

            <!-- Ringkasan Order & Voucher (Right) -->
            <div class="lg:col-span-5 space-y-6">

                <!-- Form Klaim Kode Voucher -->
                <div class="bg-white rounded-2xl p-6 border border-neutral-200 shadow-2xs space-y-4">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-orange-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                        </svg>
                        <h3 class="text-sm font-black uppercase tracking-wider text-neutral-900">
                            Voucher Diskon &amp; Promo
                        </h3>
                    </div>

                    @if($appliedVoucher)
                        <!-- Active Voucher Display -->
                        <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200/80 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" /></svg>
                                </div>
                                <div>
                                    <p class="text-xs font-black text-emerald-950 font-mono tracking-wider">{{ $appliedVoucher->code }}</p>
                                    <p class="text-[11px] text-emerald-700 font-medium">{{ $appliedVoucher->name }} (Hemat Rp {{ number_format($discountAmount, 0, ',', '.') }})</p>
                                </div>
                            </div>
                            <form action="{{ route('checkout.remove-voucher') }}" method="POST">
                                @csrf
                                <button type="submit" class="p-1.5 rounded-lg text-rose-600 hover:bg-rose-100 transition" title="Hapus Voucher">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                </button>
                            </form>
                        </div>
                    @else
                        <!-- Voucher Input Form -->
                        <form action="{{ route('checkout.apply-voucher') }}" method="POST" class="flex gap-2">
                            @csrf
                            <div class="relative flex-grow">
                                <input type="text" name="voucher_code" placeholder="Masukkan kode voucher..." required
                                       class="w-full uppercase rounded-xl border-neutral-300 text-xs font-bold p-3 focus:ring-neutral-900 focus:border-neutral-900 bg-[#f8f8f8] placeholder:normal-case placeholder:font-normal">
                            </div>
                            <button type="submit" class="px-5 py-3 rounded-xl bg-neutral-900 hover:bg-orange-500 text-white font-extrabold text-xs uppercase tracking-wider transition shrink-0 shadow-2xs">
                                Terapkan
                            </button>
                        </form>
                    @endif
                </div>

                <!-- Rincian Barang & Total Tagihan -->
                <div class="bg-white rounded-2xl p-6 sm:p-8 border border-neutral-200 shadow-2xs space-y-6">
                    <div class="border-b border-neutral-100 pb-4 flex items-center justify-between">
                        <h3 class="text-base font-black uppercase tracking-wider text-neutral-900">
                            Rincian Pesanan
                        </h3>
                        <a href="{{ route('cart.index') }}" class="text-xs text-orange-600 font-extrabold hover:underline">
                            ← Ubah Pilihan
                        </a>
                    </div>

                    <div class="divide-y divide-neutral-100 max-h-64 overflow-y-auto pr-1">
                        @foreach($carts as $cart)
                            <div class="py-3 flex items-center gap-3 text-xs font-semibold">
                                <div class="w-12 h-12 bg-neutral-100 rounded-lg flex items-center justify-center overflow-hidden shrink-0 border border-neutral-200/60 p-1">
                                    @if($cart->productVariant->product->image)
                                        <img src="{{ Str::startsWith($cart->productVariant->product->image, ['http://', 'https://']) ? $cart->productVariant->product->image : asset('storage/' . $cart->productVariant->product->image) }}" class="object-contain max-h-full max-w-full">
                                    @else
                                        <span class="text-[10px] text-neutral-400">Sepatu</span>
                                    @endif
                                </div>
                                <div class="flex-grow min-w-0">
                                    <p class="font-extrabold uppercase text-neutral-900 truncate">{{ $cart->productVariant->product->name }}</p>
                                    <p class="text-neutral-400">Ukuran EUR {{ $cart->productVariant->size }} × {{ $cart->qty }} pasang</p>
                                </div>
                                <span class="font-black text-neutral-900 shrink-0">
                                    Rp {{ number_format($cart->subtotal(), 0, ',', '.') }}
                                </span>
                            </div>
                        @endforeach
                    </div>

                    <!-- Breakdown Calculation -->
                    <div class="pt-4 border-t border-neutral-100 space-y-2.5 text-xs font-semibold uppercase">
                        <div class="flex justify-between text-neutral-600">
                            <span>Subtotal Produk</span>
                            <span class="font-black text-neutral-900">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>

                        @if($discountAmount > 0)
                            <div class="flex justify-between text-emerald-600 font-bold">
                                <span class="flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 11h.01M7 15h.01M13 7h7M13 11h7M13 15h7" /></svg>
                                    <span>Diskon ({{ $appliedVoucher->code ?? 'Voucher' }})</span>
                                </span>
                                <span>- Rp {{ number_format($discountAmount, 0, ',', '.') }}</span>
                            </div>
                        @endif

                        <div class="flex justify-between text-neutral-600">
                            <span>Ongkos Kirim</span>
                            <span class="font-black text-emerald-600">GRATIS</span>
                        </div>

                        <div class="flex justify-between items-center text-sm font-black text-neutral-900 pt-3 border-t border-neutral-100">
                            <span>Total Tagihan Akhir</span>
                            <span class="text-lg text-orange-600">Rp {{ number_format($totalPrice, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <button type="submit" form="checkout-form" class="w-full py-4 rounded-full bg-neutral-900 hover:bg-orange-500 text-white font-extrabold text-xs uppercase tracking-widest text-center transition shadow-md">
                        Konfirmasi &amp; Buat Pesanan
                    </button>
                </div>

            </div>
        </div>
    </div>

@endsection
