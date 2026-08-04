<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class DashboardController extends Controller
{
    /**
     * Tampilkan ringkasan statistik dan audit log di Dashboard Super Admin.
     */
    public function index()
    {
        $totalSales = Order::where('status', Order::STATUS_SELESAI)->sum('total');
        $totalOrders = Order::count();
        $pendingOrders = Order::where('status', Order::STATUS_PENDING)->count();
        $totalProducts = Product::count();
        $totalUsers = User::count();

        // Stok menipis (< 5 pasang)
        $lowStockVariants = ProductVariant::with('product')
            ->where('stock', '<', 5)
            ->orderBy('stock', 'asc')
            ->take(10)
            ->get();

        // Audit Trail Activity Log
        $recentActivities = ActivityLog::with('user')
            ->latest('id')
            ->take(15)
            ->get();

        return view('admin.dashboard', compact(
            'totalSales',
            'totalOrders',
            'pendingOrders',
            'totalProducts',
            'totalUsers',
            'lowStockVariants',
            'recentActivities'
        ));
    }

    /**
     * Export Laporan Penjualan ke format PDF.
     */
    public function exportPdf()
    {
        $orders = Order::with(['user', 'items.productVariant.product'])
            ->latest()
            ->get();

        $totalRevenue = $orders->where('status', Order::STATUS_SELESAI)->sum('total');

        auth()->user()->logActivity('export_report', 'Mengekspor laporan penjualan ke format PDF');

        if (class_exists(Pdf::class)) {
            $pdf = Pdf::loadView('admin.reports.pdf', compact('orders', 'totalRevenue'));
            return $pdf->download('Laporan-Penjualan-SepatuID-' . now()->format('Y-m-d') . '.pdf');
        }

        // Fallback jika dompdf belum terinstall penuh
        return view('admin.reports.pdf', compact('orders', 'totalRevenue'));
    }
}
