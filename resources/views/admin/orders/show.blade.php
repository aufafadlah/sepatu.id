@extends('layouts.admin')

@section('title', 'Detail Pesanan #' . $order->id . ' — Admin Sepatu.id')
@section('page_title', 'Detail & Verifikasi Pesanan')

@section('content')

    <div class="max-w-4xl mx-auto space-y-6" x-data="{ imgModal: false, imgUrl: '', confirmCompleteModal: false }">

        <div class="flex items-center justify-between">
            <a href="{{ route('admin.pesanan.index') }}" class="text-xs font-semibold text-slate-500 hover:text-slate-900 flex items-center gap-1.5 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                <span>Kembali ke Daftar Pesanan</span>
            </a>
            <a href="{{ route('admin.pesanan.print-address', $order) }}" target="_blank" class="px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold text-xs transition shadow-2xs flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
                <span>Cetak Alamat Label</span>
            </a>
        </div>

        <div class="bg-white rounded-2xl p-6 sm:p-8 border border-slate-200/80 shadow-2xs space-y-8">
            <!-- Header Info & Status Controls -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-slate-100 pb-6">
                <div>
                    <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Pesanan #{{ $order->id }}</h2>
                    <p class="text-xs text-slate-500 font-medium mt-0.5">
                        Pemesan: <strong class="text-slate-900">{{ $order->user->name ?? 'Pelanggan' }}</strong> ({{ $order->user->email ?? '' }}) • {{ $order->created_at->format('d M Y, H:i') }} WIB
                    </p>
                </div>

                <div class="flex flex-wrap items-center gap-3">
                    <!-- Admin Quick Button Konfirmasi Selesai -->
                    @if($order->status !== 'selesai')
                        <button type="button" @click="confirmCompleteModal = true" class="px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-xs transition shadow-2xs flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" /></svg>
                            <span>Konfirmasi Pesanan Selesai</span>
                        </button>
                    @endif

                    <!-- Dropdown Select Status Pesanan -->
                    <form action="{{ route('admin.pesanan.update-status', $order) }}" method="POST" class="flex items-center gap-2">
                        @csrf
                        @method('PATCH')
                        <label class="text-xs font-bold uppercase text-slate-500">Status:</label>
                        <select name="status" onchange="this.form.submit()" class="text-xs font-bold uppercase border border-slate-200 rounded-xl bg-slate-50 py-2 px-3 focus:ring-indigo-500 text-slate-900">
                            <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="diproses" {{ $order->status == 'diproses' ? 'selected' : '' }}>Diproses</option>
                            <option value="dikirim" {{ $order->status == 'dikirim' ? 'selected' : '' }}>Dikirim</option>
                            <option value="selesai" {{ $order->status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                        </select>
                    </form>
                </div>
            </div>

            <!-- Items list with product images -->
            <div>
                <h4 class="text-xs font-bold uppercase tracking-wider text-slate-900 mb-4">Barang yang Dipesan</h4>
                <div class="space-y-3">
                    @foreach($order->items as $item)
                        @php $prod = $item->productVariant->product ?? null; @endphp
                        <div class="flex items-center justify-between p-4 rounded-xl bg-slate-50 border border-slate-200/60 text-xs font-medium">
                            <div class="flex items-center gap-4">
                                <div class="w-14 h-14 bg-white rounded-lg p-1 border border-slate-200 flex items-center justify-center shrink-0">
                                    @if($prod && $prod->image)
                                        <img src="{{ Str::startsWith($prod->image, ['http://', 'https://']) ? $prod->image : asset('storage/' . $prod->image) }}" alt="{{ $prod->name }}" class="max-h-full max-w-full object-contain">
                                    @else
                                        <svg class="w-7 h-7 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                    @endif
                                </div>
                                <div>
                                    <h5 class="font-bold text-slate-900 text-sm">
                                        {{ $prod->name ?? 'Produk Sepatu' }}
                                    </h5>
                                    <p class="text-slate-500 mt-0.5">
                                        Ukuran EUR {{ $item->productVariant->size ?? '-' }} × {{ $item->qty }} pasang
                                    </p>
                                </div>
                            </div>
                            <div class="text-right shrink-0">
                                <span class="text-slate-400 block text-[10px] uppercase font-bold">Subtotal</span>
                                <span class="font-bold text-slate-900">
                                    Rp {{ number_format($item->subtotal(), 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Payment & Verification Box (Bukti Transfer) -->
            <div id="bukti-transfer" class="p-6 rounded-2xl border border-indigo-100 bg-indigo-50/40 space-y-4">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-indigo-100/80 pb-4">
                    <div>
                        <h4 class="text-xs font-bold uppercase tracking-wider text-indigo-950 flex items-center gap-2">
                            <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                            <span>Status &amp; Verifikasi Pembayaran</span>
                        </h4>
                        <p class="text-[11px] text-slate-500 font-medium mt-0.5">Metode: <strong>{{ $order->paymentMethodLabel() }}</strong></p>
                    </div>

                    <!-- Update Payment Status Form -->
                    <form action="{{ route('admin.pesanan.update-payment-status', $order) }}" method="POST" class="flex items-center gap-2">
                        @csrf
                        @method('PATCH')
                        <label class="text-xs font-bold uppercase text-slate-600">Status TF:</label>
                        <select name="payment_status" onchange="this.form.submit()" class="text-xs font-bold uppercase border border-slate-300 rounded-xl bg-white py-1.5 px-3 focus:ring-indigo-600 text-slate-900">
                            <option value="unpaid" {{ $order->payment_status == 'unpaid' ? 'selected' : '' }}>Belum Dibayar</option>
                            <option value="pending_verification" {{ $order->payment_status == 'pending_verification' ? 'selected' : '' }}>Menunggu Verifikasi TF</option>
                            <option value="paid" {{ $order->payment_status == 'paid' ? 'selected' : '' }}>Sudah Lunas (Paid)</option>
                        </select>
                    </form>
                </div>

                <!-- Display Transfer Proof Picture -->
                @if($order->payment_proof)
                    <div class="space-y-3 pt-2">
                        <span class="text-xs font-bold text-slate-700 block">Bukti Transfer Yang Diunggah Pelanggan:</span>
                        <div class="flex items-start gap-4">
                            <div class="relative group cursor-pointer" @click="imgModal = true; imgUrl = '{{ asset('storage/' . $order->payment_proof) }}'">
                                <img src="{{ asset('storage/' . $order->payment_proof) }}" alt="Bukti Transfer Pelanggan" class="w-44 h-44 object-cover rounded-xl border border-slate-300 shadow-xs group-hover:opacity-90 transition">
                                <div class="absolute inset-0 bg-slate-900/40 opacity-0 group-hover:opacity-100 transition rounded-xl flex items-center justify-center text-white text-xs font-bold gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7" /></svg>
                                    <span>Perbesar</span>
                                </div>
                            </div>
                            <div class="space-y-2 text-xs font-medium text-slate-600">
                                <p class="text-emerald-700 font-bold flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    <span>File foto terverifikasi di server</span>
                                </p>
                                <a href="{{ asset('storage/' . $order->payment_proof) }}" target="_blank" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-white border border-slate-300 text-slate-800 font-bold hover:bg-slate-50 transition">
                                    <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" /></svg>
                                    <span>Buka Foto Ukuran Penuh</span>
                                </a>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="p-4 rounded-xl bg-white border border-slate-200 text-slate-500 text-xs font-medium italic flex items-center gap-2">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        <span>Pelanggan belum mengunggah foto bukti transfer untuk pesanan ini.</span>
                    </div>
                @endif
            </div>

            <!-- Shipping Address & Optional Notes -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
                <div class="p-5 rounded-xl bg-slate-50 border border-slate-200/60 space-y-2.5">
                    <h4 class="font-bold text-slate-900 uppercase tracking-wider">Informasi Penerima &amp; Alamat Kirim:</h4>
                    <div class="space-y-1">
                        <p class="text-slate-900 font-bold text-sm">
                            {{ $order->recipient_name ?? $order->user->name }}
                        </p>
                        @if($order->recipient_phone || $order->user->phone)
                            <p class="text-slate-600 font-medium">
                                No. Telp / WA: <strong class="text-slate-900">{{ $order->recipient_phone ?? $order->user->phone }}</strong>
                            </p>
                        @endif
                    </div>
                    <p class="text-slate-700 leading-relaxed font-medium pt-1.5 border-t border-slate-200">
                        {{ $order->shipping_address }}
                    </p>
                </div>

                <div class="p-5 rounded-xl bg-slate-50 border border-slate-200/60 space-y-2">
                    <h4 class="font-bold text-slate-900 uppercase tracking-wider">Pesan / Catatan Tambahan Pembeli:</h4>
                    @if($order->notes)
                        <p class="text-slate-800 leading-relaxed italic font-medium bg-white p-3 rounded-lg border border-slate-200">
                            "{{ $order->notes }}"
                        </p>
                    @else
                        <p class="text-slate-400 italic">Tidak ada catatan pesan dari pembeli.</p>
                    @endif
                </div>
            </div>

            <!-- Total Payment & Discount Breakdown -->
            <div class="pt-4 border-t border-slate-100 space-y-2 text-xs">
                @if($order->discount_amount > 0)
                    <div class="flex justify-between text-slate-600 font-medium">
                        <span>Subtotal Produk</span>
                        <span class="text-slate-900 font-bold">Rp {{ number_format($order->subtotal ?? ($order->total + $order->discount_amount), 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-emerald-600 font-bold">
                        <span class="flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 11h.01M7 15h.01M13 7h7M13 11h7M13 15h7" /></svg>
                            <span>Diskon Voucher ({{ $order->voucher_code }})</span>
                        </span>
                        <span>- Rp {{ number_format($order->discount_amount, 0, ',', '.') }}</span>
                    </div>
                @endif
                <div class="flex items-center justify-between text-slate-900 pt-2 {{ $order->discount_amount > 0 ? 'border-t border-slate-100' : '' }}">
                    <span class="text-xs font-bold uppercase tracking-wider">Total Pembayaran:</span>
                    <span class="text-2xl font-black">
                        Rp {{ number_format($order->total, 0, ',', '.') }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Image Zoom Modal -->
        <div x-show="imgModal" x-cloak class="fixed inset-0 z-50 bg-slate-950/80 backdrop-blur-xs flex items-center justify-center p-4" @click.self="imgModal = false">
            <div class="relative max-w-3xl max-h-[90vh] bg-white rounded-2xl overflow-hidden p-2 shadow-2xl space-y-2">
                <div class="flex items-center justify-between px-4 py-2 border-b border-slate-100">
                    <span class="text-xs font-bold text-slate-800 uppercase tracking-wider">Bukti Transfer #{{ $order->id }}</span>
                    <button @click="imgModal = false" class="p-1 rounded-lg text-slate-400 hover:text-slate-800 hover:bg-slate-100 transition" title="Tutup">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
                <div class="max-h-[75vh] overflow-auto flex items-center justify-center">
                    <img :src="imgUrl" alt="Bukti Transfer" class="max-h-[75vh] object-contain rounded-xl">
                </div>
            </div>
        </div>

        <!-- Modal Konfirmasi Admin Selesaikan Pesanan -->
        <div x-show="confirmCompleteModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs" @click.self="confirmCompleteModal = false">
            <div x-show="confirmCompleteModal"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 transform scale-95"
                 x-transition:enter-end="opacity-100 transform scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 transform scale-100"
                 x-transition:leave-end="opacity-0 transform scale-95"
                 class="bg-white rounded-3xl p-6 sm:p-8 max-w-md w-full shadow-2xl border border-slate-200 text-center space-y-5">
                
                <div class="w-16 h-16 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center mx-auto shadow-inner">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                    </svg>
                </div>

                <div class="space-y-2">
                    <h3 class="text-lg font-bold text-slate-900">
                        Konfirmasi Selesaikan Pesanan
                    </h3>
                    <p class="text-xs text-slate-500 font-medium leading-relaxed">
                        Tandai <strong>Pesanan #{{ $order->id }}</strong> atas nama <strong class="text-slate-900">{{ $order->user->name ?? 'Pelanggan' }}</strong> sebagai <strong>Selesai</strong>?
                    </p>
                    <div class="p-3 bg-emerald-50 border border-emerald-200 rounded-xl text-[11px] text-emerald-900 text-left font-medium">
                        Pesanan akan tercatat sebagai transaksi sukses dan pelanggan dapat mengisi review ulasan produk.
                    </div>
                </div>

                <form action="{{ route('admin.pesanan.update-status', $order) }}" method="POST" class="pt-2 flex flex-col sm:flex-row items-center gap-3">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status" value="selesai">
                    <button type="button" @click="confirmCompleteModal = false" class="w-full sm:w-1/2 py-2.5 px-4 rounded-xl border border-slate-300 text-slate-700 font-bold text-xs hover:bg-slate-50 transition">
                        Batal
                    </button>
                    <button type="submit" class="w-full sm:w-1/2 py-2.5 px-4 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-xs transition shadow-md flex items-center justify-center gap-1.5">
                        <span>Ya, Selesaikan</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" /></svg>
                    </button>
                </form>
            </div>
        </div>

    </div>

@endsection
