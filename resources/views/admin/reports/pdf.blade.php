<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Laporan Penjualan — Sepatu.id</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #111;
            margin: 20px;
        }
        .header {
            text-align: center;
            border-b: 2px solid #111;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .header h1 {
            font-size: 20px;
            margin: 0;
            text-transform: uppercase;
        }
        .header p {
            margin: 2px 0 0 0;
            color: #666;
            font-size: 11px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f5f5f5;
            text-transform: uppercase;
            font-size: 10px;
        }
        .text-right {
            text-align: right;
        }
        .total-box {
            margin-top: 20px;
            text-align: right;
            font-size: 14px;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>SEPATU.ID — LAPORAN PENJUALAN OFFICIAL</h1>
        <p>Dicetak pada: {{ now()->format('d F Y H:i:s') }} WIB</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Order ID</th>
                <th>Pemesan / Penerima</th>
                <th>Tanggal Order</th>
                <th>Status</th>
                <th class="text-right">Diskon</th>
                <th class="text-right">Total (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $index => $order)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>#{{ $order->id }}</td>
                    <td>
                        {{ $order->recipient_name ?? $order->user->name ?? 'Pelanggan' }}
                        @if($order->recipient_phone)
                            <br><small style="color: #666;">{{ $order->recipient_phone }}</small>
                        @endif
                    </td>
                    <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                    <td>{{ strtoupper($order->status) }}</td>
                    <td class="text-right">
                        @if($order->discount_amount > 0)
                            -Rp {{ number_format($order->discount_amount, 0, ',', '.') }}
                        @else
                            -
                        @endif
                    </td>
                    <td class="text-right">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center;">Belum ada data penjualan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="total-box">
        Total Pendapatan Selesai: Rp {{ number_format($totalRevenue, 0, ',', '.') }}
    </div>

</body>
</html>
