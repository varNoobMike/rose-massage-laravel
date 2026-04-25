@extends('layouts.admin')

@section('page-title', 'Announcements')

@section('content')
<div class="container-fluid">

    <!-- Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <h3 class="fw-bold mb-1">Announcements</h3>
            <p class="text-muted mb-0">
                Manage promos, updates, pricing changes, and spa notices.
            </p>
        </div>

        <div class="d-flex gap-2">

            <!-- Create -->
            <a href="{{ route('announcements.create') }}"
               class="btn btn-primary rounded-pill px-4">
                <i class="bi bi-plus-lg me-2"></i>
                New Announcement
            </a>

            <!-- Refresh -->
            <a href="{{ route('announcements.index') }}"
               class="btn btn-outline-secondary rounded-pill px-4">
                <i class="bi bi-arrow-repeat me-2"></i>
                Refresh
            </a>

        </div>
    </div>

    <!-- Filters -->
    <div class="card shadow-sm border-0 mb-4 rounded">
        <div class="card-body">

            <form action="{{ route('announcements.index') }}" method="GET">
                <div class="row g-3">

                    <!-- Search -->
                    <div class="col-md-6">
                        <input type="text"
                               name="search"
                               class="form-control rounded"
                               placeholder="Search announcements..."
                               value="{{ request('search') }}">
                    </div>

                    <!-- Type -->
                    <div class="col-md-3">
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
                        <button class="btn btn-dark w-100 rounded">
                            Filter
                        </button>

                        <a href="{{ route('announcements.index') }}"
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
                        <th>Announcement</th>
                        <th class="text-center">Type</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Date</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($announcements as $announcement)
                        <tr>

                            <!-- Message -->
                            <td>
                                <div class="d-flex align-items-center">

                                    <div class="bg-light rounded d-flex align-items-center justify-content-center me-3"
                                         style="width:50px; height:50px;">
                                        <i class="bi bi-megaphone text-primary fs-5"></i>
                                    </div>

                                    <div>
                                        <div class="fw-bold">
                                            {{ $announcement->title }}
                                        </div>

                                        <small class="text-muted">
                                            {{ Str::limit($announcement->message, 60) }}
                                        </small>
                                    </div>

                                </div>
                            </td>

                            <!-- Type -->
                            <td class="text-center">
                                <span class="badge
                                    @if($announcement->type == 'promo') bg-success
                                    @elseif($announcement->type == 'update') bg-primary
                                    @elseif($announcement->type == 'alert') bg-danger
                                    @else bg-secondary
                                    @endif">
                                    {{ ucfirst($announcement->type) }}
                                </span>
                            </td>

                            <!-- Status -->
                            <td class="text-center">
                                @if($announcement->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </td>

                            <!-- Date -->
                            <td class="text-center">
                                <div class="fw-bold">
                                    {{ $announcement->created_at->format('M d, Y') }}
                                </div>
                                <small class="text-muted">
                                    {{ $announcement->created_at->format('h:i A') }}
                                </small>
                            </td>

                            <!-- Actions -->
                            <td class="text-end">

                                <div class="btn-group gap-2">

                                    <!-- View -->
                                    <a href="{{ route('announcements.show', $announcement->id) }}"
                                       class="btn btn-sm btn-outline-secondary rounded">
                                        <i class="bi bi-eye"></i>
                                    </a>

                                    <!-- Edit -->
                                    <a href="{{ route('announcements.edit', $announcement->id) }}"
                                       class="btn btn-sm btn-outline-primary rounded">
                                        <i class="bi bi-pencil"></i>
                                    </a>

                                </div>

                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">

                                <i class="bi bi-megaphone fs-1 text-muted"></i>

                                <h5 class="mt-3">No announcements found</h5>

                                <p class="text-muted mb-0">
                                    Create your first announcement for users.
                                </p>

                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
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