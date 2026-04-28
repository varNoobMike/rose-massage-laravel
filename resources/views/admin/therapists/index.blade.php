@extends('layouts.admin')

@section('page-title', 'Therapists')

@section('page-header', true)
@section('page-header-title-indexpage', 'Therapists')
@section('page-header-subtitle', 'Manage therapist records')

@section('page-header-actions')
    <a href="{{ route('therapists.index') }}" class="btn btn-outline-secondary px-4 shadow-sm">
        <i class="bi bi-arrow-repeat me-2"></i> Sync
    </a>
    @if (auth()->user()?->role !== 'receptionist')
        <a href="{{ route('therapists.create') }}" class="btn btn-primary px-4 shadow-sm ">
            <i class="bi bi-plus-lg me-2"></i> New
        </a>
    @endif
@endsection

@section('filter-area', true)
@section('filter-form')
    <form action="{{ route('therapists.index') }}" method="GET">
        <div class="row g-3">

            @php
                $role = request('role');
                $status = request('status', 'active');
            @endphp

            <!-- Search -->
            <div class="col-md-5">
                <input type="text" name="search" class="form-control" placeholder="Search name, email, ID..."
                    value="{{ request('search') }}">
            </div>

            <!-- Status -->
            <div class="col-md-4">
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

            <!-- Buttons -->
            <div class="col-md-3 d-flex gap-2">
                <button class="btn btn-dark w-100">
                    Filter
                </button>

                <a href="{{ route('therapists.index') }}" class="btn btn-outline-secondary w-100">
                    Clear
                </a>
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
                        <th>ID</th>
                        <th>User Details</th>
                        <th class="text-center">Role</th>
                        <th class="text-center">Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($users as $user)
                        <tr>

                            <!-- ID -->
                            <td class="fw-bold text-muted">
                                #{{ str_pad($user->id, 5, '0', STR_PAD_LEFT) }}
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
                            <td class="text-center">
                                <span class="badge bg-light text-dark border text-capitalize">
                                    {{ $user->role }}
                                </span>
                            </td>

                            <!-- STATUS -->
                            <td class="text-center">
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

                                    <a href="{{ route('therapists.show', $user->id) }}" class="btn btn-sm btn-secondary">
                                        <i class="bi bi-eye"></i>
                                    </a>

                                    <a href="{{ route('therapists.edit', $user->id) }}" class="btn btn-sm btn-primary">
                                        <i class="bi bi-pencil"></i>
                                    </a>

                                </div>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">

                                <i class="bi bi-people fs-1 text-muted"></i>

                                <h5 class="mt-3">No users found</h5>

                                <p class="text-muted mb-0">
                                    Try adjusting your filters.
                                </p>

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
