@extends('layouts.user')

@section('page-title', 'Bookings')

@section('breadcrumb', true)

@section('page-header', true)
@section('page-header-title', 'My Bookings')
@section('page-header-subtitle', 'View your massage therapy appointments')
@section('page-header-actions')
    <a href="{{ route('bookings.index') }}" class="btn btn-outline-secondary px-4">
        <i class="bi bi-arrow-repeat me-2"></i>
        Sync
    </a>
    <a href="{{ route('bookings.create') }}" class="btn btn-primary px-4">
        <i class="bi bi-plus-lg me-2"></i>
        New
    </a>
@endsection

@section('filter-area', true)
@section('filter-form')

    {{-- MOBILE TOGGLE --}}
    <button class="btn btn-outline-dark d-md-none w-100 mb-3" type="button" data-bs-toggle="collapse"
        data-bs-target="#bookingFilters">
        <i class="bi bi-funnel me-1"></i> Show Filters
    </button>

    <div class="collapse d-md-block" id="bookingFilters">

        <form action="{{ route('bookings.index') }}" method="GET">

            <div class="row g-2 align-items-end">

                {{-- SEARCH --}}
                <div class="col-12 col-md-3">
                    <input type="text" name="search" class="form-control" placeholder="Search booking id..."
                        value="{{ request('search') }}">
                </div>

                {{-- DATE FROM --}}
                <div class="col-12 col-md-2">
                    <div class="input-group">
                        <span class="input-group-text">From</span>
                        <input type="date" name="from" class="form-control" value="{{ request('from') }}">
                    </div>
                </div>

                {{-- DATE TO --}}
                <div class="col-12 col-md-2">
                    <div class="input-group">
                        <span class="input-group-text">To</span>
                        <input type="date" name="to" class="form-control" value="{{ request('to') }}">
                    </div>
                </div>

                {{-- STATUS --}}
                <div class="col-12 col-md-2">
                    <select name="status" class="form-select">

                        <option value="">All Status</option>
                        <option value="pending" @selected(request('status') == 'pending')>Pending</option>
                        <option value="confirmed" @selected(request('status') == 'confirmed')>Confirmed</option>
                        <option value="active" @selected(request('status') == 'active')>Active</option>
                        <option value="completed" @selected(request('status') == 'completed')>Completed</option>
                        <option value="cancelled" @selected(request('status') == 'cancelled')>Cancelled</option>

                    </select>
                </div>

                {{-- ACTIONS --}}
                <div class="col-12 col-md-3 d-flex gap-2">

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
    <div class="container px-lg-5">


        @php
            $hasFilters =
                request()->filled('search') ||
                request()->filled('status') ||
                request()->filled('therapist_assignment_status') ||
                request()->filled('from') ||
                request()->filled('to');
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

                    @if (request('status'))
                        <span @class([
                            'badge',
                            'text-capitalize',
                            'bg-warning text-dark' => request('status') === 'pending',
                            'bg-primary' => request('status') === 'confirmed',
                            'bg-success' => request('status') === 'active',
                            'bg-secondary' => request('status') === 'completed',
                            'bg-danger' => request('status') === 'cancelled',
                            'bg-dark' => request('status') === 'all' || !request('status'),
                        ])>
                            Status: {{ ucfirst(request('status') ?? 'all') }}
                        </span>
                    @endif

                    @if (request('therapist_assignment_status'))
                        <span class="badge bg-warning text-dark">
                            {{ ucfirst(request('therapist_assignment_status')) }}
                        </span>
                    @endif

                    @if (request('from') || request('to'))
                        <span class="badge bg-secondary">
                            Date:
                            {{ request('from') ?? '...' }}
                            →
                            {{ request('to') ?? '...' }}
                        </span>
                    @endif

                </div>

            </div>
        @endif

        <div class="card shadow-sm border">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">
                        <tr>
                            <th>Booking ID</th>
                            <th>Schedule</th>
                            <th>Services</th>
                            <th class="text-end">Total Services</th>
                            <th class="text-end">Total Amount</th>
                            <th class="text-center">Status</th>
                            <th class="text-end">Option</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($bookings as $booking)
                            <tr>

                                <!-- ID -->
                                <td class="fw-bold text-muted">
                                    #{{ $booking->id }}
                                </td>

                                <!-- Schedule -->
                                <td>
                                    <div class="fw-bold">
                                        {{ \Carbon\Carbon::parse($booking->booking_date)->format('M d, Y') }}
                                    </div>
                                    <small class="text-muted">
                                        {{ \Carbon\Carbon::parse($booking->start_time)->format('h:i A') }}
                                    </small>
                                </td>

                                <!-- SERVICES -->
                                <td>
                                    @php
                                        $items = $booking->items ?? collect();
                                        $first = $items->first();
                                    @endphp

                                    <div class="fw-bold">
                                        {{ $first->service_name ?? 'No Service' }}

                                        @if ($items->count() > 1)
                                            <span class="text-muted">
                                                +{{ $items->count() - 1 }} more
                                            </span>
                                        @endif
                                    </div>

                                    <small class="text-muted">
                                        @foreach ($items->take(2) as $item)
                                            {{ $item->service_name }}@if (!$loop->last)
                                                ,
                                            @endif
                                        @endforeach

                                        @if ($items->count() > 2)
                                            ...
                                        @endif
                                    </small>
                                </td>

                                <!-- TOTAL SERVICES -->
                                <td class="text-end fw-bold">
                                    {{ $items->count() }}
                                </td>

                                <!-- TOTAL AMOUNT -->
                                <td class="text-end fw-bold text-primary">
                                    ₱{{ number_format($booking->total_amount, 2) }}
                                </td>

                                <!-- STATUS -->
                                <td class="text-center">
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
                                </td>

                                <!-- ACTION -->
                                <td class="text-end">
                                    <a href="{{ route('bookings.show', $booking->id) }}" class="btn btn-sm btn-secondary">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    {{-- ⭐ ADD THIS --}}
                                    @if ($booking->status === 'completed' && !$booking->review)
                                        <a href="{{ route('reviews.create', $booking->id) }}"
                                            class="btn btn-sm btn-warning ms-1">
                                            <i class="bi bi-star-fill"></i>
                                        </a>
                                    @endif
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
        </div>

    @endsection
