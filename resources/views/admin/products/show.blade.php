@extends('layouts.admin')

@section('title', 'Detail Produk — Admin Sepatu.id')
@section('page_title', 'Detail Produk')

@section('content')

    <div class="max-w-4xl mx-auto space-y-6">

        <div class="flex items-center justify-between">
            <a href="{{ route('admin.produk.index') }}" class="text-xs font-semibold text-slate-500 hover:text-slate-900 flex items-center gap-1.5 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                <span>Kembali ke Daftar Produk</span>
            </a>
            <a href="{{ route('admin.produk.edit', $product) }}" class="px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs transition">
                Edit Produk Ini
            </a>
        </div>

        <div class="bg-white rounded-2xl p-6 sm:p-8 border border-slate-200/80 shadow-2xs space-y-8">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-8">

                <div class="md:col-span-5 bg-slate-50 p-4 rounded-xl border border-slate-200/60 aspect-square flex items-center justify-center">
                    @if($product->image)
                        <img src="{{ Str::startsWith($product->image, ['http://', 'https://']) ? $product->image : asset('storage/' . $product->image) }}" class="max-h-full max-w-full object-contain">
                    @else
                        <img src="https://images.unsplash.com/photo-1542291026-7eec264c27ff?auto=format&fit=crop&w=600&q=80" class="max-h-full max-w-full object-contain">
                    @endif
                </div>

                <div class="md:col-span-7 space-y-4">
                    <span class="px-2.5 py-1 rounded-full bg-slate-100 text-[10px] uppercase font-bold text-indigo-600">
                        {{ $product->category->name ?? 'Kategori' }}
                    </span>

                    <h2 class="text-2xl font-bold text-slate-900 tracking-tight">
                        {{ $product->name }}
                    </h2>

                    <p class="text-2xl font-black text-slate-900">
                        Rp {{ number_format($product->price, 0, ',', '.') }}
                    </p>

                    <p class="text-xs text-slate-600 font-medium leading-relaxed">
                        {{ $product->description }}
                    </p>
                </div>

            </div>

            <!-- Varian Ukuran & Stok Table -->
            <div class="border-t border-slate-100 pt-6 space-y-4">
                <h4 class="text-xs font-bold uppercase tracking-wider text-slate-900">Persediaan Varian Ukuran</h4>

                <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-7 gap-3">
                    @foreach($product->variants as $variant)
                        <div class="p-3 rounded-xl bg-slate-50 border border-slate-200/60 text-center space-y-1">
                            <span class="block text-[11px] font-semibold text-slate-400">EUR {{ $variant->size }}</span>
                            <span class="block text-sm font-black {{ $variant->stock < 5 ? 'text-rose-600' : 'text-slate-900' }}">
                                {{ $variant->stock }} pasang
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Reviews -->
            <div class="border-t border-slate-100 pt-6 space-y-4">
                <h4 class="text-xs font-bold uppercase tracking-wider text-slate-900">Ulasan Pembeli ({{ $product->reviews->count() }})</h4>

                <div class="space-y-3">
                    @forelse($product->reviews as $review)
                        <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/60 space-y-1 text-xs">
                            <div class="flex items-center justify-between font-bold">
                                <span class="text-slate-900">{{ $review->user->name ?? 'Pelanggan' }}</span>
                                <span class="inline-flex items-center gap-1 text-amber-500 font-bold">
                                    <svg class="w-3.5 h-3.5 fill-current text-amber-400" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                    <span>{{ $review->rating }}.0</span>
                                </span>
                            </div>
                            <p class="text-slate-600 font-medium">"{{ $review->comment }}"</p>
                        </div>
                    @empty
                        <p class="text-xs text-slate-400 font-medium italic">Belum ada ulasan pembeli.</p>
                    @endforelse
                </div>
            </div>
        </div>

    </div>

@endsection
