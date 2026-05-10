@extends('layouts.user')

@section('page-title', 'Announcements Details')

@section('breadcrumb', true)
@section('breadcrumb-parent', 'Announcements')
@section('breadcrumb-parent-url', route('announcements.index'))

@section('page-header', true)
@section('page-header-title', 'Announcement Details')
@section('page-header-subtitle', 'View details of this announcement')

@section('content')
    <div class="container px-lg-5">

        <div class="col-12 col-lg-8">

            <div class="card border-0 shadow-sm p-4">

                {{-- TYPE BADGE --}}
                <div class="mb-3">
                    <span @class([
                        'badge',
                        'bg-success' => $announcement->type === 'promo',
                        'bg-primary' => $announcement->type === 'update',
                        'bg-danger' => $announcement->type === 'alert',
                        'bg-secondary' => $announcement->type === 'info',
                    ])>
                        {{ ucfirst($announcement->type) }}
                    </span>
                </div>

                {{-- TITLE --}}
                <h4 class="fw-bold mb-2">
                    {{ $announcement->title }}
                </h4>

                {{-- DATE --}}
                <small class="text-muted d-block mb-3">
                    {{ $announcement->created_at->format('M d, Y • h:i A') }}
                </small>

                {{-- COVER IMAGE --}}
                @if ($announcement->cover_image)
                    <img src="{{ asset('storage/' . $announcement->cover_image) }}" class="img-fluid rounded mb-4"
                        style="max-height: 300px; object-fit: cover; width:100%;">
                @endif

                {{-- MESSAGE --}}
                <div class="mb-4 text-muted" style="line-height:1.6;">
                    {{ $announcement->message }}
                </div>

                {{-- CTA LINK --}}
                @if ($announcement->link_page)
                    <div class="mt-3">

                        <a href="{{ route($announcement->link_page . '.index') }}" class="btn btn-primary">

                            <i class="bi bi-arrow-right me-2"></i>
                            Visit {{ ucfirst($announcement->link_page) }}

                        </a>

                    </div>
                @endif

            </div>

        </div>

    </div>
@endsection
