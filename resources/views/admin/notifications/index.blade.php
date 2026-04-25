@extends('layouts.admin')

@section('page-title', 'Notifications')

@section('content')
<div class="container-fluid">

    <!-- Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <h3 class="fw-bold mb-1">Notifications</h3>
            <p class="text-muted mb-0">
                Review booking alerts, updates, and system notifications.
            </p>
        </div>

        <div class="d-flex gap-2">

            <!-- Mark all as read -->
            <form action="{{ route('notifications.readAll') }}" method="POST">
                @csrf
                <button class="btn btn-outline-primary rounded-pill px-4">
                    <i class="bi bi-check2-all me-2"></i>
                    Mark All Read
                </button>
            </form>

            <!-- Refresh -->
            <a href="{{ route('notifications.index') }}"
               class="btn btn-primary rounded-pill px-4">
                <i class="bi bi-arrow-repeat me-2"></i>
                Refresh
            </a>

        </div>
    </div>

    <!-- Filters -->
    <div class="card shadow-sm border-0 mb-4 rounded">
        <div class="card-body">

            <form action="{{ route('notifications.index') }}" method="GET">
                <div class="row g-3">

                    <!-- Search -->
                    <div class="col-md-6">
                        <input type="text"
                               name="search"
                               class="form-control rounded"
                               placeholder="Search message..."
                               value="{{ request('search') }}">
                    </div>

                    <!-- Status -->
                    <div class="col-md-3">
                        @php
                            $status = request('status', 'all');
                        @endphp

                        <select name="status" class="form-select rounded">
                            <option value="all" {{ $status == 'all' ? 'selected' : '' }}>
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

                    <!-- Actions -->
                    <div class="col-md-3 d-flex gap-2">
                        <button class="btn btn-dark w-100 rounded">
                            Filter
                        </button>

                        <a href="{{ route('notifications.index') }}"
                           class="btn btn-outline-secondary w-100 rounded">
                            Clear
                        </a>
                    </div>

                </div>
            </form>

        </div>
    </div>

    <!-- Table -->
    <div class="card shadow-sm border-0">

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
                                @if($notification->read_at)
                                    <span class="badge bg-secondary rounded">
                                        Read
                                    </span>
                                @else
                                    <span class="badge bg-success rounded">
                                        Unread
                                    </span>
                                @endif
                            </td>

                            <!-- Actions -->
                            <td class="text-end">
                                <div class="btn-group gap-2">

                                    @if(!$notification->read_at)
                                        <form action="{{ route('notifications.read', $notification->id) }}"
                                              method="POST">
                                            @csrf
                                            <button class="btn btn-sm btn-outline-primary rounded">
                                                <i class="bi bi-check-lg"></i>
                                            </button>
                                        </form>
                                    @endif

                                    @if(isset($notification->data['booking_id']))
                                        <a href="{{ route('bookings.show', $notification->data['booking_id']) }}"
                                           class="btn btn-sm btn-outline-secondary rounded">
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
        @if($notifications->hasPages())
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