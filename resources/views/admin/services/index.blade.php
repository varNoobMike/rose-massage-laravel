@extends('layouts.admin')

@section('page-title', 'Services')

@section('page-header', true)
@section('page-header-title-indexpage', 'Services')
@section('page-header-subtitle', 'Manage spa service menus')

@section('page-header-actions')
    <a href="{{ route('services.index') }}" class="btn btn-outline-secondary px-4 shadow-sm">
        <i class="bi bi-arrow-repeat me-2"></i> Sync
    </a>

    @if (in_array(auth()->user()?->role, ['admin', 'owner']))
        <a href="{{ route('services.create') }}" class="btn btn-primary px-4 shadow-sm">
            <i class="bi bi-plus-lg me-2"></i> New
        </a>
    @endif
@endsection

@section('filter-area', true)
@section('filter-form')

    {{-- MOBILE FILTER TOGGLE --}}
    <button class="btn btn-outline-dark d-md-none w-100 mb-3" type="button" data-bs-toggle="collapse"
        data-bs-target="#filterCollapse">
        <i class="bi bi-funnel me-1"></i> Show Filters
    </button>

    <div class="collapse d-md-block" id="filterCollapse">

        <form action="{{ route('services.index') }}" method="GET">

            <div class="row g-2 align-items-end">

                {{-- SEARCH FILTER --}}
                <div class="col-12 col-md-3">
                    <input type="text" name="search" class="form-control" placeholder="Search by service ID, name..."
                        value="{{ $filters['search'] ?? '' }}">
                </div>

                {{-- PRICE RANGE FILTER --}}
                <div class="col-12 col-md-3">
                    <div class="input-group">
                        <span class="input-group-text">₱</span>

                        <input type="number" name="price_from" class="form-control" placeholder="Min"
                            value="{{ $filters['price_from'] ?? '' }}">

                        <span class="input-group-text">-</span>

                        <input type="number" name="price_to" class="form-control" placeholder="Max"
                            value="{{ $filters['price_to'] ?? '' }}">
                    </div>
                </div>

                {{-- DURATION RANGE FILTER --}}
                <div class="col-12 col-md-3">
                    <div class="input-group">

                        <input type="number" name="duration_from" class="form-control" placeholder="Min"
                            value="{{ $filters['duration_from'] ?? '' }}">

                        <span class="input-group-text">-</span>

                        <input type="number" name="duration_to" class="form-control" placeholder="Max"
                            value="{{ $filters['duration_to'] ?? '' }}">

                        <span class="input-group-text">mins</span>
                    </div>
                </div>

                {{-- ACTION BUTTONS --}}
                <div class="col-12 col-md-3 d-flex gap-2">

                    <button class="btn btn-dark w-100">
                        <i class="bi bi-funnel me-1"></i>
                        Filter
                    </button>

                    <a href="{{ route('services.index') }}" class="btn btn-outline-secondary w-100">
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

                        <option value="active" @selected(($filters['status'] ?? null) === 'active')>
                            Active
                        </option>

                        <option value="inactive" @selected(($filters['status'] ?? null) === 'inactive')>
                            Inactive
                        </option>

                    </select>

                </div>

            </div>

        </form>

    </div>

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

                {{-- STATUS BADGE --}}
                @if (!empty($filters['status']))
                    <span @class([
                        'badge text-capitalize',
                        'bg-success' => $filters['status'] === 'active',
                        'bg-secondary' => $filters['status'] === 'inactive',
                    ])>
                        Status: {{ ucfirst($filters['status']) }}
                    </span>
                @endif

                {{-- PRICE BADGE --}}
                @if (!empty($filters['price_from']) || !empty($filters['price_to']))
                    <span class="badge bg-success">
                        Price:
                        ₱{{ $filters['price_from'] ?? '0' }}
                        →
                        ₱{{ $filters['price_to'] ?? '∞' }}
                    </span>
                @endif

                {{-- DURATION BADGE --}}
                @if (!empty($filters['duration_from']) || !empty($filters['duration_to']))
                    <span class="badge bg-warning text-dark">
                        Duration:
                        {{ $filters['duration_from'] ?? '0' }}
                        →
                        {{ $filters['duration_to'] ?? '∞' }} mins
                    </span>
                @endif

            </div>

        </div>
    @endif

    {{-- SERVICES TABLE --}}
    <div class="card shadow-sm border">

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">

                <thead class="table-light">
                    <tr>
                        <th>Service</th>
                        <th class="text-center d-none d-lg-table-cell">Duration</th>
                        <th>Price</th>
                        <th class="text-center d-none d-lg-table-cell">Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($services as $service)
                        <tr>

                            {{-- SERVICE INFO --}}
                            <td>
                                <div class="d-flex align-items-center">

                                    @if ($service->image)
                                        <img src="{{ asset('storage/' . $service->image) }}" alt="{{ $service->name }}"
                                            class="me-3 object-fit-cover rounded" width="50" height="50">
                                    @else
                                        <div class="bg-light text-muted d-flex align-items-center justify-content-center me-3 rounded"
                                            style="width:50px; height:50px;">
                                            <i class="bi bi-image"></i>
                                        </div>
                                    @endif

                                    <div>
                                        <div class="fw-bold">
                                            {{ $service->name }}
                                        </div>

                                        <small class="text-muted">
                                            ID #{{ $service->id }}
                                        </small>
                                    </div>

                                </div>
                            </td>

                            {{-- DURATION --}}
                            <td class="text-center d-none d-lg-table-cell">
                                <span class="badge bg-light text-dark">
                                    {{ $service->duration_minutes }} mins
                                </span>
                            </td>

                            {{-- PRICE --}}
                            <td class="fw-bold">
                                ₱{{ number_format($service->price, 2) }}
                            </td>

                            {{-- STATUS --}}
                            <td class="text-center d-none d-lg-table-cell">
                                <span class="badge {{ $service->status === 'active' ? 'bg-success' : 'bg-secondary' }}">
                                    {{ ucfirst($service->status) }}
                                </span>
                            </td>

                            {{-- ACTIONS --}}
                            <td class="text-end">
                                <div class="btn-group gap-2">

                                    <a href="{{ route('services.show', $service->id) }}" class="btn btn-sm btn-secondary">
                                        <i class="bi bi-eye"></i>
                                    </a>

                                    @if (in_array(auth()->user()?->role, ['admin', 'owner']))
                                        <a href="{{ route('services.edit', $service->id) }}"
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
                                        No services match your filters.
                                    </p>

                                    <a href="{{ route('services.index') }}" class="btn btn-outline-dark">
                                        <i class="bi bi-x-circle me-1"></i>
                                        Clear Filters
                                    </a>
                                @else
                                    {{-- NO DATA --}}
                                    <i class="bi bi-flower2 fs-1 text-muted"></i>
                                    <h5 class="mt-3">No services yet</h5>
                                    <p class="text-muted mb-0">
                                        Once services are created, they will appear here.
                                    </p>
                                @endif

                            </td>
                        </tr>
                    @endforelse

                </tbody>

            </table>
        </div>

        {{-- PAGINATION --}}
        @if ($services->hasPages())
            <div class="card-footer bg-white">

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
