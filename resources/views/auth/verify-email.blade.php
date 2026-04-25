@extends('layouts.auth')

@section('title', 'Verify Email')

@section('content')
    <div class="w-100">
        {{-- Progress Badge --}}
        <div class="role-badge">Final Step</div>
        
        {{-- Icon & Heading --}}
        <div class="mb-4">
            <div class="d-inline-flex align-items-center justify-content-center mb-3" 
                 style="width: 64px; height: 64px; background: var(--brand-pink-light); border-radius: 20px; color: var(--brand-accent);">
                <i data-lucide="mail-check" size="32"></i>
            </div>
            <h2 class="fw-bold mb-1" style="color: var(--text-main);">Verify your email</h2>
            <p class="text-muted small">We've sent a verification link to your email address. Please click the link to activate your Rose Spa account.</p>
        </div>

        {{-- Success/Resend Alert --}}
        @if (session('message'))
            <div class="alert alert-success border-0 fade show mb-4 d-flex align-items-center" 
                 style="background: #f0fff4; color: #276749; border-radius: 12px; font-size: 0.85rem;" role="alert">
                <i data-lucide="send" class="me-2" size="18"></i>
                <span>A new verification link has been sent!</span>
            </div>
        @endif

        <div class="card border-0 mb-4" style="background: var(--brand-bg); border-radius: 18px;">
            <div class="card-body p-3">
                <p class="small text-muted mb-0">
                    <i data-lucide="info" class="me-1" size="14" style="vertical-align: middle;"></i>
                    Didn't receive the email? Check your spam folder or request a new one below.
                </p>
            </div>
        </div>

        {{-- Action Buttons --}}
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="btn-auth d-flex align-items-center justify-content-center gap-2">
                <span>Resend Verification Email</span>
                <i data-lucide="refresh-cw" size="18"></i>
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}" class="mt-3">
            @csrf
            <button type="submit" class="btn btn-link auth-footer-text text-decoration-none small border-0 bg-transparent">
                <i data-lucide="log-out" size="14" class="me-1"></i>
                Log Out
            </button>
        </form>

        <div class="text-center mt-4">
            <p class="small text-muted mb-0">Need help? 
                <a href="#" class="auth-footer-text">Contact Support</a>
            </p>
        </div>
    </div>
@endsection