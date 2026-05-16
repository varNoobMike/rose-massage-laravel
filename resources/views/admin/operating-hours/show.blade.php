@extends('layouts.admin')

@section('page-title', 'Schedule #' . $operatingHour->id)
@section('breadcrumb-parent', 'Operating Hours')
@section('breadcrumb-parent-url', route('settings.operating-hours.index'))

@section('page-header', true)
@section('page-header-title-showpage', 'Schedule #' . $operatingHour->id)
@section('page-header-subtitle', 'Review and manage this operating schedule')

@section('content')

    @php
        $isClosed = $operatingHour->is_closed;
        $isMorning = \Carbon\Carbon::parse($operatingHour->start_time)->format('A') === 'AM';
    @endphp

    <div class="row g-4">

        {{-- LEFT --}}
        <div class="col-12 col-lg-8">

            <div class="card shadow-sm border">

                <div class="card-header bg-white py-3 border-bottom">

                    <div class="d-flex justify-content-between align-items-center">

                        <h6 class="mb-0 fw-bold text-uppercase small text-muted tracking-wider">
                            Schedule Information
                        </h6>

                        <span class="badge bg-light text-primary border px-3 py-2">
                            ID: #{{ str_pad($operatingHour->id, 4, '0', STR_PAD_LEFT) }}
                        </span>

                    </div>

                </div>

                <div class="card-body p-0">

                    <div class="table-responsive">

                        <table class="table table-borderless mb-0 align-middle">

                            <tbody>

                                {{-- DAY --}}
                                <tr class="border-bottom border-light">
                                    <td class="ps-4 py-4 text-muted small fw-bold text-uppercase" style="width: 30%;">
                                        Day of Week
                                    </td>

                                    <td class="py-4 pe-4 fw-bold text-dark">
                                        {{ $operatingHour->day_of_week }}

                                        @if ($isClosed)
                                            <span class="badge bg-danger ms-2">Closed</span>
                                        @endif
                                    </td>
                                </tr>

                                {{-- STATUS (NEW) --}}
                                <tr class="border-bottom border-light">
                                    <td class="ps-4 py-4 text-muted small fw-bold text-uppercase">
                                        Status
                                    </td>

                                    <td class="py-4 pe-4">
                                        @if ($isClosed)
                                            <span class="badge bg-danger px-3 py-2">
                                                Closed
                                            </span>
                                        @else
                                            <span class="badge bg-success px-3 py-2">
                                                Open
                                            </span>
                                        @endif
                                    </td>
                                </tr>

                                {{-- OPENING --}}
                                <tr class="border-bottom border-light">
                                    <td class="ps-4 py-4 text-muted small fw-bold text-uppercase">
                                        Opening Time
                                    </td>

                                    <td class="py-4 pe-4">

                                        @if ($isClosed)
                                            <span class="text-muted">—</span>
                                        @else
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-box-arrow-in-right text-success me-2"></i>
                                                <span class="fw-bold text-dark">
                                                    {{ \Carbon\Carbon::parse($operatingHour->start_time)->format('h:i A') }}
                                                </span>
                                            </div>
                                        @endif

                                    </td>
                                </tr>

                                {{-- CLOSING --}}
                                <tr class="border-bottom border-light">
                                    <td class="ps-4 py-4 text-muted small fw-bold text-uppercase">
                                        Closing Time
                                    </td>

                                    <td class="py-4 pe-4">

                                        @if ($isClosed)
                                            <span class="text-muted">—</span>
                                        @else
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-box-arrow-left text-danger me-2"></i>
                                                <span class="fw-bold text-dark">
                                                    {{ \Carbon\Carbon::parse($operatingHour->end_time)->format('h:i A') }}
                                                </span>
                                            </div>
                                        @endif

                                    </td>
                                </tr>

                                {{-- PERIOD --}}
                                <tr class="border-bottom border-light">
                                    <td class="ps-4 py-4 text-muted small fw-bold text-uppercase">
                                        Schedule Period
                                    </td>

                                    <td class="py-4 pe-4">

                                        @if ($isClosed)
                                            <span class="badge bg-secondary px-3 py-2">
                                                Not Applicable
                                            </span>
                                        @else
                                            <span
                                                class="badge {{ $isMorning ? 'bg-warning text-dark' : 'bg-dark' }} px-3 py-2">
                                                {{ $isMorning ? 'AM Schedule' : 'PM Schedule' }}
                                            </span>
                                        @endif

                                    </td>
                                </tr>

                                {{-- LOGS --}}
                                <tr>
                                    <td class="ps-4 py-4 text-muted small fw-bold text-uppercase">
                                        System Logs
                                    </td>

                                    <td class="py-4 pe-4 text-muted small">

                                        <div class="mb-1">
                                            <i class="bi bi-calendar-check me-2 opacity-50"></i>
                                            Created:
                                            <strong>{{ $operatingHour->created_at->format('M d, Y') }}</strong>
                                        </div>

                                        <div>
                                            <i class="bi bi-arrow-repeat me-2 opacity-50"></i>
                                            Last Update:
                                            <strong>{{ $operatingHour->updated_at->diffForHumans() }}</strong>
                                        </div>

                                    </td>
                                </tr>

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>

        {{-- RIGHT --}}
        <div class="col-12 col-lg-4">

            {{-- STATUS CARD (UPDATED) --}}
            <div class="card shadow-sm border mb-4 text-center">

                <div class="card-body p-4">

                    <small class="text-uppercase text-muted fw-bold mb-3 d-block tracking-wider">
                        Current Status
                    </small>

                    <div
                        class="rounded-pill py-2 px-4 d-inline-flex align-items-center mb-2
                    {{ $isClosed ? 'bg-danger bg-opacity-10 text-danger' : 'bg-success bg-opacity-10 text-success' }}">

                        <i class="bi {{ $isClosed ? 'bi-x-circle-fill' : 'bi-check-circle-fill' }} me-2"></i>

                        <span class="fw-bold text-uppercase">
                            {{ $isClosed ? 'Closed' : 'Open' }}
                        </span>

                    </div>

                    <p class="text-muted small mb-0">
                        {{ $isClosed ? 'Spa is not operating on this schedule' : 'Spa is operating during this schedule' }}
                    </p>

                </div>

            </div>

            {{-- OPERATING WINDOW --}}
            <div class="card shadow-sm border overflow-hidden mb-4">

                <div class="card-header bg-white py-3 border-bottom text-center">
                    <h6 class="mb-0 fw-bold small text-muted text-uppercase tracking-wider">
                        Operating Window
                    </h6>
                </div>

                <div class="card-body text-center py-4">

                    @if ($isClosed)
                        <div class="text-muted py-4">
                            <i class="bi bi-slash-circle fs-1 d-block mb-2"></i>
                            No operating hours (Closed day)
                        </div>
                    @else
                        <div class="display-6 fw-bold text-primary">
                            {{ \Carbon\Carbon::parse($operatingHour->start_time)->format('h:i A') }}
                        </div>

                        <div class="text-muted my-2">to</div>

                        <div class="display-6 fw-bold text-dark">
                            {{ \Carbon\Carbon::parse($operatingHour->end_time)->format('h:i A') }}
                        </div>
                    @endif

                </div>

            </div>

            {{-- ACTIONS --}}
            @if (in_array(auth()->user()?->role, ['admin', 'owner']))
                <div class="card shadow-sm border">

                    <div class="card-header bg-white py-3 border-bottom text-center">
                        <h6 class="mb-0 fw-bold text-uppercase small text-muted">
                            Actions
                        </h6>
                    </div>

                    <div class="card-body">

                        <a href="{{ route('settings.operating-hours.edit', $operatingHour->id) }}"
                            class="btn btn-primary w-100 mb-2">
                            <i class="bi bi-pencil-square me-1"></i>
                            Edit
                        </a>

                    </div>

                </div>
            @endif

        </div>

    </div>

@endsection
