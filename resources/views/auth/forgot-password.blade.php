@extends('layouts.auth')

@section('content')
    <div class="text-center mb-4">
        <h3 class="fw-bold text-uppercase mb-1">Forgot Password</h3>
        <p class="small mb-0">Enter your email to reset your password</p>
    </div>

    <!-- Alerts -->
    @include('partials.alerts')

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror">

            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <p class="small mt-2">Suddenly remember your password? <a href="{{ route('login') }}">Log in here</a></p>
        </div>

        <button class="btn btn-primary">
            Send Reset Link
        </button>
    </form>
@endsection
