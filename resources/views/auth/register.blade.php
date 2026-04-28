@extends('layouts.auth')
@section('title', 'Create Account')

@section('content')
    <div class="w-100">

        <div class="role-badge mb-3">
            New Customer Registration
        </div>

        <h2 class="fw-bold mb-1">
            Join Rose Massage
        </h2>

        <p class="text-muted small mb-4">
            Experience tranquility. Create your account today.
        </p>

        <form action="{{ route('register') }}" method="POST" class="text-start" novalidate>
            @csrf

            <!-- Alerts -->
            @include('partials.alerts')

            <!-- Full Name -->
            <div class="mb-3">
                <label class="form-label">Full Name</label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                    placeholder="Jane Doe" value="{{ old('name') }}" required>

                @error('name')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <!-- Email -->
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                    placeholder="jane@example.com" value="{{ old('email') }}" required>

                @error('email')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <!-- Password -->
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                    placeholder="••••••••" required>

                @error('password')
                    <div class="invalid-feedback d-block">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div class="mb-4">
                <label class="form-label">Confirm Password</label>
                <input type="password" name="password_confirmation" class="form-control" placeholder="••••••••" required>
            </div>

            <!-- Submit -->
            <button type="submit" class="btn btn-success w-100 d-flex align-items-center justify-content-center gap-2">
                <i class="bi bi-person-plus"></i>
                <span>Create My Account</span>
            </button>

            <!-- Login Link -->
            <div class="text-center mt-4">
                <p class="small text-muted mb-0">
                    Already have an account?
                    <a href="{{ route('login') }}" class="auth-footer-text">
                        Sign In
                    </a>
                </p>
            </div>
        </form>
    </div>
@endsection
