@extends('layouts.app')

@section('title')
    Data Pembelian
@endsection

@push('css')
    
@endpush

@section('content')
@include('components.alert.success')
<div class="card">
    @can('tambah purchase')
        <div class="card-header">
            <a href="{{ route('purchase.create') }}" class="btn btn-primary">
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
    <div class="table-responsive">
        <table class="table card-table table-vcenter text-nowrap datatable" style="min-width: 1200px">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode</th>
                    <th>Supplier</th>
                    <th>Total</th>
                    <th>Tanggal</th>
                    <th>Status</th>
                    @canany(['edit purchase', 'hapus purchase'])
                        <th>Action</th>
                    @endcanany
                </tr>
            </thead>
            <tbody>
                @forelse ($purchase as $item)
                    <tr>
                        <td>{{$loop->iteration}}</td>
                        <td>{{$item->code}}</td>
                        <td>{{$item->supplier->name}}</td>
                        <td>Rp.{{number_format($item->item->sum('total'))}}</td>
                        <td>{{\Carbon\Carbon::parse($item->date)->format('d F Y')}}</td>
                        <td>
                            @if ($item->status === 'accepted')
                                <span class="badge bg-success text-white">
                                    Diterima
                                </span>
                            @else
                                <span class="badge bg-warning text-white">
                                    Pending
                                </span>
                            @endif
                        </td>
                        @canany(['edit purchase', 'hapus purchase'])
                            <td>
                                @can('edit purchase')
                                    <a href="{{ route('purchase.export', ['code' => $item->code]) }}" class="btn btn-info btn-md">
                                        Resi
                                    </a>
                                    @if ($item->status === 'pending')
                                        @if ($item->status === 'pending')
                                            <a href="{{ route('purchase.edit', ['id' => $item->id]) }}" class="btn btn-warning btn-md">
                                                Edit
                                            </a>
                                        @endif
                                    @endif
                                @endcan
                                @if ($item->status === 'pending')
                                    @can('hapus purchase')
                                        <a href="javascript:void(0)" onclick="return deleteItem('{{ $item->id }}')" class="btn btn-danger btn-md">
                                            Hapus
                                        </a>
                                    @endcan
                                @endif
                            </td> 
                        @endcanany
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">Tidak Ada Data</td>
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
    <script>
        const BASE = "{{ route('purchase') }}";

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