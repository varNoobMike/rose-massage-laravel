@extends('layouts.admin')

@section('page-title', 'Booking Reports')

@section('page-header', true)
@section('page-header-title-indexpage', 'Booking Reports')
@section('page-header-subtitle', 'Analytics, search, and exportable booking data')


{{-- ================= FILTER AREA ================= --}}
@section('filter-area', true)

@section('filter-form')

<form action="{{ route('reports.bookings') }}" method="GET">

    {{-- MOBILE TOGGLE --}}
    <button class="btn btn-outline-dark d-md-none w-100 mb-3"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#reportFilter">
        <i class="bi bi-funnel me-1"></i>
        Show Filters
    </button>

    <div class="collapse d-md-block" id="reportFilter">

        <div class="row g-3 align-items-end">

            {{-- SEARCH --}}
            <div class="col-12 col-md-3">
                <label class="form-label small text-muted">Search</label>
                <input type="text"
                       name="search"
                       class="form-control"
                       placeholder="Booking ID, Client, Service"
                       value="{{ request('search') }}">
            </div>

            {{-- FROM --}}
            <div class="col-12 col-md-3">
                <label class="form-label small text-muted">From</label>
                <input type="date"
                       name="from"
                       class="form-control"
                       value="{{ request('from') }}">
            </div>

            {{-- TO --}}
            <div class="col-12 col-md-3">
                <label class="form-label small text-muted">To</label>
                <input type="date"
                       name="to"
                       class="form-control"
                       value="{{ request('to') }}">
            </div>

            {{-- STATUS --}}
            <div class="col-12 col-md-3">
                <label class="form-label small text-muted">Status</label>

                <select name="status" class="form-select">
                    <option value="">All Status</option>

                    @foreach(['pending','completed','cancelled'] as $status)
                        <option value="{{ $status }}" @selected(request('status') === $status)>
                            {{ ucfirst($status) }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- MIN AMOUNT --}}
            <div class="col-12 col-md-3">
                <label class="form-label small text-muted">Min Amount</label>
                <input type="number"
                       step="0.01"
                       name="min_amount"
                       class="form-control"
                       value="{{ request('min_amount') }}">
            </div>

            {{-- MAX AMOUNT --}}
            <div class="col-12 col-md-3">
                <label class="form-label small text-muted">Max Amount</label>
                <input type="number"
                       step="0.01"
                       name="max_amount"
                       class="form-control"
                       value="{{ request('max_amount') }}">
            </div>

            {{-- ACTIONS --}}
            <div class="col-12 col-md-6 d-flex gap-2">

                <button class="btn btn-dark w-100">
                    <i class="bi bi-funnel me-1"></i>
                    Apply
                </button>

                <a href="{{ route('reports.bookings') }}"
                   class="btn btn-outline-secondary w-100">
                    <i class="bi bi-x-circle me-1"></i>
                    Clear
                </a>

            </div>

        </div>

    </div>

</form>

@endsection


{{-- ================= CONTENT ================= --}}
@section('content')

{{-- 📊 ANALYTICS --}}
<div class="row g-3 mb-4 flex-nowrap flex-md-wrap overflow-auto">

    <div class="col-10 col-sm-6 col-md-3 flex-shrink-0">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body py-3">
                <div class="text-muted small">Total Bookings</div>
                <div class="fs-3 fw-bold">{{ $totalCount }}</div>
            </div>
        </div>
    </div>

    <div class="col-10 col-sm-6 col-md-3 flex-shrink-0">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body py-3">
                <div class="text-muted small">Total Revenue</div>
                <div class="fs-3 fw-bold text-success">
                    ₱{{ number_format($totalSales, 2) }}
                </div>
            </div>
        </div>
    </div>

    <div class="col-10 col-sm-6 col-md-3 flex-shrink-0">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body py-3">
                <div class="text-muted small">Avg Booking</div>
                <div class="fs-3 fw-bold text-primary">
                    ₱{{ number_format($avgBooking ?? 0, 2) }}
                </div>
            </div>
        </div>
    </div>

    <div class="col-10 col-sm-6 col-md-3 flex-shrink-0">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body py-3">
                <div class="text-muted small">Completion Rate</div>
                <div class="fs-3 fw-bold">
                    {{ $completionRate ?? 0 }}%
                </div>
            </div>
        </div>
    </div>

</div>


{{-- 📊 STATUS BREAKDOWN --}}
<div class="row g-3 mb-4 flex-nowrap flex-md-wrap overflow-auto">

    <div class="col-8 col-sm-6 col-md-4 flex-shrink-0">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body py-3">
                <div class="text-muted small">Completed</div>
                <div class="fs-4 fw-bold text-success">
                    {{ $completedCount ?? 0 }}
                </div>
            </div>
        </div>
    </div>

    <div class="col-8 col-sm-6 col-md-4 flex-shrink-0">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body py-3">
                <div class="text-muted small">Pending</div>
                <div class="fs-4 fw-bold text-warning">
                    {{ $pendingCount ?? 0 }}
                </div>
            </div>
        </div>
    </div>

    <div class="col-8 col-sm-6 col-md-4 flex-shrink-0">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body py-3">
                <div class="text-muted small">Cancelled</div>
                <div class="fs-4 fw-bold text-danger">
                    {{ $cancelledCount ?? 0 }}
                </div>
            </div>
        </div>
    </div>

</div>


{{-- 📤 EXPORT --}}
<div class="d-flex gap-2 mb-3">

    <a href=""
       class="btn btn-outline-secondary btn-sm">
        Export CSV
    </a>

    <a href=""
       class="btn btn-outline-success btn-sm">
        Export Excel
    </a>

    <a href=""
       class="btn btn-outline-danger btn-sm">
        Export PDF
    </a>

</div>


{{-- 🔍 ACTIVE FILTERS --}}
@if(request()->hasAny(['search','from','to','status','min_amount','max_amount']))
    <div class="mb-3 small text-muted">
        Filters applied:

        @if(request('search'))
            <span class="badge bg-light text-dark border">Search</span>
        @endif

        @if(request('from') || request('to'))
            <span class="badge bg-light text-dark border">Date Range</span>
        @endif

        @if(request('status'))
            <span class="badge bg-light text-dark border">
                Status: {{ ucfirst(request('status')) }}
            </span>
        @endif

        @if(request('min_amount') || request('max_amount'))
            <span class="badge bg-light text-dark border">Amount Range</span>
        @endif
    </div>
@endif


{{-- 📋 TABLE --}}
<div class="card shadow-sm border">

    <div class="card-header bg-white">
        <div class="fw-semibold">Booking Records</div>
        <small class="text-muted">Filtered, searchable, exportable data</small>
    </div>

    <div class="table-responsive">

        <table class="table table-hover align-middle mb-0">

            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Client</th>
                    <th>Services</th>

                    <th>
                        <a href="{{ request()->fullUrlWithQuery([
                            'sort_by' => 'status',
                            'sort_dir' => request('sort_dir') === 'asc' ? 'desc' : 'asc'
                        ]) }}" class="text-decoration-none text-dark">
                            Status
                        </a>
                    </th>

                    <th class="text-end">
                        <a href="{{ request()->fullUrlWithQuery([
                            'sort_by' => 'total_amount',
                            'sort_dir' => request('sort_dir') === 'asc' ? 'desc' : 'asc'
                        ]) }}" class="text-decoration-none text-dark">
                            Total
                        </a>
                    </th>

                    <th>
                        <a href="{{ request()->fullUrlWithQuery([
                            'sort_by' => 'created_at',
                            'sort_dir' => request('sort_dir') === 'asc' ? 'desc' : 'asc'
                        ]) }}" class="text-decoration-none text-dark">
                            Date
                        </a>
                    </th>
                </tr>
            </thead>

            <tbody>

                @forelse ($bookings as $booking)

                    <tr>

                        <td class="fw-bold text-muted">
                            #{{ $booking->id }}
                        </td>

                        <td>
                            <div class="fw-semibold">
                                {{ $booking->client->name ?? 'N/A' }}
                            </div>
                            <small class="text-muted">
                                ID: {{ $booking->client_id }}
                            </small>
                        </td>

                        <td>
                            @php
                                $services = $booking->items->pluck('service.name');
                            @endphp

                            {{ $services->first() ?? 'N/A' }}

                            @if($services->count() > 1)
                                <small class="text-muted">
                                    +{{ $services->count() - 1 }} more
                                </small>
                            @endif
                        </td>

                        <td>
                            <span @class([
                                'badge rounded-pill',
                                'bg-success' => $booking->status === 'completed',
                                'bg-warning text-dark' => $booking->status === 'pending',
                                'bg-danger' => $booking->status === 'cancelled',
                            ])>
                                {{ ucfirst($booking->status) }}
                            </span>
                        </td>

                        <td class="fw-bold text-end">
                            ₱{{ number_format($booking->total_amount, 2) }}
                        </td>

                        <td>
                            <div class="fw-medium">
                                {{ $booking->created_at->format('M d, Y') }}
                            </div>
                            <small class="text-muted">
                                {{ $booking->created_at->format('h:i A') }}
                            </small>
                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <div class="text-muted">
                                <i class="bi bi-bar-chart fs-1"></i>
                                <div class="mt-2 fw-semibold">No results found</div>
                                <small>Try adjusting your filters</small>
                            </div>
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>


{{-- PAGINATION --}}
<div class="mt-3">
    {{ $bookings->links() }}
</div>

@endsection