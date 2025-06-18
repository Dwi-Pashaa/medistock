@extends('layouts.app')

@section('title', 'Buat Pengembalian')

@push('css')
    
@endpush

@section('content')
<form action="{{route('retur.store')}}" method="POST">
    @csrf
    <div class="card">
        <div class="card-header">
            <a href="{{route('retur')}}" class="btn btn-primary">
                Kembali
            </a>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-6">
                    <div class="form-group mb-3">
                        <label for="" class="mb-2">Nomer PO</label>
                        <select name="purchase_id" id="purchase_id" class="form-control @error('purchase_id') is-invalid @enderror">
                            <option value="">Pilih</option>
                            @foreach ($purchase as $item)
                                <option value="{{$item->id}}">{{$item->code}}</option>
                            @endforeach
                        </select>
                        @error('purchase_id')
                            <span class="invalid-feedback">
                                {{$message}}
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group mb-3">
                        <label for="" class="mb-2">Product</label>
                        <select name="product_id" id="product_id" class="form-control @error('product_id') is-invalid @enderror">
                            <option value="">Pilih</option>
                        </select>
                        @error('product_id')
                            <span class="invalid-feedback">
                                {{$message}}
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="form-group mb-3">
                        <label for="" class="mb-2">Jumlah</label>
                        <input type="number" name="qty" id="qty" class="form-control @error('qty') is-invalid @enderror">
                        @error('qty') 
                            <span class="invalid-feedback">
                                {{$message}}
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="form-group mb-3">
                        <label for="" class="mb-2">Alasan</label>
                        <input type="text" name="message" id="message" class="form-control @error('message') is-invalid @enderror">
                        @error('message') 
                            <span class="invalid-feedback">
                                {{$message}}
                            </span>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="reset" class="btn btn-secondary float-start">Reset</button>
            <button type="submit" class="btn btn-primary float-end">Tambah</button>
        </div>
    </div>
</form>
@endsection

@push('js')
    <script>
        $(document).ready(function() {
            $("#purchase_id").change(function() {
                let purchase_id = $(this).val()

                $.ajax({
                    url: "{{route('retur.getItem')}}",
                    method: "POST",
                    data: {purchase_id: purchase_id},
                    success: function(response) {
                        console.log(response);
                        let html = '';
                        html += ' <option value="">Pilih</option>';
                        $.each(response.data, function(index, value) {
                            html += `<option value="${value.product.id}">${value.product.name}</option>`;
                        })
                        $("#product_id").html(html);
                    },
                    error: function(err) {
                        console.log(err);
                    }
                })
            })
        })
    </script>
@endpush