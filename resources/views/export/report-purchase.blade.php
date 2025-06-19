<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Resi Pembelian {{$purchase->code}}</title>
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
    </style>
</head>
<body>

    <div class="header">
        <h1>Laporan Pembelian</h1>
        <h4>Supplier: {{ $purchase->supplier->name }}</h4>
        <h4>Tanggal: {{ $purchase->date }}</h4>
        <h4>Kode: {{ $purchase->code }}</h4>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th style="text-align: center">No</th>
                <th>Product</th>
                <th style="text-align: center">Jumlah</th>
                <th style="text-align: center">Harga Satuan</th>
                <th style="text-align: center">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($purchase->item as $i => $item)
            <tr>
                <td style="text-align: center">{{ $i + 1 }}</td>
                <td>{{ $item->product->name }}</td>
                <td style="text-align: center">{{ $item->qty }}</td>
                <td style="text-align: center">{{ number_format($item->product->harga_jual, 0, ',', '.') }}</td>
                <td style="text-align: center">{{ number_format($item->qty * $item->product->harga_jual, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th style="text-align: center" colspan="4">Total</th>
                <th style="text-align: center">Rp. {{number_format($purchase->item->sum('total'), 0, ',', '.')}}</th>
            </tr>
        </tfoot>
    </table>
</body>
</html>
