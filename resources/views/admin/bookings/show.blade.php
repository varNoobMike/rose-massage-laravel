@extends('layouts.admin')

@section('page-title', 'Booking #' . $booking->id)
@section('breadcrumb-parent', 'Bookings')
@section('breadcrumb-parent-url', route('bookings.index'))

@section('content')
<div class="container-fluid">

    <!-- Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <h2 class="fw-bold text-dark mb-0 h4">
            <i class="bi bi-calendar2-check text-primary me-2"></i>
            Booking #{{ $booking->id }}
        </h2>

        <div class="d-flex gap-2">
            <a href="{{ route('bookings.edit', $booking->id) }}"
               class="btn btn-primary px-4 shadow-sm fw-bold rounded">
                <i class="bi bi-pencil-square me-2"></i> Edit
            </a>
        </div>
    </div>

    <div class="row g-4">

        <!-- Alert -->
        @if(session('success'))
                <div class="col-12">  
                    <div class="alert alert-success alert-dismissible fade show shadow-sm rounded" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                </div>
        @endif

        @if(session('error'))
                <div class="col-12">  
                    <div class="alert alert-danger alert-dismissible fade show shadow-sm rounded" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                </div>
        @endif

        <!-- LEFT: BOOKING INFO -->
        <div class="col-12 col-lg-8">

            <div class="card border-0 shadow-sm rounded-3">

                <div class="card-header bg-white py-3 border-bottom">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 fw-bold text-uppercase small text-muted">
                            Booking Information
                        </h6>

                        <span class="badge bg-light text-primary border px-3 py-2">
                            ID: #{{ str_pad($booking->id, 5, '0', STR_PAD_LEFT) }}
                        </span>
                    </div>
                </div>

                <div class="card-body p-0">

                    <table class="table table-borderless mb-0 align-middle">

                        <tbody>

                        <!-- CLIENT -->
                        <tr class="border-bottom border-light">
                            <td class="ps-4 py-4 text-muted small fw-bold text-uppercase" style="width: 30%;">
                                Client
                            </td>

                            <td class="py-4 pe-4">
                                <div class="d-flex align-items-center">

                                    @if($booking->client && $booking->client->image)
                                        <img src="{{ asset('storage/' . $booking->client->image->image) }}"
                                             class="rounded-circle me-3 object-fit-cover"
                                             width="45"
                                             height="45">
                                    @else
                                        <div class="bg-light text-muted rounded-circle d-flex align-items-center justify-content-center me-3"
                                             style="width:45px;height:45px;">
                                            <i class="bi bi-person"></i>
                                        </div>
                                    @endif

                                    <div>
                                        <div class="fw-bold">
                                            {{ optional($booking->client)->name ?? 'Unknown Client' }}
                                        </div>
                                        <small class="text-muted">
                                            {{ optional($booking->client)->email }}
                                        </small>
                                    </div>

                                </div>
                            </td>
                        </tr>

                        <!-- SCHEDULE -->
                        <tr class="border-bottom border-light">
                            <td class="ps-4 py-4 text-muted small fw-bold text-uppercase">
                                Schedule
                            </td>

                            <td class="py-4 pe-4">
                                <div class="fw-bold">
                                    {{ \Carbon\Carbon::parse($booking->booking_date)->format('M d, Y') }}
                                </div>

                                <small class="text-muted">
                                    {{ \Carbon\Carbon::parse($booking->start_time)->format('h:i A') }}
                                    -
                                    {{ \Carbon\Carbon::parse($booking->end_time)->format('h:i A') }}
                                </small>
                            </td>
                        </tr>

                        <!-- TOTAL -->
                        <tr class="border-bottom border-light">
                            <td class="ps-4 py-4 text-muted small fw-bold text-uppercase">
                                Total Amount
                            </td>

                            <td class="py-4 pe-4">
                                <span class="h4 mb-0 fw-bold text-primary">
                                    ₱{{ number_format($booking->total_amount, 2) }}
                                </span>
                            </td>
                        </tr>

                        <!-- NOTES -->
                        <tr class="border-bottom border-light">
                            <td class="ps-4 py-4 text-muted small fw-bold text-uppercase">
                                Notes
                            </td>

                            <td class="py-4 pe-4">
                                <p class="text-muted mb-0">
                                    {{ $booking->notes ?? 'No notes.' }}
                                </p>
                            </td>
                        </tr>

                        <!-- STATUS -->
                        <tr>
                            <td class="ps-4 py-4 text-muted small fw-bold text-uppercase">
                                Status
                            </td>

                            <td class="py-4 pe-4">

                                @php $status = $booking->status; @endphp

                                <span class="badge
                                    @if($status == 'pending') bg-warning text-dark
                                    @elseif($status == 'confirmed') bg-primary
                                    @elseif($status == 'active') bg-success
                                    @elseif($status == 'completed') bg-secondary
                                    @elseif($status == 'cancelled') bg-danger
                                    @endif
                                    rounded-pill px-3 py-2 text-uppercase small">

                                    {{ $status }}

                                </span>

                            </td>
                        </tr>

                        </tbody>
                    </table>

                </div>
            </div>
        </div>

        <!-- RIGHT: BOOKING ITEMS -->
        <div class="col-12 col-lg-4">

            <div class="card border-0 shadow-sm rounded-3">

                <div class="card-header bg-white py-3 border-bottom text-center">
                    <h6 class="mb-0 fw-bold text-uppercase small text-muted">
                        Booked Services
                    </h6>
                </div>

                <div class="card-body">

                    @forelse($booking->items as $item)
                        <div class="mb-3 pb-3 border-bottom">

                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <div class="fw-bold">
                                        {{ $item->service_name ?? 'Service' }}
                                    </div>

                                    <small class="text-muted">
                                        {{ $item->service_duration_minutes }} mins
                                    </small>
                                </div>

                                <div class="fw-bold text-primary">
                                    ₱{{ number_format($item->service_price ?? 0, 2) }}
                                </div>
                            </div>

                            <!-- Assigned Therapist -->
                            <div class="mt-2">
                                <small class="text-muted d-block mb-1">
                                    Assigned Therapist
                                </small>

                                @if($item->therapist)
                                    <span class="badge bg-success-subtle text-success border">
                                        <i class="bi bi-person-check me-1"></i>
                                        {{ $item->therapist->name }}
                                    </span>
                                @else
                                    <span class="badge bg-light text-muted border">
                                        <i class="bi bi-person-dash me-1"></i>
                                        Unassigned
                                    </span>
                                @endif
                            </div>

                        </div>
                    @empty
                        <div class="text-center py-5 text-muted">
                            <i class="bi bi-inbox fs-1"></i>
                            <p class="mt-2 mb-0">No services found</p>
                        </div>
                    @endforelse

                </div>

                @if($booking->items->whereNull('therapist_id')->count() > 0)
                    <div class="card-footer bg-light text-center">
                        <small class="text-warning fw-semibold">
                            <i class="bi bi-exclamation-circle me-1"></i>
                            Some services still need therapist assignment
                        </small>
                    </div>
                @endif

            </div>

        </div>

    </div>
</div>
@endsection