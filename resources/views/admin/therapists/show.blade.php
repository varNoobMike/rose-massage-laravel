@extends('layouts.admin')

@section('page-title', 'Therapist #' . $user->id)
@section('breadcrumb-parent', 'Therapists')
@section('breadcrumb-parent-url', route('therapists.index'))

@section('content')
<div class="container-fluid">

    <!-- Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <h2 class="fw-bold text-dark mb-0 h4">
            <i class="bi bi-person-circle text-primary me-2"></i>
            Therapist #{{ $user->id }}
        </h2>

        <div class="d-flex gap-2">
            <a href="{{ route('therapists.edit', $user->id) }}"
               class="btn btn-primary px-4 shadow-sm fw-bold rounded">
                <i class="bi bi-pencil-square me-2"></i>
                Edit
            </a>
        </div>
    </div>

    <div class="row g-4">

        {{-- ALERTS --}}
        @if(session('success'))
            <div class="col-12">
                <div class="alert alert-success alert-dismissible fade show shadow-sm rounded">
                    {{ session('success') }}
                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="alert">
                    </button>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="col-12">
                <div class="alert alert-danger alert-dismissible fade show shadow-sm rounded">
                    {{ session('error') }}
                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="alert">
                    </button>
                </div>
            </div>
        @endif


        <!-- LEFT SIDE -->
        <div class="col-12 col-lg-8">

            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-white py-3 border-bottom">
                    <div class="d-flex justify-content-between align-items-center">

                        <h6 class="mb-0 fw-bold text-uppercase small text-muted">
                            Receptionist Information
                        </h6>

                        <span class="badge bg-light text-primary border px-3 py-2">
                            ID: #{{ str_pad($user->id, 4, '0', STR_PAD_LEFT) }}
                        </span>

                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">

                        <table class="table table-borderless mb-0 align-middle">
                            <tbody>

                                <tr class="border-bottom border-light">
                                    <td class="ps-4 py-4 text-muted small fw-bold text-uppercase"
                                        style="width:30%;">
                                        Full Name
                                    </td>
                                    <td class="py-4 pe-4 fw-bold text-dark">
                                        {{ $user->name }}
                                    </td>
                                </tr>

                                <tr class="border-bottom border-light">
                                    <td class="ps-4 py-4 text-muted small fw-bold text-uppercase">
                                        Email Address
                                    </td>
                                    <td class="py-4 pe-4">
                                        {{ $user->email }}
                                    </td>
                                </tr>

                                <tr class="border-bottom border-light">
                                    <td class="ps-4 py-4 text-muted small fw-bold text-uppercase">
                                        Phone Number
                                    </td>
                                    <td class="py-4 pe-4">
                                        {{ $user->profile?->phone_number ?? 'N/A' }}
                                    </td>
                                </tr>

                                <tr class="border-bottom border-light">
                                    <td class="ps-4 py-4 text-muted small fw-bold text-uppercase">
                                        Address
                                    </td>
                                    <td class="py-4 pe-4">
                                        {{ $user->profile?->address ?? 'N/A' }}
                                    </td>
                                </tr>

                                <tr class="border-bottom border-light">
                                    <td class="ps-4 py-4 text-muted small fw-bold text-uppercase">
                                        Gender
                                    </td>
                                    <td class="py-4 pe-4">
                                        {{ ucfirst($user->profile?->gender ?? 'N/A') }}
                                    </td>
                                </tr>

                                <tr class="border-bottom border-light">
                                    <td class="ps-4 py-4 text-muted small fw-bold text-uppercase">
                                        Birthdate
                                    </td>
                                    <td class="py-4 pe-4">
                                        {{ $user->profile->birthdate 
                                            ? \Carbon\Carbon::parse($user->profile?->birthdate)->format('M d, Y')
                                            : 'N/A'
                                        }}
                                    </td>
                                </tr>

                                <tr class="border-bottom border-light">
                                    <td class="ps-4 py-4 text-muted small fw-bold text-uppercase">
                                        Role
                                    </td>
                                    <td class="py-4 pe-4">
                                        <span class="badge bg-light text-dark border text-capitalize">
                                            {{ $user->role }}
                                        </span>
                                    </td>
                                </tr>

                                <tr>
                                    <td class="ps-4 py-4 text-muted small fw-bold text-uppercase">
                                        System Logs
                                    </td>
                                    <td class="py-4 pe-4 text-muted small">

                                        <div class="mb-1">
                                            <i class="bi bi-calendar-check me-2 opacity-50"></i>
                                            Created:
                                            <strong>
                                                {{ $user->created_at->format('M d, Y') }}
                                            </strong>
                                        </div>

                                        <div>
                                            <i class="bi bi-arrow-repeat me-2 opacity-50"></i>
                                            Last Update:
                                            <strong>
                                                {{ $user->updated_at->diffForHumans() }}
                                            </strong>
                                        </div>

                                    </td>
                                </tr>

                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>


        <!-- RIGHT SIDE -->
        <div class="col-12 col-lg-4">

            <!-- STATUS CARD -->
            <div class="card border-0 shadow-sm rounded-3 mb-4 text-center">
                <div class="card-body p-4">

                    <small class="text-uppercase text-muted fw-bold mb-3 d-block">
                        Account Status
                    </small>

                    <div class="bg-{{ $user->status === 'active' ? 'success' : 'secondary' }} bg-opacity-10 
                                text-{{ $user->status === 'active' ? 'success' : 'secondary' }} 
                                rounded-pill py-2 px-4 d-inline-flex align-items-center mb-2">

                        <i class="bi bi-{{ $user->status === 'active' ? 'check-circle-fill' : 'slash-circle-fill' }} me-2"></i>

                        <span class="fw-bold text-uppercase">
                            {{ $user->status }}
                        </span>
                    </div>

                    <p class="text-muted small mb-0">
                        {{ $user->status === 'active'
                            ? 'User can access the platform'
                            : 'User account access is restricted'
                        }}
                    </p>

                </div>
            </div>


            <!-- PROFILE IMAGE -->
            <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
                <div class="card-header bg-white py-3 border-bottom text-center">
                    <h6 class="mb-0 fw-bold small text-muted text-uppercase">
                        Profile Image
                    </h6>
                </div>

                <div class="card-body p-3">

                    @if($user->profile?->avatar)
                        <img src="{{ asset('storage/' . $user->profile?->avatar) }}"
                             class="img-fluid rounded-3 shadow-sm w-100 object-fit-cover"
                             style="height: 250px;">
                    @else
                        <div class="bg-light rounded-3 text-center py-5 border border-dashed">
                            <i class="bi bi-person text-muted fs-1 opacity-25"></i>
                        </div>
                    @endif

                </div>
            </div>

        </div>

    </div>
</div>
@endsection