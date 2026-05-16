@extends('layouts.admin')

@section('page-title', 'Booking #' . $booking->id)
@section('breadcrumb-parent', 'Bookings')
@section('breadcrumb-parent-url', route('bookings.index'))

@section('page-header', true)
@section('page-header-title-showpage', 'Booking #' . $booking->id)
@section('page-header-subtitle', 'Review and manage this booking')

@section('content')
    <div class="row g-4">

        <!-- LEFT: BOOKING INFO -->
        <div class="col-12 col-lg-8">

            <div class="card shadow-sm border">

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

                                        @if ($booking->client && $booking->client?->profile?->avatar)
                                            <img src="{{ asset('storage/' . $booking->client?->profile?->avatar) }}"
                                                class="rounded-circle me-3 object-fit-cover" width="45" height="45">
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

                            <!-- PAYMENT STATUS & METHOD -->
                            <tr class="border-bottom border-light">
                                <td class="ps-4 py-4 text-muted small fw-bold text-uppercase">
                                    Payment Tracking
                                </td>
                                <td class="py-4 pe-4">
                                    @if ($booking->payments()->exists())
                                        @php $payment = $booking->payments->last(); @endphp

                                        <div class="d-flex flex-wrap align-items-center gap-2">
                                            <!-- Status Badge -->
                                            @if ($payment->status === 'successful')
                                                <span
                                                    class="badge bg-success-subtle text-success border border-success px-2.5 py-1.5 fw-bold">
                                                    <i class="bi bi-check-circle-fill me-1"></i> Paid
                                                </span>
                                            @elseif ($payment->status === 'pending')
                                                <span
                                                    class="badge bg-warning-subtle text-warning-dominant border border-warning px-2.5 py-1.5 fw-bold text-dark">
                                                    <i class="bi bi-hourglass-split me-1"></i> Pending Verification
                                                </span>
                                            @else
                                                <span
                                                    class="badge bg-danger-subtle text-danger border border-danger px-2.5 py-1.5 fw-bold">
                                                    <i class="bi bi-x-circle-fill me-1"></i> {{ ucfirst($payment->status) }}
                                                </span>
                                            @endif

                                            <!-- Method Badge -->
                                            <span
                                                class="badge bg-secondary-subtle text-secondary border border-secondary px-2.5 py-1.5 fw-bold text-capitalize">
                                                <i
                                                    class="bi {{ $payment->payment_method === 'cash' ? 'bi-shop' : 'bi-wallet2' }} me-1"></i>
                                                {{ $payment->payment_method }}
                                            </span>
                                        </div>

                                        <!-- CASE 1: GCash Details & Verification Controls -->
                                        @if ($payment->payment_method === 'gcash')
                                            <div class="mt-3 p-3 bg-light rounded border border-dashed fs-7">
                                                <div class="mb-1 text-secondary">
                                                    Reference Number: <strong
                                                        class="text-dark font-monospace fs-6">{{ $payment->reference_number ?? 'N/A' }}</strong>
                                                </div>

                                                @if ($payment->receipt_path)
                                                    <div class="mb-2">
                                                        <span class="text-secondary d-block mb-1">Receipt Screenshot:</span>
                                                        <a href="{{ asset('storage/' . $payment->receipt_path) }}"
                                                            target="_blank"
                                                            class="d-inline-block border p-1 bg-white rounded shadow-sm">
                                                            <img src="{{ asset('storage/' . $payment->receipt_path) }}"
                                                                alt="Receipt"
                                                                style="max-height: 90px; width: auto; object-fit: contain;">
                                                        </a>
                                                    </div>
                                                @endif

                                                @if ($payment->status === 'pending')
                                                    <div class="d-flex gap-2 mt-2 pt-2 border-top border-2 border-white">
                                                        <form
                                                            action="{{ route('payments.verify', ['payment' => $payment->id, 'action' => 'approve']) }}"
                                                            method="POST"
                                                            onsubmit="return confirm('Confirm payment received matches reference validation?')">
                                                            @csrf
                                                            <button type="submit"
                                                                class="btn btn-success btn-xs fw-bold px-3">
                                                                <i class="bi bi-patch-check-fill me-1"></i> Approve
                                                            </button>
                                                        </form>
                                                        <form action="{{ route('payments.verify', ['payment' => $payment->id, 'action' => 'reject']) }}" method="POST"
                                                            onsubmit="return confirm('Reject this reference log? User will need to submit proof again.')">
                                                            @csrf
                                                            <button type="submit"
                                                                class="btn btn-outline-danger btn-xs fw-bold px-3">
                                                                <i class="bi bi-trash3-fill me-1"></i> Reject
                                                            </button>
                                                        </form>
                                                    </div>
                                                @endif
                                            </div>
                                        @endif

                                        <!-- CASE 2: Cash Counter Payment Controls -->
                                        @if ($payment->payment_method === 'cash' && $payment->status !== 'successful')
                                            <div class="mt-3 p-3 bg-light rounded border border-dashed fs-7">
                                                <div class="text-secondary mb-2">
                                                    <i class="bi bi-info-circle me-1"></i> Customer selected Cash payment.
                                                    Collect payment at the counter.
                                                </div>

                                                <form action="{{ route('payments.verify', ['payment' => $payment->id, 'action' => 'approve']) }}" method="POST"
                                                    onsubmit="return confirm('Confirm that you have received ₱{{ number_format($booking->total_amount, 2) }} at the counter?')">
                                                    @csrf
                                                    <button type="submit" class="btn btn-success btn-sm fw-bold">
                                                        <i class="bi bi-cash-coin me-1"></i> Mark as Paid at Counter
                                                    </button>
                                                </form>
                                            </div>
                                        @endif
                                    @else
                                        <!-- No payment entry exists in DB yet -->
                                        <div class="d-flex flex-wrap align-items-center gap-2">
                                            <span
                                                class="badge bg-danger-subtle text-danger border border-danger px-2.5 py-1.5 fw-bold">
                                                <i class="bi bi-exclamation-triangle me-1"></i> Unpaid (No option selected)
                                            </span>
                                        </div>
                                    @endif
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
                            <tr class="border-bottom border-light">
                                <td class="ps-4 py-4 text-muted small fw-bold text-uppercase">
                                    Status
                                </td>

                                <td class="py-4 pe-4">

                                    @php $status = $booking->status; @endphp

                                    <span @class([
                                        'badge',
                                        'bg-warning text-dark' => $status === 'pending',
                                        'bg-primary' => $status === 'confirmed',
                                        'bg-success' => $status === 'active',
                                        'bg-secondary' => $status === 'completed',
                                        'bg-danger' => in_array($status, ['cancelled', 'rejected']),
                                    ])>
                                        {{ ucfirst($status) }}
                                    </span>

                                </td>
                            </tr>

                            <!-- SYSTEM LOGS -->
                            <tr>
                                <td class="ps-4 py-4 text-muted small fw-bold text-uppercase">
                                    System Logs
                                </td>
                                <td class="py-4 pe-4 text-muted small">

                                    <div class="mb-1">
                                        <i class="bi bi-calendar-check me-2 opacity-50"></i>
                                        Created:
                                        <strong>
                                            {{ $booking->created_at->format('M d, Y') }}
                                        </strong>
                                    </div>

                                    <div>
                                        <i class="bi bi-arrow-repeat me-2 opacity-50"></i>
                                        Last Update:
                                        <strong>
                                            {{ $booking->updated_at->diffForHumans() }}
                                        </strong>
                                    </div>

                                </td>
                            </tr>

                        </tbody>

                    </table>

                </div>
            </div>
        </div>

        <!-- RIGHT: BOOKING ITEMS -->
        <div class="col-12 col-lg-4">

            <div class="card shadow-sm border mb-3">

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

                                    @if ($item->start_time && $item->end_time)
                                        <small class="text-primary d-block mt-1">
                                            <i class="bi bi-clock me-1"></i>
                                            {{ \Carbon\Carbon::parse($item->start_time)->format('h:i A') }}
                                            -
                                            {{ \Carbon\Carbon::parse($item->end_time)->format('h:i A') }}
                                        </small>
                                    @endif
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

                                @if ($item->therapist)
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

                @if ($booking->items->whereNull('therapist_id')->count() > 0)
                    <div class="card-footer bg-light text-center">
                        <small class="text-warning fw-semibold">
                            <i class="bi bi-exclamation-circle me-1"></i>
                            Some services still need therapist assignment
                        </small>
                    </div>
                @endif

            </div>

            <!-- ACTIONS -->
            <div class="card shadow-sm border">

                <div class="card-header bg-white py-3 border-bottom text-center">
                    <h6 class="mb-0 fw-bold text-uppercase small text-muted">
                        Actions
                    </h6>
                </div>

                <div class="card-body">

                    {{-- pending: confirm / reject --}}
                    @if ($booking->status === 'pending')
                        <form method="POST" action="{{ route('bookings.confirm', $booking->id) }}"
                            onsubmit="return confirm('Confirm this booking? This action cannot be undone.')">
                            @csrf
                            <button type="submit" class="btn btn-success w-100 mb-2">
                                <i class="bi bi-check-circle me-1"></i>
                                Confirm
                            </button>
                        </form>

                        <form method="POST" action="{{ route('bookings.reject', $booking->id) }}"
                            onsubmit="return confirm('Reject this booking? This action cannot be undone.')">
                            @csrf
                            <button type="submit" class="btn btn-danger w-100">
                                <i class="bi bi-x-circle me-1"></i>
                                Reject
                            </button>
                        </form>
                    @endif

                    {{-- confirmed / active / completed --}}
                    @if (in_array($booking->status, ['confirmed', 'active', 'completed']))

                        {{-- edit --}}
                        @if ($booking->status === 'confirmed')
                            <a href="{{ route('bookings.edit', $booking->id) }}" class="btn btn-primary w-100 mb-2">
                                <i class="bi bi-pencil-square me-1"></i>
                                Edit
                            </a>
                        @endif

                        @if ($booking->items->whereNull('therapist_id')->count() > 0)
                            {{-- assign --}}
                            <a href="{{ route('therapist-assignments.index', $booking->id) }}"
                                class="btn btn-info text-white w-100 mb-2">
                                <i class="bi bi-person-plus me-1"></i>
                                Assign
                            </a>
                        @endif

                        {{-- cancel --}}
                        <form method="POST" action="{{ route('bookings.cancel', $booking->id) }}"
                            onsubmit="return confirm('Cancel this booking? This action cannot be undone.')">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger w-100">
                                <i class="bi bi-x-octagon me-1"></i>
                                Cancel
                            </button>
                        </form>

                    @endif

                    {{-- fallback --}}
                    @if (!in_array($booking->status, ['pending', 'confirmed', 'rejected', 'active', 'completed']))
                        <span class="badge bg-light text-muted border">
                            <i class="bi bi-lock me-1"></i>
                            No actions available
                        </span>
                    @endif

                </div>

            </div>

        </div>

    </div>
@endsection
