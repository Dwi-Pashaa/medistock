@extends('layouts.app')

@section('title')
    Edit Customer
@endsection

@push('css')
    
@endpush

@section('content')
<div class="card">
    <div class="card-header">
        <a href="{{ route('customer') }}" class="btn btn-primary">
            Kembali
        </a>
    </div>
    <div class="card-body">
        <form action="{{ route('customer.update', ['id' => $customer->id]) }}" method="POST">
            @csrf
            @method("PUT")
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="form-group mb-3">
                        <label for="name" class="mb-2">Nama</label>
                        <input value="{{ $customer->name }}" type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror">
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
                        <input value="{{ $customer->email }}" type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror">
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
                        <input value="{{ $customer->contact }}" type="text" name="contact" id="contact" class="form-control @error('contact') is-invalid @enderror">
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
                        <input value="{{ $customer->address }}" type="text" name="address" id="address" class="form-control @error('address') is-invalid @enderror">
                        @error('address')
                            <span class="invalid-feedback">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="form-group mb-3">
                <label for="status" class="mb-2">Status</label>
                <select name="status" id="status" class="form-control @error('status') is-invalid @enderror">
                    <option value="">Pilih</option>
                    <option value="active" {{$customer->status === "active" ? 'selected' : ''}}>Aktif</option>
                    <option value="disabled" {{$customer->status === "disabled" ? 'selected' : ''}}>Tidak Aktif</option>
                </select>
                @error('status')
                    <span class="invalid-feedback">
                        {{ $message }}
                    </span>
                @enderror
            </div>

            <div class="mt-3">
                <button type="reset" class="btn btn-secondary float-start">Reset</button>
                <button type="submit" class="btn btn-primary float-end">Ubah</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('js')
    
@endpush