<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('page-title', 'Rose Massage Services')</title>

    @include('partials.styles')

    <!-- Font Plus Jakarta Sans -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap"
        rel="stylesheet">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>

</head>


@yield('page-styles')

<body>

    <!-- Wrapper -->
    <div class="container-fluid p-0" style="min-height: 100vh;">

        <div class="row g-0">

            <!-- Sidebar - Desktop -->
            <div id="sidebar-desktop" class="col-2 bg-white border-end d-none d-lg-block min-vh-100">
                <div class="py-4 px-3">
                    <div class="mb-4 px-2">
                        <h5 class="fw-bold mb-0 text-dark" style="letter-spacing: -0.5px;">
                            ROSE MASSAGE
                        </h5>
                        <small class="text-muted text-uppercase fw-semibold" style="font-size: 0.65rem;">Control
                            Panel</small>
                    </div>

                    <ul class="nav flex-column gap-2">
                        <li class="nav-item mb-1">
                            <a href="{{ route('dashboard') }}"
                                class="nav-link {{ request()->routeIs('dashboard') ? 'text-bg-primary fw-bold' : 'text-dark opacity-75' }} px-3 py-2 d-flex align-items-center">
                                <i class="bi bi-grid-1x2-fill me-3"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>

                        <li class="nav-item mb-1">
                            <a href="{{ route('bookings.index') }}"
                                class="nav-link {{ request()->routeIs('bookings*') ? 'text-bg-primary fw-bold' : 'text-dark opacity-75' }} px-3 py-2 d-flex align-items-center">
                                <i class="bi bi-calendar-event me-3"></i>
                                <span>Bookings</span>
                            </a>
                        </li>

                        <li class="nav-item mb-1">
                            <a href="{{ route('services.index') }}"
                                class="nav-link {{ request()->routeIs('services*') ? 'text-bg-primary fw-bold' : 'text-dark opacity-75' }} px-3 py-2 d-flex align-items-center">
                                <i class="bi bi-flower1 me-3"></i>
                                <span>Services</span>
                            </a>
                        </li>

                        @php $role = auth()->user()?->role; @endphp

                        @if ($role === 'admin')
                            <li class="nav-item mb-1">
                                <a href="{{ route('users.index') }}"
                                    class="nav-link {{ request()->routeIs('users*') ? 'text-bg-primary fw-bold' : 'text-dark opacity-75' }} px-3 py-2 d-flex align-items-center">
                                    <i class="bi bi-people me-3"></i>
                                    <span>Users</span>
                                </a>
                            </li>
                        @endif

                        @if ($role === 'owner') 
                            <li class="nav-item mb-1">
                                <a href="{{ route('clients.index') }}"
                                    class="nav-link {{ request()->routeIs('clients*') ? 'text-bg-primary fw-bold' : 'text-dark opacity-75' }} px-3 py-2 d-flex align-items-center">
                                    <i class="bi bi-people me-3"></i>
                                    <span>Clients</span>
                                </a>
                            </li>

                            <li class="nav-item mb-1">
                                <a href="{{ route('receptionists.index') }}"
                                    class="nav-link {{ request()->routeIs('receptionists*') ? 'text-bg-primary fw-bold' : 'text-dark opacity-75' }} px-3 py-2 d-flex align-items-center">
                                    <i class="bi bi-person-workspace me-3"></i>
                                    <span>Receptionists</span>
                                </a>
                            </li>
                        @endif

                        @if ($role === 'owner' || $role === 'receptionist')
                            <li class="nav-item mb-1">
                                <a href="{{ route('therapists.index') }}"
                                    class="nav-link {{ request()->routeIs('therapists*') ? 'text-bg-primary fw-bold' : 'text-dark opacity-75' }} px-3 py-2 d-flex align-items-center">
                                    <i class="bi bi-person-hearts me-3"></i>
                                    <span>Therapists</span>
                                </a>
                            </li>
                        @endif

                        <li class="nav-item mb-1">
                            <a href="{{ route('announcements.index') }}"
                                class="nav-link {{ request()->routeIs('announcements*') ? 'text-bg-primary fw-bold' : 'text-dark opacity-75' }} px-3 py-2 d-flex align-items-center">
                                <i class="bi bi-megaphone me-3"></i>
                                <span>Announcements</span>
                            </a>
                        </li>

                        <li class="nav-item mb-1">
                            <a href="{{ route('reviews.index') }}"
                                class="nav-link {{ request()->routeIs('reviews*') ? 'text-bg-primary fw-bold' : 'text-dark opacity-75' }} px-3 py-2 d-flex align-items-center">
                                <i class="bi bi-star me-3"></i>
                                <span>Reviews</span>
                            </a>
                        </li>

                        @if ($role === 'admin' || $role === 'owner')
                            <li class="nav-item mb-1">
                                <a href="{{ route('reports.bookings') }}"
                                    class="nav-link {{ request()->routeIs('reports*') ? 'text-bg-primary fw-bold' : 'text-dark opacity-75' }} px-3 py-2 d-flex align-items-center">
                                    <i class="bi bi-bar-chart me-3"></i>
                                    <span>Reports</span>
                                </a>
                            </li>
                        @endif

                        <li class="nav-item mb-1">
                            <a href="{{ route('activity-logs.index') }}"
                                class="nav-link {{ request()->routeIs('activity-logs*') ? 'text-bg-primary fw-bold' : 'text-dark opacity-75' }} px-3 py-2 d-flex align-items-center">
                                <i class="bi bi-clock-history me-3"></i>
                                <span>Activity Logs</span>
                            </a>
                        </li>

                    </ul>
                </div>
            </div>

            <!-- Sidebar - Mobile -->
            <div class="offcanvas offcanvas-start" tabindex="-1" id="sidebar-mobile"
                aria-labelledby="sidebarOffcanvasLabel">
                <div class="offcanvas-header border-bottom">
                    <h5 class="offcanvas-title fw-bold" id="sidebarOffcanvasLabel">MENU</h5>
                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="offcanvas"
                        aria-label="Close"></button>
                </div>
                <div class="offcanvas-body p-0">
                    <ul class="nav flex-column gap-2">

                        <!-- Dashboard -->
                        <li class="nav-item mb-1">
                            <a href="{{ route('dashboard') }}"
                                class="nav-link {{ request()->routeIs('dashboard') ? 'text-primary bg-light fw-bold' : 'text-dark opacity-75' }} px-3 py-2 d-flex align-items-center">
                                <i class="bi bi-grid-1x2-fill me-3"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>

                        @php
                            $unreadNotificationsCount = auth()->user()->unreadNotifications()->count();
                            $announcementsCount = \App\Models\Announcement::where('is_active', 1)->count();
                        @endphp

                        <!-- Notifications -->
                        <li class="nav-item mb-1">
                            <a href="{{ route('notifications.index') }}"
                                class="nav-link {{ request()->routeIs('notifications*') ? 'text-primary bg-light fw-bold' : 'text-dark opacity-75' }} px-3 py-2 d-flex align-items-center justify-content-between">

                                <div class="d-flex align-items-center">
                                    <i class="bi bi-bell me-3"></i>
                                    <span>Notifications</span>
                                </div>

                                @if ($unreadNotificationsCount > 0)
                                    <span class="badge bg-danger rounded-pill" style="font-size:10px; padding:4px 6px;">
                                        {{ $unreadNotificationsCount }}
                                    </span>
                                @endif
                            </a>
                        </li>

                        <!-- Bookings -->
                        <li class="nav-item mb-1">
                            <a href="{{ route('bookings.index') }}"
                                class="nav-link {{ request()->routeIs('bookings*') ? 'text-primary bg-light fw-bold' : 'text-dark opacity-75' }} px-3 py-2 d-flex align-items-center">
                                <i class="bi bi-calendar-event me-3"></i>
                                <span>Bookings</span>
                            </a>
                        </li>

                        <!-- Services -->
                        <li class="nav-item mb-1">
                            <a href="{{ route('services.index') }}"
                                class="nav-link {{ request()->routeIs('services*') ? 'text-primary bg-light fw-bold' : 'text-dark opacity-75' }} px-3 py-2 d-flex align-items-center">
                                <i class="bi bi-flower1 me-3"></i>
                                <span>Services</span>
                            </a>
                        </li>

                        <!-- Users -->
                        @if ($role === 'admin')
                            <li class="nav-item mb-1">
                                <a href="{{ route('users.index') }}"
                                    class="nav-link {{ request()->routeIs('users*') ? 'text-primary bg-light fw-bold' : 'text-dark opacity-75' }} px-3 py-2 d-flex align-items-center">
                                    <i class="bi bi-people me-3"></i>
                                    <span>Users</span>
                                </a>
                            </li>
                        @endif

                         <!-- Clients, Receptionists -->
                        @if ($role === 'owner')
                            <li class="nav-item mb-1">
                                <a href="{{ route('clients.index') }}"
                                    class="nav-link {{ request()->routeIs('clients*') ? 'text-primary bg-light fw-bold' : 'text-dark opacity-75' }} px-3 py-2 d-flex align-items-center">
                                    <i class="bi bi-people me-3"></i>
                                    <span>Clients</span>
                                </a>
                            </li>

                            <li class="nav-item mb-1">
                                <a href="{{ route('receptionists.index') }}"
                                    class="nav-link {{ request()->routeIs('receptionists*') ? 'text-primary bg-light fw-bold' : 'text-dark opacity-75' }} px-3 py-2 d-flex align-items-center">
                                    <i class="bi bi-person-workspace me-3"></i>
                                    <span>Receptionists</span>
                                </a>
                            </li>
                        @endif

                        <!-- Therapists -->
                        @if ($role === 'owner' || $role === 'receptionist')
                            <li class="nav-item mb-1">
                                <a href="{{ route('therapists.index') }}"
                                    class="nav-link {{ request()->routeIs('therapists*') ? 'text-primary bg-light fw-bold' : 'text-dark opacity-75' }} px-3 py-2 d-flex align-items-center">
                                    <i class="bi bi-person-hearts me-3"></i>
                                    <span>Therapists</span>
                                </a>
                            </li>
                        @endif

                        <!-- Announcements -->
                        <li class="nav-item mb-1">
                            <a href="{{ route('announcements.index') }}"
                                    class="nav-link {{ request()->routeIs('announcements*') ? 'text-primary bg-light fw-bold' : 'text-dark opacity-75' }} px-3 py-2 d-flex align-items-center">
                                    <i class="bi bi-megaphone me-3"></i>
                                    <span>Announcements</span>
                            </a>
                        </li>

                        <!-- Reviews -->
                        <li class="nav-item mb-1">
                            <a href="{{ route('reviews.index') }}"
                                    class="nav-link {{ request()->routeIs('reviews*') ? 'text-primary bg-light fw-bold' : 'text-dark opacity-75' }} px-3 py-2 d-flex align-items-center">
                                    <i class="bi bi-star me-3"></i>
                                    <span>Reviews</span>
                            </a>
                        </li>


                        @if ($role === 'admin' || $role === 'owner')
                            <li class="nav-item mb-1">
                            <a href="{{ route('reports.bookings') }}"
                                    class="nav-link {{ request()->routeIs('reports*') ? 'text-primary bg-light fw-bold' : 'text-dark opacity-75' }} px-3 py-2 d-flex align-items-center">
                                    <i class="bi bi-bar-chart me-3"></i>
                                    <span>Reports</span>
                            </a>
                        </li>
                        @endif

                        <!-- Activity Logs -->
                        <li class="nav-item mb-1">
                            <a href="{{ route('activity-logs.index') }}"
                                    class="nav-link {{ request()->routeIs('activity-logs*') ? 'text-primary bg-light fw-bold' : 'text-dark opacity-75' }} px-3 py-2 d-flex align-items-center">
                                    <i class="bi bi-clock-history me-3"></i>
                                    <span>Activity Logs</span>
                            </a>
                        </li>
                        
                    </ul>
                </div>
            </div>

            <!-- Content -->
            <div class="col-12 col-lg-10">

                <!-- Header -->
                <nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top border-bottom py-2 py-md-3">
                    <div class="container-fluid px-3 px-lg-5">

                        <h5 class="mb-0 fw-bold text-uppercase d-block d-lg-none">Rose Massage</h5>

                        <button class="navbar-toggler shadow-none border-0 bg-light" type="button"
                            data-bs-toggle="offcanvas" data-bs-target="#sidebar-mobile"
                            aria-controls="sidebarOffcanvas">
                            <span class="navbar-toggler-icon"></span>
                        </button>

                        <div class="collapse navbar-collapse" id="navbarMain">
                            <ul class="navbar-nav ms-auto mb-2 mb-lg-0 d-flex align-items-center gap-3">

                                @auth
                                    @php
                                        $unreadNotificationsCount = auth()->user()?->unreadNotifications()?->count();
                                        $announcementsCount = \App\Models\Announcement::where('is_active', 1)->count();
                                    @endphp

                                    <!-- Notifications -->
                                    <li class="nav-item position-relative">
                                        <a class="nav-link fs-5 d-flex align-items-center p-1 position-relative"
                                            href="{{ route('notifications.index') }}">

                                            <i class="bi bi-bell"></i>

                                            @if ($unreadNotificationsCount > 0)
                                                <span
                                                    class="position-absolute top-0 start-100 
                                                        translate-middle badge rounded-pill bg-danger"
                                                    style="font-size: 10px; padding: 4px 6px; min-width: 18px;">
                                                    {{ $unreadNotificationsCount }}
                                                </span>
                                            @endif
                                        </a>
                                    </li>

                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle d-flex align-items-center p-0 shadow-none border-0"
                                        href="#" role="button" data-bs-toggle="dropdown">
                                        @if (auth()->user()->profile?->avatar)
                                            <img src="{{ asset('storage/' . auth()->user()?->profile?->avatar) }}"
                                                alt="{{ auth()->user()->name }}" class="rounded-circle border"
                                                style="width: 32px; height: 32px; object-fit: cover;">
                                        @else
                                            <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=5f4be6&color=fff"
                                                alt="{{ auth()->user()->name }}" class="rounded-circle"
                                                style="width: 32px; height: 32px;">
                                        @endif
                                    </a>

                                    <ul class="dropdown-menu dropdown-menu-end border-0 shadow rounded mt-lg-3">
                                        <li class="dropdown-header text-dark">
                                            <h6>{{ auth()->user()->name }}</h6>
                                            <p class="text-primary mb-1">{{ ucfirst(auth()->user()?->role) }}</p>
                                        </li>
                                        <li>
                                            <a href="" class="dropdown-item">
                                                <i class="bi bi-person-circle me-1"></i> Profile
                                            </a>
                                        </li>
                                        <hr class="dropdown-divider">
                                        <li>
                                            <a href="#" class="dropdown-item text-danger"
                                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                                <i class="bi bi-box-arrow-right me-1"></i> Logout
                                            </a>

                                            <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                                class="d-none">
                                                @csrf
                                            </form>
                                        </li>
                                    </ul>

                                </li>
                                @endauth
                            </ul>
                        </div>

                    </div>
                </nav>

                <!-- Main -->
                <main>
                    <div class="container-fluid p-3 px-lg-5">
                        <!-- Breadcrumb -->
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="/" class="text-decoration-none">Home</a>
                                </li>

                                @hasSection('breadcrumb-parent')
                                    <li class="breadcrumb-item"><a href="@yield('breadcrumb-parent-url')"
                                            class="text-decoration-none">@yield('breadcrumb-parent')</a></li>
                                @endif
                                @hasSection('page-title')
                                    <li class="breadcrumb-item active" aria-current="page">@yield('page-title')</li>
                                @endif
                            </ol>
                        </nav>

                        <!-- Page Header Area -->
                        @hasSection('page-header')
                            <div
                                class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
                                <div>
                                    <!-- Index page header -->
                                    @hasSection('page-header-title-indexpage')
                                        <h3 class="fw-bold mb-1">@yield('page-header-title-indexpage')</h3>
                                    @endif
                                    <!-- Show page header -->
                                    @hasSection('page-header-title-showpage')
                                        <h4 class="fw-bold mb-1">@yield('page-header-title-showpage')</h4>
                                    @endif
                                    <!-- Page subtitle -->
                                    @hasSection('page-header-subtitle')
                                        <p class="text-muted mb-0">@yield('page-header-subtitle')</p>
                                    @endif
                                </div>

                                @hasSection('page-header-actions')
                                    <div class="d-flex gap-2">
                                        @yield('page-header-actions')
                                    </div>
                                @endif
                            </div>
                        @endif

                        <!-- Alerts -->
                        @include('partials.alerts')

                        <!-- Filter Area -->
                        @hasSection('filter-area')
                            <div class="card shadow-sm border mb-4">
                                <div class="card-body">
                                    @yield('filter-form')
                                </div>
                            </div>
                        @endif

                        @yield('content')
                    </div>
                </main>

            </div>

        </div>

    </div>

    @include('partials.scripts')
    @yield('page-scripts')

</body>

</html>
