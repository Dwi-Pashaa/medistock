@extends('layouts.app')

@section('title', 'Laporan Penjualan')

@push('css')
    
@endpush

@section('content')
<div class="card">
    <div class="card-body border-bottom py-3">
        <form class="row">
            <div class="col-lg-4">
                <input type="date" name="start" id="start" class="form-control">
            </div>
            <div class="col-lg-4">
                <input type="date" name="end" id="end" class="form-control">
            </div>
            <div class="col-lg-4">
                <button type="submit" class="btn btn-primary">Filter</button>
                <a 
                    href="{{ route('report.transaction.export', ['start' => request('start'), 'end' => request('end')]) }}" 
                    class="btn btn-danger"
                >
                    Unduh PDF
                </a>
            </div>
        </form>
    </div>
    <div class="table-responsive">
        <table class="table card-table table-vcenter text-nowrap datatable" style="min-width: 1200px">
            <thead>
                <tr>
                    <th>Invoice</th>
                    <th>Tanggal</th>
                    <th>Customer</th>
                    <th>Product</th>
                    <th>Total Product</th>
                    <th>Total Harga</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($transaction as $item)
                    <tr>
                        <td>{{$item->invoice}}</td>
                        <td>{{$item->date}}</td>
                        <td>{{$item->customer->name}}</td>
                        <td>
                            <ul>
                                @foreach ($item->item as $prd)
                                    <li>{{$prd->product->name}} ({{$prd->qty}})</li>
                                @endforeach
                            </ul>
                        </td>
                        <td>{{count($item->item)}} Product</td>
                        <td>Rp. {{number_format($item->item->sum('total'))}}</td>
                        <td>
                            @if ($item->status === 'pending')
                                <span class="badge bg-warning text-white">
                                    Menunggu Pembayaran
                                </span>
                            @elseif($item->status === 'paid')
                                <span class="badge bg-success text-white">
                                    Dibayar
                                </span>
                            @else
                                <span class="badge bg-danger text-white">
                                    Dibatalkan
                                </span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="text-center" colspan="7">Tidak Ada Data Penjualan</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('js')

@endpush