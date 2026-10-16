@extends('layouts.admin')

@section('title', 'Stok Gudang — Admin Sepatu.id')
@section('page_title', 'Stok Gudang')

@section('content')

    <div class="space-y-6" x-data="{
        addVariantModal: false,
        selectedProductId: '',
        selectedSize: 40,
        selectedStock: 10,
        isSubmitting: false,
        updatingVariantId: null,

        async updateStock(variantId, inputElement) {
            const newStock = inputElement.value;
            if (newStock === '' || newStock < 0) {
                if (window.showToast) window.showToast('Jumlah stok tidak valid', 'error');
                return;
            }

            this.updatingVariantId = variantId;
            const csrfToken = document.querySelector('meta[name=\'csrf-token\']').getAttribute('content');

            try {
                const response = await fetch(`/admin/gudang/${variantId}`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ stock: parseInt(newStock) })
                });

                const data = await response.json();
                if (response.ok && data.success) {
                    if (window.showToast) window.showToast(data.message, 'success');
                    // Update visual badge
                    const badge = document.getElementById(`stock-badge-${variantId}`);
                    if (badge) {
                        badge.className = 'px-2 py-0.5 rounded-full text-[10px] font-bold ' + 
                            (parseInt(newStock) <= 0 ? 'bg-rose-50 text-rose-600 border border-rose-200' :
                            (parseInt(newStock) < 5 ? 'bg-amber-50 text-amber-600 border border-amber-200' : 'bg-emerald-50 text-emerald-600 border border-emerald-200'));
                        badge.textContent = parseInt(newStock) <= 0 ? 'Habis' : (parseInt(newStock) < 5 ? 'Menipis' : 'Aman');
                    }
                } else {
                    if (window.showToast) window.showToast(data.message || 'Gagal memperbarui stok', 'error');
                }
            } catch (err) {
                if (window.showToast) window.showToast('Terjadi kesalahan jaringan', 'error');
            } finally {
                this.updatingVariantId = null;
            }
        },

        async submitNewVariant() {
            if (!this.selectedProductId || !this.selectedSize) {
                if (window.showToast) window.showToast('Harap pilih produk dan ukuran', 'error');
                return;
            }

            this.isSubmitting = true;
            const csrfToken = document.querySelector('meta[name=\'csrf-token\']').getAttribute('content');

            try {
                const response = await fetch('{{ route('admin.gudang.store-variant') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        product_id: this.selectedProductId,
                        size: parseInt(this.selectedSize),
                        stock: parseInt(this.selectedStock) || 0
                    })
                });

                const data = await response.json();
                if (response.ok && data.success) {
                    this.addVariantModal = false;
                    if (window.showToast) window.showToast(data.message, 'success');
                    setTimeout(() => window.location.reload(), 700);
                } else {
                    if (window.showToast) window.showToast(data.message || 'Gagal menambah varian stok', 'error');
                }
            } catch (err) {
                if (window.showToast) window.showToast('Terjadi kesalahan saat menyimpan varian baru', 'error');
            } finally {
                this.isSubmitting = false;
            }
        }
    }">

        <!-- Top Header & Action -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200/80 shadow-2xs">
            <div>
                <h2 class="text-lg font-bold text-slate-900 tracking-tight">Manajemen Stok Fisik Gudang</h2>
                <p class="text-xs text-slate-500 font-medium mt-0.5">Kelola dan perbarui stok ukuran sepatu yang terkelompok rapi per produk katalog.</p>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                <button type="button" @click="addVariantModal = true" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs transition shadow-2xs">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" /></svg>
                    <span>Tambah Varian Stok Baru</span>
                </button>

                <a href="{{ route('admin.gudang.index', ['low_stock' => 1]) }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-semibold transition {{ request('low_stock') ? 'bg-rose-600 text-white shadow-2xs' : 'bg-rose-50 text-rose-600 border border-rose-200 hover:bg-rose-100' }}">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                    <span>Filter Stok Menipis (&lt; 5)</span>
                </a>

                @if(request('low_stock') || request('search'))
                    <a href="{{ route('admin.gudang.index') }}" class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl border border-slate-200 text-xs font-semibold text-slate-700 hover:bg-slate-100 transition">
                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                        <span>Reset</span>
                    </a>
                @endif
            </div>
        </div>

        <!-- Search Bar -->
        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-2xs flex items-center justify-between">
            <form action="{{ route('admin.gudang.index') }}" method="GET" class="flex items-center gap-3 w-full sm:w-auto">
                @if(request('low_stock'))
                    <input type="hidden" name="low_stock" value="1">
                @endif
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama produk sepatu..." class="w-full sm:w-80 px-4 py-2 rounded-xl border border-slate-200 text-xs font-medium focus:ring-indigo-500 focus:border-indigo-500 bg-slate-50">
                <button type="submit" class="px-4 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-semibold text-xs transition">
                    Cari
                </button>
            </form>
            <div class="hidden sm:block text-xs font-semibold text-slate-500">
                Total: <strong class="text-slate-900">{{ $products->total() }}</strong> produk
            </div>
        </div>

        <!-- Grouped Stock Table by Product -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-2xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50/70 border-b border-slate-200/80 text-slate-400 uppercase font-semibold tracking-wider text-[10px]">
                        <tr>
                            <th class="p-4 w-12 text-center">No.</th>
                            <th class="p-4 w-64">Produk Sepatu</th>
                            <th class="p-4 w-32">Kategori</th>
                            <th class="p-4 w-32">Total Stok</th>
                            <th class="p-4">Varian Ukuran &amp; Kontrol Stok Cepat (AJAX)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium text-slate-800">
                        @forelse($products as $product)
                            <tr class="hover:bg-slate-50/60 transition align-top">
                                <!-- No -->
                                <td class="p-4 text-center font-bold text-slate-400 pt-5">
                                    {{ ($products->currentPage() - 1) * $products->perPage() + $loop->iteration }}
                                </td>

                                <!-- Product Info -->
                                <td class="p-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-12 h-12 rounded-xl bg-slate-50 border border-slate-200 overflow-hidden flex items-center justify-center p-1 shrink-0">
                                            @if($product->image)
                                                <img src="{{ Str::startsWith($product->image, ['http://', 'https://']) ? $product->image : asset('storage/' . $product->image) }}" class="max-h-full max-w-full object-contain">
                                            @else
                                                <img src="https://images.unsplash.com/photo-1542291026-7eec264c27ff?auto=format&fit=crop&w=200&q=80" class="max-h-full max-w-full object-contain">
                                            @endif
                                        </div>
                                        <div>
                                            <a href="{{ route('admin.produk.show', $product) }}" class="font-bold text-slate-900 hover:text-indigo-600 transition block">
                                                {{ $product->name }}
                                            </a>
                                            <span class="text-[11px] text-slate-400 font-normal">
                                                Rp {{ number_format($product->price, 0, ',', '.') }}
                                            </span>
                                        </div>
                                    </div>
                                </td>

                                <!-- Category -->
                                <td class="p-4 pt-5">
                                    <span class="px-2.5 py-1 rounded-full bg-slate-100 text-[10px] font-semibold text-slate-600">
                                        {{ $product->category->name ?? '-' }}
                                    </span>
                                </td>

                                <!-- Total Stock -->
                                <td class="p-4 pt-5">
                                    @php $tot = $product->totalStock(); @endphp
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase {{ $tot < 5 ? 'bg-rose-50 text-rose-600' : 'bg-emerald-50 text-emerald-600' }}">
                                        {{ $tot }} pasang
                                    </span>
                                </td>

                                <!-- Grouped Size Variants with Inline AJAX Editor -->
                                <td class="p-4">
                                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-2">
                                        @forelse($product->variants as $variant)
                                            <div class="p-2.5 rounded-xl border border-slate-200 bg-slate-50 flex flex-col justify-between gap-1.5 shadow-2xs">
                                                <div class="flex items-center justify-between">
                                                    <span class="font-black text-slate-900 text-xs">EUR {{ $variant->size }}</span>
                                                    <span id="stock-badge-{{ $variant->id }}"
                                                          class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $variant->stock <= 0 ? 'bg-rose-50 text-rose-600 border border-rose-200' : ($variant->stock < 5 ? 'bg-amber-50 text-amber-600 border border-amber-200' : 'bg-emerald-50 text-emerald-600 border border-emerald-200') }}">
                                                        {{ $variant->stock <= 0 ? 'Habis' : ($variant->stock < 5 ? 'Menipis' : 'Aman') }}
                                                    </span>
                                                </div>
                                                <div class="flex items-center gap-1.5 pt-1">
                                                    <input type="number"
                                                           id="stock-input-{{ $variant->id }}"
                                                           value="{{ $variant->stock }}"
                                                           min="0"
                                                           class="w-16 text-center rounded-lg border-slate-200 text-xs font-bold p-1 bg-white focus:ring-indigo-500">
                                                    <button type="button"
                                                            @click="updateStock({{ $variant->id }}, document.getElementById('stock-input-{{ $variant->id }}'))"
                                                            :disabled="updatingVariantId === {{ $variant->id }}"
                                                            title="Simpan Stok (AJAX)"
                                                            class="px-2 py-1 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-bold text-[10px] transition shrink-0 flex items-center justify-center">
                                                        <svg x-show="updatingVariantId !== {{ $variant->id }}" class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" /></svg>
                                                        <svg x-show="updatingVariantId === {{ $variant->id }}" class="animate-spin w-3 h-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                                    </button>
                                                </div>
                                            </div>
                                        @empty
                                            <div class="col-span-full py-2 text-slate-400 italic text-[11px]">
                                                Belum ada varian ukuran untuk produk ini.
                                            </div>
                                        @endforelse

                                        <!-- Quick Add Size Button for this Product -->
                                        <button type="button"
                                                @click="selectedProductId = '{{ $product->id }}'; addVariantModal = true"
                                                class="p-2.5 rounded-xl border border-dashed border-slate-300 hover:border-indigo-500 hover:bg-indigo-50/50 text-slate-500 hover:text-indigo-600 flex items-center justify-center gap-1 transition text-xs font-bold">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" /></svg>
                                            <span>+ Ukuran</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-8 text-center text-slate-400 font-medium text-xs">
                                    Tidak ada data stok produk ditemukan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-slate-100">
                {{ $products->links() }}
            </div>
        </div>

        <!-- Modal Tambah Varian Ukuran Baru -->
        <div x-show="addVariantModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs" @click.self="addVariantModal = false">
            <div x-show="addVariantModal"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 class="bg-white rounded-3xl p-6 sm:p-8 max-w-md w-full shadow-2xl border border-slate-200 space-y-5">

                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <h3 class="text-base font-bold text-slate-900">
                        Tambah Varian Ukuran &amp; Stok
                    </h3>
                    <button type="button" @click="addVariantModal = false" class="text-slate-400 hover:text-slate-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                <div class="space-y-4 text-xs font-semibold">
                    <div>
                        <label class="block text-slate-700 mb-1">Pilih Produk Sepatu <span class="text-rose-500">*</span></label>
                        <select x-model="selectedProductId" class="w-full rounded-xl border-slate-200 text-xs font-medium p-3 bg-slate-50 focus:ring-indigo-500">
                            <option value="">-- Pilih Produk --</option>
                            @foreach($allProducts as $p)
                                <option value="{{ $p->id }}">{{ $p->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-slate-700 mb-1">Ukuran EUR <span class="text-rose-500">*</span></label>
                            <input type="number" x-model="selectedSize" min="30" max="50" placeholder="Contoh: 42" class="w-full rounded-xl border-slate-200 text-xs font-bold p-3 bg-slate-50 focus:ring-indigo-500 text-center">
                        </div>
                        <div>
                            <label class="block text-slate-700 mb-1">Jumlah Pasang Stok <span class="text-rose-500">*</span></label>
                            <input type="number" x-model="selectedStock" min="0" placeholder="Contoh: 15" class="w-full rounded-xl border-slate-200 text-xs font-bold p-3 bg-slate-50 focus:ring-indigo-500 text-center">
                        </div>
                    </div>
                </div>

                <div class="pt-3 flex items-center gap-3">
                    <button type="button" @click="addVariantModal = false" class="w-1/2 py-2.5 px-4 rounded-xl border border-slate-200 text-slate-700 font-bold text-xs hover:bg-slate-50 transition">
                        Batal
                    </button>
                    <button type="button" @click="submitNewVariant()" :disabled="isSubmitting" class="w-1/2 py-2.5 px-4 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs transition shadow-sm flex items-center justify-center gap-2">
                        <svg x-show="isSubmitting" class="animate-spin h-3.5 w-3.5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        <span x-text="isSubmitting ? 'Menyimpan...' : 'Simpan Stok'"></span>
                    </button>
                </div>

            </div>
        </div>

    </div>

@endsection
