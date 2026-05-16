@extends('layouts.admin')

@section('page-title', 'Assign Therapist - Booking #' . $booking->id)
@section('breadcrumb-parent', 'Booking #' . $booking->id)
@section('breadcrumb-parent-url', route('bookings.show', $booking->id))

@section('page-header', true)
@section('page-header-title-showpage', 'Assign Therapist')
@section('page-header-subtitle', 'Assign therapists to booking services')

@section('content')

    {{-- GLOBAL VALIDATION ERROR --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Please fix the errors below.</strong>
        </div>
    @endif

    <form method="POST" action="{{ route('therapist-assignments.update', $booking->id) }}">
        @csrf
        @method('PUT')

        <div class="row g-4">

            <!-- LEFT: ASSIGNMENT AREA -->
            <div class="col-12 col-lg-8">

                <div class="card shadow-sm border">

                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="mb-0 fw-bold text-uppercase small text-muted">
                            Therapist Assignment
                        </h6>
                    </div>

                    <div class="card-body">

                        @foreach ($booking->items as $item)
                            @php
                                $fieldName = "assignments.$item->id";
                            @endphp

                            <div class="border rounded p-3 mb-3">

                                <!-- SERVICE HEADER -->
                                <div class="d-flex justify-content-between align-items-center mb-2">

                                    <div>
                                        <div class="fw-bold">
                                            {{ $item->service_name }}
                                        </div>
                                        <small class="text-muted">
                                            {{ $item->service_duration_minutes }} mins
                                        </small>
                                        @if ($item->start_time && $item->end_time)
                                            <small class="text-primary d-block mt-1">
                                                {{ \Carbon\Carbon::parse($item->start_time)->format('g:i A') }}
                                                -
                                                {{ \Carbon\Carbon::parse($item->end_time)->format('g:i A') }}
                                            </small>
                                        @endif
                                    </div>

                                    <span class="text-primary fw-bold">
                                        ₱{{ number_format($item->service_price, 2) }}
                                    </span>
                                </div>

                                <!-- ASSIGN SECTION -->
                                <div class="mt-3">

                                    <label class="form-label small text-muted fw-bold">
                                        Select Available Therapist
                                    </label>

                                    <select name="assignments[{{ $item->id }}]"
                                        class="form-select @error($fieldName) is-invalid @enderror">

                                        <option value="">-- Unassigned --</option>

                                        @foreach ($item->available_therapists as $therapist)
                                            <option value="{{ $therapist->id }}" @selected($item->therapist_id == $therapist->id)>
                                                {{ $therapist->name }}
                                            </option>
                                        @endforeach

                                    </select>

                                    {{-- VALIDATION ERROR PER ITEM --}}
                                    @error($fieldName)
                                        <div class="invalid-feedback d-block">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                </div>

                                <!-- STATUS -->
                                <div class="mt-2">

                                    @if ($item->therapist)
                                        <span class="badge bg-success-subtle text-success border">
                                            <i class="bi bi-person-check me-1"></i>
                                            Assigned to {{ $item->therapist->name }}
                                        </span>
                                    @else
                                        <span class="badge bg-light text-muted border">
                                            <i class="bi bi-person-dash me-1"></i>
                                            Not Assigned
                                        </span>
                                    @endif

                                </div>

                            </div>
                        @endforeach

                    </div>

                    <div class="card-footer bg-light text-end">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-check2-circle me-2"></i>
                            Save Assignments
                        </button>
                    </div>

                </div>

            </div>

            <!-- RIGHT: BOOKING SUMMARY -->
            <div class="col-12 col-lg-4">

                <div class="card shadow-sm border">

                    <div class="card-header bg-white py-3 border-bottom text-center">
                        <h6 class="mb-0 fw-bold text-uppercase small text-muted">
                            Booking Summary
                        </h6>
                    </div>

                    <div class="card-body">

                        <div class="mb-3">
                            <div class="text-muted small">Client</div>
                            <div class="fw-bold">
                                {{ optional($booking->client)->name ?? 'Walk-in' }}
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="text-muted small">Schedule</div>
                            <div class="fw-bold">
                                {{ $booking->booking_date }}
                            </div>
                            <small class="text-muted">
                                {{ \Carbon\Carbon::parse($booking->start_time)->format('g:i A') }}
                                -
                                {{ \Carbon\Carbon::parse($booking->end_time)->format('g:i A') }}
                            </small>
                        </div>

                        <div class="mb-3">
                            <div class="text-muted small">Status</div>

                            <span class="badge bg-primary text-uppercase">
                                {{ $booking->status }}
                            </span>
                        </div>

                        <hr>

                        <div>
                            <div class="text-muted small mb-2">Services</div>

                            @foreach ($booking->items as $item)
                                <div class="mb-2">

                                    <div class="d-flex justify-content-between small">
                                        <span>{{ $item->service_name }}</span>
                                        <span>₱{{ number_format($item->service_price, 2) }}</span>
                                    </div>

                                    @if ($item->start_time && $item->end_time)
                                        <small class="text-muted d-block">
                                            {{ \Carbon\Carbon::parse($item->start_time)->format('g:i A') }}
                                            -
                                            {{ \Carbon\Carbon::parse($item->end_time)->format('g:i A') }}
                                        </small>
                                    @endif

                                </div>
                            @endforeach
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </form>

@endsection
