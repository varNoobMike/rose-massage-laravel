@extends('layouts.admin')

@section('page-title', 'Change Password')

@section('breadcrumb-parent', 'Security')
@section('breadcrumb-parent-url', route('account-security.index'))

@section('page-header', true)
@section('page-header-title-showpage', 'Change Password')
@section('page-header-subtitle', 'Update your account password.')

@section('content')
    <div class="row">

        <div class="col-12">

            <div class="card shadow-sm border">

                <div class="card-header bg-white py-3 border-bottom text-center">
                    <h6 class="mb-0 fw-bold small text-muted text-uppercase">
                        Update Password
                    </h6>
                </div>

                <div class="card-body p-4">

                    <form action="{{ route('account-security.password.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Current Password -->
                        <div class="mb-3">

                            <label class="form-label small text-muted">
                                Current Password
                            </label>

                            <input type="password" name="current_password"
                                class="form-control @error('current_password') is-invalid @enderror"
                                placeholder="Enter current password">

                            @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            <!-- Fogot Password Link -->
                            <div class="mt-2">
                                <a href="{{ route('logout.and.forgot') }}" class="small text-decoration-none">
                                    Forgot your password?
                                </a>
                            </div>

                        </div>

                        <!-- New Password -->
                        <div class="mb-3">

                            <label class="form-label small text-muted">
                                New Password
                            </label>

                            <input type="password" name="password"
                                class="form-control @error('password') is-invalid @enderror"
                                placeholder="Enter new password">

                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                        </div>

                        <!-- Confirm Password -->
                        <div class="mb-4">

                            <label class="form-label small text-muted">
                                Confirm New Password
                            </label>

                            <input type="password" name="password_confirmation" class="form-control"
                                placeholder="Confirm new password">

                        </div>

                        <!-- INFO -->
                        <p class="text-muted small d-flex align-items-center mb-4">
                            <i class="bi bi-info-circle me-2"></i>
                            Use a strong password with at least 8 characters.
                        </p>

                        <!-- BUTTON -->
                        <div class="d-flex justify-content-end">

                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-shield-lock me-1"></i>
                                Update Password
                            </button>

                        </div>

                    </form>

                </div>

            </div>

            <!-- NOTE -->
            <div class="text-center mt-3">

                <p class="text-muted small mb-0 d-flex justify-content-center align-items-center">
                    <i class="bi bi-lock me-2"></i>
                    Your password is encrypted and securely stored.
                </p>

            </div>

        </div>

    </div>
@endsection
