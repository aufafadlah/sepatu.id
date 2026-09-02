<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Voucher;
use Illuminate\Http\Request;

class VoucherController extends Controller
{
    /**
     * Tampilkan daftar voucher promo diskon.
     */
    public function index(Request $request)
    {
        $query = Voucher::withCount('orders');

        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            if ($request->input('status') === 'active') {
                $query->where('is_active', true);
            } elseif ($request->input('status') === 'inactive') {
                $query->where('is_active', false);
            }
        }

        $vouchers = $query->latest()->paginate(10)->withQueryString();
        $totalVouchers = Voucher::count();

        return view('admin.vouchers.index', compact('vouchers', 'totalVouchers'));
    }

    /**
     * Form tambah voucher baru.
     */
    public function create()
    {
        return view('admin.vouchers.create');
    }

    /**
     * Simpan voucher baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:vouchers,code',
            'name' => 'required|string|max:150',
            'type' => 'required|in:percentage,fixed',
            'amount' => 'required|numeric|min:0.01',
            'min_spend' => 'nullable|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'is_active' => 'boolean',
            'starts_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after_or_equal:starts_at',
        ]);

        $validated['code'] = strtoupper(trim($validated['code']));
        $validated['is_active'] = $request->has('is_active');
        $validated['min_spend'] = $validated['min_spend'] ?? 0;

        $voucher = Voucher::create($validated);

        auth()->user()->logActivity('create_voucher', "Membuat voucher promo baru: {$voucher->code} ({$voucher->name})");

        return redirect()->route('admin.vouchers.index')->with('success', "Voucher {$voucher->code} berhasil dibuat!");
    }

    /**
     * Form edit voucher.
     */
    public function edit(Voucher $voucher)
    {
        return view('admin.vouchers.edit', compact('voucher'));
    }

    /**
     * Update voucher.
     */
    public function update(Request $request, Voucher $voucher)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:vouchers,code,' . $voucher->id,
            'name' => 'required|string|max:150',
            'type' => 'required|in:percentage,fixed',
            'amount' => 'required|numeric|min:0.01',
            'min_spend' => 'nullable|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'is_active' => 'boolean',
            'starts_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after_or_equal:starts_at',
        ]);

        $validated['code'] = strtoupper(trim($validated['code']));
        $validated['is_active'] = $request->has('is_active');
        $validated['min_spend'] = $validated['min_spend'] ?? 0;

        $voucher->update($validated);

        auth()->user()->logActivity('update_voucher', "Memperbarui voucher promo: {$voucher->code}");

        return redirect()->route('admin.vouchers.index')->with('success', "Voucher {$voucher->code} berhasil diperbarui!");
    }

    /**
     * Toggle status aktif/nonaktif.
     */
    public function toggleStatus(Voucher $voucher)
    {
        $voucher->is_active = !$voucher->is_active;
        $voucher->save();

        $statusText = $voucher->is_active ? 'diaktifkan' : 'dinonaktifkan';
        auth()->user()->logActivity('toggle_voucher', "Mengubah status voucher {$voucher->code} menjadi {$statusText}");

        return back()->with('success', "Status voucher {$voucher->code} berhasil {$statusText}!");
    }

    /**
     * Hapus voucher.
     */
    public function destroy(Voucher $voucher)
    {
        $code = $voucher->code;
        $voucher->delete();

        auth()->user()->logActivity('delete_voucher', "Menghapus voucher: {$code}");

        return redirect()->route('admin.vouchers.index')->with('success', "Voucher {$code} berhasil dihapus!");
    }
}
