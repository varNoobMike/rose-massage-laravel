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

        @if (auth()->user()->role !== 'receptionist')
            <!-- Users -->
            <div class="col-md-3">
                <div class="card shadow-sm border p-3 h-100 d-flex flex-row align-items-center gap-3">
                    <i class="bi bi-person-circle fs-2 text-primary"></i>
                    <div>
                        <small class="text-muted">Total Users</small>
                        <h3 class="fw-bold mb-0">{{ $totalUsers }}</h3>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card shadow-sm border p-3 h-100 d-flex flex-row align-items-center gap-3">
                    <i class="bi bi-person-check fs-2 text-success"></i>
                    <div>
                        <small class="text-muted">Active Users</small>
                        <h3 class="fw-bold mb-0">{{ $activeUsers }}</h3>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card shadow-sm border p-3 h-100 d-flex flex-row align-items-center gap-3">
                    <i class="bi bi-person-exclamation fs-2 text-warning"></i>
                    <div>
                        <small class="text-muted">Pending Users</small>
                        <h3 class="fw-bold mb-0">{{ $pendingUsers }}</h3>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card shadow-sm border p-3 h-100 d-flex flex-row align-items-center gap-3">
                    <i class="bi bi-person-x fs-2 text-danger"></i>
                    <div>
                        <small class="text-muted">Inactive Users</small>
                        <h3 class="fw-bold mb-0">{{ $inactiveUsers }}</h3>
                    </div>
                </div>
            </div>
        @endif

        <!-- Clients (optional if same as users with role client) -->
        <div class="col-md-3">
            <div class="card shadow-sm border p-3 h-100 d-flex flex-row align-items-center gap-3">
                <i class="bi bi-person-hearts fs-2 text-info"></i>
                <div>
                    <small class="text-muted">Clients</small>
                    <h3 class="fw-bold mb-0">{{ $totalClients ?? 0 }}</h3>
                </div>
            </div>
        </div>

        <!-- Services -->
        <div class="col-md-3">
            <div class="card shadow-sm border p-3 h-100 d-flex flex-row align-items-center gap-3">
                <i class="bi bi-flower1 fs-2 text-success"></i>
                <div>
                    <small class="text-muted">Services</small>
                    <h3 class="fw-bold mb-0">{{ $totalServices }}</h3>
                </div>
            </div>
        </div>

        <!-- Therapists -->
        <div class="col-md-3">
            <div class="card shadow-sm border p-3 h-100 d-flex flex-row align-items-center gap-3">
                <i class="bi bi-heart-pulse fs-2 text-danger"></i>
                <div>
                    <small class="text-muted">Therapists</small>
                    <h3 class="fw-bold mb-0">{{ $totalTherapists }}</h3>
                </div>
            </div>
        </div>

        <!-- Owners -->
        @if (auth()->user()->role !== 'receptionist')
            <div class="col-md-3">
                <div class="card shadow-sm border p-3 h-100 d-flex flex-row align-items-center gap-3">
                    <i class="bi bi-shield-lock fs-2 text-dark"></i>
                    <div>
                        <small class="text-muted">Owners</small>
                        <h3 class="fw-bold mb-0">{{ $totalOwner ?? 0 }}</h3>
                    </div>
                </div>
            </div>

            <!-- Receptionists -->
            <div class="col-md-3">
                <div class="card shadow-sm border p-3 h-100 d-flex flex-row align-items-center gap-3">
                    <i class="bi bi-person-workspace fs-2 text-warning"></i>
                    <div>
                        <small class="text-muted">Receptionists</small>
                        <h3 class="fw-bold mb-0">{{ $totalReceptionist ?? 0 }}</h3>
                    </div>
                </div>
            </div>
        @endif

        <!-- Available Therapists -->
        <div class="col-md-3">
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
                        <th>Customer</th>
                        <th>Time</th>
                        <th>Therapist</th>
                        <th>Status</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($todayBookingsList as $booking)
                        <tr>

                            <!-- Customer -->
                            <td class="fw-semibold">
                                <i class="bi bi-person me-1 text-muted"></i>
                                {{ $booking->client->name ?? 'N/A' }}
                            </td>

                            <!-- Service -->
                            <td>
                                <i class="bi bi-flower2 me-1 text-success"></i>
                                {{ $booking->items->first()->service->name ?? 'N/A' }}
                            </td>

                            <!-- Time -->
                            <td>
                                <i class="bi bi-clock me-1 text-info"></i>
                                {{ \Carbon\Carbon::parse($booking->start_time)->format('h:i A') ?? '-' }}
                            </td>

                            <!-- Therapist -->
                            <td>
                                <i class="bi bi-heart-pulse me-1 text-danger"></i>
                                {{ $booking->therapist->name ?? 'Unassigned' }}
                            </td>

                            <!-- Status -->
                            <td>
                                <span
                                    class="badge 
                                @if ($booking->status == 'completed') bg-success
                                @elseif($booking->status == 'pending') bg-warning
                                @elseif($booking->status == 'active') bg-info
                                @elseif($booking->status == 'cancelled') bg-danger
                                @else bg-secondary @endif">
                                    {{ ucfirst($booking->status) }}
                                </span>
                            </td>

                            <!-- Action -->
                            <td class="text-end">
                                <a href="{{ route('bookings.show', $booking->id) }}" class="btn btn-sm btn-secondary">
                                    View
                                </a>
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                No bookings for today
                            </td>
                        </tr>
                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

@endsection
