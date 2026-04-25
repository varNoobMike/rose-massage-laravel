@extends('layouts.user')

@section('page-title', 'Notifications')

@section('content')

<div class="container-fluid">

    <!-- Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <h3 class="fw-bold mb-1">Annnouncements</h3>
            <p class="text-muted mb-0">
                View announcements.
            </p>
        </div>

        <div class="d-flex gap-2">

            <!-- Refresh -->
            <a href="{{ route('notifications.index') }}"
               class="btn btn-primary rounded-pill px-4">
                <i class="bi bi-arrow-repeat me-2"></i>
                Refresh
            </a>

        </div>
    </div>

    <!-- Filters -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">

            <form action="{{ route('announcements.index') }}" method="GET">
                <div class="row g-3">

                    <!-- Search -->
                    <div class="col-md-6">
                        <input type="text"
                               name="search"
                               class="form-control"
                               placeholder="Search notifications..."
                               value="{{ request('search') }}">
                    </div>

                    <!-- Type -->
                    <div class="col-md-3">
                        @php
                            $status = request('type', 'all');
                        @endphp

                         <select name="type" class="form-select rounded">
                            <option value="">All Types</option>
                            <option value="promo" {{ request('type') == 'promo' ? 'selected' : '' }}>Promo</option>
                            <option value="update" {{ request('type') == 'update' ? 'selected' : '' }}>Update</option>
                            <option value="alert" {{ request('type') == 'alert' ? 'selected' : '' }}>Alert</option>
                            <option value="info" {{ request('type') == 'info' ? 'selected' : '' }}>Info</option>
                        </select>
                    </div>

                    <!-- Actions -->
                    <div class="col-md-3 d-flex gap-2">
                        <button class="btn btn-dark w-100">Filter</button>
                        <a href="{{ route('announcements.index') }}" class="btn btn-outline-secondary w-100">Clear</a>
                    </div>

                </div>
            </form>

        </div>
    </div>

    <!-- Cards -->
    <div class="card shadow-sm border-0">

        <!-- Announcement List -->
        <div class="card-body">

            <div class="row g-3">

                @forelse($announcements as $announcement)

                    <div class="col-12 col-md-6 col-xl-4">

                        <div class="border rounded-3 p-3 h-100 shadow-sm bg-light-subtle d-flex flex-column">

                            <!-- TYPE + STATUS -->
                            <div class="d-flex justify-content-between align-items-center mb-2">

                                @php
                                    $badgeClass = match($announcement->type) {
                                        'promo' => 'success',
                                        'update' => 'primary',
                                        'alert' => 'danger',
                                        default => 'secondary'
                                    };
                                @endphp

                                <span class="badge bg-{{ $badgeClass }}">
                                    {{ ucfirst($announcement->type) }}
                                </span>

                                @if($announcement->is_active)
                                    <span class="badge bg-success-subtle text-success border">
                                        Active
                                    </span>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary border">
                                        Inactive
                                    </span>
                                @endif

                            </div>

                            <!-- TITLE -->
                            <h6 class="fw-bold mb-2">
                                {{ $announcement->title }}
                            </h6>

                            <!-- MESSAGE -->
                            <p class="text-muted small flex-grow-1 mb-3">
                                {{ \Illuminate\Support\Str::limit($announcement->message, 100) }}
                            </p>

                            <!-- FOOTER -->
                            <div class="d-flex justify-content-between align-items-center mt-auto">

                                <small class="text-muted">
                                    {{ $announcement->created_at->format('M d') }}
                                </small>

                                <a href="{{ route('announcements.show', $announcement->id) }}"
                                class="btn btn-sm btn-outline-primary">
                                    View
                                </a>

                            </div>

                        </div>

                    </div>

                @empty
                    <div class="col-12 text-center py-5">
                        <i class="bi bi-megaphone fs-1 text-muted opacity-50"></i>
                        <h5 class="mt-3">No announcements</h5>
                    </div>
                @endforelse

            </div>

        </div>
     
        <!-- Pagination -->
        @if($announcements->hasPages())
            <div class="card-footer bg-white">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">

                    <small class="text-muted">
                        Showing {{ $announcements->firstItem() }}
                        to {{ $announcements->lastItem() }}
                        of {{ $announcements->total() }} announcements
                    </small>

                    {{ $announcements->appends(request()->query())->links() }}

                </div>
            </div>
        @endif

    </div>

</div>

@endsection