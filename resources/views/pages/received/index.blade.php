@extends('layouts.app')

@section('title', 'Daftar Penerimaan Barang')

@push('css')
@endpush

@section('content')
<div class="card">
    <div class="card-body">
        <form>
            <div class="row">
                <div class="col-lg-8">
                    <div class="form-group mb-3">
                        <label class="mb-2">Nomor PO</label>
                        <select name="code" id="code" class="form-control">
                            <option value="">Pilih</option>
                            @foreach ($purchaseAll as $item)
                                <option value="{{ $item->code }}" {{ request('code') == $item->code ? 'selected' : '' }}>
                                    {{ $item->code }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-lg-4">
                    <button type="submit" class="btn btn-primary w-100 mt-4">Cari</button>
                </div>
            </div>
        </form>
    </div>

    <div class="table-responsive p-3">
        <form action="{{route('received.store')}}" method="POST" id="form-accepted">
            @csrf
            <input type="hidden" name="code" id="code" value="{{request('code')}}">
            <table class="table table-bordered table-striped text-center">
                <thead class="thead-dark">
                    <tr>
                        <th>No</th>
                        <th>Nama Produk</th>
                        <th>Jumlah</th>
                        <th>Total Harga</th>
                    </tr>
                </thead>
                <tbody>
                    @if (request()->has('code') && request('code') != '')
                        @forelse ($items as $data)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    {{ $data->product->name ?? '-' }}
                                    <input type="hidden" name="product_id[]" id="product_id" value="{{$data->product->id}}">
                                </td>
                                <td>
                                    {{ $data->qty ?? '-' }}
                                    <input type="hidden" name="stock[]" id="stock" value="{{$data->qty}}">
                                </td>
                                <td>Rp {{number_format($data->total) ?? '-'}}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-danger">Data tidak ditemukan untuk kode tersebut.</td>
                            </tr>
                        @endforelse
                    @else
                        <tr>
                            <td colspan="5" class="text-muted">Silakan pilih kode PO terlebih dahulu.</td>
                        </tr>
                    @endif
                </tbody>
                @if (count($items) > 0)
                    <tfoot>
                        <tr>
                            <th colspan="5">
                                <button type="button" class="btn btn-primary w-100" id="submitReceived">Terima Barang</button>
                            </th>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </form>
    </div>
</div>
@endsection

@push('js')
<script>
    document.getElementById('submitReceived').addEventListener('click', function () {
        document.getElementById('form-accepted').submit();
    });
</script>
@endpush
