@extends('layouts.admin')

@section('page-title', 'Clients')

@section('page-header', true)
@section('page-header-title-indexpage', 'Clients')
@section('page-header-subtitle', 'manage client accounts')

@section('page-header-actions')
    <a href="{{ route('clients.index') }}" class="btn btn-outline-secondary px-4 shadow-sm">
        <i class="bi bi-arrow-repeat me-2"></i> Sync
    </a>
@endsection

@section('filter-area', true)
@section('filter-form')

    <button class="btn btn-outline-dark d-md-none w-100 mb-3" type="button" data-bs-toggle="collapse"
        data-bs-target="#userFilterCollapse">
        <i class="bi bi-funnel me-1"></i> Show Filters
    </button>

    <div class="collapse d-md-block" id="userFilterCollapse">

        <form action="{{ route('clients.index') }}" method="GET">

            <div class="row g-2 align-items-end">

                {{-- search --}}
                <div class="col-12 col-md-5">
                    <input type="text" name="search" class="form-control" placeholder="Search by name, email..."
                        value="{{ $filters['search'] ?? '' }}">
                </div>

                {{-- status --}}
                <div class="col-12 col-md-4">
                    <select name="status" class="form-select">

                        <option value="" @selected(empty($filters['status']))>
                            All status
                        </option>

                        <option value="active" @selected(($filters['status'] ?? null) === 'active')>
                            Active
                        </option>

                        <option value="inactive" @selected(($filters['status'] ?? null) === 'inactive')>
                            Inactive
                        </option>

                    </select>
                </div>

                {{-- actions --}}
                <div class="col-12 col-md-3 d-flex gap-2">

                    <button class="btn btn-dark w-100">
                        <i class="bi bi-funnel me-1"></i>
                        Filter
                    </button>

                    <a href="{{ route('clients.index') }}" class="btn btn-outline-secondary w-100">
                        <i class="bi bi-x-circle me-1"></i>
                        Clear
                    </a>

                </div>

            </div>

        </form>

    </div>

@endsection


@section('content')

    @if ($hasFilters)
        <div class="alert alert-info d-flex justify-content-between align-items-center py-2 px-3 mb-3">

            <div class="d-flex flex-wrap gap-2 align-items-center">

                <strong class="me-2">
                    <i class="bi bi-funnel-fill"></i> Filters applied:
                </strong>

                @if (!empty($filters['search']))
                    <span class="badge bg-dark">
                        Search: {{ $filters['search'] }}
                    </span>
                @endif

                @if (!empty($filters['status']))
                    <span @class([
                        'badge text-capitalize',
                        'bg-success' => $filters['status'] === 'active',
                        'bg-secondary' => $filters['status'] === 'inactive',
                    ])>
                        Status: {{ $filters['status'] }}
                    </span>
                @endif

            </div>

        </div>
    @endif


    <div class="card shadow-sm border">

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">

                <thead class="table-light">
                    <tr>
                        <th>User</th>
                        <th class="text-center d-none d-lg-table-cell">Role</th>
                        <th class="text-center d-none d-lg-table-cell">Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($users as $user)
                        <tr>

                            <td>
                                <div class="d-flex align-items-center">

                                    @if ($user->profile?->avatar)
                                        <img src="{{ asset('storage/' . $user->profile->avatar) }}"
                                            class="rounded-circle me-3 object-fit-cover" width="45" height="45">
                                    @else
                                        <div class="bg-light text-muted rounded-circle d-flex align-items-center justify-content-center me-3"
                                            style="width:45px;height:45px;">
                                            <i class="bi bi-person"></i>
                                        </div>
                                    @endif

                                    <div>
                                        <div class="fw-bold">{{ $user->name }}</div>

                                        <small class="text-muted">
                                            {{ $user->email }}
                                        </small>

                                        <div class="text-muted small">
                                            ID: #{{ $user->id }}
                                        </div>
                                    </div>

                                </div>
                            </td>

                            <td class="text-center d-none d-lg-table-cell">
                                <span class="badge bg-light text-dark border text-capitalize">
                                    {{ $user->role }}
                                </span>
                            </td>

                            <td class="text-center d-none d-lg-table-cell">
                                <span @class([
                                    'badge',
                                    'bg-success' => $user->status === 'active',
                                    'bg-secondary' => $user->status !== 'active',
                                ])>
                                    {{ ucfirst($user->status) }}
                                </span>
                            </td>

                            <td class="text-end">
                                <div class="btn-group gap-2">

                                    <a href="{{ route('clients.show', $user->id) }}" class="btn btn-sm btn-secondary">
                                        <i class="bi bi-eye"></i>
                                    </a>

                                </div>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-5">

                                @if ($hasFilters)
                                    <i class="bi bi-search fs-1 text-muted"></i>
                                    <h5 class="mt-3">No clients found</h5>
                                    <p class="text-muted mb-3">No clients match your filters.</p>

                                    <a href="{{ route('clients.index') }}" class="btn btn-outline-dark">
                                        Clear filters
                                    </a>
                                @else
                                    <i class="bi bi-people fs-1 text-muted"></i>
                                    <h5 class="mt-3">No clients yet</h5>
                                    <p class="text-muted mb-0">Clients will appear here once created.</p>
                                @endif

                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

        {{-- PAGINATION --}}
        @if ($users->hasPages())
            <div class="card-footer bg-white">

                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">

                    <small class="text-muted">
                        Showing {{ $users->firstItem() }}
                        to {{ $users->lastItem() }}
                        of {{ $users->total() }} users
                    </small>

                    {{ $users->appends(request()->query())->links() }}

                </div>

            </div>
        @endif

    </div>
@endsection
