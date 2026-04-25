@extends('layouts.admin')

@section('page-title', 'Services')

@section('content')
<div class="container-fluid">

    <!-- Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <h3 class="fw-bold mb-1">Services</h3>
            <p class="text-muted mb-0">
                Review and manage treatment pricing, duration, and availability.
            </p>
        </div>

        <div class="d-flex gap-2">

            <!-- SYNC BUTTON -->
            <a href="{{ route('services.index') }}"
                class="btn btn-outline-primary rounded-pill px-4"
                title="Sync all bookings">
                <i class="bi bi-arrow-repeat me-2"></i>
                Sync
            </a>

            <!-- NEW BUTTON -->
            <a href="{{ route('services.create') }}"
            class="btn btn-primary rounded-pill px-4">
                <i class="bi bi-plus-lg me-2"></i>
                New
            </a>

        </div>

    </div>

    <!-- Filters -->
    <div class="card shadow-sm border-0 mb-4 rounded">
        <div class="card-body">

            <form action="{{ route('services.index') }}" method="GET">
                <div class="row g-3">

                    <div class="col-md-6">
                        <input type="text"
                               name="search"
                               class="form-control rounded"
                               placeholder="Search service name, id..."
                               value="{{ request('search') }}">
                    </div>

                    <div class="col-md-3">
                        @php
                            $status = request('status', 'active');
                        @endphp

                        <select name="status" class="form-select rounded">

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

                    <div class="col-md-3 d-flex gap-2">
                        <button class="btn btn-dark w-100 rounded">
                            Filter
                        </button>

                        <a href="{{ route('services.index') }}"
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
                        <th>Service</th>
                        <th class="text-center">Duration</th>
                        <th class="text-center">Price</th>
                        <th class="text-center">Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($services as $service)
                        <tr>

                            <!-- SERVICE -->
                            <td>
                                <div class="d-flex align-items-center">

                                    @if($service->image)
                                        <img
                                            src="{{ asset('storage/' . $service->image) }}"
                                            alt="{{ $service->name }}"
                                            class="rounded me-3 object-fit-cover"
                                            width="50"
                                            height="50"
                                        >
                                    @else
                                        <div class="bg-light text-muted rounded d-flex align-items-center justify-content-center me-3"
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

                            <!-- DURATION -->
                            <td class="text-center">
                                <span class="badge bg-light text-dark">
                                    {{ $service->duration_minutes }} mins
                                </span>
                            </td>

                            <!-- PRICE -->
                            <td class="text-center fw-bold">
                                ₱{{ number_format($service->price, 2) }}
                            </td>

                            <!-- STATUS -->
                            <td class="text-center">
                                @if($service->status === 'active')
                                    <span class="badge bg-success rounded">
                                        Active
                                    </span>
                                @else
                                    <span class="badge bg-secondary rounded">
                                        Inactive
                                    </span>
                                @endif
                            </td>

                            <!-- ACTIONS -->
                            <td class="text-end">
                                <div class="btn-group gap-2">

                                    <a href="{{ route('services.show', $service->id) }}"
                                       class="btn btn-sm btn-outline-secondary rounded">
                                        <i class="bi bi-eye"></i>
                                    </a>

                                    <a href="{{ route('services.edit', $service->id) }}"
                                       class="btn btn-sm btn-outline-primary rounded">
                                        <i class="bi bi-pencil"></i>
                                    </a>

                                </div>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">

                                <i class="bi bi-search fs-1 text-muted"></i>

                                <h5 class="mt-3">No services found</h5>

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
        @if($services->hasPages())
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
</div>
@endsection