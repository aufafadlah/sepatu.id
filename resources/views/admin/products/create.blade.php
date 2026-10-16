@extends('layouts.admin')

@section('title', 'Tambah Produk — Admin Sepatu.id')
@section('page_title', 'Tambah Produk Baru')

@section('content')

    <div class="max-w-4xl mx-auto space-y-6">

        <a href="{{ route('admin.produk.index') }}" class="text-xs font-semibold text-slate-500 hover:text-slate-900 flex items-center gap-1.5 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            <span>Kembali ke Daftar Produk</span>
        </a>

        <form action="{{ route('admin.produk.store') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-2xl p-6 sm:p-8 border border-slate-200/80 shadow-2xs space-y-6">
            @csrf

            <h3 class="text-base font-bold text-slate-900 border-b border-slate-100 pb-3">
                Informasi Produk Sepatu
            </h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

                <!-- Kategori -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Kategori <span class="text-rose-500">*</span></label>
                    <select name="category_id" required class="w-full rounded-xl border-slate-200 text-xs font-medium p-3 focus:ring-indigo-500 focus:border-indigo-500 bg-slate-50">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Nama Produk -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Produk <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required placeholder="Contoh: Air Runner X" class="w-full rounded-xl border-slate-200 text-xs font-medium p-3 focus:ring-indigo-500 focus:border-indigo-500 bg-slate-50">
                </div>

                <!-- Harga Asli -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Harga Asli (Rp) <span class="text-rose-500">*</span></label>
                    <input type="number" id="price" name="price" value="{{ old('price') }}" required min="0" placeholder="599000" class="w-full rounded-xl border-slate-200 text-xs font-medium p-3 focus:ring-indigo-500 focus:border-indigo-500 bg-slate-50">
                </div>

                <!-- Foto File / URL -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Foto File atau URL</label>
                    <input type="file" name="image" class="w-full text-xs text-slate-500 border border-slate-200 rounded-xl p-2 bg-slate-50 mb-2">
                    <input type="text" name="image_url" value="{{ old('image_url') }}" placeholder="Atau paste URL Foto CDN..." class="w-full rounded-xl border-slate-200 text-xs font-medium p-2.5 bg-slate-50">
                </div>

                <!-- Pengaturan Diskon Khusus -->
                <div class="sm:col-span-2 p-4 rounded-2xl bg-indigo-50/50 border border-indigo-100 space-y-3">
                    <div class="flex items-center gap-2">
                        <span class="p-1 rounded-lg bg-indigo-100 text-indigo-700">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 11h.01M7 15h.01M13 7h7M13 11h7M13 15h7" /></svg>
                        </span>
                        <h4 class="text-xs font-bold text-indigo-950 uppercase tracking-wider">Pengaturan Diskon Khusus (Opsional)</h4>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                        <div>
                            <label class="block text-slate-700 font-semibold mb-1">Diskon Persen (%)</label>
                            <input type="number" id="discount_percent" name="discount_percent" value="{{ old('discount_percent') }}" min="0" max="100" placeholder="Contoh: 20 (untuk diskon 20%)" class="w-full rounded-xl border-slate-200 text-xs font-medium p-2.5 bg-white focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="block text-slate-700 font-semibold mb-1">Harga Setelah Diskon (Rp)</label>
                            <input type="number" id="discount_price" name="discount_price" value="{{ old('discount_price') }}" min="0" placeholder="Contoh: 479200" class="w-full rounded-xl border-slate-200 text-xs font-medium p-2.5 bg-white focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                    </div>
                    <p class="text-[11px] text-slate-500">Isi salah satu kolom diskon, sistem akan otomatis menyesuaikan harga coret di etalase toko.</p>
                    <div id="discount-preview" class="hidden text-xs font-semibold text-emerald-700 bg-emerald-100/70 border border-emerald-200 p-2.5 rounded-xl"></div>
                </div>

            </div>

            <!-- Deskripsi -->
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Deskripsi Lengkap <span class="text-rose-500">*</span></label>
                <textarea name="description" rows="4" required placeholder="Tuliskan spesifikasi bahan dan keunggulan sepatu..." class="w-full rounded-xl border-slate-200 text-xs font-medium p-3 focus:ring-indigo-500 focus:border-indigo-500 bg-slate-50">{{ old('description') }}</textarea>
            </div>

            <!-- Varian Ukuran & Stok -->
            <div class="border-t border-slate-100 pt-6 space-y-4">
                <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Varian Ukuran EUR &amp; Stok Awal</h4>

                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                    @php $sizes = [38, 39, 40, 41, 42, 43, 44]; @endphp
                    @foreach($sizes as $index => $sz)
                        <div class="p-3 rounded-xl bg-slate-50 border border-slate-200/60 space-y-1">
                            <label class="block text-[11px] font-bold text-slate-800">Size {{ $sz }}</label>
                            <input type="hidden" name="variants[{{ $index }}][size]" value="{{ $sz }}">
                            <input type="number" name="variants[{{ $index }}][stock]" value="{{ old("variants.$index.stock", 10) }}" min="0" required class="w-full rounded-lg border-slate-200 text-xs font-bold p-2 bg-white text-center">
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100 flex justify-end">
                <button type="submit" class="px-6 py-3 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs transition shadow-2xs">
                    Simpan Produk Baru
                </button>
            </div>
        </form>

    </div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const priceInput = document.getElementById('price');
    const percentInput = document.getElementById('discount_percent');
    const priceDiscountInput = document.getElementById('discount_price');
    const preview = document.getElementById('discount-preview');

    function updatePreview() {
        const price = parseFloat(priceInput?.value) || 0;
        const percent = parseFloat(percentInput?.value) || 0;
        const discountPrice = parseFloat(priceDiscountInput?.value) || 0;

        if (price > 0 && (percent > 0 || discountPrice > 0)) {
            let finalP = price;
            let effPercent = percent;
            if (discountPrice > 0 && discountPrice < price) {
                finalP = discountPrice;
                effPercent = Math.round(((price - discountPrice) / price) * 100);
            } else if (percent > 0) {
                finalP = Math.round(price * (1 - percent / 100));
            }
            if (preview) {
                preview.classList.remove('hidden');
                preview.innerHTML = `💡 Preview Toko: Harga Promo <strong>Rp ${finalP.toLocaleString('id-ID')}</strong> (Hemat Rp ${(price - finalP).toLocaleString('id-ID')} / ${effPercent}%)`;
            }
        } else if (preview) {
            preview.classList.add('hidden');
        }
    }

    percentInput?.addEventListener('input', function () {
        const price = parseFloat(priceInput?.value) || 0;
        const percent = parseFloat(this.value) || 0;
        if (price > 0 && percent > 0 && percent <= 100) {
            priceDiscountInput.value = Math.round(price * (1 - percent / 100));
        } else if (!this.value) {
            priceDiscountInput.value = '';
        }
        updatePreview();
    });

    priceDiscountInput?.addEventListener('input', function () {
        const price = parseFloat(priceInput?.value) || 0;
        const discPrice = parseFloat(this.value) || 0;
        if (price > 0 && discPrice > 0 && discPrice < price) {
            percentInput.value = Math.round(((price - discPrice) / price) * 100);
        } else if (!this.value) {
            percentInput.value = '';
        }
        updatePreview();
    });

    priceInput?.addEventListener('input', updatePreview);
    updatePreview();
});
</script>
@endpush
