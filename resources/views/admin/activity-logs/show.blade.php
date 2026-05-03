@extends('layouts.admin')

@section('page-title', 'Log #' . $log->id)

@section('breadcrumb-parent', 'Activity Logs')
@section('breadcrumb-parent-url', route('activity-logs.index'))

@section('page-header', true)
@section('page-header-title-showpage', 'Log #' . $log->id)
@section('page-header-subtitle', 'System activity details and audit trail')

@section('content')

@php
    $route = match(class_basename($log->subject_type)) {
        'Announcement' => route('announcements.show', $log->subject_id),
        'Review' => route('reviews.show', $log->subject_id),
        'Booking' => route('bookings.show', $log->subject_id),
        'Service' => route('services.show', $log->subject_id),
        'User' => route('users.show', $log->subject_id),
        default => null
    };

    $old = is_array($log->old_values)
        ? $log->old_values
        : json_decode($log->old_values ?? '[]', true);

    $new = is_array($log->new_values)
        ? $log->new_values
        : json_decode($log->new_values ?? '[]', true);
    $keys = array_unique(array_merge(array_keys($old), array_keys($new)));
@endphp

<div class="row g-4">

    {{-- LEFT SIDE --}}
    <div class="col-12 col-lg-8">

        {{-- MAIN INFO CARD --}}
        <div class="card shadow-sm border">
            <div class="card-header bg-white py-3 border-bottom">
                <div class="d-flex justify-content-between align-items-center">

                    <h6 class="mb-0 fw-bold text-uppercase small text-muted tracking-wider">
                        Activity Information
                    </h6>

                    <span class="badge bg-light text-primary border px-3 py-2">
                        ID: #{{ $log->id }}
                    </span>

                </div>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">

                    <table class="table table-borderless mb-0 align-middle">
                        <tbody>

                            {{-- USER --}}
                            <tr class="border-bottom border-light">
                                <td class="ps-4 py-4 text-muted small fw-bold text-uppercase" style="width:30%;">
                                    User
                                </td>

                                <td class="py-4 pe-4">
                                    <div class="d-flex align-items-center">

                                        @if ($log->user?->profile?->avatar)
                                            <img src="{{ asset('storage/' . $log->user->profile->avatar) }}"
                                                 class="rounded-circle me-3 object-fit-cover"
                                                 width="45" height="45">
                                        @else
                                            <div class="bg-light text-muted rounded-circle d-flex align-items-center justify-content-center me-3"
                                                 style="width:45px;height:45px;">
                                                <i class="bi bi-person"></i>
                                            </div>
                                        @endif

                                        <div>
                                            <div class="fw-bold">
                                                {{ $log->user->name ?? 'System' }}
                                            </div>
                                            <small class="text-muted text-uppercase">
                                                {{ $log->user->role ?? 'system' }}
                                            </small>
                                        </div>

                                    </div>
                                </td>
                            </tr>

                            {{-- ACTION --}}
                            <tr class="border-bottom border-light">
                                <td class="ps-4 py-4 text-muted small fw-bold text-uppercase">
                                    Action
                                </td>

                                <td class="py-4 pe-4">
                                    <span class="badge
                                        @if($log->action === 'created') bg-success
                                        @elseif($log->action === 'updated') bg-primary
                                        @else bg-danger
                                        @endif">

                                        {{ strtoupper($log->action) }}
                                    </span>
                                </td>
                            </tr>

                            {{-- SUBJECT --}}
                            <tr class="border-bottom border-light">
                                <td class="ps-4 py-4 text-muted small fw-bold text-uppercase">
                                    Subject
                                </td>

                                <td class="py-4 pe-4">
                                    <span class="fw-bold text-dark">
                                        {{ class_basename($log->subject_type) }}
                                    </span>
                                    <span class="text-muted">
                                        #{{ $log->subject_id }}
                                    </span>

                                    @if($route)
                                        <a href="{{ $route }}" class="btn btn-sm btn-outline-primary ms-3">
                                            <i class="bi bi-box-arrow-up-right me-1"></i>
                                            Show Subject
                                        </a>
                                    @endif
                                </td>
                            </tr>

                            {{-- MESSAGE --}}
                            <tr class="border-bottom border-light">
                                <td class="ps-4 py-4 text-muted small fw-bold text-uppercase">
                                    Message
                                </td>

                                <td class="py-4 pe-4">
                                    <div class="fw-medium text-dark">
                                        {{ $log->message }}
                                    </div>
                                </td>
                            </tr>

                            {{-- DATE --}}
                            <tr>
                                <td class="ps-4 py-4 text-muted small fw-bold text-uppercase">
                                    System Logs
                                </td>

                                <td class="py-4 pe-4 text-muted small">

                                    <div class="mb-1">
                                        <i class="bi bi-calendar-check me-2 opacity-50"></i>
                                        Created:
                                        <strong>{{ $log->created_at->format('M d, Y') }}</strong>
                                    </div>

                                    <div>
                                        <i class="bi bi-clock-history me-2 opacity-50"></i>
                                        Time:
                                        <strong>{{ $log->created_at->format('h:i A') }}</strong>
                                    </div>

                                </td>
                            </tr>

                        </tbody>
                    </table>

                </div>
            </div>
        </div>

        {{-- DATA CHANGES (OLD vs NEW) --}}
        @if(count($keys))
        <div class="card shadow-sm border mt-4">

            <div class="card-header bg-white py-3 border-bottom">
                <h6 class="mb-0 fw-bold text-uppercase small text-muted tracking-wider">
                    Data Changes
                </h6>
            </div>

            <div class="card-body p-0">

                <div class="table-responsive">
                    <table class="table table-bordered mb-0 align-middle">

                        <thead class="table-light">
                            <tr>
                                <th style="width:50%">Old Values</th>
                                <th style="width:50%">New Values</th>
                            </tr>
                        </thead>

                        <tbody>

                            @foreach ($keys as $key)
                                <tr>

                                    {{-- OLD --}}
                                    <td class="text-muted small">
                                        <strong>{{ $key }}</strong><br>
                                        @if(isset($old[$key]))
                                            {{ is_array($old[$key]) ? json_encode($old[$key]) : $old[$key] }}
                                        @else
                                            <span class="fst-italic">—</span>
                                        @endif
                                    </td>

                                    {{-- NEW --}}
                                    <td class="small">
                                        <strong>{{ $key }}</strong><br>
                                        @if(isset($new[$key]))
                                            {{ is_array($new[$key]) ? json_encode($new[$key]) : $new[$key] }}
                                        @else
                                            <span class="fst-italic">—</span>
                                        @endif
                                    </td>

                                </tr>
                            @endforeach

                        </tbody>

                    </table>
                </div>

            </div>
        </div>
        @endif

    </div>

    {{-- RIGHT SIDE --}}
    <div class="col-12 col-lg-4">

        {{-- ACTIVITY TYPE --}}
        <div class="card shadow-sm border text-center">
            <div class="card-body p-4">

                <small class="text-uppercase text-muted fw-bold mb-3 d-block tracking-wider">
                    Activity Type
                </small>

                <div class="bg-light border rounded-pill py-2 px-4 d-inline-flex align-items-center mb-2">

                    <i class="bi bi-activity me-2 text-primary"></i>

                    <span class="fw-bold text-uppercase">
                        {{ $log->action }}
                    </span>

                </div>

                <p class="text-muted small mb-0">
                    System audit trail record
                </p>

            </div>
        </div>

    </div>

</div>

@endsection