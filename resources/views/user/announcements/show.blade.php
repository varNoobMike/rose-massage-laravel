@extends('layouts.user')

@section('page-title', 'Announcement')

@section('breadcrumb', true)

@section('page-header', true)
@section('page-header-title', 'Announcement')
@section('page-header-subtitle', 'Details and full information')

@section('content')
<div class="container px-lg-5">

        <div class="col-12 col-lg-8">

            <div class="card border-0 shadow-sm p-4">

                {{-- TYPE BADGE --}}
                <div class="mb-3">
                    @php
                        $typeClass = match($announcement->type) {
                            'promo' => 'bg-primary',
                            'update' => 'bg-info text-dark',
                            'alert' => 'bg-danger',
                            'info' => 'bg-secondary',
                            default => 'bg-secondary',
                        };
                    @endphp

                    <span class="badge {{ $typeClass }}">
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
                    <img src="{{ asset('storage/' . $announcement->cover_image) }}"
                         class="img-fluid rounded mb-4"
                         style="max-height: 300px; object-fit: cover; width:100%;">
                @endif

                {{-- MESSAGE --}}
                <div class="mb-4 text-muted" style="line-height:1.6;">
                    {{ $announcement->message }}
                </div>

                {{-- CTA LINK --}}
                @if ($announcement->link_page)
                    <div class="mt-3">

                        <a href="{{ route($announcement->link_page . '.index') }}"
                           class="btn btn-primary">

                            <i class="bi bi-arrow-right me-2"></i>
                            Visit {{ ucfirst($announcement->link_page) }}

                        </a>

                    </div>
                @endif

            </div>

        </div>

</div>
@endsection