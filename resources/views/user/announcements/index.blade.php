@extends('layouts.user')

@section('page-title', 'Announcements')

@section('breadcrumb', true)

@section('page-header', true)
@section('page-header-title', 'Announcements')
@section('page-header-subtitle', 'Latest updates, promos, and important notices')

@section('page-header-actions')
    <a href="{{ route('announcements.index') }}" class="btn btn-outline-secondary px-4 shadow-sm">
        <i class="bi bi-arrow-repeat me-2"></i> Sync
    </a>
@endsection

@section('filter-area', true)
@section('filter-form')

    {{-- FILTER FORM --}}
    <form action="{{ route('announcements.index') }}" method="GET">

        <div class="row g-3">

            {{-- SEARCH FILTER --}}
            <div class="col-md-3">
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
                    <input type="date" name="date_from" class="form-control" value="{{ $filters['date_from'] ?? '' }}">
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
            <div class="col-md-3 d-flex gap-2">

                <button class="btn btn-dark w-100">
                    <i class="bi bi-funnel me-1"></i>
                    Filter
                </button>

                <a href="{{ route('announcements.index') }}" class="btn btn-outline-secondary w-100">
                    <i class="bi bi-x-circle me-1"></i>
                    Clear
                </a>

            </div>

        </div>

    </form>

@endsection

@section('content')

    <div class="container px-lg-5">

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

                    {{-- DATE BADGE --}}
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

        {{-- GRID --}}
        <div class="row d-flex justify-content-center g-4">

            @forelse ($announcements as $announcement)
                <div class="col-12 col-md-6 col-lg-4">

                    {{-- CLICKABLE CARD --}}
                    <a href="{{ route('announcements.show', $announcement->id) }}"
                        class="text-decoration-none text-reset d-block h-100">

                        <div class="card border-0 shadow-sm h-100 p-3" style="min-height: 280px;">

                            {{-- TYPE BADGE --}}
                            <div class="mb-2">

                                <span @class([
                                    'badge',
                                    'bg-success' => $announcement->type === 'promo',
                                    'bg-primary' => $announcement->type === 'update',
                                    'bg-danger' => $announcement->type === 'alert',
                                    'bg-secondary' => $announcement->type === 'info',
                                ])>
                                    {{ ucfirst($announcement->type) }}
                                </span>

                            </div>

                            {{-- IMAGE --}}
                            @if ($announcement->cover_image)
                                <img src="{{ asset('storage/' . $announcement->cover_image) }}"
                                    class="img-fluid rounded mb-3" style="height: 140px; object-fit: cover;">
                            @else
                                <div class="bg-light rounded mb-3 d-flex align-items-center justify-content-center"
                                    style="height: 140px;">
                                    <i class="bi bi-image fs-2 text-muted"></i>
                                </div>
                            @endif

                            {{-- TITLE --}}
                            <div class="fw-semibold mb-1">
                                {{ $announcement->title }}
                            </div>

                            {{-- MESSAGE --}}
                            <p class="text-muted mb-3">
                                {{ Str::limit($announcement->message, 100) }}
                            </p>

                            {{-- DATE --}}
                            <small class="text-muted">
                                {{ $announcement->created_at->format('M d, Y') }}
                            </small>

                        </div>

                    </a>

                </div>

            @empty

                {{-- EMPTY STATE --}}
                <div class="d-flex flex-column justify-content-center align-items-center">

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
                        <i class="bi bi-megaphone fs-1 text-muted"></i>
                        <h5 class="mt-3">No announcements yet</h5>
                        <p class="text-muted mb-0">
                            Once announcements are created, they will appear here.
                        </p>
                    @endif

                </div>
            @endforelse

        </div>

        {{-- PAGINATION --}}
        @if ($announcements->hasPages())
            <div class="card-footer bg-white shadow-sm p-3 mt-4">

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
