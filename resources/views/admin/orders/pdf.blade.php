<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Pesanan - Syamama Kitchen</title>
    <style>
        @page {
            margin: 15mm;
        }
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 10pt;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 3px solid #FA7302;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0 0 5px 0;
            color: #FA7302;
            font-size: 18pt;
        }
        .header p {
            margin: 0;
            color: #666;
            font-size: 9pt;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        table thead th {
            background-color: #FA7302;
            color: white;
            padding: 8px 5px;
            text-align: left;
            font-size: 9pt;
            border: 1px solid #ddd;
        }
        table tbody td {
            padding: 6px 5px;
            border: 1px solid #ddd;
            font-size: 8.5pt;
            vertical-align: top;
        }
        table tbody tr:nth-child(even) {
            background-color: #FFF3E8;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .status {
            padding: 3px 8px;
            border-radius: 12px;
            font-weight: bold;
            font-size: 8pt;
            display: inline-block;
        }
        .status-pending {
            background-color: #FFF3E0;
            color: #E65100;
        }
        .status-completed {
            background-color: #E8F5E9;
            color: #388E3C;
        }
        .status-cancelled {
            background-color: #FFEBEE;
            color: #C62828;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 8pt;
            color: #999;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Laporan Pesanan - Syamama Kitchen</h1>
        <p>Diekspor pada: {{ $exportDate }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="3%">No</th>
                <th width="10%">Kode Order</th>
                <th width="12%">Pelanggan</th>
                <th width="10%">Telepon</th>
                <th width="15%">Alamat</th>
                <th width="20%">Item Pesanan</th>
                <th width="10%">Total</th>
                <th width="10%">Status</th>
                <th width="10%">Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $index => $order)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $order->order_code }}</td>
                    <td>{{ $order->customer_name }}</td>
                    <td>{{ $order->customer_phone }}</td>
                    <td>{{ $order->customer_address }}</td>
                    <td>
                        @foreach($order->items as $item)
                            {{ $item->product_name }} x{{ $item->quantity }}@if(!$loop->last), @endif
                        @endforeach
                    </td>
                    <td class="text-right">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                    <td class="text-center">
                        <span class="status status-{{ $order->status }}">
                            @if($order->status == 'pending') Pending
                            @elseif($order->status == 'processing') Proses
                            @elseif($order->status == 'completed') Selesai
                            @elseif($order->status == 'cancelled') Batal
                            @else {{ $order->status }}
                            @endif
                        </span>
                    </td>
                    <td class="text-center">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>&copy; {{ date('Y') }} Syamama Kitchen - Healthy & Natural</p>
    </div>
</body>
</html>
