@extends('layouts.user')

@section('page-title', 'Announcement')

@section('content')

<div class="container py-4">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h3 class="fw-bold mb-1">
                {{ $announcement->title }}
            </h3>

            <!-- Breadcrumb style line -->
            <p class="text-muted mb-0">
                <a href="{{ route('announcements.index') }}" class="text-decoration-none text-muted">
                    Announcements
                </a>
                <span class="mx-1">/</span>
                <span class="text-dark">Details</span>
            </p>

        </div>

        <a href="{{ route('announcements.index') }}" class="btn btn-outline-secondary rounded-pill">
            <i class="bi bi-arrow-left me-2"></i> Back
        </a>

    </div>

    <!-- Main Card -->
    <div class="card border-0 shadow-sm rounded-4">

        <div class="card-body p-4 p-md-5">

            <!-- TYPE + DATE -->
            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">

                @php
                    $badgeClass = match($announcement->type) {
                        'promo' => 'success',
                        'update' => 'primary',
                        'alert' => 'danger',
                        default => 'secondary'
                    };
                @endphp

                <span class="badge bg-{{ $badgeClass }} px-3 py-2">
                    {{ ucfirst($announcement->type) }}
                </span>

                <small class="text-muted">
                    {{ $announcement->created_at->format('F d, Y') }}
                </small>

            </div>

            <!-- TITLE -->
            <h2 class="fw-bold mb-3">
                {{ $announcement->title }}
            </h2>

            <!-- MESSAGE -->
            <div class="text-muted fs-6 lh-lg mb-4" style="white-space: pre-line;">
                {{ $announcement->message }}
            </div>

            <!-- LINK -->
            @if($announcement->link_url)
                <a href="{{ $announcement->link_url }}"
                   target="_blank"
                   class="btn btn-outline-primary rounded-pill px-4">
                    <i class="bi bi-box-arrow-up-right me-2"></i>
                    Visit Link
                </a>
            @endif

        </div>

    </div>

    <!-- Status bar -->
    <div class="text-center mt-3">
        @if($announcement->is_active)
            <span class="text-success small fw-bold">
                • Active Announcement
            </span>
        @else
            <span class="text-muted small">
                • Inactive
            </span>
        @endif
    </div>

</div>

@endsection