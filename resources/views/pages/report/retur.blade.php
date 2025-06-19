@extends('layouts.app')

@section('title', 'Laporan Pembelian')

@push('css')
    
@endpush

@section('content')
<div class="card">
    <div class="card-body border-bottom py-3">
        <div class="row">
            <div class="col-lg-4">
                <form class="d-flex">
                    <select name="code" id="code" class="form-control me-2">
                        <option value="">Semua</option>
                        @foreach ($purchase as $prc)
                            <option value="{{ $prc->code }}" {{ request('code') == $prc->code ? 'selected' : '' }}>
                                {{ $prc->code }}
                            </option>
                        @endforeach
                    </select>
                    <a href="{{route('report.export', ['code' => request('code')])}}" class="btn btn-danger">
                        Unduh Report
                    </a>
                </form>
            </div>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table card-table table-vcenter text-nowrap datatable" style="min-width: 1200px">
            <thead>
                <tr>
                    <th>Nomor PO</th>
                    <th>Product</th>
                    <th>Jumlah</th>
                    <th>Alasan</th>
                    <th>User</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($retur as $item)
                    <tr>
                        <td>{{$item->purchase->code}}</td>
                        <td>{{$item->product->name}}</td>
                        <td>{{$item->qty}}</td>
                        <td>{{$item->message}}</td>
                        <td>{{$item->user->name}}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">Tidak Ada Data Pengembalian</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('js')
    <script>
        const BASE = "{{ route('report.retur') }}";

        let params = new URLSearchParams(window.location.search);
        $("#code").change(function() {
            params.set('code', $(this).val());
            window.location.href = BASE + '?' + params.toString();
        });
    </script>
@endpush