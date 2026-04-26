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
    <form action="{{ route('bookings.index') }}" method="GET">
        <div class="row g-3">

            <div class="col-md-5">
                <input type="text" name="search" class="form-control" placeholder="Search booking id..."
                    value="{{ request('search') }}">
            </div>

            <div class="col-md-2">
                <input type="date" name="date" class="form-control" value="{{ request('date') }}">
            </div>

            <div class="col-md-2">
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed
                    </option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed
                    </option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled
                    </option>
                </select>
            </div>

            <div class="col-md-3 d-flex gap-2">
                <button class="btn btn-dark w-100">Filter</button>
                <a href="{{ route('bookings.index') }}" class="btn btn-outline-secondary w-100">Clear</a>
            </div>

        </div>
    </form>
@endsection

@section('content')

    <!-- Table -->
    <div class="container px-lg-5">

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
                                </td>

                            </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5 text-muted">
                                        No bookings found
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
