@extends('layouts.app')

@section('title', 'Detail Pembelian')

@push('css')
    
@endpush

@section('content')
    <div class="card">
        <div class="card-header">
            <b>Detail Pembelian</b>
        </div>
        <div class="card-body">
            <h4>Nomor PO : #{{$purchase->code}}</h4>
            <h4>Supplier : {{$purchase->supplier->name}}</h4>
            <h4>Tanggal  : {{$purchase->date}}</h4>
        </div>
        <div class="table-responsive">
            <table class="table card-table table-vcenter text-nowrap text-center datatable" style="min-width: 1200px">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Qty</th>
                        <th>Harga</th>
                        <th>SubTotal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($purchase->item as $item)
                        <tr>
                            <td>{{$item->product->name}}</td>
                            <td>{{$item->qty}}</td>
                            <td>{{number_format($item->product->harga_jual)}}</td>
                            <td>{{number_format($item->product->harga_jual * $item->qty)}}</td>
                        </tr>
                    @empty
                        
                    @endforelse
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="3">Total</th>
                        <th>Rp.{{number_format($purchase->item->sum('total'))}}</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
@endsection

@push('js')
    
@endpush