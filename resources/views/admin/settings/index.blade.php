@extends('layouts.admin')

@section('page-title', 'Settings')

@section('page-header', true)
@section('page-header-title-showpage', 'Settings')
@section('page-header-subtitle', 'Manage system configuration and preferences')

@section('content')
    <div class="row">

        <div class="col-12">

            <!-- OPERATING HOURS -->
            <div class="card shadow-sm border mb-3">

                <div class="card-body d-flex justify-content-between align-items-center p-4">

                    <div class="d-flex align-items-center">

                        <div class="me-3 text-primary">
                            <i class="bi bi-clock-history fs-4"></i>
                        </div>

                        <div>
                            <h6 class="mb-1 fw-bold">Operating Hours</h6>
                            <p class="text-muted small mb-0">
                                Configure your business opening and closing schedule
                            </p>
                        </div>

                    </div>

                    <a href="{{ route('settings.operating-hours.index') }}" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-arrow-right me-1"></i>
                        Manage
                    </a>

                </div>

            </div>

            <!-- SETTINGS NOTE -->
            <div class="text-center mt-4">

                <p class="text-muted small mb-0 d-flex justify-content-center align-items-center">
                    <i class="bi bi-info-circle me-2"></i>
                    Keep your system settings updated to ensure smooth operations.
                </p>

            </div>

        </div>

    </div>
@endsection
