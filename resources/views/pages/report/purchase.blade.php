@extends('layouts.app')

@section('title', 'Laporan Pembelian')

@push('css')
    
@endpush

@section('content')
<div class="card">
    <div class="card-body border-bottom py-3">
        <div class="d-flex">
            <div class="text-secondary">
                <div class="mx-2 d-inline-block">
                    <select name="sort" id="sort" class="form-control">
                        @php
                            $opts = [
                                10,25,50,100
                            ];
                        @endphp 
                        @foreach ($opts as $opt)
                            <option value="{{ $opt }}" {{ request('sort') == $opt ? 'selected' : '' }}>{{ $opt }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="ms-auto text-secondary">
                <form>
                    <div class="input-group mb-2">
                        <input type="text" class="form-control" name="search" placeholder="Search for…">
                        <button class="btn" type="submit">
                            <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-search"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" /><path d="M21 21l-6 -6" /></svg>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table card-table table-vcenter text-nowrap datatable" style="min-width: 1200px">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Tanggal</th>
                    <th>Supplier</th>
                    <th>Total</th>
                    <th>Status</th>
                    @can('unduh report')
                        <th>Action</th>
                    @endcan
                </tr>
            </thead>
            <tbody>
                @forelse ($purchase as $item)
                    <tr>
                        <td>
                            {{$item->code}}
                        </td>
                        <td>
                            {{$item->date}}
                        </td>
                        <td>
                            {{$item->supplier->name}}
                        </td>
                        <td>
                            Rp. {{number_format($item->item->sum('total'))}}
                        </td>
                        <td>
                            @if ($item->status === 'accepted')
                                <span class="badge bg-primary text-white">
                                    Complete
                                </span>
                            @else
                                <span class="badge bg-warning text-white">
                                    Pending
                                </span>
                            @endif
                        </td>
                        @can('unduh report')
                            <td>
                                <a href="{{route('report.detailPurchase', ['code' => $item->code])}}" class="btn btn-primary">
                                    Detail
                                </a>
                                <a href="{{route('report.purchase.export', ['code' => $item->code])}}" class="btn btn-success">
                                    Unduh
                                </a>
                            </td>
                        @endcan
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">Tidak Ada Data</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer d-flex align-items-center">
        <p class="m-0 text-secondary">
            Showing <span>{{ $purchase->firstItem() }}</span> 
            to <span>{{ $purchase->lastItem() }}</span> of
            <span>{{ $purchase->total() }}</span> entries
        </p>
        <ul class="pagination m-0 ms-auto">
            {{ $purchase->links() }}
        </ul>
    </div>
</div>
@endsection

@push('js')
    
@endpush