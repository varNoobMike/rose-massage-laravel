@extends('layouts.user')

@section('page-title', 'Services')

@section('breadcrumb', true)

@section('page-header', true)
@section('page-header-title', 'Our Services')
@section('page-header-subtitle', 'Browse our massage therapy offers')

@section('page-header-actions')
    <a href="{{ route('services.index') }}" class="btn btn-outline-secondary px-4 shadow-sm">
        <i class="bi bi-arrow-repeat me-2"></i> Sync
    </a>
@endsection

@section('filter-area', true)
@section('filter-form')

    {{-- MOBILE TOGGLE BUTTON --}}
    <button class="btn btn-outline-dark d-md-none w-100 mb-3" type="button" data-bs-toggle="collapse"
        data-bs-target="#filterCollapse">
        <i class="bi bi-funnel me-1"></i> Show Filters
    </button>

    <div class="collapse d-md-block" id="filterCollapse">

        <form action="{{ route('services.index') }}" method="GET">

            <div class="row g-2 align-items-end">

                {{-- SEARCH --}}
                <div class="col-12 col-md-3">
                    <input type="text" name="search" class="form-control" placeholder="Search service name..."
                        value="{{ request('search') }}">
                </div>

                {{-- PRICE RANGE --}}
                <div class="col-12 col-md-3">
                    <div class="input-group">
                        <span class="input-group-text">₱</span>
                        <input type="number" name="price_from" class="form-control" placeholder="Min"
                            value="{{ request('price_from') }}">

                        <span class="input-group-text">-</span>

                        <input type="number" name="price_to" class="form-control" placeholder="Max"
                            value="{{ request('price_to') }}">
                    </div>
                </div>

                {{-- DURATION RANGE --}}
                <div class="col-12 col-md-3">
                    <div class="input-group">
                        <input type="number" name="duration_from" class="form-control" placeholder="Min"
                            value="{{ request('duration_from') }}">

                        <span class="input-group-text">-</span>

                        <input type="number" name="duration_to" class="form-control" placeholder="Max"
                            value="{{ request('duration_to') }}">

                        <span class="input-group-text">mins</span>
                    </div>
                </div>

                {{-- ACTIONS --}}
                <div class="col-12 col-md-3 d-flex gap-2">

                    <button type="submit" class="btn btn-dark w-100">
                        <i class="bi bi-funnel me-1"></i>
                        Filter
                    </button>

                    <a href="{{ route('services.index') }}" class="btn btn-outline-secondary w-100">
                        <i class="bi bi-x-circle me-1"></i>
                        Clear
                    </a>

                </div>

            </div>

        </form>

    </div>

@endsection

@section('content')
    <div class="container px-lg-5">

        @php
            $hasFilters =
                request()->filled('search') ||
                request()->filled('status') ||
                request()->filled('price_from') ||
                request()->filled('price_to') ||
                request()->filled('duration_from') ||
                request()->filled('duration_to');
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

                    @if (request('status'))
                        <span class="badge bg-primary">
                            Status: {{ ucfirst(request('status')) }}
                        </span>
                    @endif

                    @if (request('price_from') || request('price_to'))
                        <span class="badge bg-success">
                            Price:
                            ₱{{ request('price_from') ?? '0' }}
                            →
                            ₱{{ request('price_to') ?? '∞' }}
                        </span>
                    @endif

                    @if (request('duration_from') || request('duration_to'))
                        <span class="badge bg-warning text-dark">
                            Duration:
                            {{ request('duration_from') ?? '0' }}
                            →
                            {{ request('duration_to') ?? '∞' }} mins
                        </span>
                    @endif

                </div>

            </div>
        @endif

        <!-- Card Grid -->
        <div class="row g-4 justify-content-center">

            @forelse($services as $service)
                <div class="col-12 col-md-6 col-lg-4">

                    <div class="card h-100 border-0 shadow-sm overflow-hidden">

                        <!-- IMAGE -->
                        <div class="position-relative">

                            <img src="{{ asset('storage/' . $service->image) }}" alt="{{ $service->name }}" class="w-100"
                                style="height: 210px; object-fit: cover;">

                            <!-- DURATION BADGE -->
                            <span class="badge bg-dark position-absolute top-0 end-0 m-3 px-3 py-2">
                                ⏱ {{ $service->duration_minutes }} mins
                            </span>

                        </div>

                        <!-- BODY -->
                        <div class="card-body p-4 text-center">

                            <!-- NAME -->
                            <h5 class="fw-semibold mb-2">
                                {{ $service->name }}
                            </h5>

                            <!-- PRICE -->
                            <div class="mb-3">
                                <span class="text-primary fw-bold fs-4">
                                    ₱{{ number_format($service->price, 2) }}
                                </span>

                                <small class="text-muted d-block">
                                    per session
                                </small>
                            </div>

                            <!-- CTA -->
                            <a href="{{ route('bookings.create', ['service' => $service->id]) }}"
                                class="btn btn-primary w-100 fw-semibold">
                                Book Now
                            </a>

                        </div>

                    </div>

                </div>

            @empty

                <div class="d-flex flex-column justify-content-center align-items-center">
                    @if ($hasFilters)
                        {{-- EMPTY DUE TO FILTERS --}}
                        <i class="bi bi-search fs-1 text-muted"></i>
                        <h5 class="mt-3">No results found</h5>
                        <p class="text-muted mb-3">
                            No services match your filters.
                        </p>

                        <a href="{{ route('services.index') }}" class="btn btn-outline-dark">
                            <i class="bi bi-x-circle me-1"></i>
                            Clear Filters
                        </a>
                    @else
                        {{-- EMPTY DATABASE --}}
                        <i class="bi bi-flower2 fs-1 text-muted"></i>
                        <h5 class="mt-3">No services yet</h5>
                        <p class="text-muted mb-0">
                           No services available yet.
                        </p>
                    @endif
                </div>
            @endforelse

        </div>

        <!-- Pagination -->
        @if ($services->hasPages())
            <div class="card-footer bg-white shadow-sm p-3 mt-4">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">

                    <small class="text-muted">
                        Showing {{ $services->firstItem() }}
                        to {{ $services->lastItem() }}
                        of {{ $services->total() }} services
                    </small>

                    {{ $services->appends(request()->query())->links() }}

                </div>
            </div>
        @endif
    </div>


@endsection
