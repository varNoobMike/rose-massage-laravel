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
    <form action="{{ route('announcements.index') }}" method="GET">
        <div class="row g-3">

            <div class="col-md-3">
                <input type="text" name="search" class="form-control" placeholder="Search announcement title, message..."
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

        @php
            $hasFilters =
                request()->filled('search') ||
                request()->filled('type') ||
                request()->filled('status') ||
                request()->filled('from') ||
                request()->filled('to');
        @endphp

        @if ($hasFilters)
            <div class="alert alert-info d-flex justify-content-between align-items-center py-2 px-3 mb-3">

                <div class="d-flex flex-wrap gap-2 align-items-center">

                    <strong class="me-2">
                        <i class="bi bi-funnel-fill"></i> Filters applied:
                    </strong>

                    {{-- SEARCH --}}
                    @if (request('search'))
                        <span class="badge bg-dark">
                            Search: {{ request('search') }}
                        </span>
                    @endif

                    {{-- TYPE --}}
                    @if (request('type'))
                        <span @class([
                            'badge',
                            'text-capitalize',
                            'bg-success' => request('type') === 'promo',
                            'bg-primary' => request('type') === 'update',
                            'bg-danger' => request('type') === 'alert',
                            'bg-secondary' => request('type') === 'info',
                            'bg-dark' => !in_array(request('type'), [
                                'promo',
                                'update',
                                'alert',
                                'info',
                            ]),
                        ])>
                            Type: {{ request('type') }}
                        </span>
                    @endif

                    {{-- DATE RANGE --}}
                    @if (request('from') || request('to'))
                        <span class="badge bg-secondary">
                            Date:
                            {{ request('from') ?? '...' }}
                            →
                            {{ request('to') ?? '...' }}
                        </span>
                    @endif

                </div>

            </div>
        @endif

        <div class="row d-flex justify-content-center g-4">

            @forelse ($announcements as $announcement)
                <div class="col-12 col-md-6 col-lg-4">

                    {{-- CLICKABLE CARD --}}
                    <a href="{{ route('announcements.show', $announcement->id) }}"
                        class="text-decoration-none text-reset d-block h-100">

                        <div class="card border-0 shadow-sm h-100 p-3" style="min-height: 280px;">

                            {{-- TYPE BADGE --}}
                            <div class="mb-2">
                                @php
                                    $typeClass = match ($announcement->type) {
                                        'success' => 'bg-success',
                                        'warning' => 'bg-warning text-dark',
                                        'danger' => 'bg-danger',
                                        'promo' => 'bg-primary',
                                        default => 'bg-secondary',
                                    };
                                @endphp

                                <span class="badge {{ $typeClass }}">
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

                            {{-- CTA --}}
                            @if ($announcement->link)
                                <div class="mt-auto pt-2 d-flex align-items-center text-primary fw-semibold small">
                                    <span>View details</span>
                                    <i class="bi bi-arrow-right ms-1"></i>
                                </div>
                            @endif

                        </div>

                    </a>
                </div>

            @empty
                <div class="d-flex flex-column justify-content-center align-items-center">
                    @if ($hasFilters)
                        {{-- EMPTY DUE TO FILTERS --}}
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
                        {{-- EMPTY DATABASE --}}
                        <i class="bi bi-megaphone fs-1 text-muted"></i>
                        <h5 class="mt-3">No services yet</h5>
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
