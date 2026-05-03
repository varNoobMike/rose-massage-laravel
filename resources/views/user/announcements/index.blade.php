@extends('layouts.user')

@section('page-title', 'Announcements')

@section('breadcrumb', true)

@section('page-header', true)
@section('page-header-title', 'Announcements')
@section('page-header-subtitle', 'Latest updates, promos, and important notices')

@section('filter-area', true)
@section('filter-form')
<form action="{{ route('announcements.index') }}" method="GET">
    <div class="row g-3">

        <div class="col-md-9">
            <input type="text" name="search"
                class="form-control"
                placeholder="Search announcements..."
                value="{{ request('search') }}">
        </div>

        <div class="col-md-3 d-flex gap-2">
            <button class="btn btn-dark w-100">
                <i class="bi bi-funnel me-1"></i>
                Filter
            </button>

            <a href="{{ route('announcements.index') }}"
               class="btn btn-outline-secondary w-100">
               <i class="bi bi-x-circle me-1"></i>
                Clear
            </a>
        </div>

    </div>
</form>
@endsection

@section('content')
<div class="container px-lg-5">

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
                                $typeClass = match($announcement->type) {
                                    'success' => 'bg-success',
                                    'warning' => 'bg-warning text-dark',
                                    'danger'  => 'bg-danger',
                                    'promo'   => 'bg-primary',
                                    default   => 'bg-secondary',
                                };
                            @endphp

                            <span class="badge {{ $typeClass }}">
                                {{ ucfirst($announcement->type) }}
                            </span>
                        </div>

                        {{-- IMAGE --}}
                        @if ($announcement->cover_image)
                            <img src="{{ asset('storage/' . $announcement->cover_image) }}"
                                class="img-fluid rounded mb-3"
                                style="height: 140px; object-fit: cover;">
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
            <div class="col-12 text-center py-5">
                <p class="text-muted fst-italic mb-0">
                    No announcements available
                </p>
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