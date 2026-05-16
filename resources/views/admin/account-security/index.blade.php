@extends('layouts.admin')

@section('page-title', 'Account Security')

@section('page-header', true)
@section('page-header-title-showpage', 'Account Security')
@section('page-header-subtitle', 'Manage your password and security settings')

@section('content')
    <div class="row">

        <div class="col-12">

            <!-- CHANGE PASSWORD -->
            <div class="card shadow-sm border mb-3">

                <div class="card-body d-flex justify-content-between align-items-center p-4">

                    <div class="d-flex align-items-center">

                        <div class="me-3 text-primary">
                            <i class="bi bi-shield-lock fs-4"></i>
                        </div>

                        <div>
                            <h6 class="mb-1 fw-bold">Change Password</h6>
                            <p class="text-muted small mb-0">
                                Update your current password for better security
                            </p>
                        </div>

                    </div>

                    <a href="{{ route('account-security.password.edit') }}" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-arrow-right me-1"></i>
                        Manage
                    </a>

                </div>

            </div>

            <!-- SECURITY NOTE -->
            <div class="text-center mt-4">

                <p class="text-muted small mb-0 d-flex justify-content-center align-items-center">
                    <i class="bi bi-info-circle me-2"></i>
                    Keep your account secure by updating your password regularly.
                </p>

            </div>

        </div>

    </div>
@endsection
