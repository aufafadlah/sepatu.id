@extends('layouts.admin')

@section('title', 'Edit Produk — Admin Sepatu.id')
@section('page_title', 'Edit Produk')

@section('content')

    <div class="max-w-4xl mx-auto space-y-6">

        <a href="{{ route('admin.produk.index') }}" class="text-xs font-semibold text-slate-500 hover:text-slate-900 flex items-center gap-1.5 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            <span>Kembali ke Daftar Produk</span>
        </a>

        <form action="{{ route('admin.produk.update', $product) }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-2xl p-6 sm:p-8 border border-slate-200/80 shadow-2xs space-y-6">
            @csrf
            @method('PUT')

            <h3 class="text-base font-bold text-slate-900 border-b border-slate-100 pb-3">
                Edit Produk: {{ $product->name }}
            </h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

                <!-- Kategori -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Kategori <span class="text-rose-500">*</span></label>
                    <select name="category_id" required class="w-full rounded-xl border-slate-200 text-xs font-medium p-3 focus:ring-indigo-500 focus:border-indigo-500 bg-slate-50">
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Nama Produk -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Produk <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $product->name) }}" required class="w-full rounded-xl border-slate-200 text-xs font-medium p-3 focus:ring-indigo-500 focus:border-indigo-500 bg-slate-50">
                </div>

                <!-- Harga -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Harga (Rp) <span class="text-rose-500">*</span></label>
                    <input type="number" name="price" value="{{ old('price', $product->price) }}" required min="0" class="w-full rounded-xl border-slate-200 text-xs font-medium p-3 focus:ring-indigo-500 focus:border-indigo-500 bg-slate-50">
                </div>

                <!-- Foto File / URL -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Ubah Foto File / URL</label>
                    <input type="file" name="image" class="w-full text-xs text-slate-500 border border-slate-200 rounded-xl p-2 bg-slate-50 mb-2">
                    <input type="text" name="image_url" value="{{ old('image_url', Str::startsWith($product->image, 'http') ? $product->image : '') }}" placeholder="URL foto..." class="w-full rounded-xl border-slate-200 text-xs font-medium p-2.5 bg-slate-50">
                </div>

            </div>

            <!-- Deskripsi -->
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Deskripsi Lengkap <span class="text-rose-500">*</span></label>
                <textarea name="description" rows="4" required class="w-full rounded-xl border-slate-200 text-xs font-medium p-3 focus:ring-indigo-500 focus:border-indigo-500 bg-slate-50">{{ old('description', $product->description) }}</textarea>
            </div>

            <!-- Varian Ukuran & Stok -->
            <div class="border-t border-slate-100 pt-6 space-y-4">
                <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Perbarui Varian Ukuran &amp; Stok</h4>

                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                    @php
                        $existingVariants = $product->variants->keyBy('size');
                        $sizes = [38, 39, 40, 41, 42, 43, 44];
                    @endphp
                    @foreach($sizes as $index => $sz)
                        @php $stockVal = isset($existingVariants[$sz]) ? $existingVariants[$sz]->stock : 0; @endphp
                        <div class="p-3 rounded-xl bg-slate-50 border border-slate-200/60 space-y-1">
                            <label class="block text-[11px] font-bold text-slate-800">Size {{ $sz }}</label>
                            <input type="hidden" name="variants[{{ $index }}][size]" value="{{ $sz }}">
                            <input type="number" name="variants[{{ $index }}][stock]" value="{{ old("variants.$index.stock", $stockVal) }}" min="0" required class="w-full rounded-lg border-slate-200 text-xs font-bold p-2 bg-white text-center">
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100 flex justify-end gap-3">
                <a href="{{ route('admin.produk.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs transition shadow-2xs">
                    Perbarui Produk
                </button>
            </div>
        </form>

    </div>

@endsection
