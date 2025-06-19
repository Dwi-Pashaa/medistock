<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Laporan Penjualan Product</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
            margin: 20px;
        }

        h1, h2, h3 {
            margin: 0;
            padding: 0;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .table th,
        .table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }

        .table th {
            background-color: #f2f2f2;
        }

        .header,
        .footer {
            margin-bottom: 20px;
        }

        @page {
            size: A4 landscape;
            margin: 20mm;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1 style="text-align: center">Laporan Penjualan Product</h1>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>Invoice</th>
                <th style="text-align: center">Tanggal</th>
                <th style="text-align: center">Customer</th>
                <th style="text-align: center">Product</th>
                <th style="text-align: center">Total Product</th>
                <th style="text-align: center">Total Harga</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($transaction as $item)
                    <tr>
                        <td>{{$item->invoice}}</td>
                        <td style="text-align: center">{{$item->date}}</td>
                        <td style="text-align: center">{{$item->customer->name}}</td>
                        <td>
                            <ul>
                                @foreach ($item->item as $prd)
                                    <li>{{$prd->product->name}} ({{$prd->qty}})</li>
                                @endforeach
                            </ul>
                        </td>
                        <td style="text-align: center">{{count($item->item)}} Product</td>
                        <td style="text-align: center">Rp. {{number_format($item->item->sum('total'))}}</td>
                        <td style="text-align: center">
                            @if ($item->status === 'pending')
                                Menunggu Pembayaran
                            @elseif($item->status === 'paid')
                                Dibayar
                            @else
                                Dibatalkan
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td style="text-align: center" colspan="7">Tidak Ada Data Penjualan</td>
                    </tr>
                @endforelse
        </tbody>
    </table>
</body>
</html>
