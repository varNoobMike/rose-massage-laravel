@extends('layouts.admin')

@section('page-title', 'Activity Logs')

@section('page-header', true)
@section('page-header-title-indexpage', 'Activity Logs')
@section('page-header-subtitle', 'System audit trail and user actions')

@section('page-header-actions')
    <a href="{{ route('activity-logs.index') }}" class="btn btn-outline-secondary px-4 shadow-sm">
        <i class="bi bi-arrow-repeat me-2"></i> Sync
    </a>
@endsection

@section('filter-area', true)
@section('filter-form')

<form action="{{ route('activity-logs.index') }}" method="GET">

    {{-- MOBILE TOGGLE --}}
    <button class="btn btn-outline-dark d-md-none w-100 mb-3"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#activityLogFilter">
        <i class="bi bi-funnel me-1"></i>
        Show Filters
    </button>

    <div class="collapse d-md-block" id="activityLogFilter">

        <div class="row g-3 align-items-end">

            {{-- SEARCH --}}
            <div class="col-12 col-md-5">
                <input type="text"
                       name="search"
                       class="form-control"
                       placeholder="Search logs, messages, users..."
                       value="{{ request('search') }}">
            </div>

            {{-- ACTION --}}
            <div class="col-12 col-md-3">
                <select name="action" class="form-select">
                    <option value="" @selected(request('action') == '')>All Actions</option>
                    <option value="created" @selected(request('action') == 'created')>Created</option>
                    <option value="updated" @selected(request('action') == 'updated')>Updated</option>
                    <option value="deleted" @selected(request('action') == 'deleted')>Deleted</option>
                </select>
            </div>

            {{-- USER ID --}}
            <div class="col-12 col-md-2">
                <input type="number"
                       name="user_id"
                       class="form-control"
                       placeholder="User ID"
                       value="{{ request('user_id') }}">
            </div>

            {{-- SUBJECT TYPE --}}
            <div class="col-12 col-md-2">
                <select name="subject_type" class="form-select">
                    <option value="" @selected(request('subject_type') == '')>All Subjects</option>
                    <option value="App\Models\Booking" @selected(request('subject_type') == 'App\Models\Booking')>Booking</option>
                    <option value="App\Models\User" @selected(request('subject_type') == 'App\Models\User')>User</option>
                    <option value="App\Models\Review" @selected(request('subject_type') == 'App\Models\Review')>Review</option>
                    <option value="App\Models\Service" @selected(request('subject_type') == 'App\Models\Service')>Service</option>
                    <option value="App\Models\Announcement" @selected(request('subject_type') == 'App\Models\Announcement')>Announcement</option>
                </select>
            </div>

            {{-- DATE FROM --}}
            <div class="col-12 col-md-2">
                <input type="date"
                       name="from"
                       class="form-control"
                       value="{{ request('from') }}">
            </div>

            {{-- DATE TO --}}
            <div class="col-12 col-md-2">
                <input type="date"
                       name="to"
                       class="form-control"
                       value="{{ request('to') }}">
            </div>

            {{-- ACTIONS --}}
            <div class="col-12 col-md-3 d-flex gap-2">

                <button class="btn btn-dark w-100">
                    <i class="bi bi-funnel me-1"></i>
                    Apply
                </button>

                <a href="{{ route('activity-logs.index') }}"
                   class="btn btn-outline-secondary w-100">
                    <i class="bi bi-x-circle me-1"></i>
                    Clear
                </a>

            </div>

        </div>

    </div>

</form>

@endsection

@section('content')

<div class="card shadow-sm border">

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">

            <thead class="table-light">
                <tr>
                    <th>Log ID</th>
                    <th>User</th>
                    <th>Subject</th>
                    <th>Message</th>
                    <th class="text-center">Date</th>
                    <th class="text-end">Action</th>
                </tr>
            </thead>

            <tbody>

                @forelse ($logs as $log)
                    @php
                        $user = $log->user;
                        $isMe = auth()->id() === $log->user_id;

                        // Smart route resolver
                        $route = match(class_basename($log->subject_type)) {
                            'Announcement' => route('announcements.show', $log->subject_id),
                            'Review' => route('reviews.show', $log->subject_id),
                            'Booking' => route('bookings.show', $log->subject_id),
                            'Service' => route('services.show', $log->subject_id),
                            'User' => route('users.show', $log->subject_id),
                            default => null
                        };
                    @endphp

                    <tr>

                        {{-- LOG ID --}}
                        <td class="fw-bold text-muted">
                            #{{ $log->id }}
                        </td>

                        {{-- USER --}}
                        <td>
                            <div class="d-flex align-items-center">

                                @if ($user?->profile?->avatar)
                                    <img src="{{ asset('storage/' . $user->profile->avatar) }}"
                                         class="rounded-circle me-2 object-fit-cover"
                                         width="40" height="40">
                                @else
                                    <div class="bg-light text-muted rounded-circle d-flex align-items-center justify-content-center me-2"
                                         style="width:40px;height:40px;">
                                        <i class="bi bi-person"></i>
                                    </div>
                                @endif

                                <div>
                                    <div class="fw-bold">
                                        {{ $isMe ? 'You' : ($user->name ?? 'System') }}
                                    </div>

                                    <small class="text-muted text-uppercase">
                                        {{ $user->role ?? 'system' }}
                                    </small>
                                </div>

                            </div>
                        </td>

                        {{-- SUBJECT + ACTION --}}
                        <td>
                            <div class="d-flex flex-column gap-1">

                                {{-- ACTION BADGE --}}
                                <span class="badge
                                    @if($log->action === 'created') bg-success
                                    @elseif($log->action === 'updated') bg-primary
                                    @else bg-danger
                                    @endif w-fit">

                                    {{ strtoupper($log->action) }}
                                </span>

                                {{-- SUBJECT --}}
                                <span class="badge bg-light text-dark">
                                    {{ class_basename($log->subject_type) }} #{{ $log->subject_id }}
                                </span>

                            </div>
                        </td>

                        {{-- MESSAGE --}}
                        <td>
                            <div class="fw-medium">
                                {{ $log->message }}
                            </div>
                        </td>

                        {{-- DATE --}}
                        <td class="text-center">
                            <div class="fw-bold">
                                {{ $log->created_at->format('M d, Y') }}
                            </div>
                            <small class="text-muted">
                                {{ $log->created_at->format('h:i A') }}
                            </small>
                        </td>

                        {{-- ACTION --}}
                        <td class="text-end">

                            @php
                                $modelClass = $log->subject_type;

                                $exists = $modelClass && $log->subject_id
                                    ? $modelClass::find($log->subject_id)
                                    : null;

                                $route = match(class_basename($log->subject_type)) {
                                    'Announcement' => route('announcements.show', $log->subject_id),
                                    'Review'       => route('reviews.show', $log->subject_id),
                                    'Booking'      => route('bookings.show', $log->subject_id),
                                    'Service'      => route('services.show', $log->subject_id),
                                    'User'         => route('users.show', $log->subject_id),
                                    default        => null,
                                };
                            @endphp

                            {{-- IF STILL EXISTS --}}
                            @if ($exists && $route)
                                <a href="{{ $route }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye"></i>
                                </a>

                            {{-- IF DELETED --}}
                            @elseif (!$exists)
                                <span class="badge bg-danger">
                                    Deleted
                                </span>

                            {{-- FALLBACK --}}
                            @else
                                <button class="btn btn-sm btn-outline-secondary" disabled>
                                    <i class="bi bi-eye"></i>
                                </button>
                            @endif

                        </td>

                    </tr>

                @empty
                    <tr>
                        <td colspan="6" class="text-center py-5">

                            <i class="bi bi-activity fs-1 text-muted"></i>

                            <h5 class="mt-3">No activity logs yet</h5>

                            <p class="text-muted mb-0">
                                System activities will appear here automatically.
                            </p>

                        </td>
                    </tr>
                @endforelse

                </tbody>

        </table>
    </div>

</div>

@endsection