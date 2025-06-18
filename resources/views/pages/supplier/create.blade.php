@extends('layouts.app')

@section('title')
    Tambah Supplier
@endsection

@push('css')
    
@endpush

@section('content')
<div class="card">
    <div class="card-header">
        <a href="{{ route('supplier') }}" class="btn btn-primary">
            Kembali
        </a>
    </div>
    <div class="card-body">
        <form action="{{ route('supplier.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="form-group mb-3">
                        <label for="name" class="mb-2">Nama</label>
                        <input value="{{ old('name') }}" type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror">
                        @error('name')
                            <span class="invalid-feedback">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="form-group mb-3">
                        <label for="email" class="mb-2">Email</label>
                        <input value="{{ old('email') }}" type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror">
                        @error('email')
                            <span class="invalid-feedback">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="form-group mb-3">
                        <label for="contact" class="mb-2">Kontak</label>
                        <input value="{{ old('contact') }}" type="text" name="contact" id="contact" class="form-control @error('contact') is-invalid @enderror">
                        @error('contact')
                            <span class="invalid-feedback">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="form-group mb-3">
                        <label for="address" class="mb-2">Alamat</label>
                        <input value="{{ old('address') }}" type="text" name="address" id="address" class="form-control @error('address') is-invalid @enderror">
                        @error('address')
                            <span class="invalid-feedback">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="mt-3">
                <button type="reset" class="btn btn-secondary float-start">Reset</button>
                <button type="submit" class="btn btn-primary float-end">Tambah</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('js')
    
@endpush