@extends('layouts.user')

@section('page-title', 'Notifications')

@section('breadcrumb', true)

@section('page-header', true)
@section('page-header-title', 'Notifications')
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
        <div class="row g-3">

            <!-- Search -->
            <div class="col-md-6">
                <input type="text" name="search" class="form-control" placeholder="Search notifications..."
                    value="{{ request('search') }}">
            </div>

            <!-- Status -->
            <div class="col-md-3">
                @php
                    $status = request('status', 'all');
                @endphp

                <select name="status" class="form-select">
                    <option value="all" {{ $status == 'all' ? 'selected' : '' }}>All</option>
                    <option value="unread" {{ $status == 'unread' ? 'selected' : '' }}>Unread</option>
                    <option value="read" {{ $status == 'read' ? 'selected' : '' }}>Read</option>
                </select>
            </div>

            <!-- Actions -->
            <div class="col-md-3 d-flex gap-2">
                <button class="btn btn-dark w-100">Filter</button>
                <a href="{{ route('notifications.index') }}" class="btn btn-outline-secondary w-100">Clear</a>
            </div>

        </div>
    </form>
@endsection

@section('content')

    <div class="container px-lg-5">

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

                                <!-- MESSAGE -->
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

                                <!-- TYPE -->
                                <td class="text-center">
                                    <span class="badge bg-light text-dark">
                                        Booking
                                    </span>
                                </td>

                                <!-- DATE -->
                                <td class="text-center">
                                    {{ $notification->created_at->format('M d, Y h:i A') }}
                                </td>

                                <!-- STATUS -->
                                <td class="text-center">
                                    @if ($notification->read_at)
                                        <span class="badge bg-secondary">Read</span>
                                    @else
                                        <span class="badge bg-success">Unread</span>
                                    @endif
                                </td>

                                <!-- ACTIONS -->
                                <td class="text-end">
                                    <div class="btn-group gap-2">

                                        <!-- Mark as read -->
                                        @if (!$notification->read_at)
                                            <form action="{{ route('notifications.read', $notification->id) }}"
                                                method="POST">
                                                @csrf
                                                <button class="btn btn-sm btn-outline-primary">
                                                    <i class="bi bi-check-lg"></i>
                                                </button>
                                            </form>
                                        @endif

                                        <!-- View booking -->
                                        @if (isset($notification->data['booking_id']))
                                            <a href="{{ route('bookings.show', $notification->data['booking_id']) }}"
                                                class="btn btn-sm btn-outline-secondary">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        @endif

                                    </div>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5">

                                    <i class="bi bi-bell-slash fs-1 text-muted"></i>

                                    <h5 class="mt-3">No notifications found</h5>

                                    <p class="text-muted mb-0">
                                        You're all caught up.
                                    </p>

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

    </div>

@endsection
