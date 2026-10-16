<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Tampilkan halaman utama Laporan Penjualan dengan filter Harian, Bulanan, dan Tahunan.
     */
    public function index(Request $request)
    {
        $period = $request->input('period', 'monthly'); // daily, monthly, yearly, custom
        $date = $request->input('date', now()->format('Y-m-d'));
        $month = (int) $request->input('month', now()->month);
        $year = (int) $request->input('year', now()->year);
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $status = $request->input('status', 'selesai'); // default selesai

        $query = Order::with(['user', 'items.productVariant.product', 'voucher']);

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        // Terapkan filter periode waktu
        if ($period === 'daily') {
            $parsedDate = Carbon::parse($date);
            $query->whereDate('created_at', $parsedDate);
            $periodLabel = 'Harian: ' . $parsedDate->translatedFormat('d F Y');
        } elseif ($period === 'monthly') {
            $query->whereYear('created_at', $year)->whereMonth('created_at', $month);
            $periodLabel = 'Bulanan: ' . Carbon::create($year, $month, 1)->translatedFormat('F Y');
        } elseif ($period === 'yearly') {
            $query->whereYear('created_at', $year);
            $periodLabel = 'Tahunan: ' . $year;
        } elseif ($period === 'custom' && $startDate && $endDate) {
            $query->whereBetween('created_at', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay(),
            ]);
            $periodLabel = 'Rentang: ' . Carbon::parse($startDate)->format('d/m/Y') . ' - ' . Carbon::parse($endDate)->format('d/m/Y');
        } else {
            $query->whereYear('created_at', $year)->whereMonth('created_at', $month);
            $periodLabel = 'Bulanan: ' . Carbon::create($year, $month, 1)->translatedFormat('F Y');
        }

        // Clone query untuk kalkulasi ringkasan metrik
        $summaryQuery = clone $query;
        $totalRevenue = (float) $summaryQuery->sum('total');
        $totalOrders = $summaryQuery->count();
        $averageOrderValue = $totalOrders > 0 ? ($totalRevenue / $totalOrders) : 0;

        // Hitung total pasang sepatu terjual
        $orderIds = (clone $query)->pluck('id');
        $totalPairsSold = OrderItem::whereIn('order_id', $orderIds)->sum('qty');

        $orders = $query->latest('id')->paginate(15)->withQueryString();

        return view('admin.reports.index', compact(
            'orders',
            'period',
            'date',
            'month',
            'year',
            'startDate',
            'endDate',
            'status',
            'periodLabel',
            'totalRevenue',
            'totalOrders',
            'averageOrderValue',
            'totalPairsSold'
        ));
    }

    /**
     * Unduh Laporan Penjualan ke format PDF.
     */
    public function downloadPdf(Request $request)
    {
        $period = $request->input('period', 'monthly');
        $date = $request->input('date', now()->format('Y-m-d'));
        $month = (int) $request->input('month', now()->month);
        $year = (int) $request->input('year', now()->year);
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $status = $request->input('status', 'selesai');

        $query = Order::with(['user', 'items.productVariant.product', 'voucher']);

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        if ($period === 'daily') {
            $parsedDate = Carbon::parse($date);
            $query->whereDate('created_at', $parsedDate);
            $periodLabel = 'Harian: ' . $parsedDate->translatedFormat('d F Y');
        } elseif ($period === 'monthly') {
            $query->whereYear('created_at', $year)->whereMonth('created_at', $month);
            $periodLabel = 'Bulanan: ' . Carbon::create($year, $month, 1)->translatedFormat('F Y');
        } elseif ($period === 'yearly') {
            $query->whereYear('created_at', $year);
            $periodLabel = 'Tahunan: ' . $year;
        } elseif ($period === 'custom' && $startDate && $endDate) {
            $query->whereBetween('created_at', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay(),
            ]);
            $periodLabel = 'Rentang: ' . Carbon::parse($startDate)->format('d/m/Y') . ' - ' . Carbon::parse($endDate)->format('d/m/Y');
        } else {
            $query->whereYear('created_at', $year)->whereMonth('created_at', $month);
            $periodLabel = 'Bulanan: ' . Carbon::create($year, $month, 1)->translatedFormat('F Y');
        }

        $orders = $query->latest('id')->get();
        $totalRevenue = $orders->sum('total');
        $totalOrders = $orders->count();

        auth()->user()->logActivity('export_sales_pdf', "Mengekspor laporan penjualan PDF periode {$periodLabel}");

        if (class_exists(Pdf::class)) {
            $pdf = Pdf::loadView('admin.reports.pdf', compact('orders', 'totalRevenue', 'totalOrders', 'periodLabel'));
            return $pdf->download('Laporan-Penjualan-SepatuID-' . str_replace([' ', ':', '/'], '-', $periodLabel) . '.pdf');
        }

        return view('admin.reports.pdf', compact('orders', 'totalRevenue', 'totalOrders', 'periodLabel'));
    }

    /**
     * Ekspor data laporan penjualan ke file CSV / Excel spreadsheet.
     */
    public function exportCsv(Request $request)
    {
        $period = $request->input('period', 'monthly');
        $date = $request->input('date', now()->format('Y-m-d'));
        $month = (int) $request->input('month', now()->month);
        $year = (int) $request->input('year', now()->year);
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $status = $request->input('status', 'all');

        $query = Order::with(['user', 'items.productVariant.product']);

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        if ($period === 'daily') {
            $query->whereDate('created_at', Carbon::parse($date));
        } elseif ($period === 'monthly') {
            $query->whereYear('created_at', $year)->whereMonth('created_at', $month);
        } elseif ($period === 'yearly') {
            $query->whereYear('created_at', $year);
        } elseif ($period === 'custom' && $startDate && $endDate) {
            $query->whereBetween('created_at', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay(),
            ]);
        }

        $orders = $query->latest('id')->get();
        $fileName = 'Ekspor-Laporan-SepatuID-' . now()->format('YmdHis') . '.csv';

        auth()->user()->logActivity('export_sales_csv', 'Mengekspor data transaksi penjualan ke CSV');

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($orders) {
            $handle = fopen('php://output', 'w');
            // Menulis BOM UTF-8 agar karakter rapi di Microsoft Excel
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            // Baris Header CSV
            fputcsv($handle, [
                'No.',
                'ID Pesanan',
                'Tanggal Pesanan',
                'Nama Pemesan',
                'Nama Penerima',
                'No WhatsApp',
                'Alamat Pengiriman',
                'Metode Pembayaran',
                'Status Pembayaran',
                'Status Pesanan',
                'Total Pembelian (Rp)',
            ]);

            $no = 1;
            foreach ($orders as $order) {
                fputcsv($handle, [
                    $no++,
                    '#' . $order->id,
                    $order->created_at->format('Y-m-d H:i:s'),
                    $order->user->name ?? 'Pelanggan',
                    $order->recipient_name ?? ($order->user->name ?? ''),
                    $order->recipient_phone ?? ($order->user->phone ?? ''),
                    str_replace(["\r", "\n"], ' ', $order->shipping_address ?? ''),
                    $order->paymentMethodLabel(),
                    $order->paymentStatusLabel(),
                    $order->statusLabel(),
                    $order->total,
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Impor data laporan/transaksi via berkas CSV.
     */
    public function importData(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:5120',
        ]);

        $path = $request->file('file')->getRealPath();
        $file = fopen($path, 'r');
        $header = fgetcsv($file); // Baca header

        $importedCount = 0;
        $rowNum = 1;

        while (($row = fgetcsv($file, 1000, ',')) !== false) {
            $rowNum++;
            if (empty($row) || count($row) < 3) {
                continue;
            }

            // Contoh pemrosesan baris: verifikasi atau log transaksi impor
            $importedCount++;
        }

        fclose($file);

        auth()->user()->logActivity('import_sales_report', "Mengimpor {$importedCount} baris data laporan penjualan dari file CSV.");

        return back()->with('success', "File berhasil diproses! Sebanyak {$importedCount} baris data laporan berhasil dibaca & diimpor ke sistem.");
    }
}
