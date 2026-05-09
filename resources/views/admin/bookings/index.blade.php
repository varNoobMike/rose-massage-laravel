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
                        placeholder="Search by booking ID, client name, email..." value="{{ request('search') }}">
                </div>

                {{-- DATE FROM --}}
                <div class="col-md-2">
                    <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                </div>

                {{-- DATE TO --}}
                <div class="col-md-2">
                    <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                </div>

                {{-- ACTIONS --}}
                <div class="col-md-3 d-flex gap-2">

                    {{-- FILTER --}}
                    <button class="btn btn-dark w-100" type="submit">
                        <i class="bi bi-funnel"></i>
                    </button>

                    {{-- MORE --}}
                    <button class="btn btn-primary w-100" type="button" data-bs-toggle="collapse"
                        data-bs-target="#advancedFilters">
                        <i class="bi bi-three-dots"></i>
                    </button>

                    {{-- CLEAR --}}
                    <a href="{{ route('bookings.index') }}" class="btn btn-outline-secondary w-100">
                        <i class="bi bi-x-circle"></i>
                    </a>

                </div>

                {{-- STATUS --}}
                <div class="col-md-4 mt-2">
                    <select name="status" class="form-select">

                        <option value="">All Status</option>

                        <option value="pending" @selected(request('status') == 'pending')>Pending</option>
                        <option value="confirmed" @selected(request('status') == 'confirmed')>Confirmed</option>
                        <option value="active" @selected(request('status') == 'active')>Active</option>
                        <option value="completed" @selected(request('status') == 'completed')>Completed</option>
                        <option value="cancelled" @selected(request('status') == 'cancelled')>Cancelled</option>

                    </select>
                </div>

            </div>

            {{-- ADVANCED FILTERS --}}
            <div class="collapse mt-3 {{ request()->filled('therapist_assignment_status') ||
            request()->filled('therapist') ||
            request()->filled('service')
                ? 'show'
                : '' }}"
                id="advancedFilters">

                <div class="row g-3">

                    {{-- THERAPIST ASSIGNMENT --}}
                    <div class="col-md-4">
                        <select name="therapist_assignment_status" class="form-select">

                            <option value="">All Assignment</option>

                            <option value="unassigned" @selected(request('therapist_assignment_status') == 'unassigned')>
                                Unassigned
                            </option>

                            <option value="partial" @selected(request('therapist_assignment_status') == 'partial')>
                                Partially Assigned
                            </option>

                            <option value="completed" @selected(request('therapist_assignment_status') == 'completed')>
                                Completely Assigned
                            </option>

                        </select>
                    </div>

                    {{-- SERVICE --}}
                    <div class="col-md-4">
                        <select name="service" class="form-select">
                            <option value="">All Services</option>
                            @forelse($services as $service)
                                <option value="{{ $service->id }}" @selected(request('service') == $service->id)>
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
                            <option value="">All Therapists</option>
                            @forelse($therapists as $therapist)
                                <option value="{{ $therapist->id }}" @selected(request('therapist') == $therapist->id)>
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

    @php
        $hasFilters =
            request()->filled('search') ||
            request()->filled('date_from') ||
            request()->filled('date_to') ||
            request()->filled('status') ||
            request()->filled('therapist_assignment_status') ||
            request()->filled('service') ||
            request()->filled('therapist');
    @endphp

    @if ($hasFilters)
        <div class="alert alert-info d-flex justify-content-between align-items-center py-2 px-3 mb-3">
            <div class="d-flex flex-wrap gap-2 align-items-center">

                <strong class="me-2">
                    <i class="bi bi-funnel-fill"></i> Filters applied:
                </strong>

                @if (request('search'))
                    <span class="badge bg-dark">
                        Search: {{ request('search') }}
                    </span>
                @endif

                @if (request('date_from') || request('date_to'))
                    <span class="badge bg-secondary">
                        Date:
                        {{ request('date_from') ?? '...' }}
                        →
                        {{ request('date_to') ?? '...' }}
                    </span>
                @endif

                @if (request('status'))
                    <span @class([
                        'badge',
                        'text-capitalize',
                        'bg-warning text-dark' => request('status') === 'pending',
                        'bg-primary' => request('status') === 'confirmed',
                        'bg-success' => request('status') === 'active',
                        'bg-secondary' => request('status') === 'completed',
                        'bg-danger' => request('status') === 'cancelled',
                        'bg-dark' => !request('status'),
                    ])>
                        Status: {{ ucfirst(request('status') ?? 'all') }}
                    </span>
                @endif

                @if (request('therapist_assignment_status'))
                    <span @class([
                        'badge',
                        'text-capitalize',
                        'bg-warning text-dark' =>
                            request('therapist_assignment_status') === 'unassigned',
                        'bg-white text-dark' =>
                            request('therapist_assignment_status') === 'partial',
                        'bg-success-subtle text-success' =>
                            request('therapist_assignment_status') === 'completed',
                    ])>
                        Therapist Assignment: {{ ucfirst(request('therapist_assignment_status')) }}
                    </span>
                @endif


                @if ($selectedService)
                    <span class="badge bg-dark">
                        Service: {{ $selectedService->name }}
                    </span>
                @endif

                @if ($selectedTherapist)
                    <span class="badge bg-dark">
                        Therapist: {{ $selectedTherapist->name }}
                    </span>
                @endif

            </div>

        </div>
    @endif

    <!-- Table -->
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
                            $assignedTherapists = $booking->items
                                ->whereNotNull('therapist_id')
                                ->pluck('therapist')
                                ->filter()
                                ->unique('id');

                            $totalItems = $booking->items->count();
                            $assignedCount = $booking->items->whereNotNull('therapist_id')->count();
                        @endphp

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

                                <span
                                    class="badge
                                    @if ($status == 'pending') bg-warning text-dark
                                    @elseif($status == 'confirmed') bg-primary
                                    @elseif($status == 'active') bg-success
                                    @elseif($status == 'completed') bg-secondary
                                    @elseif($status == 'cancelled' || $status === 'rejected') bg-danger @endif
                                        text-uppercase small">
                                    {{ $status }}
                                </span>

                            </td>

                            <!-- ACTIONS -->
                            <td class="text-end">
                                <div class="d-flex justify-content-end gap-2">

                                    {{-- DROPDOWN --}}
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-secondary" data-bs-toggle="dropdown">
                                            <i class="bi bi-three-dots-vertical"></i>
                                        </button>

                                        <ul class="dropdown-menu dropdown-menu-end shadow-sm">

                                            {{-- VIEW --}}
                                            <li>
                                                <a href="{{ route('bookings.show', $booking->id) }}"
                                                    class="dropdown-item btn btn-sm btn-outline-secondary" title="View">
                                                    <i class="bi bi-eye me-2"></i> View
                                                </a>
                                            </li>

                                            {{-- PENDING --}}
                                            @if ($status === 'pending')
                                                <li>
                                                    <form action="{{ route('bookings.confirm', $booking->id) }}"
                                                        method="POST" onsubmit="return confirm('Confirm this booking?')">
                                                        @csrf
                                                        <button class="dropdown-item text-success">
                                                            <i class="bi bi-check-lg me-2"></i> Confirm
                                                        </button>
                                                    </form>
                                                </li>

                                                <li>
                                                    <form action="{{ route('bookings.reject', $booking->id) }}"
                                                        method="POST" onsubmit="return confirm('Reject this booking?')">
                                                        @csrf
                                                        <button class="dropdown-item text-danger">
                                                            <i class="bi bi-x-circle me-2"></i> Reject
                                                        </button>
                                                    </form>
                                                </li>
                                            @endif

                                            {{-- CONFIRMED, etc --}}
                                            @if (in_array($status, ['confirmed', 'active', 'completed']))
                                                <li>
                                                    <a href="{{ route('therapist-assignments.index', $booking->id) }}"
                                                        class="dropdown-item text-success">
                                                        <i class="bi bi-person-plus me-2"></i> Assign Therapist
                                                    </a>
                                                </li>
                                            @endif


                                            <li>
                                                <a href="{{ route('bookings.edit', $booking->id) }}"
                                                    class="dropdown-item btn btn-sm btn-outline-secondary" title="Edit">
                                                    <i class="bi bi-pencil-square me-2"></i> Edit
                                                </a>
                                            </li>

                                        </ul>
                                    </div>

                                </div>
                            </td>

                        </tr>
                    @empty

                        <tr>
                            <td colspan="7" class="text-center py-5">

                                @if ($hasFilters)
                                    {{-- EMPTY DUE TO FILTERS --}}
                                    <i class="bi bi-search fs-1 text-muted"></i>
                                    <h5 class="mt-3">No results found</h5>
                                    <p class="text-muted mb-3">
                                        No bookings match your filters.
                                    </p>

                                    <a href="{{ route('bookings.index') }}" class="btn btn-outline-dark">
                                        <i class="bi bi-x-circle me-1"></i>
                                        Clear Filters
                                    </a>
                                @else
                                    {{-- EMPTY DATABASE --}}
                                    <i class="bi bi-calendar-x fs-1 text-muted"></i>
                                    <h5 class="mt-3">No bookings yet</h5>
                                    <p class="text-muted mb-0">
                                        Once bookings are created, they will appear here.
                                    </p>
                                @endif

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

