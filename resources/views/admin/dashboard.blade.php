@extends('layouts.admin')

@section('title', 'Overview')

@section('page-title', 'Dashboard')

@section('content')

    <div class="d-flex align-items-center justify-content-between mb-3 mt-4">
        <h5 class="fw-bold mb-0 d-flex align-items-center gap-2">
            <i class="bi bi-bar-chart-line text-primary"></i>
            Booking Summary
        </h5>
    </div>

    <div class="row g-4 mb-4 align-items-stretch">

        <!-- Total Bookings -->
        <div class="col-md-3">
            <a href="{{ route('bookings.index') }}" class="text-decoration-none text-dark">
                <div class="card shadow-sm border p-3 h-100 d-flex flex-row align-items-center gap-3"
                    style="min-height: 120px;">
                    <i class="bi bi-calendar-check fs-2 text-primary"></i>
                    <div>
                        <small class="text-muted">Total Bookings</small>
                        <h3 class="fw-bold mb-0">{{ $totalBookings }}</h3>
                    </div>
                </div>
            </a>
        </div>

        <!-- Today's Bookings -->
        <div class="col-md-3">
            <a href="{{ route('bookings.index', ['date' => now()->toDateString()]) }}"
                class="text-decoration-none text-dark">
                <div class="card shadow-sm border p-3 h-100 d-flex flex-row align-items-center gap-3"
                    style="min-height: 120px;">
                    <i class="bi bi-clock-history fs-2 text-info"></i>
                    <div>
                        <small class="text-muted">Today's Bookings</small>
                        <h3 class="fw-bold mb-0">{{ $todayBookings }}</h3>
                    </div>
                </div>
            </a>
        </div>

        <!-- Pending -->
        <div class="col-md-3">
            <a href="{{ route('bookings.index', ['status' => 'pending']) }}" class="text-decoration-none text-dark">
                <div class="card shadow-sm border p-3 h-100 d-flex flex-row align-items-center gap-3"
                    style="min-height: 120px;">
                    <i class="bi bi-hourglass-split fs-2 text-warning"></i>
                    <div>
                        <small class="text-muted">Pending</small>
                        <h3 class="fw-bold mb-0">{{ $pendingBookings }}</h3>
                    </div>
                </div>
            </a>
        </div>

        <!-- Confirmed -->
        <div class="col-md-3">
            <a href="{{ route('bookings.index', ['status' => 'confirmed']) }}" class="text-decoration-none text-dark">
                <div class="card shadow-sm border p-3 h-100 d-flex flex-row align-items-center gap-3"
                    style="min-height: 120px;">
                    <i class="bi bi-check2-circle fs-2 text-primary"></i>
                    <div>
                        <small class="text-muted">Confirmed</small>
                        <h3 class="fw-bold mb-0">{{ $confirmedBookings ?? 0 }}</h3>
                    </div>
                </div>
            </a>
        </div>

        <!-- Active / Ongoing -->
        <div class="col-md-3">
            <a href="{{ route('bookings.index', ['status' => 'active']) }}" class="text-decoration-none text-dark">
                <div class="card shadow-sm border p-3 h-100 d-flex flex-row align-items-center gap-3"
                    style="min-height: 120px;">
                    <i class="bi bi-play-circle fs-2 text-info"></i>
                    <div>
                        <small class="text-muted">Active</small>
                        <h3 class="fw-bold mb-0">{{ $activeBookings ?? 0 }}</h3>
                    </div>
                </div>
            </a>
        </div>

        <!-- Completed -->
        <div class="col-md-3">
            <a href="{{ route('bookings.index', ['status' => 'completed']) }}" class="text-decoration-none text-dark">
                <div class="card shadow-sm border p-3 h-100 d-flex flex-row align-items-center gap-3"
                    style="min-height: 120px;">
                    <i class="bi bi-check-circle fs-2 text-success"></i>
                    <div>
                        <small class="text-muted">Completed</small>
                        <h3 class="fw-bold mb-0">{{ $completedBookings }}</h3>
                    </div>
                </div>
            </a>
        </div>

        <!-- Cancelled -->
        <div class="col-md-3">
            <a href="{{ route('bookings.index', ['status' => 'cancelled']) }}" class="text-decoration-none text-dark">
                <div class="card shadow-sm border p-3 h-100 d-flex flex-row align-items-center gap-3"
                    style="min-height: 120px;">
                    <i class="bi bi-x-circle fs-2 text-danger"></i>
                    <div>
                        <small class="text-muted">Cancelled</small>
                        <h3 class="fw-bold mb-0">{{ $cancelledBookings }}</h3>
                    </div>
                </div>
            </a>
        </div>

    </div>

    <div class="d-flex align-items-center justify-content-between mb-3 mt-5">

        <h5 class="fw-bold mb-0 d-flex align-items-center gap-2">
            <i class="bi bi-cash-stack text-success"></i>
            Revenue Summary
        </h5>

    </div>

    <div class="row g-4 mb-4 align-items-stretch">

        @if (auth()->user()->role !== 'receptionist')
            <!-- Total Revenue -->
            <div class="col-md-3">
                <div class="card shadow-sm border p-3 h-100 d-flex flex-row align-items-center gap-3"
                    style="min-height: 120px;">
                    <i class="bi bi-cash-stack fs-2 text-success"></i>
                    <div>
                        <small class="text-muted">Total Revenue</small>
                        <h3 class="fw-bold mb-0">
                            ₱{{ number_format($totalRevenue, 2) }}
                        </h3>
                    </div>
                </div>
            </div>
        @endif

        <!-- Today's Revenue -->
        <div class="col-md-3">
            <div class="card shadow-sm border p-3 h-100 d-flex flex-row align-items-center gap-3"
                style="min-height: 120px;">
                <i class="bi bi-graph-up-arrow fs-2 text-primary"></i>
                <div>
                    <small class="text-muted">Today's Revenue</small>
                    <h3 class="fw-bold mb-0">
                        ₱{{ number_format($todayRevenue, 2) }}
                    </h3>
                </div>
            </div>
        </div>

        @if (auth()->user()->role !== 'receptionist')
            <!-- Weekly Revenue -->
            <div class="col-md-3">
                <div class="card shadow-sm border p-3 h-100 d-flex flex-row align-items-center gap-3"
                    style="min-height: 120px;">
                    <i class="bi bi-calendar-week fs-2 text-info"></i>
                    <div>
                        <small class="text-muted">Weekly Revenue</small>
                        <h3 class="fw-bold mb-0">
                            ₱{{ number_format($weeklyRevenue, 2) }}
                        </h3>
                    </div>
                </div>
            </div>

            <!-- Monthly Revenue -->
            <div class="col-md-3">
                <div class="card shadow-sm border p-3 h-100 d-flex flex-row align-items-center gap-3"
                    style="min-height: 120px;">
                    <i class="bi bi-calendar-month fs-2 text-warning"></i>
                    <div>
                        <small class="text-muted">Monthly Revenue</small>
                        <h3 class="fw-bold mb-0">
                            ₱{{ number_format($monthlyRevenue, 2) }}
                        </h3>
                    </div>
                </div>
            </div>
        @endif

    </div>

    <div class="d-flex align-items-center justify-content-between mb-3 mt-5">

        <h5 class="fw-bold mb-0 d-flex align-items-center gap-2">
            <i class="bi bi-people text-primary"></i>
            Resource Summary
        </h5>

    </div>

    <div class="row g-4 mb-4 align-items-stretch">

        @if (auth()->user()->role === 'admin')
            <div class="col-md-3">
                <a href="{{ route('users.index') }}" class="text-decoration-none text-dark">
                    <div class="card shadow-sm border p-3 h-100 d-flex flex-row align-items-center gap-3">
                        <i class="bi bi-person-circle fs-2 text-primary"></i>
                        <div>
                            <small class="text-muted">Total Users</small>
                            <h3 class="fw-bold mb-0">{{ $totalUsers }}</h3>
                        </div>
                    </div>
                </a>
            </div>


            <div class="col-md-3">
                <a href="{{ route('users.index', ['status' => 'active']) }}" class="text-decoration-none text-dark">
                    <div class="card shadow-sm border p-3 h-100 d-flex flex-row align-items-center gap-3">
                        <i class="bi bi-person-check fs-2 text-success"></i>
                        <div>
                            <small class="text-muted">Active Users</small>
                            <h3 class="fw-bold mb-0">{{ $activeUsers }}</h3>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-3">
                <a href="{{ route('users.index', ['status', 'pending']) }}" class="text-decoration-none text-dark">
                    <div class="card shadow-sm border p-3 h-100 d-flex flex-row align-items-center gap-3">
                        <i class="bi bi-person-exclamation fs-2 text-warning"></i>
                        <div>
                            <small class="text-muted">Pending Users</small>
                            <h3 class="fw-bold mb-0">{{ $pendingUsers }}</h3>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-3">
                <a href="{{ route('users.index', ['status' => 'inactive']) }}" class="text-decoration-none text-dark">
                    <div class="card shadow-sm border p-3 h-100 d-flex flex-row align-items-center gap-3">
                        <i class="bi bi-person-x fs-2 text-danger"></i>
                        <div>
                            <small class="text-muted">Inactive Users</small>
                            <h3 class="fw-bold mb-0">{{ $inactiveUsers }}</h3>
                        </div>
                    </div>
                </a>
            </div>
        @endif

        <!-- Clients (optional if same as users with role client) -->
        <div class="col-md-3">
            <a href="{{ route('users.index', ['role' => 'client']) }}" class="text-decoration-none text-dark">
                <div class="card shadow-sm border p-3 h-100 d-flex flex-row align-items-center gap-3">
                    <i class="bi bi-person-hearts fs-2 text-info"></i>
                    <div>
                        <small class="text-muted">Clients</small>
                        <h3 class="fw-bold mb-0">{{ $totalClients ?? 0 }}</h3>
                    </div>
                </div>
            </a>
        </div>

        <!-- Services -->
        <div class="col-md-3">
            <a href="{{ route('services.index') }}" class="text-decoration-none text-dark">
                <div class="card shadow-sm border p-3 h-100 d-flex flex-row align-items-center gap-3">
                    <i class="bi bi-flower1 fs-2 text-success"></i>
                    <div>
                        <small class="text-muted">Services</small>
                        <h3 class="fw-bold mb-0">{{ $totalServices }}</h3>
                    </div>
                </div>
            </a>
        </div>

        @if (auth()->user()?->role === 'owner')
            <!-- Therapists -->
            <div class="col-md-3">
                <a href="{{ route('therapists.index') }}" class="text-decoration-none text-dark">
                    <div class="card shadow-sm border p-3 h-100 d-flex flex-row align-items-center gap-3">
                        <i class="bi bi-heart-pulse fs-2 text-danger"></i>
                        <div>
                            <small class="text-muted">Therapists</small>
                            <h3 class="fw-bold mb-0">{{ $totalTherapists }}</h3>
                        </div>
                    </div>
                </a>
            </div>
        @endif

        <!-- Owners -->
        @if (auth()->user()->role === 'admin')
            <div class="col-md-3">
                <a href="{{ route('users.index', ['role' => 'owner']) }}" class="text-decoration-none text-dark">
                    <div class="card shadow-sm border p-3 h-100 d-flex flex-row align-items-center gap-3">
                        <i class="bi bi-shield-lock fs-2 text-dark"></i>
                        <div>
                            <small class="text-muted">Owners</small>
                            <h3 class="fw-bold mb-0">{{ $totalOwner ?? 0 }}</h3>
                        </div>
                    </div>
                </a>
            </div>
        @endif

        @if (auth()->user()?->role === 'owner')
            <!-- Receptionists -->
            <div class="col-md-3">
                <a href="{{ route('receptionists.index') }}" class="text-decoration-none text-dark">
                    <div class="card shadow-sm border p-3 h-100 d-flex flex-row align-items-center gap-3">
                        <i class="bi bi-person-workspace fs-2 text-warning"></i>
                        <div>
                            <small class="text-muted">Receptionists</small>
                            <h3 class="fw-bold mb-0">{{ $totalReceptionist ?? 0 }}</h3>
                        </div>
                    </div>
                </a>
            </div>
        @endif


        <!-- Available Therapists, hide for now -->
        <div class="col-md-3 d-none">
            <div class="card shadow-sm border p-3 h-100 d-flex flex-row align-items-center gap-3">
                <i class="bi bi-check2-circle fs-2 text-success"></i>
                <div>
                    <small class="text-muted">Available Today</small>
                    <h3 class="fw-bold mb-0">{{ $availableTherapists ?? 0 }}</h3>
                </div>
            </div>
        </div>

    </div>

    <!-- Today's operations -->
    <div class="d-flex align-items-center justify-content-between mb-3 mt-5">
        <h5 class="fw-bold mb-0 d-flex align-items-center gap-2">
            <i class="bi bi-activity text-danger"></i>
            Today’s Operations
        </h5>
    </div>

    <div class="card shadow-sm border">

        <div class="card-header fw-bold d-flex align-items-center gap-2">
            <i class="bi bi-list-ul text-danger"></i>
            Today’s Bookings
        </div>

        <div class="table-responsive">

            <table class="table mb-0 align-middle">

                <thead class="table-light">
                    <tr>
                        <th>Booking ID</th>
                        <th class="d-none d-lg-table-cell">Client</th>
                        <th>Schedule</th>
                        <th class="d-none d-lg-table-cell">Therapist</th>
                        <th class="d-none d-lg-table-cell">Total</th>
                        <th class="d-none d-lg-table-cell">Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($todayBookingsList as $booking)
                        <tr>

                            <!-- BOOKING ID -->
                            <td class="fw-bold text-muted">
                                #{{ $booking->id }}
                            </td>

                            <!-- CLIENT -->
                            <td class="d-none d-lg-table-cell">
                                <div class="d-flex align-items-center">

                                    @if ($booking->client && $booking->client?->profile?->avatar)
                                        <img src="{{ asset('storage/' . $booking->client?->profile?->avatar) }}"
                                            class="rounded-circle me-3 object-fit-cover" width="45" height="45">
                                    @else
                                        <div class="bg-light text-muted rounded-circle d-flex align-items-center justify-content-center me-3"
                                            style="width:45px;height:45px;">
                                            <i class="bi bi-person"></i>
                                        </div>
                                    @endif

                                    <div>
                                        <div class="fw-bold">
                                            {{ optional($booking->client)->name ?? 'Unknown Client' }}
                                        </div>

                                        <small class="text-muted">
                                            {{ optional($booking->client)->email }}
                                        </small>
                                    </div>

                                </div>
                            </td>

                            <!-- SCHEDULE -->
                            <td class="">
                                <div class="fw-semibold">
                                    {{ \Carbon\Carbon::parse($booking->booking_date)->format('M d, Y') }}
                                </div>
                                <small class="text-muted">
                                    {{ \Carbon\Carbon::parse($booking->start_time)->format('h:i A') }}
                                    -
                                    {{ \Carbon\Carbon::parse($booking->end_time)->format('h:i A') }}
                                </small>
                            </td>

                            <!-- THERAPIST -->
                            <td class="d-none d-lg-table-cell">
                                @php
                                    $assignedTherapists = $booking->items
                                        ->whereNotNull('therapist_id')
                                        ->pluck('therapist')
                                        ->filter()
                                        ->unique('id');

                                    $totalItems = $booking->items->count();
                                    $assignedCount = $booking->items->whereNotNull('therapist_id')->count();
                                @endphp

                                @if ($assignedTherapists->count())
                                    <div class="d-flex flex-wrap gap-1">

                                        @foreach ($assignedTherapists->take(2) as $therapist)
                                            <span class="badge bg-success-subtle text-success border">
                                                {{ $therapist->name }}
                                            </span>
                                        @endforeach

                                        @if ($assignedTherapists->count() > 2)
                                            <span class="badge bg-light text-muted border">
                                                +{{ $assignedTherapists->count() - 2 }} more
                                            </span>
                                        @endif

                                    </div>

                                    <small class="text-muted d-block mt-1">
                                        {{ $assignedCount }}/{{ $totalItems }} assigned
                                    </small>
                                @else
                                    <span class="badge bg-warning text-dark">
                                        Unassigned
                                    </span>
                                    <small class="text-muted d-block mt-1">
                                        0/{{ $totalItems }} assigned
                                    </small>
                                @endif
                            </td>

                            <!-- TOTAL -->
                            <td class="fw-bold d-none d-lg-table-cell">
                                ₱{{ number_format($booking->total_amount, 2) }}
                            </td>

                            <!-- STATUS -->
                            <td class="d-none d-lg-table-cell">

                                @php $status = $booking->status; @endphp

                                <span
                                    class="badge
                                    @if ($status == 'pending') bg-warning text-dark
                                    @elseif($status == 'confirmed') bg-primary
                                    @elseif($status == 'active') bg-success
                                    @elseif($status == 'completed') bg-secondary
                                    @elseif($status == 'cancelled') bg-danger @endif
                                        text-uppercase small">
                                    {{ $status }}
                                </span>

                            </td>

                            <!-- ACTIONS -->
                            <td class="text-end">

                                <div class="btn-group gap-2">

                                    <a href="{{ route('bookings.show', $booking->id) }}"
                                        class="btn btn-sm btn-secondary">
                                        <i class="bi bi-eye"></i>
                                    </a>

                                    <a href="{{ route('bookings.edit', $booking->id) }}" class="btn btn-sm btn-primary">
                                        <i class="bi bi-pencil"></i>
                                    </a>

                                </div>

                            </td>

                        </tr>
                    @empty

                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <i class="bi bi-calendar-x fs-1 text-muted"></i>
                                <h5 class="mt-3">No bookings found</h5>
                                <p class="text-muted mb-0">Try adjusting your filters.</p>
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

@endsection
