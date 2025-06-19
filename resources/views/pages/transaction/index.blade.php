@extends('layouts.app')

@section('title')
    Data Penjualan
@endsection

@push('css')
    
@endpush

@section('content')
@include('components.alert.success')
<div class="card">
    @can('tambah sale')
        <div class="card-header">
            <a href="{{route('transaction.create')}}" class="btn btn-primary">
                Tambah
            </a>
        </div>
    @endcan
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
    <div class="table-responsive-lg">
        <table class="table card-table table-vcenter text-nowrap datatable">
            <thead>
                <tr>
                    <th>Invoice</th>
                    <th>Tanggal</th>
                    <th>Customer</th>
                    <th>Total Product</th>
                    <th>Total Harga</th>
                    <th>Status</th>
                    @canany(['edit sale', 'hapus sale'])
                        <th>Action</th>
                    @endcanany
                </tr>
            </thead>
            <tbody>
                @forelse ($transaction as $item)
                    <tr>
                        <td>
                            {{$item->invoice}}
                        </td>
                        <td>
                            {{$item->date}}
                        </td>
                        <td>
                            {{$item->customer->name}}
                        </td>
                        <td>
                            {{count($item->item)}} Product
                        </td>
                        <td>
                            Rp. {{number_format($item->item->sum('total'))}}
                        </td>
                        <td>
                            <form action="{{route('transaction.updateStatus', ['id' => $item->id])}}" id="status-form" method="POST">
                                @csrf
                                @method("PUT")
                                <select name="status" id="status" class="form-control" onchange="document.getElementById('status-form').submit();">
                                    @php
                                        $status = [
                                            [
                                                'key' => 'pending',
                                                'name' => 'Menunggu Pembayaran'
                                            ], 
                                            [
                                                'key' => 'paid',
                                                'name' => 'Dibayarkan'
                                            ], 
                                            [
                                                'key' => 'unpaid',
                                                'name' => 'Dibatalkan'
                                            ]
                                        ];
                                    @endphp
                                    @foreach ($status as $st)
                                        <option value="{{$st['key']}}" {{$st['key'] === $item->status ? 'selected' : ''}}>{{$st['name']}}</option>
                                    @endforeach
                                </select>
                            </form>
                        </td>
                        @canany(['edit sale', 'hapus sale'])
                            <td>
                                @can('edit sale')
                                    <a href="{{ route('transaction.export', ['invoice' => $item->invoice]) }}" class="btn btn-info btn-md">
                                        Resi
                                    </a>
                                    @if ($item->status === 'pending')
                                        <a href="{{ route('transaction.edit', ['id' => $item->id]) }}" class="btn btn-warning btn-md">
                                            Edit
                                        </a>
                                    @endif
                                @endcan
                                @can('hapus sale')
                                    <a href="javascript:void(0)" onclick="return deleteItem('{{ $item->id }}')" class="btn btn-danger btn-md">
                                        Hapus
                                    </a>
                                @endcan
                            </td> 
                        @endcanany
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">Tidak Ada Data</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer d-flex align-items-center">
        <p class="m-0 text-secondary">
            Showing <span>{{ $transaction->firstItem() }}</span> 
            to <span>{{ $transaction->lastItem() }}</span> of
            <span>{{ $transaction->total() }}</span> entries
        </p>
        <ul class="pagination m-0 ms-auto">
            {{ $transaction->links() }}
        </ul>
    </div>
</div>
@endsection

@push('js')
    <script>
        const BASE = "{{ route('transaction') }}";

        let params = new URLSearchParams(window.location.search);
        $("#sort").change(function() {
            params.set('sort', $(this).val());
            window.location.href = BASE + '?' + params.toString();
        });

        const Toast = Swal.mixin({
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.onmouseenter = Swal.stopTimer;
                toast.onmouseleave = Swal.resumeTimer;
            }
        });

        function deleteItem(id) {
            Swal.fire({
                title: "Peringatan !",
                text: "Anda yakin ingin menghapus data ini?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Hapus",
                cancelButtonText: "Batal"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: BASE + '/' + id + '/destroy',
                        method: "DELETE",
                        dataType: "json",
                        success: function(response) {
                            Toast.fire({
                                icon: response.status,
                                title: response.message
                            });

                            setTimeout(() => {
                                window.location.reload();
                            }, 3000);
                        },
                        error: function(err) {
                            Toast.fire({
                                icon: "error",
                                title: "Server Error"
                            });
                        }
                    })
                }
            });
        }
    </script>
@endpush