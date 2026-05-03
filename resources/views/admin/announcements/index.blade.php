@extends('layouts.admin')

@section('page-title', 'Announcements')

@section('page-header', true)
@section('page-header-title-indexpage', 'Announcements')
@section('page-header-subtitle', 'Manage client announcements')

@section('page-header-actions')
    <a href="{{ route('announcements.index') }}" class="btn btn-outline-secondary px-4 shadow-sm">
        <i class="bi bi-arrow-repeat me-2"></i> Sync
    </a>
    @if (auth()->user()->role !== 'receptionist')
        <a href="{{ route('announcements.create') }}" class="btn btn-primary px-4 shadow-sm">
            <i class="bi bi-plus-lg me-2"></i> New
        </a>
    @endif
@endsection

@section('filter-area', true)
@section('filter-form')

<form action="{{ route('announcements.index') }}" method="GET">

    {{-- MOBILE TOGGLE --}}
    <button class="btn btn-outline-dark d-md-none w-100 mb-3"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#announcementFilter">
        <i class="bi bi-funnel me-1"></i>
        Show Filters
    </button>

    <div class="collapse d-md-block" id="announcementFilter">

        <div class="row g-3 align-items-end">

            {{-- SEARCH --}}
            <div class="col-12 col-md-3">
                <input type="text"
                       name="search"
                       class="form-control"
                       placeholder="Search announcements..."
                       value="{{ request('search') }}">
            </div>

            {{-- TYPE --}}
            <div class="col-12 col-md-2">
                <select name="type" class="form-select">
                    <option value="">All Types</option>
                    <option value="promo" {{ request('type') == 'promo' ? 'selected' : '' }}>Promo</option>
                    <option value="update" {{ request('type') == 'update' ? 'selected' : '' }}>Update</option>
                    <option value="alert" {{ request('type') == 'alert' ? 'selected' : '' }}>Alert</option>
                    <option value="info" {{ request('type') == 'info' ? 'selected' : '' }}>Info</option>
                </select>
            </div>

            {{-- DATE --}}
            <div class="col-12 col-md-2">
                <input type="date"
                       name="date"
                       class="form-control"
                       value="{{ request('date') }}">
            </div>

            {{-- STATUS --}}
            <div class="col-12 col-md-2">
                <select name="status" class="form-select">

                    <option value="" {{ request('status') == null ? 'selected' : '' }}>
                        All Status
                    </option>

                    <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>
                        Active
                    </option>

                    <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>
                        Inactive
                    </option>

                </select>
            </div>

            {{-- ACTIONS --}}
            <div class="col-12 col-md-3 d-flex gap-2">

                <button class="btn btn-dark w-100">
                    <i class="bi bi-funnel me-1"></i>
                    Apply
                </button>

                <a href="{{ route('announcements.index') }}"
                   class="btn btn-outline-secondary w-100">
                    <i class="bi bi-x-circle me-1"></i>
                    Clear
                </a>

            </div>

        </div>

    </div>

</form>

@endsection

@section('content')
    <!-- Table -->
    <div class="card shadow-sm border">

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

                                    <div class="bg-light rounded overflow-hidden d-flex align-items-center justify-content-center me-3"
                                         style="width:50px; height:50px;">

                                        @if ($announcement->cover_image)
                                            <img src="{{ asset('storage/' . $announcement->cover_image) }}"
                                                 class="w-100 h-100 object-fit-cover">
                                        @else
                                            <i class="bi bi-megaphone text-primary fs-5"></i>
                                        @endif

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
                                <span
                                    class="badge
                                    @if ($announcement->type == 'promo') bg-success
                                    @elseif($announcement->type == 'update') bg-primary
                                    @elseif($announcement->type == 'alert') bg-danger
                                    @else bg-secondary @endif">
                                    {{ ucfirst($announcement->type) }}
                                </span>
                            </td>

                            <!-- Status -->
                            <td class="text-center">
                                @if ($announcement->is_active)
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
                                        class="btn btn-sm btn-secondary">
                                        <i class="bi bi-eye"></i>
                                    </a>

                                    <!-- Edit, Delete -->
                                    @if (auth()->user()?->role === 'admin' || auth()->user()?->role === 'owner')
                                        <a href="{{ route('announcements.edit', $announcement->id) }}"
                                            class="btn btn-sm btn-primary">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('announcements.destroy', $announcement->id) }}" method="POST"
                                            onsubmit="return confirm('Delete this announcement? This action cannot be undone.')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-danger">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    @endif

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
        @if ($announcements->hasPages())
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
@endsection
