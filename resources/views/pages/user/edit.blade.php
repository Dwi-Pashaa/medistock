@extends('layouts.app')

@section('title')
    {{ $user->name }}
@endsection

@push('css')
    
@endpush

@section('content')
<div class="card">
    <div class="card-header">
        <a href="{{ route('user') }}" class="btn btn-primary">
            Kembali
        </a>
    </div>
    <div class="card-body">
        <form action="{{ route('user.update', ['id' => $user->id]) }}" method="POST">
            @csrf
            @method("PUT")
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="form-group mb-3">
                        <label for="name" class="mb-2">Nama Lengkap</label>
                        <input value="{{ $user->name }}" type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror">
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
                        <input value="{{ $user->email }}" type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror">
                        @error('email')
                            <span class="invalid-feedback">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="form-group mb-3">
                <label for="username" class="mb-2">Level</label>
                <select name="role" id="role" class="form-control @error('role') is-invalid @enderror">
                    <option value="">Pilih</option>
                    @foreach ($role as $item)
                        <option value="{{ $item->name }}" {{ $user->roles[0]->name == $item->name ? 'selected' : '' }}>{{ $item->name }}</option>
                    @endforeach
                </select>
                @error('role')
                    <span class="invalid-feedback">
                        {{ $message }}
                    </span>
                @enderror
            </div>
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="form-group mb-2">
                        <label for="password" class="mb-2">Password</label>
                        <input value="{{ old('password') }}" type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror">
                        @error('password')
                            <span class="invalid-feedback">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                    <span class="text-muted mt-1">Kosongkan jika tidak ingin mengubah password</span>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="form-group mb-2">
                        <label for="password_confirmation" class="mb-2">Konfirmasi Password</label>
                        <input value="{{ old('password_confirmation') }}" type="password" name="password_confirmation" id="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror">
                        @error('password_confirmation')
                            <span class="invalid-feedback">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                    <span class="text-muted">Kosongkan jika tidak ingin mengubah password</span>
                </div>
            </div>

            <div class="mt-3">
                <button type="reset" class="btn btn-secondary float-start">Reset</button>
                <button type="submit" class="btn btn-primary float-end">Update</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('js')
    
@endpush