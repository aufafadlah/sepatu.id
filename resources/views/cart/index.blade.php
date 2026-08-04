@extends('layouts.main')

@section('title', 'Keranjang Belanja — Sepatu.id')

@section('content')

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <h1 class="text-3xl font-black uppercase tracking-tight text-neutral-900 mb-8">
            Keranjang Belanja
        </h1>

        @if($carts->isEmpty())
            <div class="bg-[#f8f8f8] rounded-2xl p-12 border border-neutral-200 text-center max-w-md mx-auto">
                <h3 class="text-base font-bold uppercase tracking-wider text-neutral-900">Keranjang Belanja Kosong</h3>
                <p class="text-xs text-neutral-500 mt-1">Anda belum menambahkan produk sepatu ke keranjang belanja.</p>
                <a href="{{ route('catalog.index') }}" class="inline-block mt-6 px-8 py-3 rounded-full bg-neutral-900 text-white font-extrabold text-xs uppercase tracking-widest hover:bg-orange-500 transition">
                    Mulai Belanja
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                <!-- Cart Items List (Left) -->
                <div class="lg:col-span-8 space-y-4">
                    @foreach($carts as $cart)
                        <div class="bg-white rounded-2xl p-5 border border-neutral-200 shadow-2xs flex flex-col sm:flex-row items-center justify-between gap-4">
                            <div class="flex items-center gap-4 w-full sm:w-auto">
                                <div class="w-20 h-20 bg-[#f8f8f8] rounded-xl overflow-hidden flex-shrink-0 border border-neutral-200 p-2 flex items-center justify-center">
                                    @if($cart->productVariant->product->image ?? false)
                                        <img src="{{ Str::startsWith($cart->productVariant->product->image, ['http://', 'https://']) ? $cart->productVariant->product->image : asset('storage/' . $cart->productVariant->product->image) }}"
                                             class="max-h-full max-w-full object-contain">
                                    @else
                                        <img src="https://images.unsplash.com/photo-1542291026-7eec264c27ff?auto=format&fit=crop&w=300&q=80"
                                             class="max-h-full max-w-full object-contain">
                                    @endif
                                </div>
                                <div>
                                    <h4 class="font-black text-neutral-900 text-sm uppercase tracking-tight">
                                        <a href="{{ route('catalog.show', $cart->productVariant->product->slug) }}" class="hover:text-orange-500 transition">
                                            {{ $cart->productVariant->product->name }}
                                        </a>
                                    </h4>
                                    <p class="text-xs text-neutral-500 font-semibold uppercase">Ukuran (EUR): <span class="font-extrabold text-neutral-900">{{ $cart->productVariant->size }}</span></p>
                                    <p class="text-xs font-black text-neutral-900 mt-1">
                                        Rp {{ number_format($cart->productVariant->product->price, 0, ',', '.') }}
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-center justify-between sm:justify-end gap-6 w-full sm:w-auto border-t sm:border-t-0 border-neutral-100 pt-3 sm:pt-0">
                                <!-- Update Qty Form -->
                                <form action="{{ route('cart.update', $cart) }}" method="POST" class="flex items-center gap-2">
                                    @csrf
                                    @method('PATCH')
                                    <input type="number" name="qty" value="{{ $cart->qty }}" min="1" max="{{ $cart->productVariant->stock }}"
                                           onchange="this.form.submit()"
                                           class="w-16 text-center text-xs font-extrabold border-neutral-300 rounded-full bg-[#f8f8f8] py-1.5 focus:ring-neutral-900">
                                </form>

                                <!-- Subtotal -->
                                <div class="text-right">
                                    <span class="text-[10px] text-neutral-400 block font-bold uppercase tracking-wider">Subtotal</span>
                                    <span class="text-sm font-black text-neutral-900">
                                        Rp {{ number_format($cart->subtotal(), 0, ',', '.') }}
                                    </span>
                                </div>

                                <!-- Delete Button -->
                                <form action="{{ route('cart.destroy', $cart) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-rose-600 hover:text-rose-700 rounded-full transition">
                                        ✕
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Order Summary (Right) -->
                <div class="lg:col-span-4">
                    <div class="bg-white rounded-2xl p-6 border border-neutral-200 shadow-2xs space-y-6 sticky top-28">
                        <h3 class="text-base font-black text-neutral-900 uppercase tracking-wider border-b border-neutral-100 pb-4">
                            Ringkasan Belanja
                        </h3>

                        <div class="space-y-3 text-xs font-semibold uppercase">
                            <div class="flex justify-between text-neutral-600">
                                <span>Total Item ({{ $carts->sum('qty') }} pasang)</span>
                                <span class="font-black text-neutral-900">Rp {{ number_format($totalPrice, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-neutral-600">
                                <span>Estimasi Ongkos Kirim</span>
                                <span class="font-black text-emerald-600">GRATIS</span>
                            </div>
                        </div>

                        <div class="pt-4 border-t border-neutral-100 flex justify-between items-baseline">
                            <span class="text-xs font-extrabold uppercase text-neutral-900 tracking-wider">Total Pembayaran</span>
                            <span class="text-2xl font-black text-neutral-900">
                                Rp {{ number_format($totalPrice, 0, ',', '.') }}
                            </span>
                        </div>

                        <a href="{{ route('checkout.index') }}" class="block w-full py-4 rounded-full bg-neutral-900 hover:bg-orange-500 text-white font-extrabold text-xs uppercase tracking-widest text-center transition">
                            Lanjut ke Checkout ➔
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </div>

@endsection
