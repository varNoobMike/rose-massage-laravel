@extends('layouts.admin')

@section('page-title', 'Users')

@section('page-header', true)
@section('page-header-title-indexpage', 'Users')
@section('page-header-subtitle', 'Manage user accounts')

@section('page-header-actions')
    <a href="{{ route('users.index') }}" class="btn btn-outline-secondary px-4 shadow-sm">
        <i class="bi bi-arrow-repeat me-2"></i> Sync
    </a>
    @if (auth()->user()?->role !== 'receptionist')
        <a href="{{ route('users.create') }}" class="btn btn-primary px-4 shadow-sm ">
            <i class="bi bi-plus-lg me-2"></i> New
        </a>
    @endif
@endsection

@section('filter-area', true)
@section('filter-form')

    {{-- MOBILE TOGGLE BUTTON --}}
    <button class="btn btn-outline-dark d-md-none w-100 mb-3" type="button" data-bs-toggle="collapse"
        data-bs-target="#userFilterCollapse">
        <i class="bi bi-funnel me-1"></i> Show Filters
    </button>

    @php
        $role = request('role');
        $status = request('status', 'active');
    @endphp

    @php
        $hasFilters = request()->filled('search') || request()->filled('role') || request()->filled('status');
    @endphp

    <div class="collapse d-md-block" id="userFilterCollapse">

        <form action="{{ route('users.index') }}" method="GET">

            <div class="row g-2 align-items-end">

                <!-- SEARCH -->
                <div class="col-12 col-md-5">
                    <input type="text" name="search" class="form-control" placeholder="Search name, email, ID..."
                        value="{{ request('search') }}">
                </div>

                <!-- ROLE -->
                <div class="col-12 col-md-2">
                    <select name="role" class="form-select">

                        <option value="" {{ empty($role) ? 'selected' : '' }}>
                            All Roles
                        </option>

                        <option value="client" {{ $role == 'client' ? 'selected' : '' }}>
                            Client
                        </option>

                        <option value="therapist" {{ $role == 'therapist' ? 'selected' : '' }}>
                            Therapist
                        </option>

                        @if (auth()->user()->role !== 'receptionist')
                            <option value="receptionist" {{ $role == 'receptionist' ? 'selected' : '' }}>
                                Receptionist
                            </option>
                        @endif

                        @if (auth()->user()->role !== 'owner' && auth()->user()->role !== 'receptionist')
                            <option value="owner" {{ $role == 'owner' ? 'selected' : '' }}>
                                Owner
                            </option>
                        @endif

                    </select>
                </div>

                <!-- STATUS -->
                <div class="col-12 col-md-2">
                    <select name="status" class="form-select">

                        <option value="all" {{ $status == 'all' ? 'selected' : '' }}>
                            All Status
                        </option>

                        <option value="active" {{ $status == 'active' ? 'selected' : '' }}>
                            Active
                        </option>

                        <option value="inactive" {{ $status == 'inactive' ? 'selected' : '' }}>
                            Inactive
                        </option>

                    </select>
                </div>

                <!-- ACTIONS -->
                <div class="col-12 col-md-3 d-flex gap-2">

                    <button class="btn btn-dark w-100">
                        <i class="bi bi-funnel me-1"></i>
                        Filter
                    </button>

                    <a href="{{ route('users.index') }}" class="btn btn-outline-secondary w-100">
                        <i class="bi bi-x-circle me-1"></i>
                        Clear
                    </a>

                </div>

            </div>

        </form>

    </div>

@endsection

@section('content')

    @php
        $hasFilters = request()->filled('search') || request()->filled('role') || request()->filled('status');
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

                @if (request('role'))
                    <span class="badge bg-light text-dark text-capitalize">
                        Role: {{ request('role') }}
                    </span>
                @endif

                @if (request('status'))
                    <span class="badge bg-success text-capitalize">
                        Status: {{ request('status') }}
                    </span>
                @endif

            </div>


        </div>
    @endif

    <!-- Table -->
    <div class="card shadow-sm border">

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">

                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>User Details</th>
                        <th class="text-center d-none d-lg-table-cell">Role</th>
                        <th class="text-center d-none d-lg-table-cell">Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($users as $user)
                        <tr>

                            <!-- ID -->
                            <td class="fw-bold text-muted">
                                #{{ $user->id }}
                            </td>

                            <!-- USER DETAILS -->
                            <td>
                                <div class="d-flex align-items-center">

                                    @if ($user->profile?->avatar)
                                        <img src="{{ asset('storage/' . $user->profile?->avatar) }}"
                                            class="rounded-circle me-3 object-fit-cover" width="45" height="45">
                                    @else
                                        <div class="bg-light text-muted rounded-circle d-flex align-items-center justify-content-center me-3"
                                            style="width:45px;height:45px;">
                                            <i class="bi bi-person"></i>
                                        </div>
                                    @endif

                                    <div>
                                        <div class="fw-bold">
                                            {{ $user->name }}
                                        </div>

                                        <small class="text-muted">
                                            {{ $user->email }}
                                        </small>
                                    </div>

                                </div>
                            </td>

                            <!-- ROLE -->
                            <td class="text-center d-none d-lg-table-cell">
                                <span class="badge bg-light text-dark border text-capitalize">
                                    {{ $user->role }}
                                </span>
                            </td>

                            <!-- STATUS -->
                            <td class="text-center d-none d-lg-table-cell">
                                @if ($user->status === 'active')
                                    <span class="badge bg-success">
                                        Active
                                    </span>
                                @else
                                    <span class="badge bg-secondary">
                                        Inactive
                                    </span>
                                @endif
                            </td>

                            <!-- ACTIONS -->
                            <td class="text-end">
                                <div class="btn-group gap-2">

                                    <a href="{{ route('users.show', $user->id) }}" class="btn btn-sm btn-secondary">
                                        <i class="bi bi-eye"></i>
                                    </a>

                                    @if (auth()->user()->role !== 'receptionist')
                                        <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-primary">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                    @endif

                                </div>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">

                                @if ($hasFilters)
                                    {{-- EMPTY DUE TO FILTERS --}}
                                    <i class="bi bi-search fs-1 text-muted"></i>
                                    <h5 class="mt-3">No users found</h5>
                                    <p class="text-muted mb-3">
                                        No users match your filters.
                                    </p>

                                    <a href="{{ route('users.index') }}" class="btn btn-outline-dark">
                                        <i class="bi bi-x-circle me-1"></i>
                                        Clear Filters
                                    </a>
                                @else
                                    {{-- EMPTY DATABASE --}}
                                    <i class="bi bi-people fs-1 text-muted"></i>
                                    <h5 class="mt-3">No users yet</h5>
                                    <p class="text-muted mb-0">
                                        Once users are created, they will appear here.
                                    </p>
                                @endif

                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

        <!-- Pagination -->
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
