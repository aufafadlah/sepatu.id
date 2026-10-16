@extends('layouts.main')

@section('title', 'Keranjang Belanja — Sepatu.id')

@section('content')

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
            <div>
                <h1 class="text-3xl font-black uppercase tracking-tight text-neutral-900">
                    Keranjang Belanja
                </h1>
                <p class="text-xs text-neutral-500 font-medium mt-1">
                    Pilih produk yang ingin Anda checkout sekarang. Produk yang tidak dicentang akan tetap aman tersimpan di keranjang.
                </p>
            </div>
            <a href="{{ route('catalog.index') }}" class="inline-flex items-center gap-2 text-xs font-extrabold uppercase tracking-wider text-orange-600 hover:text-orange-700 transition">
                <span>← Lanjut Belanja</span>
            </a>
        </div>

        @if($carts->isEmpty())
            <div class="bg-[#f8f8f8] rounded-3xl p-12 border border-neutral-200 text-center max-w-md mx-auto space-y-4">
                <div class="w-16 h-16 rounded-full bg-neutral-200/60 flex items-center justify-center mx-auto text-neutral-400">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-base font-black uppercase tracking-wider text-neutral-900">Keranjang Belanja Kosong</h3>
                    <p class="text-xs text-neutral-500 mt-1">Anda belum menambahkan produk sepatu ke keranjang belanja.</p>
                </div>
                <a href="{{ route('catalog.index') }}" class="inline-block mt-4 px-8 py-3.5 rounded-full bg-neutral-900 text-white font-extrabold text-xs uppercase tracking-widest hover:bg-orange-500 transition shadow-md">
                    Mulai Belanja Sekarang
                </a>
            </div>
        @else
            @php
                $cartData = $carts->map(fn($c) => [
                    'id' => $c->id,
                    'qty' => (int)$c->qty,
                    'subtotal' => (float)$c->subtotal(),
                ])->values();
            @endphp

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8"
                 x-data="{
                     items: {{ json_encode($cartData) }},
                     selectedIds: {{ json_encode($carts->pluck('id')->values()) }},
                     
                     isAllSelected() {
                         return this.items.length > 0 && this.selectedIds.length === this.items.length;
                     },
                     toggleAll(e) {
                         if (e.target.checked) {
                             this.selectedIds = this.items.map(i => i.id);
                         } else {
                             this.selectedIds = [];
                         }
                     },
                     isSelected(id) {
                         return this.selectedIds.includes(id);
                     },
                     toggleItem(id) {
                         const idx = this.selectedIds.indexOf(id);
                         if (idx > -1) {
                             this.selectedIds.splice(idx, 1);
                         } else {
                             this.selectedIds.push(id);
                         }
                     },
                     getSelectedCount() {
                         return this.selectedIds.length;
                     },
                     getSelectedQty() {
                         return this.items
                             .filter(i => this.selectedIds.includes(i.id))
                             .reduce((acc, curr) => acc + curr.qty, 0);
                     },
                     getSelectedTotal() {
                         return this.items
                             .filter(i => this.selectedIds.includes(i.id))
                             .reduce((acc, curr) => acc + curr.subtotal, 0);
                     },
                     formatRupiah(num) {
                         return 'Rp ' + Number(num).toLocaleString('id-ID');
                     }
                 }">

                <!-- Cart Items List (Left) -->
                <div class="lg:col-span-8 space-y-4">

                    <!-- Select All Bar -->
                    <div class="bg-white rounded-2xl p-4 border border-neutral-200 shadow-2xs flex items-center justify-between">
                        <label class="flex items-center gap-3 cursor-pointer select-none">
                            <input type="checkbox"
                                   :checked="isAllSelected()"
                                   @change="toggleAll($event)"
                                   class="w-5 h-5 rounded-lg text-orange-500 focus:ring-orange-500 border-neutral-300">
                            <span class="text-xs font-black uppercase tracking-wider text-neutral-900">
                                Pilih Semua (<span x-text="items.length"></span> Produk)
                            </span>
                        </label>
                        <span class="text-xs font-bold text-neutral-500">
                            <span x-text="getSelectedCount()" class="text-orange-600 font-black"></span> item dipilih
                        </span>
                    </div>

                    @foreach($carts as $cart)
                        <div class="bg-white rounded-2xl p-5 border transition shadow-2xs flex flex-col sm:flex-row items-center justify-between gap-4"
                             :class="isSelected({{ $cart->id }}) ? 'border-orange-200 ring-1 ring-orange-500/20 bg-white' : 'border-neutral-200 bg-neutral-50/60 opacity-75'">
                            
                            <div class="flex items-center gap-4 w-full sm:w-auto">
                                <!-- Checkbox Item -->
                                <div class="shrink-0 flex items-center">
                                    <input type="checkbox"
                                           :checked="isSelected({{ $cart->id }})"
                                           @click="toggleItem({{ $cart->id }})"
                                           class="w-5 h-5 rounded-lg text-orange-500 focus:ring-orange-500 border-neutral-300 cursor-pointer">
                                </div>

                                <!-- Foto Produk -->
                                <div class="w-20 h-20 bg-[#f8f8f8] rounded-xl overflow-hidden flex-shrink-0 border border-neutral-200 p-2 flex items-center justify-center">
                                    @if($cart->productVariant->product->image ?? false)
                                        <img src="{{ Str::startsWith($cart->productVariant->product->image, ['http://', 'https://']) ? $cart->productVariant->product->image : asset('storage/' . $cart->productVariant->product->image) }}"
                                             class="max-h-full max-w-full object-contain">
                                    @else
                                        <img src="https://images.unsplash.com/photo-1542291026-7eec264c27ff?auto=format&fit=crop&w=300&q=80"
                                             class="max-h-full max-w-full object-contain">
                                    @endif
                                </div>

                                <!-- Detail Info -->
                                <div>
                                    <h4 class="font-black text-neutral-900 text-sm uppercase tracking-tight">
                                        <a href="{{ route('catalog.show', $cart->productVariant->product->slug) }}" class="hover:text-orange-500 transition">
                                            {{ $cart->productVariant->product->name }}
                                        </a>
                                    </h4>
                                    <p class="text-xs text-neutral-500 font-semibold uppercase mt-0.5">
                                        Ukuran (EUR): <span class="font-extrabold text-neutral-900">{{ $cart->productVariant->size }}</span>
                                    </p>
                                    @if($cart->productVariant->product && $cart->productVariant->product->hasDiscount())
                                        <div class="flex items-center gap-2 mt-1">
                                            <span class="text-xs font-black text-orange-600">
                                                Rp {{ number_format($cart->productVariant->product->finalPrice(), 0, ',', '.') }}
                                            </span>
                                            <span class="text-[11px] text-neutral-400 line-through">
                                                Rp {{ number_format($cart->productVariant->product->price, 0, ',', '.') }}
                                            </span>
                                            <span class="text-[10px] font-black text-white bg-orange-500 px-1.5 py-0.2 rounded-full">
                                                -{{ $cart->productVariant->product->discountPercent() }}%
                                            </span>
                                        </div>
                                    @else
                                        <p class="text-xs font-black text-neutral-900 mt-1">
                                            Rp {{ number_format($cart->productVariant->product->price ?? 0, 0, ',', '.') }}
                                        </p>
                                    @endif
                                </div>
                            </div>

                            <div class="flex items-center justify-between sm:justify-end gap-6 w-full sm:w-auto border-t sm:border-t-0 border-neutral-100 pt-3 sm:pt-0">
                                <!-- Update Qty Form -->
                                <form action="{{ route('cart.update', $cart) }}" method="POST" class="flex items-center gap-2">
                                    @csrf
                                    @method('PATCH')
                                    <input type="number" name="qty" value="{{ $cart->qty }}" min="1" max="{{ $cart->productVariant->stock }}"
                                           onchange="this.form.submit()"
                                           class="w-16 text-center text-xs font-extrabold border-neutral-300 rounded-xl bg-[#f8f8f8] py-1.5 focus:ring-neutral-900">
                                </form>

                                <!-- Subtotal Item -->
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
                                    <button type="submit" class="p-2 text-neutral-400 hover:text-rose-600 hover:bg-rose-50 rounded-xl transition" title="Hapus dari keranjang">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach

                    <div class="p-4 rounded-2xl bg-amber-50/70 border border-amber-200/70 flex items-center gap-3 text-xs text-amber-800">
                        <svg class="w-5 h-5 text-amber-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Hanya produk yang <strong>dicentang</strong> yang akan diproses ke pembayaran. Produk yang tidak dicentang akan tetap berada di keranjang untuk dibeli nanti.</span>
                    </div>
                </div>

                <!-- Order Summary (Right) -->
                <div class="lg:col-span-4">
                    <div class="bg-white rounded-2xl p-6 border border-neutral-200 shadow-2xs space-y-6 sticky top-28">
                        <h3 class="text-base font-black text-neutral-900 uppercase tracking-wider border-b border-neutral-100 pb-4">
                            Ringkasan Belanja
                        </h3>

                        <div class="space-y-3 text-xs font-semibold uppercase">
                            <div class="flex justify-between text-neutral-600">
                                <span>Produk Terpilih</span>
                                <span class="font-black text-neutral-900">
                                    <span x-text="getSelectedCount()"></span> Item (<span x-text="getSelectedQty()"></span> pasang)
                                </span>
                            </div>
                            <div class="flex justify-between text-neutral-600">
                                <span>Estimasi Ongkos Kirim</span>
                                <span class="font-black text-emerald-600">GRATIS</span>
                            </div>
                        </div>

                        <div class="pt-4 border-t border-neutral-100 flex justify-between items-baseline">
                            <span class="text-xs font-extrabold uppercase text-neutral-900 tracking-wider">Total Pembayaran</span>
                            <span class="text-2xl font-black text-neutral-900" x-text="formatRupiah(getSelectedTotal())"></span>
                        </div>

                        <!-- Form Checkout Pilihan -->
                        <form action="{{ route('checkout.index') }}" method="GET">
                            <template x-for="id in selectedIds" :key="id">
                                <input type="hidden" name="selected[]" :value="id">
                            </template>

                            <button type="submit"
                                    :disabled="selectedIds.length === 0"
                                    :class="selectedIds.length > 0 ? 'bg-neutral-900 hover:bg-orange-500 text-white cursor-pointer shadow-md' : 'bg-neutral-200 text-neutral-400 cursor-not-allowed'"
                                    class="flex items-center justify-center gap-2 w-full py-4 rounded-full font-extrabold text-xs uppercase tracking-widest transition">
                                <span x-text="selectedIds.length > 0 ? 'Checkout (' + getSelectedCount() + ' Item)' : 'Pilih Item Dulu'"></span>
                                <svg x-show="selectedIds.length > 0" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                            </button>
                        </form>

                        <p class="text-[11px] text-neutral-400 text-center font-medium">
                            Transaksi aman &amp; terenkripsi. 100% Garansi Uang Kembali jika produk tidak sesuai.
                        </p>
                    </div>
                </div>
            </div>
        @endif
    </div>

@endsection
