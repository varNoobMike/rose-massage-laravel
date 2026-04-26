<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('page-title', 'Rose Massage Services')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootswatch@5.3.3/dist/pulse/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap"
        rel="stylesheet">
</head>

<style>
    body {
        font-family: 'Plus Jakarta Sans', sans-serif;
    }
</style>

@yield('page-styles')

<body>

    <!-- Wrapper -->
    <div class="container-fluid p-0" style="min-height: 100vh;">

        <div class="row g-0">

            <!-- Sidebar - Desktop -->
            <div class="col-2 bg-white border-end d-none d-lg-block min-vh-100">
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

                        <li class="nav-item mb-1">
                            <a href="{{ route('users.index') }}"
                                class="nav-link {{ request()->routeIs('users*') ? 'text-bg-primary fw-bold' : 'text-dark opacity-75' }} px-3 py-2 d-flex align-items-center">
                                <i class="bi bi-people me-3"></i>
                                <span>Users</span>
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
                        <li class="nav-item mb-1">
                            <a href="{{ route('dashboard') }}"
                                class="nav-link {{ request()->routeIs('dashboard') ? 'text-primary bg-light fw-bold' : 'text-dark opacity-75' }} px-3 py-2 d-flex align-items-center">
                                <i class="bi bi-grid-1x2-fill me-3"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>

                        <li class="nav-item mb-1">
                            <a href="{{ route('bookings.index') }}"
                                class="nav-link {{ request()->routeIs('bookings*') ? 'text-primary bg-light fw-bold' : 'text-dark opacity-75' }} px-3 py-2 d-flex align-items-center">
                                <i class="bi bi-calendar-event me-3"></i>
                                <span>Bookings</span>
                            </a>
                        </li>

                        <li class="nav-item mb-1">
                            <a href="{{ route('services.index') }}"
                                class="nav-link {{ request()->routeIs('services*') ? 'text-primary bg-light fw-bold' : 'text-dark opacity-75' }} px-3 py-2 d-flex align-items-center">
                                <i class="bi bi-flower1 me-3"></i>
                                <span>Services</span>
                            </a>
                        </li>

                        <li class="nav-item mb-1">
                            <a href="{{ route('users.index') }}"
                                class="nav-link {{ request()->routeIs('users*') ? 'text-primary bg-light fw-bold' : 'text-dark opacity-75' }} px-3 py-2 d-flex align-items-center">
                                <i class="bi bi-people me-3"></i>
                                <span>Users</span>
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

                        <h5 class="mb-0 fw-bold">Lorem</h5>

                        <button class="navbar-toggler shadow-none border-0 bg-light" type="button"
                            data-bs-toggle="offcanvas" data-bs-target="#sidebar-mobile"
                            aria-controls="sidebarOffcanvas">
                            <span class="navbar-toggler-icon"></span>
                        </button>

                        <div class="collapse navbar-collapse" id="navbarMain">
                            <ul class="navbar-nav ms-auto mb-2 mb-lg-0 d-flex align-items-center gap-3">

                                <li class="nav-item">
                                    <a class="nav-link fs-5 d-flex align-items-center p-1"
                                        href="{{ route('notifications.index') }}">
                                        <i class="bi bi-bell"></i>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link fs-5 d-flex align-items-center p-1"
                                        href="{{ route('announcements.index') }}">
                                        <i class="bi bi-megaphone"></i>
                                    </a>
                                </li>

                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle d-flex align-items-center p-0 shadow-none border-0"
                                        href="#" role="button" data-bs-toggle="dropdown">
                                        @if (auth()->user()->profile?->avatar)
                                            <img src="{{ asset('storage/' . auth()->user()->profile->avatar) }}"
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
                                            <p class="text-primary mb-1">{{ auth()->user()->role }}</p>
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

                        <!-- Alert -->
                        @if (session('success'))
                            <div class="col-12">
                                <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                                    {{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            </div>
                        @endif

                        @if (session('info'))
                            <div class="col-12">
                                <div class="alert alert-info alert-dismissible fade show shadow-sm" role="alert">
                                    {{ session('info') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            </div>
                        @endif

                        @if (session('warning'))
                            <div class="col-12">
                                <div class="alert alert-warning alert-dismissible fade show shadow-sm" role="alert">
                                    {{ session('warning') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="col-12">
                                <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                                    {{ session('error') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            </div>
                        @endif

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

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @yield('page-scripts')

</body>

</html>
