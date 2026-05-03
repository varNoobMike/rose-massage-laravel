@extends('layouts.admin')

@section('page-title', 'Bookings')

@section('page-header', true)
@section('page-header-title-indexpage', 'Bookings')
@section('page-header-subtitle', 'Manage massage appointments')

@section('page-header-actions')
    <a href="{{ route('bookings.index') }}" class="btn btn-outline-secondary px-4 shadow-sm">
        <i class="bi bi-arrow-repeat me-2"></i> Sync
    </a>
    <a href="{{ route('bookings.create') }}" class="btn btn-primary px-4 shadow-sm d-none">
        <!-- Disable for now, future features -->
        <i class="bi bi-plus-lg me-2"></i> New
    </a>
@endsection


@section('filter-area', true)
@section('filter-form')

    {{-- MOBILE TOGGLE BUTTON --}}
    <div class="d-md-none mb-2">
        <button class="btn btn-outline-dark w-100"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#bookingFilters">
            <i class="bi bi-funnel me-1"></i> Show Filters
        </button>
    </div>

    <div class="collapse d-md-block" id="bookingFilters">

        <form action="{{ route('bookings.index') }}" method="GET">

            <div class="row g-3">

                {{-- SEARCH --}}
                <div class="col-md-4">
                    <input type="text"
                           name="search"
                           class="form-control"
                           placeholder="Search booking client name, id, email..."
                           value="{{ request('search') }}">
                </div>

                {{-- FROM DATE --}}
                <div class="col-md-2">
                    <input type="date"
                           name="from"
                           class="form-control"
                           value="{{ request('from') }}">
                </div>

                {{-- TO DATE --}}
                <div class="col-md-2">
                    <input type="date"
                           name="to"
                           class="form-control"
                           value="{{ request('to') }}">
                </div>

                {{-- STATUS --}}
                <div class="col-md-2">
                    <select name="status" class="form-select">

                        <option value="" @selected(request('status') == '')>
                            All Status
                        </option>

                        <option value="pending" @selected(request('status') == 'pending')>
                            Pending
                        </option>

                        <option value="confirmed" @selected(request('status') == 'confirmed')>
                            Confirmed
                        </option>

                        <option value="active" @selected(request('status') == 'active')>
                            Active
                        </option>

                        <option value="completed" @selected(request('status') == 'completed')>
                            Completed
                        </option>

                        <option value="cancelled" @selected(request('status') == 'cancelled')>
                            Cancelled
                        </option>

                    </select>
                </div>

                {{-- THERAPIST ASSIGNMENT --}}
                <div class="col-md-2">
                    <select name="therapist_assignment_status" class="form-select">

                        <option value="" @selected(request('therapist_assignment_status') == '')>
                            All Assignment
                        </option>

                        <option value="unassigned" @selected(request('therapist_assignment_status') == 'unassigned')>
                            Unassigned
                        </option>

                        <option value="assigned" @selected(request('therapist_assignment_status') == 'assigned')>
                            Assigned
                        </option>

                    </select>
                </div>

                {{-- ACTIONS --}}
                <div class="col-md-3 d-flex gap-2">
                    <button class="btn btn-dark w-100">
                        <i class="bi bi-funnel me-1"></i>
                        Filter
                    </button>

                    <a href="{{ route('bookings.index') }}" class="btn btn-outline-secondary w-100">
                        <i class="bi bi-x-circle me-1"></i>
                        Clear
                    </a>
                </div>

            </div>

        </form>

    </div>

@endsection

@section('content')

    <!-- Table -->
    <div class="card shadow-sm border">
        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">

                <thead class="table-light">
                    <tr>
                        <th>Booking ID</th>
                        <th class="d-none d-lg-table-cell">Client</th>
                        <th>Schedule</th>
                        <th class="d-none d-lg-table-cell">Therapist</th>
                        <th class="d-none d-lg-table-cell">Total</th>
                        <th class="d-none d-lg-table-cell">Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($bookings as $booking)
                        <tr>

                            <!-- BOOKING ID -->
                            <td class="fw-bold text-muted">
                                #{{ $booking->id }}
                            </td>

                            <!-- CLIENT -->
                            <td class="d-none d-lg-table-cell">
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

                            <!-- SCHEDULE -->
                            <td class="">
                                <div class="fw-semibold">
                                    {{ \Carbon\Carbon::parse($booking->booking_date)->format('M d, Y') }}
                                </div>
                                <small class="text-muted">
                                    {{ \Carbon\Carbon::parse($booking->start_time)->format('h:i A') }}
                                    -
                                    {{ \Carbon\Carbon::parse($booking->end_time)->format('h:i A') }}
                                </small>
                            </td>

                            <!-- THERAPIST -->
                            <td class="d-none d-lg-table-cell">
                                @php
                                    $assignedTherapists = $booking->items
                                        ->whereNotNull('therapist_id')
                                        ->pluck('therapist')
                                        ->filter()
                                        ->unique('id');

                                    $totalItems = $booking->items->count();
                                    $assignedCount = $booking->items->whereNotNull('therapist_id')->count();
                                @endphp

                                @if ($assignedTherapists->count())
                                    <div class="d-flex flex-wrap gap-1">

                                        @foreach ($assignedTherapists->take(2) as $therapist)
                                            <span class="badge bg-success-subtle text-success border">
                                                {{ $therapist->name }}
                                            </span>
                                        @endforeach

                                        @if ($assignedTherapists->count() > 2)
                                            <span class="badge bg-light text-muted border">
                                                +{{ $assignedTherapists->count() - 2 }} more
                                            </span>
                                        @endif

                                    </div>

                                    <small class="text-muted d-block mt-1">
                                        {{ $assignedCount }}/{{ $totalItems }} assigned
                                    </small>
                                @else
                                    <span class="badge bg-warning text-dark">
                                        Unassigned
                                    </span>
                                    <small class="text-muted d-block mt-1">
                                        0/{{ $totalItems }} assigned
                                    </small>
                                @endif
                            </td>

                            <!-- TOTAL -->
                            <td class="fw-bold d-none d-lg-table-cell">
                                ₱{{ number_format($booking->total_amount, 2) }}
                            </td>

                            <!-- STATUS -->
                            <td class="d-none d-lg-table-cell">

                                @php $status = $booking->status; @endphp

                                <span
                                    class="badge
                                    @if ($status == 'pending') bg-warning text-dark
                                    @elseif($status == 'confirmed') bg-primary
                                    @elseif($status == 'active') bg-success
                                    @elseif($status == 'completed') bg-secondary
                                    @elseif($status == 'cancelled') bg-danger @endif
                                        text-uppercase small">
                                            {{ $status }}
                                </span>

                            </td>

                            <!-- ACTIONS -->
                            <td class="text-end">

                                <div class="btn-group gap-2">

                                    <a href="{{ route('bookings.show', $booking->id) }}" class="btn btn-sm btn-secondary">
                                        <i class="bi bi-eye"></i>
                                    </a>

                                    <a href="{{ route('bookings.edit', $booking->id) }}" class="btn btn-sm btn-primary">
                                        <i class="bi bi-pencil"></i>
                                    </a>

                                </div>

                            </td>

                        </tr>
                    @empty

                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <i class="bi bi-calendar-x fs-1 text-muted"></i>
                                <h5 class="mt-3">No bookings found</h5>
                                <p class="text-muted mb-0">Try adjusting your filters.</p>
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        <!-- Pagination -->
        @if ($bookings->hasPages())
            <div class="card-footer bg-white">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">

                    <small class="text-muted">
                        Showing {{ $bookings->firstItem() }}
                        to {{ $bookings->lastItem() }}
                        of {{ $bookings->total() }} bookings
                    </small>

                    {{ $bookings->appends(request()->query())->links() }}

                </div>
            </div>
        @endif

    </div>

@endsection
