@extends('layouts.user')

@section('page-title', 'Services')

@section('breadcrumb', true)

@section('page-header', true)
@section('page-header-title-indexpage', 'Our Services')
@section('page-header-subtitle', 'Browse our massage therapy offers')

@section('filter-area', true)
@section('filter-form')
    <form action="{{ route('services.index') }}" method="GET">
        <div class="row g-3">

            <div class="col-md-9">
                <input type="text" name="search" class="form-control" placeholder="Search service name, id..."
                    value="{{ request('search') }}">
            </div>

            <div class="col-md-3 d-flex gap-2">
                <button class="btn btn-dark w-100">
                    Filter
                </button>

                <a href="{{ route('services.index') }}" class="btn btn-outline-secondary w-100">
                    Clear
                </a>
            </div>

        </div>
    </form>
@endsection

@section('content')
    <div class="container px-lg-5">
        <!-- Card Grid -->
        <div class="row g-4 justify-content-center">

            @forelse($services as $service)
                <div class="col-12 col-md-6 col-lg-4">

                    <div class="card h-100 border-0 shadow-sm overflow-hidden">

                        <!-- IMAGE -->
                        <img src="{{ asset('storage/' . $service->image) }}" alt="{{ $service->name }}" class="w-100"
                            style="height: 180px; object-fit: cover;">

                        <!-- BODY -->
                        <div class="card-body p-4 text-center">

                            <h5 class="fw-semibold mb-2">
                                {{ $service->name }}
                            </h5>

                            <p class="text-primary fw-bold fs-5 mb-3">
                                ₱{{ number_format($service->price, 2) }}
                            </p>

                            <a href="{{ route('bookings.create', ['service' => $service->id]) }}"
                                class="btn btn-outline-primary w-100">
                                Book Now
                            </a>

                        </div>

                    </div>

                </div>

            @empty

                <div class="col-12 text-center py-5">
                    <p class="text-muted mb-0">No services available yet</p>
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
