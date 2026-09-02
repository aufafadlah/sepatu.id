@extends('layouts.main')

@section('title', 'Wishlist Saya — Sepatu.id')

@section('content')

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <h1 class="text-3xl font-black uppercase tracking-tight text-neutral-900 mb-8">
            Wishlist Saya
        </h1>

        @if($wishlists->isEmpty())
            <div class="bg-[#f8f8f8] rounded-2xl p-12 border border-neutral-200 text-center max-w-md mx-auto">
                <h3 class="text-base font-bold uppercase tracking-wider text-neutral-900">Wishlist Masih Kosong</h3>
                <p class="text-xs text-neutral-500 mt-1">Simpan sepatu favoritmu di wishlist agar mudah dicari nanti.</p>
                <a href="{{ route('catalog.index') }}" class="inline-block mt-6 px-8 py-3 rounded-full bg-neutral-900 text-white font-extrabold text-xs uppercase tracking-widest hover:bg-orange-500 transition">
                    Cari Sepatu Favorit
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($wishlists as $wishlist)
                    @php $product = $wishlist->product; @endphp
                    @if($product)
                        <div class="bg-white rounded-xl border border-neutral-200 shadow-2xs p-4 flex flex-col justify-between group">
                            <div>
                                <div class="bg-[#f8f8f8] rounded-lg overflow-hidden aspect-square flex items-center justify-center mb-3 p-4">
                                    @if($product->image)
                                        <img src="{{ Str::startsWith($product->image, ['http://', 'https://']) ? $product->image : asset('storage/' . $product->image) }}"
                                             class="max-h-full max-w-full object-contain">
                                    @else
                                        <img src="https://images.unsplash.com/photo-1542291026-7eec264c27ff?auto=format&fit=crop&w=400&q=80"
                                             class="max-h-full max-w-full object-contain">
                                    @endif
                                </div>
                                <h3 class="font-extrabold text-neutral-900 text-xs uppercase tracking-tight">
                                    <a href="{{ route('catalog.show', $product->slug) }}" class="hover:text-orange-500 transition">
                                        {{ $product->name }}
                                    </a>
                                </h3>
                                <p class="text-xs font-black text-neutral-900 mt-1">
                                    Rp {{ number_format($product->price, 0, ',', '.') }}
                                </p>
                            </div>

                            <div class="pt-4 mt-4 border-t border-neutral-100 flex items-center gap-2">
                                <a href="{{ route('catalog.show', $product->slug) }}" class="flex-1 text-center py-2.5 rounded-full bg-neutral-900 hover:bg-orange-500 text-white text-[11px] font-extrabold uppercase tracking-wider transition">
                                    Detail
                                </a>
                                <form action="{{ route('wishlist.toggle', $product) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="p-2.5 text-rose-500 hover:text-rose-700 hover:bg-rose-50 rounded-full transition" title="Hapus dari wishlist">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        @endif
    </div>

@endsection
