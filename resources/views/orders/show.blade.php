@extends('layouts.main')

@section('title', 'Detail Pesanan #' . $order->id . ' — Sepatu.id')

@section('content')

    <div x-data="{ confirmModal: false }" class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="mb-6 flex items-center justify-between">
            <a href="{{ route('orders.index') }}" class="text-xs font-extrabold uppercase tracking-wider text-neutral-600 hover:text-neutral-900 flex items-center gap-1.5 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                <span>Kembali ke Pesanan Saya</span>
            </a>
            <span class="text-xs font-extrabold text-neutral-400">Order ID: #{{ $order->id }}</span>
        </div>

        {{-- ========================================================= --}}
        {{-- BANNER 1: Auto-Cancel 5 Hari (Transfer Belum Bayar)        --}}
        {{-- ========================================================= --}}
        @if($order->payment_method === 'transfer' && $order->payment_status === \App\Models\Order::PAYMENT_UNPAID && $order->status === \App\Models\Order::STATUS_PENDING)
            @php $deadline = $order->created_at->addDays(5); $hoursLeft = now()->diffInHours($deadline, false); @endphp
            <div class="mb-6 p-4 rounded-2xl bg-amber-50 border-2 border-amber-400 shadow flex flex-col sm:flex-row items-start sm:items-center gap-4">
                <div class="flex items-center justify-center w-10 h-10 rounded-xl bg-amber-100 text-amber-700 shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
                <div class="flex-1 text-xs">
                    <p class="font-black text-amber-900 uppercase tracking-wide text-sm mb-0.5">⚠️ Batas Waktu Pembayaran</p>
                    <p class="text-amber-800 font-semibold leading-relaxed">
                        Harap selesaikan pembayaran transfer dan upload buktinya sebelum
                        <strong class="text-amber-950">{{ $deadline->isoFormat('dddd, D MMMM YYYY [pukul] HH:mm') }} WIB</strong>.
                        @if($hoursLeft > 0)
                            <span class="ml-1 inline-block bg-amber-200 text-amber-900 font-black px-2 py-0.5 rounded-full text-[11px]">
                                Sisa: {{ floor($hoursLeft / 24) > 0 ? floor($hoursLeft / 24) . ' hari ' : '' }}{{ $hoursLeft % 24 }} jam
                            </span>
                        @else
                            <span class="ml-1 inline-block bg-rose-200 text-rose-900 font-black px-2 py-0.5 rounded-full text-[11px]">Waktu Hampir Habis!</span>
                        @endif
                    </p>
                    <p class="text-amber-600 font-medium mt-1">Pesanan yang melampaui batas waktu akan <strong>otomatis dibatalkan</strong> oleh sistem dan stok akan dikembalikan.</p>
                </div>
                <a href="#upload-bukti" class="shrink-0 px-5 py-2.5 rounded-full bg-amber-500 hover:bg-amber-600 text-white font-extrabold text-xs uppercase tracking-wider transition">
                    Upload Sekarang
                </a>
            </div>
        @endif

        {{-- ========================================================= --}}
        {{-- BANNER 2: Pesanan Dibatalkan                               --}}
        {{-- ========================================================= --}}
        @if($order->status === 'dibatalkan')
            <div class="mb-6 p-4 rounded-2xl bg-rose-50 border-2 border-rose-300 flex items-start gap-4">
                <div class="w-10 h-10 rounded-xl bg-rose-100 text-rose-600 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </div>
                <div class="text-xs">
                    <p class="font-black text-rose-900 uppercase tracking-wide text-sm mb-0.5">Pesanan Dibatalkan</p>
                    <p class="text-rose-700 font-medium leading-relaxed">
                        Pesanan ini telah dibatalkan.
                        @if($order->payment_method === 'transfer')
                            Jika pembayaran sudah terlanjur dikirim, silakan hubungi kami via WhatsApp untuk proses pengembalian dana (refund).
                        @endif
                        Stok produk yang dipesan sudah dikembalikan ke gudang. Anda dapat berbelanja kembali kapan saja.
                    </p>
                    <a href="{{ route('catalog.index') }}" class="inline-flex items-center gap-1.5 mt-2 text-rose-700 font-bold hover:text-rose-900 underline transition">
                        Belanja Lagi →
                    </a>
                </div>
            </div>
        @endif

        <div class="bg-white rounded-2xl p-6 sm:p-10 border border-neutral-200 shadow-2xs space-y-8">
            <!-- Header Status -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-neutral-100 pb-6">
                <div>
                    <h1 class="text-2xl font-black uppercase tracking-tight text-neutral-900">Pesanan #{{ $order->id }}</h1>
                    <p class="text-xs text-neutral-500 font-semibold mt-0.5">Dibuat pada {{ $order->created_at->format('d F Y, H:i') }} WIB</p>
                </div>

                <div class="flex flex-wrap items-center gap-2">
                    @php
                        $statusClasses = [
                            'pending'     => 'bg-amber-100 text-amber-900 border-amber-300',
                            'diproses'    => 'bg-blue-100 text-blue-900 border-blue-300',
                            'dikirim'     => 'bg-indigo-100 text-indigo-900 border-indigo-300',
                            'selesai'     => 'bg-emerald-100 text-emerald-900 border-emerald-300',
                            'dibatalkan'  => 'bg-rose-100 text-rose-900 border-rose-300',
                        ];

                        $paymentClasses = [
                            'unpaid'               => 'bg-rose-100 text-rose-800 border-rose-300',
                            'pending_verification' => 'bg-amber-100 text-amber-900 border-amber-300',
                            'paid'                 => 'bg-emerald-100 text-emerald-800 border-emerald-300',
                        ];
                    @endphp
                    <span class="px-4 py-2 rounded-full text-xs font-extrabold border uppercase tracking-wider {{ $statusClasses[$order->status] ?? 'bg-neutral-100 text-neutral-800' }}">
                        Status: {{ $order->status }}
                    </span>

                    <span class="px-4 py-2 rounded-full text-xs font-extrabold border uppercase tracking-wider {{ $paymentClasses[$order->payment_status] ?? 'bg-neutral-100 text-neutral-800' }}">
                        Pembayaran: {{ $order->paymentStatusLabel() }}
                    </span>
                </div>
            </div>

            <!-- Items Table with Product Images & Direct Review Action -->
            <div>
                <h4 class="text-xs font-extrabold text-neutral-900 uppercase tracking-widest mb-4">Rincian Barang</h4>
                <div class="space-y-3">
                    @foreach($order->items as $item)
                        @php $prod = $item->productVariant->product ?? null; @endphp
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between p-4 rounded-xl bg-[#f8f8f8] border border-neutral-200 gap-4 text-xs">
                            <div class="flex items-center gap-4">
                                <div class="w-14 h-14 bg-white rounded-lg p-1 border border-neutral-200 flex items-center justify-center shrink-0">
                                    @if($prod && $prod->image)
                                        <img src="{{ Str::startsWith($prod->image, ['http://', 'https://']) ? $prod->image : asset('storage/' . $prod->image) }}" alt="{{ $prod->name }}" class="max-h-full max-w-full object-contain">
                                    @else
                                        <svg class="w-8 h-8 text-neutral-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                    @endif
                                </div>
                                <div>
                                    <h5 class="font-extrabold text-neutral-900 text-sm uppercase">
                                        @if($prod)
                                            <a href="{{ route('catalog.show', $prod->slug) }}" class="hover:text-orange-500 transition">
                                                {{ $prod->name }}
                                            </a>
                                        @else
                                            Produk Sepatu
                                        @endif
                                    </h5>
                                    <p class="text-neutral-500 font-semibold mt-0.5">
                                        Ukuran EUR {{ $item->productVariant->size ?? '-' }} × {{ $item->qty }} pasang
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center justify-between sm:justify-end gap-4 shrink-0">
                                <div class="text-right">
                                    <span class="text-neutral-400 block text-[10px] uppercase font-bold">Harga Satuan</span>
                                    <span class="font-black text-neutral-900">
                                        Rp {{ number_format($item->price_at_purchase, 0, ',', '.') }}
                                    </span>
                                </div>
                                @if($order->status === 'selesai' && $prod)
                                    <a href="{{ route('catalog.show', $prod->slug) }}#review-section" class="px-3.5 py-2 rounded-full bg-amber-50 hover:bg-amber-100 text-amber-900 font-extrabold text-[11px] border border-amber-200 transition flex items-center gap-1">
                                        <span>★ Beri Ulasan</span>
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Shipping Address & Payment Method & Optional Seller Notes -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
                <div class="p-5 rounded-xl bg-[#f8f8f8] border border-neutral-200 space-y-2.5">
                    <h4 class="font-extrabold text-neutral-900 uppercase tracking-wider">Informasi Penerima &amp; Alamat:</h4>
                    <div class="space-y-1">
                        <p class="text-neutral-900 font-bold text-sm">
                            {{ $order->recipient_name ?? $order->user->name }}
                        </p>
                        @if($order->recipient_phone || $order->user->phone)
                            <p class="text-neutral-600 font-medium">
                                Telp/WA: <strong>{{ $order->recipient_phone ?? $order->user->phone }}</strong>
                            </p>
                        @endif
                    </div>
                    <p class="text-neutral-700 leading-relaxed font-medium pt-1 border-t border-neutral-200/80">
                        {{ $order->shipping_address }}
                    </p>
                    @if($order->notes)
                        <div class="pt-2 border-t border-neutral-200 mt-2">
                            <span class="font-bold text-neutral-900 block">Pesan / Catatan Tambahan:</span>
                            <p class="text-neutral-600 italic mt-0.5">"{{ $order->notes }}"</p>
                        </div>
                    @endif
                </div>

                <div class="p-5 rounded-xl bg-[#f8f8f8] border border-neutral-200 space-y-2">
                    <h4 class="font-extrabold text-neutral-900 uppercase tracking-wider">Metode Pembayaran:</h4>
                    <p class="text-neutral-900 font-bold text-sm">{{ $order->paymentMethodLabel() }}</p>
                    @if($order->payment_method === 'transfer')
                        <div class="text-neutral-500 text-[11px] font-mono mt-1 space-y-0.5">
                            <p>BCA: 8830-1234-5678 (PT Sepatu Indonesia)</p>
                            <p>Mandiri: 137-000-9876-543</p>
                        </div>
                    @else
                        <p class="text-emerald-700 font-semibold text-[11px]">Bayar langsung ke kurir saat pengantaran barang.</p>
                    @endif
                </div>
            </div>

            <!-- Payment Upload / Verification Section -->
            @if($order->payment_method === 'transfer' && $order->status !== 'dibatalkan')
                <div id="upload-bukti" class="p-6 rounded-2xl border border-neutral-200 bg-[#fbfbfb] space-y-4">
                    <h4 class="text-xs font-black text-neutral-900 uppercase tracking-widest">Bukti Pembayaran Transfer</h4>
                    
                    @if($order->payment_proof)
                        <div class="space-y-3">
                            <div class="flex items-center gap-4">
                                <a href="{{ asset('storage/' . $order->payment_proof) }}" target="_blank" class="block w-32 h-32 rounded-xl overflow-hidden border border-neutral-300 hover:opacity-90 transition">
                                    <img src="{{ asset('storage/' . $order->payment_proof) }}" alt="Bukti Pembayaran" class="w-full h-full object-cover">
                                </a>
                                <div>
                                    <span class="text-xs font-extrabold text-emerald-600 flex items-center gap-1">
                                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" /></svg>
                                        <span>Bukti Transfer Telah Diupload</span>
                                    </span>
                                    <span class="text-[11px] text-neutral-400 block mt-1">Admin sedang memverifikasi pembayaran Anda.</span>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($order->payment_status !== \App\Models\Order::PAYMENT_PAID)
                        <form action="{{ route('orders.upload-proof', $order) }}" method="POST" enctype="multipart/form-data" class="space-y-3 pt-2">
                            @csrf
                            <label class="block text-xs font-bold text-neutral-700">
                                {{ $order->payment_proof ? 'Ganti Foto Bukti Pembayaran:' : 'Unggah Foto Bukti Transfer (JPG/PNG/WEBP, Max 3MB):' }}
                            </label>
                            <div class="flex items-center gap-3">
                                <input type="file" name="payment_proof" required accept="image/*" class="text-xs text-neutral-500 file:mr-4 file:py-2.5 file:px-5 file:rounded-full file:border-0 file:text-xs file:font-extrabold file:bg-neutral-900 file:text-white hover:file:bg-orange-500 transition cursor-pointer">
                                <button type="submit" class="px-6 py-2.5 rounded-full bg-orange-500 hover:bg-orange-600 text-white font-extrabold text-xs uppercase tracking-wider transition">
                                    Upload Bukti
                                </button>
                            </div>
                        </form>
                    @endif
                </div>
            @endif

            {{-- ========================================================= --}}
            {{-- SECTION: Tombol Pesanan Diterima — Logika yang Benar       --}}
            {{-- Hanya tampil jika status = 'dikirim' DAN payment = 'paid'  --}}
            {{-- ========================================================= --}}
            @if($order->status === 'dikirim' && $order->payment_status === \App\Models\Order::PAYMENT_PAID)
                {{-- Pesanan sedang dalam pengiriman dan sudah dibayar: Tampilkan tombol konfirmasi --}}
                <div class="p-6 rounded-2xl bg-indigo-50 border border-indigo-200 text-indigo-900 space-y-3 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div>
                        <h4 class="font-extrabold uppercase text-xs flex items-center gap-2">
                            <svg class="w-4 h-4 text-indigo-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            Pesanan Dalam Pengiriman
                        </h4>
                        <p class="text-xs text-indigo-700 font-medium mt-1">Jika sepatu pesanan Anda sudah tiba dan diterima dengan selamat, klik tombol konfirmasi untuk menyelesaikan pesanan & memberikan ulasan.</p>
                    </div>
                    <button type="button" @click="confirmModal = true" class="px-6 py-3.5 rounded-full bg-indigo-700 hover:bg-indigo-800 text-white text-xs font-extrabold uppercase tracking-widest transition shrink-0 flex items-center gap-1.5 shadow-md">
                        <span>Pesanan Sudah Diterima</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" /></svg>
                    </button>
                </div>

            @elseif($order->status === 'pending' || $order->status === 'diproses')
                {{-- Pesanan masih diproses: Informasi status perjalanan --}}
                <div class="p-5 rounded-2xl bg-blue-50 border border-blue-200 text-blue-900 flex items-start gap-3">
                    <div class="w-8 h-8 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center shrink-0 mt-0.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <div class="text-xs">
                        <p class="font-extrabold uppercase tracking-wide mb-0.5">Pesanan Sedang Disiapkan</p>
                        <p class="text-blue-700 font-medium leading-relaxed">Tombol konfirmasi terima barang akan aktif setelah pesanan dikirimkan oleh kurir dan pembayaran terverifikasi.</p>
                    </div>
                </div>

            @elseif($order->status === 'selesai')
                {{-- Pesanan sudah selesai --}}
                <div class="p-6 rounded-2xl bg-emerald-50/60 border border-emerald-200 text-emerald-900 space-y-2 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" /></svg>
                        <div>
                            <p class="font-extrabold text-xs uppercase">Pesanan Telah Selesai</p>
                            <p class="text-[11px] text-neutral-600 font-medium">Terima kasih! Anda dapat memberikan rating bintang dan ulasan opsional untuk produk yang Anda beli.</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Total Breakdown -->
            <div class="pt-4 border-t border-neutral-100 space-y-2 text-xs font-bold">
                @if($order->discount_amount > 0)
                    <div class="flex justify-between text-neutral-600 font-semibold">
                        <span>Subtotal Produk</span>
                        <span class="text-neutral-900 font-bold">Rp {{ number_format($order->subtotal ?? ($order->total + $order->discount_amount), 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-emerald-600">
                        <span class="flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 11h.01M7 15h.01M13 7h7M13 11h7M13 15h7" /></svg>
                            <span>Diskon Voucher ({{ $order->voucher_code }})</span>
                        </span>
                        <span>- Rp {{ number_format($order->discount_amount, 0, ',', '.') }}</span>
                    </div>
                @endif
                <div class="flex items-center justify-between text-neutral-900 pt-2 {{ $order->discount_amount > 0 ? 'border-t border-neutral-100' : '' }}">
                    <span class="text-xs font-extrabold uppercase tracking-wider">Total Tagihan Pembayaran:</span>
                    <span class="text-2xl font-black text-neutral-900">
                        Rp {{ number_format($order->total, 0, ',', '.') }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Custom UI Modal Konfirmasi Pesanan Diterima -->
        <div x-show="confirmModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-neutral-950/60 backdrop-blur-xs" @click.self="confirmModal = false">
            <div x-show="confirmModal"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 transform scale-95"
                 x-transition:enter-end="opacity-100 transform scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 transform scale-100"
                 x-transition:leave-end="opacity-0 transform scale-95"
                 class="bg-white rounded-3xl p-6 sm:p-8 max-w-md w-full shadow-2xl border border-neutral-200 text-center space-y-5">
            
                <!-- Icon -->
                <div class="w-16 h-16 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center mx-auto shadow-inner">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                    </svg>
                </div>

                <!-- Content -->
                <div class="space-y-2">
                    <h3 class="text-lg font-black uppercase tracking-tight text-neutral-900">
                        Konfirmasi Pesanan Diterima
                    </h3>
                    <p class="text-xs text-neutral-600 font-medium leading-relaxed">
                        Apakah paket sepatu untuk <span class="font-bold text-neutral-900">Pesanan #{{ $order->id }}</span> sudah Anda terima dengan kondisi baik?
                    </p>
                    <div class="p-3 bg-emerald-50 border border-emerald-200 rounded-xl text-[11px] text-emerald-800 text-left font-medium flex items-start gap-2 mt-2">
                        <svg class="w-4 h-4 text-emerald-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        <span>Setelah dikonfirmasi, status pesanan otomatis berubah menjadi <strong>Selesai</strong> dan Anda dapat memberikan ulasan bintang &amp; foto/video produk.</span>
                    </div>
                </div>

                <!-- Action Buttons -->
                <form action="{{ route('orders.confirm-received', $order) }}" method="POST" class="pt-2 flex flex-col sm:flex-row items-center gap-3">
                    @csrf
                    <button type="button" @click="confirmModal = false" class="w-full sm:w-1/2 py-3 px-4 rounded-full border border-neutral-300 text-neutral-700 font-bold text-xs uppercase tracking-wider hover:bg-neutral-100 transition">
                        Belum Diterima
                    </button>
                    <button type="submit" class="w-full sm:w-1/2 py-3 px-4 rounded-full bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-xs uppercase tracking-wider transition shadow-md flex items-center justify-center gap-1.5">
                        <span>Ya, Diterima</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" /></svg>
                    </button>
                </form>

            </div>
        </div>

    </div>

@endsection
