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
        <button class="btn btn-outline-dark d-md-none w-100 mb-3" type="button" data-bs-toggle="collapse"
            data-bs-target="#activityLogFilter">

            <i class="bi bi-funnel me-1"></i>
            Show Filters
        </button>

        <div class="collapse d-md-block" id="activityLogFilter">

            <div class="row g-3 align-items-end">

                {{-- SEARCH --}}
                <div class="col-12 col-md-5">
                    <input type="text" name="search" class="form-control" placeholder="Search logs, messages, users..."
                        value="{{ $filters['search'] ?? '' }}">
                </div>

                {{-- ACTION --}}
                <div class="col-12 col-md-3">
                    <select name="action" class="form-select">

                        <option value="" @selected(empty($filters['action']))>
                            All Actions
                        </option>

                        <option value="created" @selected(($filters['action'] ?? null) === 'created')>
                            Created
                        </option>

                        <option value="updated" @selected(($filters['action'] ?? null) === 'updated')>
                            Updated
                        </option>

                        <option value="deleted" @selected(($filters['action'] ?? null) === 'deleted')>
                            Deleted
                        </option>

                    </select>
                </div>

                {{-- SUBJECT TYPE --}}
                <div class="col-12 col-md-2">
                    <select name="subject_type" class="form-select">

                        <option value="" @selected(empty($filters['subject_type']))>
                            All Subjects
                        </option>

                        <option value="App\Models\Booking" @selected(($filters['subject_type'] ?? null) === 'App\Models\Booking')>
                            Booking
                        </option>

                        <option value="App\Models\User" @selected(($filters['subject_type'] ?? null) === 'App\Models\User')>
                            User
                        </option>

                        <option value="App\Models\Review" @selected(($filters['subject_type'] ?? null) === 'App\Models\Review')>
                            Review
                        </option>

                        <option value="App\Models\Service" @selected(($filters['subject_type'] ?? null) === 'App\Models\Service')>
                            Service
                        </option>

                        <option value="App\Models\Announcement" @selected(($filters['subject_type'] ?? null) === 'App\Models\Announcement')>
                            Announcement
                        </option>

                    </select>
                </div>

                {{-- DATE FROM --}}
                <div class="col-md-2">
                    <div class="input-group">
                        <span class="input-group-text">From</span>

                        <input type="date" name="date_from" class="form-control"
                            value="{{ $filters['date_from'] ?? '' }}">
                    </div>
                </div>

                {{-- DATE TO --}}
                <div class="col-md-2">
                    <div class="input-group">
                        <span class="input-group-text">To</span>

                        <input type="date" name="date_to" class="form-control" value="{{ $filters['date_to'] ?? '' }}">
                    </div>
                </div>

                {{-- ACTIONS --}}
                <div class="col-12 col-md-3 d-flex gap-2">

                    <button class="btn btn-dark w-100">
                        <i class="bi bi-funnel me-1"></i>
                        Filter
                    </button>

                    <a href="{{ route('activity-logs.index') }}" class="btn btn-outline-secondary w-100">

                        <i class="bi bi-x-circle me-1"></i>
                        Clear
                    </a>

                </div>

            </div>

        </div>

    </form>

@endsection

@section('content')

    {{-- FILTER SUMMARY --}}
    @if ($hasFilters)
        <div class="alert alert-info d-flex justify-content-between align-items-center py-2 px-3 mb-3">

            <div class="d-flex flex-wrap gap-2 align-items-center">

                <strong class="me-2">
                    <i class="bi bi-funnel-fill"></i> Filters applied:
                </strong>

                {{-- SEARCH --}}
                @if (!empty($filters['search']))
                    <span class="badge bg-dark">
                        Search: {{ $filters['search'] }}
                    </span>
                @endif

                {{-- ACTION --}}
                @if (!empty($filters['action']))
                    <span @class([
                        'badge',
                        'text-capitalize',
                        'bg-success text-white' => ($filters['action'] ?? null) === 'created',
                        'bg-primary' => ($filters['action'] ?? null) === 'updated',
                        'bg-danger' => ($filters['action'] ?? null) === 'deleted'
                    ])>
                        Action: {{ ucfirst($filters['action']) }}
                    </span>
                @endif

                {{-- SUBJECT --}}
                @if (!empty($filters['subject_type']))
                    <span class="badge bg-light text-dark border">
                        Subject:
                        {{ class_basename($filters['subject_type']) }}
                    </span>
                @endif

                {{-- DATE --}}
                @if (!empty($filters['date_from']) || !empty($filters['date_to']))
                    <span class="badge bg-secondary">
                        Date:
                        {{ $filters['date_from'] ?? '...' }}
                        →
                        {{ $filters['date_to'] ?? '...' }}
                    </span>
                @endif

            </div>

        </div>
    @endif

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
                                            class="rounded-circle me-2 object-fit-cover" width="40" height="40">
                                    @else
                                        <div class="bg-light text-muted rounded-circle d-flex align-items-center justify-content-center me-2"
                                            style="width:40px;height:40px;">

                                            <i class="bi bi-person"></i>
                                        </div>
                                    @endif

                                    <div>

                                        <div class="fw-bold">
                                            {{ $isMe ? 'You' : $user->name ?? 'System' }}
                                        </div>

                                        <small class="text-muted text-uppercase">
                                            {{ $user->role ?? 'system' }}
                                        </small>

                                    </div>

                                </div>
                            </td>

                            {{-- SUBJECT --}}
                            <td>

                                <div class="d-flex flex-column gap-1">

                                    {{-- ACTION BADGE --}}
                                    <span
                                        class="badge
                                        @if ($log->action === 'created') bg-success
                                        @elseif ($log->action === 'updated')
                                            bg-primary
                                        @else
                                            bg-danger @endif w-fit">

                                        {{ strtoupper($log->action) }}
                                    </span>

                                    {{-- SUBJECT --}}
                                    <span class="badge bg-light text-dark">
                                        {{ class_basename($log->subject_type) }}
                                        #{{ $log->subject_id }}
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

                                    $exists =
                                        $modelClass && $log->subject_id ? $modelClass::find($log->subject_id) : null;

                                    $route = match (class_basename($log->subject_type)) {
                                        'Announcement' => route('announcements.show', $log->subject_id),
                                        'Review' => route('reviews.show', $log->subject_id),
                                        'Booking' => route('bookings.show', $log->subject_id),
                                        'Service' => route('services.show', $log->subject_id),
                                        'User' => route('users.show', $log->subject_id),
                                        default => null,
                                    };
                                @endphp

                                {{-- EXISTS --}}
                                @if ($exists && $route)
                                    <a href="{{ $route }}" class="btn btn-sm btn-outline-primary">

                                        <i class="bi bi-eye"></i>
                                    </a>

                                    {{-- DELETED --}}
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

                                @if ($hasFilters)
                                    <i class="bi bi-search fs-1 text-muted"></i>

                                    <h5 class="mt-3">
                                        No logs found
                                    </h5>

                                    <p class="text-muted mb-3">
                                        No activity logs match your filters.
                                    </p>

                                    <a href="{{ route('activity-logs.index') }}" class="btn btn-outline-dark">

                                        <i class="bi bi-x-circle me-1"></i>
                                        Clear Filters
                                    </a>
                                @else
                                    <i class="bi bi-activity fs-1 text-muted"></i>

                                    <h5 class="mt-3">
                                        No activity logs yet
                                    </h5>

                                    <p class="text-muted mb-0">
                                        System activities will appear here automatically.
                                    </p>
                                @endif

                            </td>
                        </tr>
                    @endforelse

                </tbody>

            </table>
        </div>

        {{-- PAGINATION --}}
        @if ($logs->hasPages())
            <div class="card-footer bg-white">

                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">

                    <small class="text-muted">
                        Showing {{ $logs->firstItem() }}
                        to {{ $logs->lastItem() }}
                        of {{ $logs->total() }} logs
                    </small>

                    {{ $logs->links() }}

                </div>

            </div>
        @endif

    </div>

@endsection
