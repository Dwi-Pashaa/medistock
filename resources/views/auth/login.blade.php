@extends('layouts.auth')

@section('title', 'Login')

@section('content')
    <h2 class="h2 text-center mb-5">{{ config('app.name') }}</h2>
    <hr class="divide">
    <form action="{{route('post.login')}}" method="POST" autocomplete="off" novalidate id="form-login">
        @csrf
        <div class="form-group mb-3">
            <label class="form-label">
                Email <span class="text-danger">*</span>
            </label>
            <input type="text" name="email" id="email" class="form-control @error('email') is-invalid @enderror" placeholder="Email" autocomplete="off" />
            @error('email')
                <span class="invalid-feedback">
                    {{ $message }}
                </span>
            @enderror
        </div>
        <div class="form-group mb-3">
            <label class="form-label">
                Password
                <span class="text-danger">*</span>
            </label>
            <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" placeholder="Password" autocomplete="off" />
            @error('password')
                <span class="invalid-feedback">
                    {{ $message }}
                </span>
            @enderror
        </div>
        <div class="form-footer">
            <button type="submit" class="btn btn-primary w-100">
                Login
            </button>
        </div>
    </form>
@endsection