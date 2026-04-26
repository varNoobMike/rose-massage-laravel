@extends('layouts.user')

@section('page-title', 'Booking #' . $booking->id)

@section('breadcrumb', true)
@section('breadcrumb-parent', 'Bookings')
@section('breadcrumb-parent-url', route('bookings.index'))

@section('page-header', true)
@section('page-header-title', 'Booking #' . $booking->id)
@section('page-header-subtitle', 'Review your massage therapy session details')

@section('content')

    <div class="container px-lg-5">

        <div class="row g-4">

            <!-- LEFT: Booking Info -->
            <div class="col-md-8">

                <!-- Booking Card -->
                <div class="card shadow-sm border mb-4">
                    <div class="card-body">

                        <h5 class="fw-bold mb-3">Appointment Details</h5>

                        <div class="row g-3">

                            <div class="col-md-6">
                                <small class="text-muted">Date</small>
                                <div class="fw-bold">
                                    {{ \Carbon\Carbon::parse($booking->booking_date)->format('M d, Y') }}
                                </div>
                            </div>

                            <div class="col-md-6">
                                <small class="text-muted">Time</small>
                                <div class="fw-bold">
                                    {{ \Carbon\Carbon::parse($booking->start_time)->format('h:i A') }}
                                </div>
                            </div>

                            <div class="col-md-6">
                                <small class="text-muted">Status</small>
                                <div>
                                    @php $status = $booking->status; @endphp

                                    <span
                                        class="badge
                                @if ($status == 'pending') bg-warning text-dark
                                @elseif($status == 'confirmed') bg-primary
                                @elseif($status == 'active') bg-success
                                @elseif($status == 'completed') bg-secondary
                                @elseif($status == 'cancelled') bg-danger @endif">
                                        {{ ucfirst($status) }}
                                    </span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <small class="text-muted">Total Amount</small>
                                <div class="fw-bold text-primary">
                                    ₱{{ number_format($booking->total_amount, 2) }}
                                </div>
                            </div>

                        </div>

                    </div>
                </div>

                <!-- Services Card -->
                <div class="card shadow-sm border">
                    <div class="card-body">

                        <h5 class="fw-bold mb-3">Services Included</h5>

                        @php
                            $items = $booking->items ?? collect();
                        @endphp

                        @forelse($items as $item)
                            <div class="d-flex justify-content-between align-items-center border-bottom py-2">
                                <div>
                                    <div class="fw-bold">{{ $item->service_name }}</div>
                                    <small class="text-muted">
                                        {{ $item->description ?? 'No description' }}
                                    </small>
                                </div>

                                <div class="fw-bold text-primary">
                                    ₱{{ number_format($item->price, 2) }}
                                </div>
                            </div>
                        @empty
                            <p class="text-muted mb-0">No services found.</p>
                        @endforelse

                        <div class="d-flex justify-content-between mt-3 pt-2 border-top">
                            <span class="fw-bold">Total Services</span>
                            <span class="fw-bold">{{ $items->count() }}</span>
                        </div>

                    </div>
                </div>

            </div>

            <!-- RIGHT: Summary -->
            <div class="col-md-4">

                <!-- Summary Card -->
                <div class="card shadow-sm border mb-4">
                    <div class="card-body">

                        <h5 class="fw-bold mb-3">Summary</h5>

                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Booking ID</span>
                            <span class="fw-bold">
                                #{{ str_pad($booking->id, 5, '0', STR_PAD_LEFT) }}
                            </span>
                        </div>

                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Services</span>
                            <span class="fw-bold">{{ $items->count() }}</span>
                        </div>

                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Status</span>
                            <span class="fw-bold text-capitalize">{{ $booking->status }}</span>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between">
                            <span class="fw-bold">Total</span>
                            <span class="fw-bold text-primary">
                                ₱{{ number_format($booking->total_amount, 2) }}
                            </span>
                        </div>

                    </div>
                </div>

                <!-- Optional Actions -->
                <div class="card shadow-sm border">
                    <div class="card-body">

                        <h6 class="fw-bold mb-3">Actions</h6>

                        <a href="{{ route('bookings.index') }}" class="btn btn-outline-secondary w-100 mb-2">
                            Back to Bookings
                        </a>

                        @if ($booking->status == 'pending')
                            <button class="btn btn-danger w-100">
                                Cancel Booking
                            </button>
                        @endif

                    </div>
                </div>

            </div>
        </div>

    </div>

@endsection
