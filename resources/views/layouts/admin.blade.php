<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('page-title', 'Rose Massage Services')</title>

    <!-- Bootswatch Pulse -->
    <link href="https://cdn.jsdelivr.net/npm/bootswatch@5.3.3/dist/pulse/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Plus Jakarta Sans -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap"
          rel="stylesheet">


    <style>
        body {
            background: #f8f9fc;
            overflow-x: hidden;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        /* Topbar */
        .topbar {
            background: white;
            height: 70px;
            border-bottom: 1px solid #e9ecef;
            z-index: 1030;
        }

        /* Desktop Sidebar */
        .sidebar {
            width: 250px;
            min-height: 100vh;
            background: white;
            border-right: 1px solid #e9ecef;
            position: fixed;
            top: 0;
            left: 0;
            padding-top: 70px;
        }

        .sidebar .nav-link {
            color: #495057;
            padding: 12px 18px;
            margin-bottom: 6px;
            font-weight: 500;
        }

        .sidebar .nav-link:hover {
            background: #f8f9fa;
        }

        .sidebar .nav-link.active {
            background: var(--bs-primary);
            border-radius: 8px;
            color: white;
        }

        /* Main Content */
        .main-content {
            margin-left: 250px;
            padding: 90px 25px 25px;
        }

        /* Mobile */
        @media (max-width: 991px) {
            .sidebar {
                display: none;
            }

            .main-content {
                margin-left: 0;
                padding-top: 90px;
            }
        }
    </style>
</head>
<body>

<!-- TOPBAR -->
<nav class="navbar topbar fixed-top px-4 shadow-sm">
    <div class="container-fluid">

        <!-- Mobile menu -->
        <button class="btn d-lg-none"
                data-bs-toggle="offcanvas"
                data-bs-target="#mobileSidebar">
            <i class="bi bi-list fs-4"></i>
        </button>

        <!-- Logo -->
        <span class="fw-bold fs-5">
            <i class="bi bi-flower1 text-primary"></i> Spa Admin
        </span>

        <!-- Right dropdown -->
        <div class="dropdown ms-auto">

            <button class="btn border-0 d-flex align-items-center gap-2"
                    data-bs-toggle="dropdown">

                {{-- Avatar --}}
                @if(auth()->user()->profile?->avatar)
                    <img src="{{ asset('storage/' . auth()->user()->profile->avatar) }}"
                        class="rounded-circle shadow-sm"
                        width="42"
                        height="42"
                        style="object-fit: cover;">
                @else
                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center shadow-sm"
                        style="width:42px; height:42px;">
                        <i class="bi bi-person-fill"></i>
                    </div>
                @endif

                {{-- Name --}}
                <div class="text-start d-none d-md-block">
                    <div class="fw-semibold text-dark small">
                        {{ auth()->user()->name }}
                    </div>
                    <div class="text-muted small">
                        {{ ucfirst(auth()->user()->role) }}
                    </div>
                </div>

                <i class="bi bi-chevron-down text-muted small"></i>
            </button>

            <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-3 p-2"
                style="min-width: 280px;">

                {{-- User info header --}}
                <li class="px-3 py-2 border-bottom mb-2">
                    <div class="fw-bold">
                        {{ auth()->user()->name }}
                    </div>

                    <div class="text-muted small">
                        {{ auth()->user()->email }}
                    </div>

                    <span class="badge bg-light text-primary mt-2">
                        {{ ucfirst(auth()->user()->role) }}
                    </span>
                </li>

                {{-- Profile --}}
                <li>
                    <a class="dropdown-item rounded"
                    href="#">
                        <i class="bi bi-person me-2"></i>
                        My Profile
                    </a>
                </li>

                {{-- Logout --}}
                <li>
                    <form method="POST"
                        action="{{ route('logout') }}">
                        @csrf

                        <button class="dropdown-item text-danger rounded">
                            <i class="bi bi-box-arrow-right me-2"></i>
                            Logout
                        </button>
                    </form>
                </li>

            </ul>

        </div>

    </div>
</nav>

<!-- DESKTOP SIDEBAR -->
<div class="sidebar d-none d-lg-block">
    <div class="p-3">

        <ul class="nav flex-column">

            <li class="nav-item">
                <a href=""
                   class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="bi bi-grid me-2"></i>
                    Dashboard
                </a>
            </li>

            @if(auth()->user()->role === 'admin')
                <li class="nav-item">
                    <a href="{{ route('users.index') }}"
                    class="nav-link {{ request()->routeIs('users.index') ? 'active' : '' }}">
                        <i class="bi bi-people me-2"></i>
                        Users
                    </a>
                </li>
            @endif

            <li class="nav-item">
                <a href="{{route('bookings.index')}}"
                   class="nav-link {{ request()->routeIs('bookings*') ? 'active' : '' }}">
                    <i class="bi bi-calendar-check me-2"></i>
                    Bookings
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('services.index') }}"
                   class="nav-link {{ request()->routeIs('services.*') ? 'active' : '' }}">
                    <i class="bi bi-heart me-2"></i>
                    Services
                </a>
            </li>

            @if(auth()->user()->role === 'owner')
            <li class="nav-item">
                <a href="{{ route('receptionists.index') }}"
                   class="nav-link {{ request()->routeIs('receptionists.*') ? 'active' : '' }}">
                    <i class="bi bi-people me-2"></i>
                    Receptionists
                </a>
            </li>
            @endif

             @if(auth()->user()->role === 'owner' || auth()->user()->role === 'receptionist')
            <li class="nav-item">
                <a href=""
                   class="nav-link {{ request()->routeIs('admin.therapists*') ? 'active' : '' }}">
                    <i class="bi bi-people me-2"></i>
                    Therapists
                </a>
            </li>
            @endif

            <li class="nav-item">
                <a href=""
                   class="nav-link {{ request()->routeIs('admin.customers*') ? 'active' : '' }}">
                    <i class="bi bi-person me-2"></i>
                    Clents
                </a>
            </li>

        </ul>
    </div>
</div>

<!-- MOBILE SIDEBAR -->
<div class="offcanvas offcanvas-start"
     tabindex="-1"
     id="mobileSidebar">

    <div class="offcanvas-header">
        <h5 class="offcanvas-title">
            Spa Admin
        </h5>

        <button type="button"
                class="btn-close"
                data-bs-dismiss="offcanvas">
        </button>
    </div>

    <div class="offcanvas-body">

        <ul class="nav flex-column">

            <li class="nav-item">
                <a href=""
                   class="nav-link">
                    Dashboard
                </a>
            </li>

            @if(auth()->user()->role === 'admin')
                <li class="nav-item">
                    <a href="{{ route('users.index') }}"
                    class="nav-link">
                        Users
                    </a>
                </li>
            @endif

            <li class="nav-item">
                <a href="{{ route('bookings.index') }}"
                   class="nav-link">
                    Bookings
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('services.index') }}"
                   class="nav-link">
                    Services
                </a>
            </li>

            <li class="nav-item">
                <a href=""
                   class="nav-link">
                    Therapists
                </a>
            </li>

            <li class="nav-item">
                <a href=""
                   class="nav-link">
                    Customers
                </a>
            </li>

        </ul>
    </div>
</div>

<!-- MAIN CONTENT -->
<div class="main-content">

        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-3 bg-transparent p-0">

                <li class="breadcrumb-item">
                    <a href="/" class="text-decoration-none text-muted">
                        Home
                    </a>
                </li>

                @hasSection('breadcrumb-parent')
                    <li class="breadcrumb-item">
                        <a href="@yield('breadcrumb-parent-url')" class="text-decoration-none text-muted">
                            @yield('breadcrumb-parent')
                        </a>
                    </li>
                @endif

                <li class="breadcrumb-item active text-primary fw-semibold">
                    @yield('page-title', 'Dashboard')
                </li>

            </ol>
        </nav>

    @yield('content')
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

@yield('page-scripts')

</body>
</html>