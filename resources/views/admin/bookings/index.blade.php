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
        <button class="btn btn-outline-dark w-100" type="button" data-bs-toggle="collapse" data-bs-target="#bookingFilters">
            <i class="bi bi-funnel me-1"></i> Show Filters
        </button>
    </div>

    <div class="collapse d-md-block" id="bookingFilters">

        <form action="{{ route('bookings.index') }}" method="GET">

            <div class="row g-3 align-items-end">

                {{-- SEARCH --}}
                <div class="col-md-5">
                    <input type="text" name="search" class="form-control"
                        placeholder="Search by booking ID, client name, email..." value="{{ $filters['search'] ?? '' }}">
                </div>

                {{-- DATE FROM --}}
                <div class="col-md-2">
                    <div class="input-group">
                        <span class="input-group-text">From</span>
                        <input type="date" name="date_from" class="form-control"
                            value="{{ $filters['date_from'] ?? '' }}">
                    </div>
                </div>

                {{-- DATE TO --}}
                <div class="col-md-2">
                    <div class="input-group">
                        <span class="input-group-text">To</span>
                        <input type="date" name="date_to" class="form-control" value="{{ $filters['date_to'] ?? '' }}">
                    </div>
                </div>

                {{-- ACTIONS --}}
                <div class="col-12 col-md-3 d-flex gap-2">
                    <button class="btn btn-dark w-100">
                        <i class="bi bi-funnel me-1"></i> Filter
                    </button>

                    <button class="btn btn-primary w-100" type="button" data-bs-toggle="collapse"
                        data-bs-target="#advancedFilters">
                        <i class="bi bi-three-dots me-1"></i> More
                    </button>

                    <a href="{{ route('bookings.index') }}" class="btn btn-outline-secondary w-100">
                        <i class="bi bi-x-circle me-1"></i> Clear
                    </a>
                </div>

                {{-- STATUS --}}
                <div class="col-md-4 mt-2">
                    <select name="status" class="form-select">
                        <option value="" @selected(empty($filters['status'] ?? null))>All Status</option>
                        <option value="pending" @selected(($filters['status'] ?? null) === 'pending')>Pending</option>
                        <option value="confirmed" @selected(($filters['status'] ?? null) === 'confirmed')>Confirmed</option>
                        <option value="rejected" @selected(($filters['status'] ?? null) === 'rejected')>Rejected</option>
                        <option value="active" @selected(($filters['status'] ?? null) === 'active')>Active</option>
                        <option value="completed" @selected(($filters['status'] ?? null) === 'completed')>Completed</option>
                        <option value="cancelled" @selected(($filters['status'] ?? null) === 'cancelled')>Cancelled</option>
                    </select>
                </div>

            </div>

            {{-- ADVANCED FILTERS --}}
            <div class="collapse mt-3 {{ $hasAdvancedFilters ? 'show' : '' }}" id="advancedFilters">

                <div class="row g-3">

                    {{-- THERAPIST ASSIGNMENT --}}
                    <div class="col-md-4">
                        <select name="therapist_assignment_status" class="form-select">

                            <option value="" @selected(empty($filters['therapist_assignment_status'] ?? null))>
                                All Assignment
                            </option>

                            <option value="unassigned" @selected(($filters['therapist_assignment_status'] ?? null) === 'unassigned')>
                                Unassigned
                            </option>

                            <option value="partial" @selected(($filters['therapist_assignment_status'] ?? null) === 'partial')>
                                Partially Assigned
                            </option>

                            <option value="fully_assigned" @selected(($filters['therapist_assignment_status'] ?? null) === 'fully_assigned')>
                                Fully Assigned
                            </option>

                        </select>
                    </div>

                    {{-- SERVICE --}}
                    <div class="col-md-4">
                        <select name="service" class="form-select">

                            <option value="" @selected(empty($filters['service'] ?? null))>All Services</option>

                            @forelse($services as $service)
                                <option value="{{ $service->id }}" @selected(($filters['service'] ?? null) == $service->id)>
                                    {{ $service->name }}
                                </option>
                            @empty
                                <option value="" disabled>No services yet</option>
                            @endforelse

                        </select>
                    </div>

                    {{-- THERAPIST --}}
                    <div class="col-md-4">
                        <select name="therapist" class="form-select">

                            <option value="" @selected(empty($filters['therapist'] ?? null))>All Therapists</option>

                            @forelse($therapists as $therapist)
                                <option value="{{ $therapist->id }}" @selected(($filters['therapist'] ?? null) == $therapist->id)>
                                    {{ $therapist->name }}
                                </option>
                            @empty
                                <option value="" disabled>No therapists yet</option>
                            @endforelse

                        </select>
                    </div>

                </div>

            </div>

        </form>

    </div>

@endsection


@section('content')

    {{-- FILTERS APPLIED --}}
    @if ($hasFilters)
        <div class="alert alert-info d-flex justify-content-between align-items-center py-2 px-3 mb-3">

            <div class="d-flex flex-wrap gap-2 align-items-center">

                <strong class="me-2">
                    <i class="bi bi-funnel-fill"></i> Filters applied:
                </strong>

                {{-- SEARCH --}}
                @if (!empty($filters['search']))
                    <span class="badge bg-dark">
                        Search: {{ $filters['search'] }}
                    </span>
                @endif

                {{-- DATE RANGE --}}
                @if (!empty($filters['date_from']) || !empty($filters['date_to']))
                    <span class="badge bg-secondary">
                        Date:
                        {{ $filters['date_from'] ?? '...' }}
                        →
                        {{ $filters['date_to'] ?? '...' }}
                    </span>
                @endif

                {{-- STATUS --}}
                @if (!empty($filters['status']))
                    <span @class([
                        'badge',
                        'text-capitalize',
                        'bg-warning text-dark' => ($filters['status'] ?? null) === 'pending',
                        'bg-primary' => ($filters['status'] ?? null) === 'confirmed',
                        'bg-success' => ($filters['status'] ?? null) === 'active',
                        'bg-secondary' => ($filters['status'] ?? null) === 'completed',
                        'bg-danger' => in_array($filters['status'] ?? null, [
                            'cancelled',
                            'rejected',
                        ]),
                    ])>
                        Status: {{ ucfirst($filters['status']) }}
                    </span>
                @endif

                {{-- THERAPIST ASSIGNMENT --}}
                @if (!empty($filters['therapist_assignment_status']))
                    <span @class([
                        'badge',
                        'text-capitalize',
                        'bg-warning text-dark' =>
                            ($filters['therapist_assignment_status'] ?? null) === 'unassigned',
                        'bg-white text-dark' =>
                            ($filters['therapist_assignment_status'] ?? null) === 'partial',
                        'bg-success-subtle text-success' =>
                            ($filters['therapist_assignment_status'] ?? null) === 'fully_assigned',
                    ])>
                        Therapist Assignment:
                        {{ ucfirst($filters['therapist_assignment_status']) }}
                    </span>
                @endif

                {{-- SERVICE --}}
                @if ($selectedService)
                    <span class="badge bg-dark">
                        Service: {{ $selectedService->name }}
                    </span>
                @endif

                {{-- THERAPIST --}}
                @if ($selectedTherapist)
                    <span class="badge bg-dark">
                        Therapist: {{ $selectedTherapist->name }}
                    </span>
                @endif

            </div>

        </div>
    @endif


    <div id="bookings-table-wrapper" class="card shadow-sm border">
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

                <tbody id="bookings-table-body">

                    @forelse($bookings as $booking)

                        @php
                            $status = $booking->status;
                            $items = $booking->items ?? collect();

                            $assignedTherapists = $items
                                ->whereNotNull('therapist_id')
                                ->pluck('therapist')
                                ->filter()
                                ->unique('id');

                            $totalItems = $items->count();
                            $assignedCount = $items->whereNotNull('therapist_id')->count();
                        @endphp

                        <tr>

                            <td class="fw-bold text-muted">#{{ $booking->id }}</td>

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

                            <td>
                                <div class="fw-semibold">
                                    {{ \Carbon\Carbon::parse($booking->booking_date)->format('M d, Y') }}
                                </div>
                                <small class="text-muted">
                                    {{ \Carbon\Carbon::parse($booking->start_time)->format('h:i A') }}
                                    -
                                    {{ \Carbon\Carbon::parse($booking->end_time)->format('h:i A') }}
                                </small>
                            </td>

                            <td class="d-none d-lg-table-cell">

                                @if ($assignedTherapists->isNotEmpty())
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
                                    <span class="badge bg-warning text-dark">Unassigned</span>
                                    <small class="text-muted d-block mt-1">
                                        0/{{ $totalItems }} assigned
                                    </small>
                                @endif

                            </td>

                            <td class="fw-bold d-none d-lg-table-cell">
                                ₱{{ number_format($booking->total_amount, 2) }}
                            </td>

                            <td class="text-center">
                                <span @class([
                                    'badge',
                                    'text-capitalize',
                                    'bg-warning text-dark' => $booking->status === 'pending',
                                    'bg-primary' => $booking->status === 'confirmed',
                                    'bg-success' => $booking->status === 'active',
                                    'bg-secondary' => $booking->status === 'completed',
                                    'bg-danger' => in_array($booking->status, ['rejected', 'cancelled']),
                                ])>
                                    {{ $booking->status }}
                                </span>
                            </td>

                            <td class="text-end">
                                <div class="d-flex justify-content-end gap-2">

                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-secondary" data-bs-toggle="dropdown">
                                            <i class="bi bi-three-dots-vertical"></i>
                                        </button>

                                        <ul class="dropdown-menu dropdown-menu-end shadow-sm">

                                            <li>
                                                <a href="{{ route('bookings.show', $booking->id) }}"
                                                    class="dropdown-item">
                                                    <i class="bi bi-eye me-2"></i> View
                                                </a>
                                            </li>

                                            @if ($status === 'pending')
                                                <li>
                                                    <form action="{{ route('bookings.confirm', $booking->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        <button class="dropdown-item text-success">
                                                            <i class="bi bi-check-lg me-2"></i> Confirm
                                                        </button>
                                                    </form>
                                                </li>

                                                <li>
                                                    <form action="{{ route('bookings.reject', $booking->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        <button class="dropdown-item text-danger">
                                                            <i class="bi bi-x-circle me-2"></i> Reject
                                                        </button>
                                                    </form>
                                                </li>
                                            @endif

                                            @if (in_array($status, ['confirmed', 'active', 'completed']))
                                                <li>
                                                    <a href="{{ route('therapist-assignments.index', $booking->id) }}"
                                                        class="dropdown-item text-success">
                                                        <i class="bi bi-person-plus me-2"></i> Assign Therapist
                                                    </a>
                                                </li>
                                            @endif

                                        </ul>
                                    </div>

                                </div>
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="7" class="text-center py-5">

                                @if ($hasFilters)
                                    <i class="bi bi-search fs-1 text-muted"></i>
                                    <h5 class="mt-3">No results found</h5>
                                    <p class="text-muted mb-3">No bookings match your filters.</p>
                                    <a href="{{ route('bookings.index') }}" class="btn btn-outline-dark">
                                        Clear Filters
                                    </a>
                                @else
                                    <i class="bi bi-calendar-x fs-1 text-muted"></i>
                                    <h5 class="mt-3">No bookings yet</h5>
                                    <p class="text-muted mb-0">Once bookings are created, they will appear here.</p>
                                @endif

                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

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
