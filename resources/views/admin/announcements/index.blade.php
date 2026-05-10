@extends('layouts.admin')

@section('page-title', 'Announcements')

@section('page-header', true)
@section('page-header-title-indexpage', 'Announcements')
@section('page-header-subtitle', 'Manage client announcements')

@section('page-header-actions')
    <a href="{{ route('announcements.index') }}" class="btn btn-outline-secondary px-4 shadow-sm">
        <i class="bi bi-arrow-repeat me-2"></i> Sync
    </a>

    @if (in_array(auth()->user()?->role, ['admin', 'owner']))
        <a href="{{ route('announcements.create') }}" class="btn btn-primary px-4 shadow-sm">
            <i class="bi bi-plus-lg me-2"></i> New
        </a>
    @endif
@endsection

@section('filter-area', true)
@section('filter-form')

    {{-- MOBILE FILTER TOGGLE --}}
    <form action="{{ route('announcements.index') }}" method="GET">

        <button class="btn btn-outline-dark d-md-none w-100 mb-3" type="button" data-bs-toggle="collapse"
            data-bs-target="#announcementFilter">
            <i class="bi bi-funnel me-1"></i>
            Show Filters
        </button>

        <div class="collapse d-md-block" id="announcementFilter">

            <div class="row g-3 align-items-end">

                {{-- SEARCH FILTER --}}
                <div class="col-12 col-md-3">
                    <input type="text" name="search" class="form-control" placeholder="Search title or message..."
                        value="{{ $filters['search'] ?? '' }}">
                </div>

                {{-- TYPE FILTER --}}
                <div class="col-12 col-md-2">
                    <select name="type" class="form-select">

                        <option value="" @selected(empty($filters['type'] ?? null))>
                            All Types
                        </option>

                        <option value="promo" @selected(($filters['type'] ?? null) === 'promo')>
                            Promo
                        </option>

                        <option value="update" @selected(($filters['type'] ?? null) === 'update')>
                            Update
                        </option>

                        <option value="alert" @selected(($filters['type'] ?? null) === 'alert')>
                            Alert
                        </option>

                        <option value="info" @selected(($filters['type'] ?? null) === 'info')>
                            Info
                        </option>

                    </select>
                </div>

                {{-- DATE FROM --}}
                <div class="col-md-2">
                    <div class="input-group">
                        <span class="input-group-text">From</span>
                        <input type="date" name="date_from" class="form-control"
                            value="{{ $filters['date_from'] ?? '' }}">
                    </div>
                </div>

                {{-- DATE TO --}}
                <div class="col-md-2">
                    <div class="input-group">
                        <span class="input-group-text">To</span>
                        <input type="date" name="date_to" class="form-control" value="{{ $filters['date_to'] ?? '' }}">
                    </div>
                </div>

                {{-- ACTION BUTTONS --}}
                <div class="col-12 col-md-3 d-flex gap-2">

                    <button class="btn btn-dark w-100">
                        <i class="bi bi-funnel me-1"></i>
                        Filter
                    </button>

                    <a href="{{ route('announcements.index') }}" class="btn btn-outline-secondary w-100">
                        <i class="bi bi-x-circle me-1"></i>
                        Clear
                    </a>

                </div>

                {{-- STATUS FILTER --}}
                <div class="col-12 col-md-3">
                    <select name="status" class="form-select">

                        <option value="" @selected(empty($filters['status'] ?? null))>
                            All Status
                        </option>

                        <option value="1" @selected(($filters['status'] ?? null) == '1')>
                            Active
                        </option>

                        <option value="0" @selected(($filters['status'] ?? null) == '0')>
                            Inactive
                        </option>

                    </select>
                </div>

            </div>

        </div>

    </form>

@endsection

@section('content')

    {{-- FILTER SUMMARY --}}
    @if ($hasFilters)
        <div class="alert alert-info d-flex justify-content-between align-items-center py-2 px-3 mb-3">

            <div class="d-flex flex-wrap gap-2 align-items-center">

                <strong class="me-2">
                    <i class="bi bi-funnel-fill"></i> Filters applied:
                </strong>

                {{-- SEARCH BADGE --}}
                @if (!empty($filters['search']))
                    <span class="badge bg-dark">
                        Search: {{ $filters['search'] }}
                    </span>
                @endif

                {{-- TYPE BADGE --}}
                @if (!empty($filters['type']))
                    <span @class([
                        'badge text-capitalize',
                        'bg-success' => $filters['type'] === 'promo',
                        'bg-primary' => $filters['type'] === 'update',
                        'bg-danger' => $filters['type'] === 'alert',
                        'bg-secondary' => $filters['type'] === 'info',
                    ])>
                        Type: {{ ucfirst($filters['type']) }}
                    </span>
                @endif

                {{-- STATUS BADGE --}}
                @if (!empty($filters['status']))
                    <span @class([
                        'badge text-capitalize',
                        'bg-success' => $filters['status'] == '1',
                        'bg-secondary' => $filters['status'] == '0',
                    ])>
                        Status: {{ $filters['status'] == '1' ? 'Active' : 'Inactive' }}
                    </span>
                @endif

                {{-- DATE RANGE BADGE --}}
                @if (!empty($filters['date_from']) || !empty($filters['date_to']))
                    <span class="badge bg-secondary">
                        Date:
                        {{ $filters['date_from'] ?? '...' }}
                        →
                        {{ $filters['date_to'] ?? '...' }}
                    </span>
                @endif

            </div>

        </div>
    @endif

    {{-- TABLE --}}
    <div class="card shadow-sm border">

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">

                <thead class="table-light">
                    <tr>
                        <th>Announcement</th>
                        <th class="text-center">Type</th>
                        <th class="text-center d-none d-lg-table-cell">Status</th>
                        <th class="text-center d-none d-lg-table-cell">Date</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($announcements as $announcement)
                        <tr>

                            {{-- ANNOUNCEMENT INFO --}}
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

                                        <small class="text-muted d-block">
                                            ID #{{ $announcement->id }}
                                        </small>

                                        <small class="text-muted">
                                            {{ Str::limit($announcement->message, 60) }}
                                        </small>
                                    </div>

                                </div>
                            </td>

                            {{-- TYPE --}}
                            <td class="text-center">
                                <span @class([
                                    'badge',
                                    'bg-success' => $announcement->type === 'promo',
                                    'bg-primary' => $announcement->type === 'update',
                                    'bg-danger' => $announcement->type === 'alert',
                                    'bg-secondary' => $announcement->type === 'info',
                                ])>
                                    {{ ucfirst($announcement->type) }}
                                </span>
                            </td>

                            {{-- STATUS --}}
                            <td class="text-center d-none d-lg-table-cell">
                                <span class="badge {{ $announcement->is_active ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $announcement->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>

                            {{-- DATE --}}
                            <td class="text-center d-none d-lg-table-cell">
                                <div class="fw-bold">
                                    {{ $announcement->created_at->format('M d, Y') }}
                                </div>
                                <small class="text-muted">
                                    {{ $announcement->created_at->format('h:i A') }}
                                </small>
                            </td>

                            {{-- ACTIONS --}}
                            <td class="text-end">
                                <div class="btn-group gap-2">

                                    <a href="{{ route('announcements.show', $announcement->id) }}"
                                        class="btn btn-sm btn-secondary">
                                        <i class="bi bi-eye"></i>
                                    </a>

                                    @if (in_array(auth()->user()?->role, ['admin', 'owner']))
                                        <a href="{{ route('announcements.edit', $announcement->id) }}"
                                            class="btn btn-sm btn-primary">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                    @endif

                                </div>
                            </td>

                        </tr>

                    @empty

                        {{-- EMPTY STATE --}}
                        <tr>
                            <td colspan="5" class="text-center py-5">

                                @if ($hasFilters)
                                    {{-- NO RESULTS --}}
                                    <i class="bi bi-search fs-1 text-muted"></i>
                                    <h5 class="mt-3">No results found</h5>
                                    <p class="text-muted mb-3">
                                        No announcements match your filters.
                                    </p>

                                    <a href="{{ route('announcements.index') }}" class="btn btn-outline-dark">
                                        <i class="bi bi-x-circle me-1"></i>
                                        Clear Filters
                                    </a>
                                @else
                                    {{-- NO DATA --}}
                                    <i class="bi bi-megaphone-x fs-1 text-muted"></i>
                                    <h5 class="mt-3">No announcements yet</h5>
                                    <p class="text-muted mb-0">
                                        Once announcements are created, they will appear here.
                                    </p>
                                @endif

                            </td>
                        </tr>
                    @endforelse

                </tbody>

            </table>
        </div>

        {{-- PAGINATION --}}
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
