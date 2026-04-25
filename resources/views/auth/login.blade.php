@extends('layouts.auth')
@section('title', 'Login')

@section('content')

    <div class="text-center mb-4">
        <h3 class="fw-bold text-uppercase mb-1">Login</h3>
        <p class="small mb-0">Enter your credentials to access Rose Massage</p>
    </div>

    <form action="{{ route('login.post') }}" method="POST" novalidate>
        @csrf

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="mb-3">
            <label for="email" class="form-label fw-semibold text-uppercase">Email</label>
            <div class="input-group has-validation">
                <span class="input-group-text bg-light border-end-0 @error('email') border-danger text-danger @enderror">
                    <i class="bi bi-envelope-fill {{ $errors->has('email') ? '' : 'text-primary' }}"></i>
                </span>
                <input 
                    type="email" 
                    class="form-control border-start-0 @error('email') is-invalid @enderror" 
                    id="email" 
                    name="email" 
                    value="{{ old('email') }}" 
                    placeholder="user@example.com"
                >
                @error('email')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label fw-semibold text-uppercase">Password</label>
            <div class="input-group has-validation">
                <span class="input-group-text bg-light border-end-0 @error('password') border-danger text-danger @enderror">
                    <i class="bi bi-shield-lock-fill {{ $errors->has('password') ? '' : 'text-primary' }}"></i>
                </span>
                <input 
                    type="password" 
                    class="form-control border-start-0 @error('password') is-invalid @enderror" 
                    id="password" 
                    name="password" 
                    placeholder="********"
                    autocomplete="off"
                >
                @error('password')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>

        <button type="submit" class="btn btn-primary w-100 shadow-sm">
            <i class="bi bi-box-arrow-in-right me-2"></i> Login
        </button>

        <p class="small text-center mt-2">Don't have an account? <a href="{{ route('register') }}">Sign up here</a></p>

    </form>

@endsection


@section('pageStyles')
    <style>
        /* When any part of the input group is focused */
    .input-group:focus-within .input-group-text {
        background-color: var(--bs-primary) !important; /* Pulse theme purple */
        color: white !important;
        border-color: var(--bs-primary) !important;
        transition: all 0.3s ease;
    }

    /* Keep the icon white when the background turns primary */
    .input-group:focus-within .input-group-text i {
        color: white !important;
    }
    </style>
@endsection