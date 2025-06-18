@extends('layouts.app')

@section('title', 'Tambah Product')

@push('css')
    
@endpush

@section('content')
<form action="{{route('product.store')}}" method="POST">
    @csrf
    <div class="card">
        <div class="card-header">
            <a href="{{route('product')}}" class="btn btn-primary">
                Kembali
            </a>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-6">
                    <div class="form-group mb-3">
                        <label for="" class="mb-2">Barcode</label>
                        <input type="text" name="code" id="code" class="form-control @error('code') is-invalid @enderror">
                        @error('code')
                            <span class="invalid-feedback">
                                {{$message}}
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group mb-3">
                        <label for="" class="mb-2">Nama Product</label>
                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror">
                        @error('name')
                            <span class="invalid-feedback">
                                {{$message}}
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="form-group mb-3">
                        <label for="" class="mb-2">Stock <span class="text-info" style="font-size: 12px">(Stock hanya bisa di isi dari menu received)</span></label>
                        <input type="text" name="" id="" class="form-control" value="0" disabled>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group mb-3">
                        <label for="" class="mb-2">Harga Beli</label>
                        <input type="text" name="harga_beli" id="harga_beli" class="form-control @error('harga_beli') is-invalid @enderror">
                        @error('harga_beli')
                            <span class="invalid-feedback">
                                {{$message}}
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group mb-3">
                        <label for="" class="mb-2">Harga Jual</label>
                        <input type="text" name="harga_jual" id="harga_jual" class="form-control @error('harga_jual') is-invalid @enderror">
                        @error('harga_jual')
                            <span class="invalid-feedback">
                                {{$message}}
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group mb-3">
                        <label for="" class="mb-2">Kategori</label>
                        <select name="kategori_id" id="kategori_id" class="form-control @error('kategori_id') is-invalid @enderror">
                            <option value="">Pilih</option>
                            @foreach ($kategori as $ktg)
                                <option value="{{$ktg->id}}">{{$ktg->name}}</option>
                            @endforeach
                        </select>
                        @error('kategori_id')
                            <span class="invalid-feedback">
                                {{$message}}
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group mb-3">
                        <label for="" class="mb-2">Supplier</label>
                        <select name="supplier_id" id="supplier_id" class="form-control @error('supplier_id') is-invalid @enderror">
                            <option value="">Pilih</option>
                            @foreach ($supplier as $sup)
                                <option value="{{$sup->id}}">{{$sup->name}}</option>
                            @endforeach
                        </select>
                        @error('supplier_id')
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
    
@endpush