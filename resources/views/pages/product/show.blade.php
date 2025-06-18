@extends('layouts.app')

@section('title', 'Cetak Barcode')

@push('css')
    
@endpush

@section('content')
    <div class="card">
        <div class="card-header">
            <a href="{{ route('product') }}" class="btn btn-primary">Kembali</a>
        </div>
        <div class="card-body">
            <form>
                <div class="row mb-5 justify-content-center">
                    <div class="col-lg-3">
                        <div class="form-group mb-3">
                            <label for="count" class="mb-2">Jumlah Barcode</label>
                            <input type="number" name="count" id="count" class="form-control" value="{{ $count ?? 10 }}">
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <div class="form-group mb-3">
                            <button type="submit" class="btn btn-primary w-100 mt-4">Tampilkan</button>
                        </div>
                    </div>
                </div>
            </form>
            <div id="printArea" class="row justify-content-center">
                @for ($i = 1; $i <= ($count ?? 10); $i++)
                    <div class="col-lg-3 col-md-4 col-sm-6 mb-4 barcode-item">
                        {!! DNS1D::getBarcodeHTML($product->code, 'C128', 1.5, 40) !!}
                        <div class="code text-secondary mt-2">{{ $product->code }}</div>
                    </div>
                @endfor
            </div>
        </div>
        <div class="card-footer">
            <a href="javascript:void(0)" onclick="cetak()" class="btn btn-danger w-100">Cetak Semua Barcode</a>
        </div>
    </div>
@endsection

@push('js')
<script>
    function cetak() {
        const printContents = document.getElementById('printArea').innerHTML;
        const win = window.open('', '_blank');

        win.document.open();
        win.document.write(`
            <html>
                <head>
                    <title>Preview Barcode</title>
                    <style>
                        @media print {
                            @page {
                                size: landscape;
                                margin: 10mm;
                            }
                            body {
                                font-family: Arial, sans-serif;
                                text-align: center;
                                margin: 0;
                                padding: 0;
                            }
                            .barcode-container {
                                display: flex;
                                flex-wrap: wrap;
                                justify-content: center;
                            }
                            .barcode-item {
                                width: 25%;
                                padding: 10px;
                                box-sizing: border-box;
                            }
                            .barcode-item .code {
                                margin-top: 8px;
                                font-size: 14px;
                                color: #000;
                            }
                        }
                    </style>
                </head>
                <body>
                    <div class="barcode-container">
                        ${printContents}
                    </div>
                    <script>
                        window.onload = function() {
                            window.print();
                            window.onafterprint = function() {
                                window.close();
                            }
                        }
                    <\/script>
                </body>
            </html>
        `);
        win.document.close();
    }
</script>
@endpush