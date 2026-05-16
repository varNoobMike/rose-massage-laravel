@extends('layouts.admin')

@section('page-title', 'Operating Hours')
@section('breadcrumb-parent', 'Settings')
@section('breadcrumb-parent-url', route('settings.index'))

@section('page-header', true)
@section('page-header-title-indexpage', 'Operating Hours')
@section('page-header-subtitle', 'Manage spa business schedule')

@section('page-header-actions')
    <a href="{{ route('settings.operating-hours.index') }}" class="btn btn-outline-secondary px-4 shadow-sm">
        <i class="bi bi-arrow-repeat me-2"></i> Sync
    </a>
@endsection

@section('filter-area', true)
@section('filter-form')

    {{-- MOBILE FILTER TOGGLE --}}
    <button class="btn btn-outline-dark d-md-none w-100 mb-3" type="button" data-bs-toggle="collapse"
        data-bs-target="#filterCollapse">
        <i class="bi bi-funnel me-1"></i> Show Filters
    </button>

    <div class="collapse d-md-block" id="filterCollapse">

        <form action="{{ route('settings.operating-hours.index') }}" method="GET">

            <div class="row g-2 align-items-end">

                {{-- SEARCH --}}
                <div class="col-12 col-md-3">
                    <input type="text" name="search" class="form-control" placeholder="Search by day..."
                        value="{{ $filters['search'] ?? '' }}">
                </div>

                {{-- DAY OF WEEK --}}
                <div class="col-12 col-md-3">
                    <select name="day_of_week" class="form-select">
                        <option value="" @selected(empty($filters['day_of_week'] ?? null))>All Days</option>

                        @foreach (['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'] as $day)
                            <option value="{{ $day }}" @selected(($filters['day_of_week'] ?? null) === $day)>
                                {{ $day }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- PERIOD --}}
                <div class="col-12 col-md-3">
                    <select name="period" class="form-select">
                        <option value="" @selected(empty($filters['period'] ?? null))>All Periods</option>
                        <option value="am" @selected(($filters['period'] ?? null) === 'am')>AM</option>
                        <option value="pm" @selected(($filters['period'] ?? null) === 'pm')>PM</option>
                    </select>
                </div>

                {{-- IS CLOSED --}}
                <div class="col-12 col-md-3">
                    <select name="is_closed" class="form-select">
                        <option value="" @selected(($filters['is_closed'] ?? '') === '')>All Status</option>
                        <option value="0" @selected(($filters['is_closed'] ?? null) === '0')>Open</option>
                        <option value="1" @selected(($filters['is_closed'] ?? null) === '1')>Closed</option>
                    </select>
                </div>

                {{-- ACTIONS --}}
                <div class="col-4 d-flex gap-2">
                    <button class="btn btn-dark w-100">
                        <i class="bi bi-funnel me-1"></i> Filter
                    </button>

                    <a href="{{ route('settings.operating-hours.index') }}" class="btn btn-outline-secondary w-100">
                        <i class="bi bi-x-circle me-1"></i> Clear
                    </a>
                </div>

            </div>
        </form>
    </div>

@endsection

@section('content')

    {{-- FILTER SUMMARY --}}
    @if ($hasFilters)
        <div class="alert alert-info d-flex justify-content-between align-items-center py-2 px-3 mb-3">

            <div class="d-flex flex-wrap gap-2 align-items-center">

                <strong class="me-2">
                    <i class="bi bi-funnel-fill"></i> Filters:
                </strong>

                @if (!empty($filters['search']))
                    <span class="badge bg-dark">Search: {{ $filters['search'] }}</span>
                @endif

                @if (!empty($filters['day_of_week']))
                    <span class="badge bg-primary">Day: {{ $filters['day_of_week'] }}</span>
                @endif

                @if (!empty($filters['period']))
                    <span class="badge bg-warning text-dark text-uppercase">
                        {{ $filters['period'] }}
                    </span>
                @endif

                @if (($filters['is_closed'] ?? '') !== '')
                    <span class="badge bg-danger">
                        {{ $filters['is_closed'] == '1' ? 'Closed' : 'Open' }}
                    </span>
                @endif

            </div>

        </div>
    @endif

    {{-- TABLE --}}
    <div class="card shadow-sm border">

        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">

                <thead class="table-light">
                    <tr>
                        <th>Day</th>
                        <th class="text-center">Opening</th>
                        <th class="text-center">Closing</th>
                        <th class="text-center d-none d-lg-table-cell">Period</th>
                        <th class="text-center d-none d-lg-table-cell">Closed</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($operatingHours as $hour)
                        <tr>

                            {{-- DAY --}}
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center me-3"
                                        style="width:50px; height:50px;">
                                        <i class="bi bi-clock-history text-muted"></i>
                                    </div>

                                    <div>
                                        <div class="fw-bold">
                                            {{ $hour->day_of_week }}
                                        </div>

                                        <small class="text-muted">
                                            ID #{{ $hour->id }}
                                        </small>
                                    </div>
                                </div>
                            </td>

                            {{-- OPENING --}}
                            <td class="text-center">
                                @if ($hour->is_closed)
                                    <span class="text-muted">—</span>
                                @else
                                    <span class="badge bg-light text-dark">
                                        {{ \Carbon\Carbon::parse($hour->start_time)->format('h:i A') }}
                                    </span>
                                @endif
                            </td>

                            {{-- CLOSING --}}
                            <td class="text-center">
                                @if ($hour->is_closed)
                                    <span class="text-muted">—</span>
                                @else
                                    <span class="badge bg-light text-dark">
                                        {{ \Carbon\Carbon::parse($hour->end_time)->format('h:i A') }}
                                    </span>
                                @endif
                            </td>

                            {{-- PERIOD --}}
                            <td class="text-center d-none d-lg-table-cell">
                                @if ($hour->is_closed)
                                    <span class="badge bg-secondary">Closed</span>
                                @else
                                    @php
                                        $isMorning = \Carbon\Carbon::parse($hour->start_time)->format('A') === 'AM';
                                    @endphp

                                    <span class="badge {{ $isMorning ? 'bg-warning text-dark' : 'bg-dark' }}">
                                        {{ $isMorning ? 'AM' : 'PM' }}
                                    </span>
                                @endif
                            </td>

                            {{-- CLOSED STATUS (NEW COLUMN) --}}
                            <td class="text-center d-none d-lg-table-cell">
                                @if ($hour->is_closed)
                                    <span class="badge bg-danger">Yes</span>
                                @else
                                    <span class="badge bg-success">No</span>
                                @endif
                            </td>

                            {{-- ACTIONS --}}
                            <td class="text-end">
                                <div class="btn-group gap-2">

                                    <a href="{{ route('settings.operating-hours.show', $hour->id) }}"
                                        class="btn btn-sm btn-secondary">
                                        <i class="bi bi-eye"></i>
                                    </a>

                                    @if (in_array(auth()->user()?->role, ['admin', 'owner']))
                                        <a href="{{ route('settings.operating-hours.edit', $hour->id) }}"
                                            class="btn btn-sm btn-primary">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                    @endif

                                </div>
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="6" class="text-center py-5">

                                @if ($hasFilters)
                                    <i class="bi bi-search fs-1 text-muted"></i>
                                    <h5 class="mt-3">No results found</h5>
                                    <p class="text-muted mb-3">No operating schedules match your filters.</p>

                                    <a href="{{ route('operating-hours.index') }}" class="btn btn-outline-dark">
                                        <i class="bi bi-x-circle me-1"></i> Clear Filters
                                    </a>
                                @else
                                    <i class="bi bi-clock fs-1 text-muted"></i>
                                    <h5 class="mt-3">No schedules yet</h5>
                                    <p class="text-muted mb-0">
                                        Once schedules are created, they will appear here.
                                    </p>
                                @endif

                            </td>
                        </tr>
                    @endforelse

                </tbody>

            </table>

        </div>

        @if ($operatingHours->hasPages())
            <div class="card-footer bg-white">

                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">

                    <small class="text-muted">
                        Showing {{ $operatingHours->firstItem() }}
                        to {{ $operatingHours->lastItem() }}
                        of {{ $operatingHours->total() }} schedules
                    </small>

                    {{ $operatingHours->appends(request()->query())->links() }}

                </div>

            </div>
        @endif

    </div>

@endsection
