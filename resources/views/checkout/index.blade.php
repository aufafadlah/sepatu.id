@extends('layouts.main')

@section('title', 'Checkout Pesanan — Sepatu.id')

@section('content')

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <h1 class="text-3xl font-black uppercase tracking-tight text-neutral-900 mb-8">
            Checkout Pesanan
        </h1>

        <form action="{{ route('checkout.store') }}" method="POST" class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            @csrf

            <!-- Form Alamat Pengiriman (Left) -->
            <div class="lg:col-span-7 bg-white rounded-2xl p-6 sm:p-8 border border-neutral-200 shadow-2xs space-y-6">
                <h3 class="text-base font-black uppercase tracking-wider text-neutral-900 border-b border-neutral-100 pb-4">
                    Alamat Pengiriman &amp; Kontak
                </h3>

                <div class="space-y-4 text-xs font-semibold">
                    <div>
                        <label class="block uppercase tracking-wider text-neutral-600 mb-1">Nama Penerima:</label>
                        <input type="text" value="{{ auth()->user()->name }}" disabled class="w-full rounded-xl border-neutral-200 bg-[#f8f8f8] font-bold p-3">
                    </div>

                    <div>
                        <label class="block uppercase tracking-wider text-neutral-600 mb-1">Email Pengirim:</label>
                        <input type="email" value="{{ auth()->user()->email }}" disabled class="w-full rounded-xl border-neutral-200 bg-[#f8f8f8] font-bold p-3">
                    </div>

                    <div>
                        <label for="shipping_address" class="block uppercase tracking-wider text-neutral-900 font-extrabold mb-1">Alamat Lengkap Pengiriman <span class="text-rose-500">*</span></label>
                        <textarea id="shipping_address" name="shipping_address" rows="4" required
                                  placeholder="Tuliskan nama jalan, nomor rumah, RT/RW, kelurahan, kecamatan, kota/kabupaten, serta kode pos..."
                                  class="w-full rounded-xl border-neutral-300 focus:ring-neutral-900 focus:border-neutral-900 p-3 font-medium">{{ old('shipping_address') }}</textarea>
                    </div>
                </div>

                <div class="p-4 rounded-xl bg-[#f8f8f8] border border-neutral-200 text-neutral-800 text-xs space-y-1">
                    <p class="font-bold uppercase tracking-wider">💳 Pembayaran (Simulasi Retail):</p>
                    <p class="text-neutral-500 font-medium">Pesanan dikonfirmasi otomatis saat dibuat (Status: <strong>Pending / Diproses</strong>).</p>
                </div>
            </div>

            <!-- Ringkasan Order & Konfirmasi (Right) -->
            <div class="lg:col-span-5 bg-white rounded-2xl p-6 sm:p-8 border border-neutral-200 shadow-2xs space-y-6 flex flex-col justify-between">
                <div>
                    <h3 class="text-base font-black uppercase tracking-wider text-neutral-900 border-b border-neutral-100 pb-4">
                        Rincian Barang
                    </h3>

                    <div class="divide-y divide-neutral-100 max-h-72 overflow-y-auto pr-2">
                        @foreach($carts as $cart)
                            <div class="py-3 flex items-center justify-between text-xs font-semibold">
                                <div>
                                    <p class="font-extrabold uppercase text-neutral-900">{{ $cart->productVariant->product->name }}</p>
                                    <p class="text-neutral-400">Ukuran {{ $cart->productVariant->size }} × {{ $cart->qty }} pasang</p>
                                </div>
                                <span class="font-black text-neutral-900">
                                    Rp {{ number_format($cart->subtotal(), 0, ',', '.') }}
                                </span>
                            </div>
                        @endforeach
                    </div>

                    <div class="pt-4 border-t border-neutral-100 space-y-2 text-xs font-semibold uppercase">
                        <div class="flex justify-between text-neutral-600">
                            <span>Subtotal Produk</span>
                            <span class="font-black text-neutral-900">Rp {{ number_format($totalPrice, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-neutral-600">
                            <span>Ongkos Kirim</span>
                            <span class="font-black text-emerald-600">GRATIS</span>
                        </div>
                        <div class="flex justify-between text-sm font-black text-neutral-900 pt-3 border-t border-neutral-100">
                            <span>Total Tagihan</span>
                            <span>Rp {{ number_format($totalPrice, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                <button type="submit" class="w-full py-4 rounded-full bg-neutral-900 hover:bg-orange-500 text-white font-extrabold text-xs uppercase tracking-widest text-center transition">
                    Konfirmasi &amp; Buat Pesanan
                </button>
            </div>
        </form>
    </div>

@endsection
