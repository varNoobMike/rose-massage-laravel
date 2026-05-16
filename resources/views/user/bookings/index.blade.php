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

    {{-- mobile toggle --}}
    <button class="btn btn-outline-dark d-md-none w-100 mb-3" type="button" data-bs-toggle="collapse"
        data-bs-target="#bookingFilters">
        <i class="bi bi-funnel me-1"></i> Show Filters
    </button>

    <div class="collapse d-md-block" id="bookingFilters">

        <form action="{{ route('bookings.index') }}" method="GET">

            <div class="row g-2 align-items-end">

                {{-- search --}}
                <div class="col-12 col-md-3">
                    <input type="text" name="search" class="form-control"
                        placeholder="Search by booking ID or service..." value="{{ $filters['search'] ?? '' }}">
                </div>

                {{-- date from --}}
                <div class="col-12 col-md-2">
                    <div class="input-group">
                        <span class="input-group-text">From</span>
                        <input type="date" name="date_from" class="form-control"
                            value="{{ $filters['date_from'] ?? '' }}">
                    </div>
                </div>

                {{-- date to --}}
                <div class="col-12 col-md-2">
                    <div class="input-group">
                        <span class="input-group-text">To</span>
                        <input type="date" name="date_to" class="form-control" value="{{ $filters['date_to'] ?? '' }}">
                    </div>
                </div>

                {{-- status --}}
                <div class="col-12 col-md-3">
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="pending" @selected(($filters['status'] ?? '') === 'pending')>Pending</option>
                        <option value="confirmed" @selected(($filters['status'] ?? '') === 'confirmed')>Confirmed</option>
                        <option value="active" @selected(($filters['status'] ?? '') === 'active')>Active</option>
                        <option value="completed" @selected(($filters['status'] ?? '') === 'completed')>Completed</option>
                        <option value="cancelled" @selected(($filters['status'] ?? '') === 'cancelled')>Cancelled</option>
                    </select>
                </div>

                {{-- more --}}
                <div class="col-12 col-md-2">
                    <button class="btn btn-primary w-100" type="button" data-bs-toggle="collapse"
                        data-bs-target="#advancedFilters">
                        <i class="bi bi-three-dots me-1"></i> More
                    </button>
                </div>



            </div>

            {{-- advanced filters --}}
            <div class="collapse mt-3 {{ $hasAdvancedFilters ? 'show' : '' }}" id="advancedFilters">

                <div class="row g-3">

                    {{-- service --}}
                    <div class="col-md-4">
                        <select name="service" class="form-select">
                            <option value="">All Services</option>
                            @forelse($services as $service)
                                <option value="{{ $service->id }}" @selected(($filters['service'] ?? '') == $service->id)>
                                    {{ $service->name }}
                                </option>
                            @empty
                                <option value="" disabled>No services yet</option>
                            @endforelse
                        </select>
                    </div>

                </div>

            </div>

            {{-- actions --}}
            <div class="col-12 col-md-3 d-flex gap-2 mt-3">
                <button class="btn btn-dark w-100">
                    <i class="bi bi-funnel me-1"></i> Filter
                </button>
                <a href="{{ route('bookings.index') }}" class="btn btn-outline-secondary w-100">
                    <i class="bi bi-x-circle me-1"></i> Clear
                </a>
            </div>

        </form>

    </div>

@endsection

@section('content')

    <div class="container px-lg-5">

        {{-- filters applied --}}
        @if ($hasFilters)
            <div class="alert alert-info d-flex justify-content-between align-items-center py-2 px-3 mb-3">
                <div class="d-flex flex-wrap gap-2 align-items-center">

                    <strong class="me-2">
                        <i class="bi bi-funnel-fill"></i> Filters applied:
                    </strong>

                    @if (!empty($filters['search']))
                        <span class="badge bg-dark">
                            Search: {{ $filters['search'] }}
                        </span>
                    @endif

                    @if (!empty($filters['status']))
                        <span class="badge bg-primary text-capitalize">
                            Status: {{ $filters['status'] }}
                        </span>
                    @endif

                    @if (!empty($filters['date_from']) || !empty($filters['date_to']))
                        <span class="badge bg-secondary">
                            Date:
                            {{ $filters['date_from'] ?? '...' }} → {{ $filters['date_to'] ?? '...' }}
                        </span>
                    @endif

                    @if (!empty($selectedService))
                        <span class="badge bg-dark">
                            Service: {{ $selectedService->name }}
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
                            <th class="d-none d-lg-table-cell">Services</th>
                            <th class="text-end d-none d-lg-table-cell">Total Services</th>
                            <th class="text-end d-none d-lg-table-cell">Total Amount</th>
                            <th class="text-center">Status</th>
                            <th class="text-end">Option</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($bookings as $booking)
                            @php
                                $items = $booking->items ?? collect();
                                $firstItem = $items->first();
                            @endphp

                            <tr>

                                {{-- id --}}
                                <td class="fw-bold text-muted">
                                    #{{ $booking->id }}
                                </td>

                                {{-- schedule --}}
                                <td>
                                    <div class="fw-bold">
                                        {{ \Carbon\Carbon::parse($booking->booking_date)->format('M d, Y') }}
                                    </div>
                                    <small class="text-muted">
                                        {{ \Carbon\Carbon::parse($booking->start_time)->format('h:i A') }}
                                    </small>
                                </td>

                                {{-- services --}}
                                <td class="d-none d-lg-table-cell">
                                    <div class="fw-bold">
                                        {{ $firstItem->service_name ?? 'No Service' }}

                                        @if ($items->count() > 1)
                                            <span class="text-muted">
                                                +{{ $items->count() - 1 }} more
                                            </span>
                                        @endif
                                    </div>

                                    <small class="text-muted">
                                        {{ $items->pluck('service_name')->take(2)->implode(', ') }}
                                        @if ($items->count() > 2)
                                            ...
                                        @endif
                                    </small>
                                </td>

                                {{-- count --}}
                                <td class="text-end fw-bold d-none d-lg-table-cell">
                                    {{ $items->count() }}
                                </td>

                                {{-- amount --}}
                                <td class="text-end fw-bold text-primary d-none d-lg-table-cell">
                                    ₱{{ number_format($booking->total_amount, 2) }}
                                </td>

                                {{-- status --}}
                                <td class="text-center">
                                    <span @class([
                                        'badge',
                                        'text-capitalize',
                                        'bg-warning text-dark' => $booking->status === 'pending',
                                        'bg-primary' => $booking->status === 'confirmed',
                                        'bg-success' => $booking->status === 'active',
                                        'bg-secondary' => $booking->status === 'completed',
                                        'bg-danger' => in_array($booking->status, ['cancelled', 'rejected']),
                                    ])>
                                        {{ $booking->status }}
                                    </span>
                                </td>

                                {{-- actions --}}
                                <td class="text-end">
                                    <a href="{{ route('bookings.show', $booking->id) }}" class="btn btn-sm btn-secondary">
                                        <i class="bi bi-eye"></i>
                                    </a>

                                    @if ($booking->status === 'completed' && empty($booking->review))
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
                                        <i class="bi bi-search fs-1 text-muted"></i>
                                        <h5 class="mt-3">No results found</h5>
                                        <a href="{{ route('bookings.index') }}" class="btn btn-outline-dark">
                                            Clear Filters
                                        </a>
                                    @else
                                        <i class="bi bi-calendar-x fs-1 text-muted"></i>
                                        <h5 class="mt-3">No bookings yet</h5>
                                    @endif
                                </td>
                            </tr>
                        @endforelse

                    </tbody>

                </table>
            </div>

        </div>

    </div>

@endsection
