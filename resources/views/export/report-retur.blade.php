<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Laporan Pengembalian Product</title>
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
        <h1 style="text-align: center">Laporan Pengembalian Product</h1>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th style="text-align: center">No</th>
                <th>Nomor PO</th>
                <th style="text-align: center">Product</th>
                <th style="text-align: center">Jumlah</th>
                <th style="text-align: center">Alasan</th>
                <th style="text-align: center">User</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($retur as $item)
                <tr>
                    <td style="text-align: center">{{$loop->iteration}}</td>
                    <td>{{$item->purchase->code}}</td>
                    <td style="text-align: center">{{$item->product->name}}</td>
                    <td style="text-align: center">{{$item->qty}}</td>
                    <td style="text-align: center">{{$item->message}}</td>
                    <td style="text-align: center">{{$item->user->name}}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">Tidak Ada Data Pengembalian</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
