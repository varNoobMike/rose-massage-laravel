@extends('layouts.auth')

@section('content')
    <div class="text-center mb-4">
        <h3 class="fw-bold text-uppercase mb-1">Reset Password</h3>
        <p class="small mb-0">Enter your email and new password to reset your password</p>
    </div>

    <!-- Alerts -->
    @include('partials.alerts')

    <form method="POST" action="{{ route('password.update') }}">
        @csrf

        <input type="hidden" name="token" value="{{ $token }}">

        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" value="{{ $email }}"
                class="form-control @error('email') is-invalid @enderror">

            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label>New Password</label>
            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror">
        </div>

        <div class="mb-3">
            <label>Confirm Password</label>
            <input type="password" name="password_confirmation" class="form-control">
        </div>

        <button class="btn btn-success">
            Reset Password
        </button>
    </form>
@endsection
