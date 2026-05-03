@extends('layouts.admin')

@section('page-title', 'Notifications')

@section('page-header', true)
@section('page-header-title-indexpage', 'Notifications')
@section('page-header-subtitle', 'View booking and system alerts')

@section('page-header-actions')
    <a href="{{ route('notifications.index') }}" class="btn btn-outline-secondary px-4 shadow-sm">
        <i class="bi bi-arrow-repeat me-2"></i> Sync
    </a>
    <form action="{{ route('notifications.readAll') }}" method="POST">
        @csrf
        <button class="btn btn-primary px-4">
            <i class="bi bi-check2-all me-2"></i>
            Mark All Read
        </button>
    </form>
@endsection

@section('filter-area', true)
@section('filter-form')

    <form action="{{ route('notifications.index') }}" method="GET">

        {{-- MOBILE TOGGLE --}}
        <button class="btn btn-outline-dark d-md-none w-100 mb-3" type="button" data-bs-toggle="collapse"
            data-bs-target="#notificationFilter">
            <i class="bi bi-funnel me-1"></i>
            Show Filters
        </button>

        <div class="collapse d-md-block" id="notificationFilter">

            <div class="row g-3 align-items-end">

                {{-- SEARCH --}}
                <div class="col-12 col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="Search message..."
                        value="{{ request('search') }}">
                </div>

                {{-- DATE FROM --}}
                <div class="col-md-2">
                    <div class="input-group">
                        <span class="input-group-text">From</span>
                        <input type="date" name="from" class="form-control" value="{{ request('from') }}">
                    </div>
                </div>

                {{-- DATE TO --}}
                <div class="col-md-2">
                    <div class="input-group">
                        <span class="input-group-text">To</span>
                        <input type="date" name="to" class="form-control" value="{{ request('to') }}">
                    </div>
                </div>

                {{-- STATUS --}}
                <div class="col-12 col-md-3">

                    @php
                        $status = request('status', 'all');
                    @endphp

                    <select name="status" class="form-select">

                        <option value="" {{ $status == 'all' ? 'selected' : '' }}>
                            All Notifications
                        </option>

                        <option value="unread" {{ $status == 'unread' ? 'selected' : '' }}>
                            Unread
                        </option>

                        <option value="read" {{ $status == 'read' ? 'selected' : '' }}>
                            Read
                        </option>

                    </select>

                </div>

                {{-- ACTIONS --}}
                <div class="col-12 col-md-3 d-flex gap-2">

                    <button class="btn btn-dark w-100">
                        Filter
                    </button>

                    <a href="{{ route('notifications.index') }}" class="btn btn-outline-secondary w-100">
                        Clear
                    </a>

                </div>

            </div>

        </div>

    </form>

@endsection

@section('content')

    @php
        $hasFilters =
            request()->filled('search') ||
            request()->filled('from') ||
            request()->filled('to') ||
            request()->filled('status');
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

                @if (request('from') || request('to'))
                    <span class="badge bg-secondary">
                        Date:
                        {{ request('from') ?? '...' }}
                        →
                        {{ request('to') ?? '...' }}
                    </span>
                @endif

                @if (request('status'))
                    <span @class([
                        'badge text-capitalize',
                        'bg-success' => request('status') === 'unread',
                        'bg-secondary' => request('status') === 'read',
                    ])>
                        Status: {{ ucfirst(request('status')) }}
                    </span>
                @endif

            </div>

        </div>
    @endif

    <!-- Table -->
    <div class="card shadow-sm border">

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">

                <thead class="table-light">
                    <tr>
                        <th>Notification</th>
                        <th class="text-center">Type</th>
                        <th class="text-center">Date</th>
                        <th class="text-center">Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($notifications as $notification)
                        <tr>

                            <!-- Message -->
                            <td>
                                <div class="d-flex align-items-center">

                                    <div class="bg-light rounded d-flex align-items-center justify-content-center me-3"
                                        style="width:50px; height:50px;">
                                        <i class="bi bi-bell text-primary fs-5"></i>
                                    </div>

                                    <div>
                                        <div class="fw-bold">
                                            {{ $notification->data['message'] ?? 'Notification' }}
                                        </div>

                                        <small class="text-muted">
                                            Booking #{{ $notification->data['booking_id'] ?? '-' }}
                                        </small>
                                    </div>

                                </div>
                            </td>

                            <!-- Type -->
                            <td class="text-center">
                                <span class="badge bg-light text-dark">
                                    Booking
                                </span>
                            </td>

                            <!-- Date -->
                            <td class="text-center">
                                {{ $notification->created_at->format('M d, Y h:i A') }}
                            </td>

                            <!-- Status -->
                            <td class="text-center">
                                @if ($notification->read_at)
                                    <span class="badge bg-secondary">
                                        Read
                                    </span>
                                @else
                                    <span class="badge bg-success">
                                        Unread
                                    </span>
                                @endif
                            </td>

                            <!-- Actions -->
                            <td class="text-end">
                                <div class="btn-group gap-2">

                                    @if (!$notification->read_at)
                                        <form action="{{ route('notifications.read', $notification->id) }}" method="POST">
                                            @csrf
                                            <button class="btn btn-sm btn-primary">
                                                <i class="bi bi-check-lg"></i>
                                            </button>
                                        </form>
                                    @endif


                                    @php $type = $notification->type; @endphp

                                    @if ($type === 'App\Notifications\NewBookingNotification')
                                        <a href="{{ route('bookings.show', $notification->data['booking_id']) }}"
                                            class="btn btn-sm btn-secondary">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    @elseif ($type === 'App\Notifications\NewBookingReviewNotification')
                                        <a href="{{ route('reviews.show', $notification->data['review_id']) }}"
                                            class="btn btn-sm btn-secondary">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    @endif

                                </div>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">

                                @if ($hasFilters)
                                    {{-- EMPTY DUE TO FILTERS --}}
                                    <i class="bi bi-bell fs-1 text-muted"></i>
                                    <h5 class="mt-3">No results found</h5>
                                    <p class="text-muted mb-3">
                                        No notifications match your filters.
                                    </p>

                                    <a href="{{ route('notifications.index') }}" class="btn btn-outline-dark">
                                        <i class="bi bi-x-circle me-1"></i>
                                        Clear Filters
                                    </a>
                                @else
                                    {{-- EMPTY DATABASE --}}
                                    <i class="bi bi-bell fs-1 text-muted"></i>
                                    <h5 class="mt-3">No notifications yet</h5>
                                    <p class="text-muted mb-0">
                                        Once notifications are created, they will appear here.
                                    </p>
                                @endif

                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

        <!-- Pagination -->
        @if ($notifications->hasPages())
            <div class="card-footer bg-white">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">

                    <small class="text-muted">
                        Showing {{ $notifications->firstItem() }}
                        to {{ $notifications->lastItem() }}
                        of {{ $notifications->total() }} notifications
                    </small>

                    {{ $notifications->appends(request()->query())->links() }}

                </div>
            </div>
        @endif

    </div>
@endsection
