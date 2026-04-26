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

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <style>
        body {
            background: #f8f9fc;
            font-family: 'Inter', sans-serif;
        }

        h1,
        h2,
        h3,
        h4 {
            font-family: 'Cormorant Garamond';
        }
    </style>

    @yield('page-styles')
</head>

<body>

    <div class="container-fluid p-0">

        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top border-bottom py-3">
            <div class="container px-lg-5">

                <h5 class="mb-0 fw-bold text-uppercase">Rose</h5>

                <button class="navbar-toggler shadow-none border-0 bg-light" type="button" data-bs-toggle="offcanvas"
                    data-bs-target="#sidebar-mobile" aria-controls="sidebarOffcanvas">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarMain">

                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0 d-flex align-items-center gap-3 text-">

                        <!-- HOME -->
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('home') ? 'text-primary fw-bold border-bottom border-3 border-primary' : 'text-dark' }}"
                                href="{{ route('home') }}">
                                Home
                            </a>
                        </li>

                        <!-- SERVICES -->
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('services.*') ? 'text-primary fw-bold border-bottom border-3 border-primary' : 'text-dark' }}"
                                href="{{ route('services.index') }}">
                                Our Services
                            </a>
                        </li>

                        <!-- ABOUT -->
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('about.*') ? 'text-primary fw-bold border-bottom border-3 border-primary' : 'text-dark' }}"
                                href="{{ route('about.index') }}">
                                About Us
                            </a>
                        </li>

                        <!-- CONTACT -->
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('contact.*') ? 'text-primary fw-bold border-bottom border-3 border-primary' : 'text-dark' }}"
                                href="{{ route('contact.index') }}">
                                Contact Us
                            </a>
                        </li>

                        <!-- BOOK NOW -->
                        <li class="nav-item ms-lg-2">
                            <a class="btn btn-primary shadow-sm px-3 py-2" href="{{ route('bookings.create') }}">
                                Book Now
                            </a>
                        </li>

                        @auth
                            <!-- MY BOOKINGS -->
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('bookings.*') ? 'text-primary fw-bold border-bottom border-3 border-primary' : 'text-dark' }}"
                                    href="{{ route('bookings.index') }}">
                                    My Bookings
                                </a>
                            </li>
                            @php
                                $unreadNotificationsCount = auth()->user()->unreadNotifications()->count();
                                $announcementsCount = \App\Models\Announcement::count();
                            @endphp

                            <!-- Notifications -->
                            <li class="nav-item position-relative">
                                <a class="nav-link fs-5 position-relative
        {{ request()->routeIs('notifications.*') ? 'text-primary fw-bold border-bottom border-3 border-primary' : 'text-dark' }}"
                                    href="{{ route('notifications.index') }}">

                                    <i class="bi bi-bell"></i>

                                    @if ($unreadNotificationsCount > 0)
                                        <span
                                            class="position-absolute top-0 start-100 translate-middle badge bg-danger rounded-pill"
                                            style="font-size:10px; padding:3px 5px; min-width:18px;">
                                            {{ $unreadNotificationsCount }}
                                        </span>
                                    @endif
                                </a>
                            </li>

                            <!-- Announcements -->
                            <li class="nav-item position-relative">
                                <a class="nav-link fs-5 position-relative
        {{ request()->routeIs('announcements.*') ? 'text-primary fw-bold border-bottom border-3 border-primary' : 'text-dark' }}"
                                    href="{{ route('announcements.index') }}">

                                    <i class="bi bi-megaphone"></i>

                                    @if ($announcementsCount > 0)
                                        <span
                                            class="position-absolute top-0 start-100 translate-middle badge bg-danger rounded-pill"
                                            style="font-size:10px; padding:3px 5px; min-width:18px;">
                                            {{ $announcementsCount }}
                                        </span>
                                    @endif
                                </a>
                            </li>
                        @endauth

                        <!-- GUEST -->
                        @guest
                            <li class="nav-item ms-lg-2">
                                <a class="btn btn-dark shadow-sm px-4 py-2" href="{{ route('login') }}">
                                    Login
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="btn btn-outline-dark shadow-sm px-4 py-2" href="{{ route('register') }}">
                                    Register
                                </a>
                            </li>
                        @endguest

                        <!-- AUTH -->
                        @auth
                            <li class="nav-item dropdown ms-lg-2">

                                <!-- AVATAR BUTTON -->
                                <a class="nav-link dropdown-toggle d-flex align-items-center gap-2 p-1" href="#"
                                    role="button" data-bs-toggle="dropdown" aria-expanded="false">

                                    <!-- Avatar image or fallback -->
                                    <img src="{{ Auth::user()->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) }}"
                                        class="rounded-circle" width="32" height="32" alt="avatar">

                                </a>

                                <!-- DROPDOWN -->
                                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">

                                    <!-- USER INFO -->
                                    <li class="px-3 py-2">
                                        <div class="fw-bold">{{ Auth::user()->name }}</div>
                                        <small class="text-muted">{{ Auth::user()->email }}</small>
                                    </li>

                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>

                                    <!-- VIEW PROFILE -->
                                    <li>
                                        <a class="dropdown-item" href="">
                                            <i class="bi bi-person me-2"></i> View Profile
                                        </a>
                                    </li>

                                    <!-- BOOKINGS (optional if you want inside dropdown too) -->
                                    <li>
                                        <a class="dropdown-item" href="{{ route('bookings.index') }}">
                                            <i class="bi bi-calendar-event me-2"></i> My Bookings
                                        </a>
                                    </li>

                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>

                                    <!-- LOGOUT -->
                                    <li>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button class="dropdown-item text-danger" type="submit">
                                                <i class="bi bi-box-arrow-right me-2"></i> Logout
                                            </button>
                                        </form>
                                    </li>

                                </ul>
                            </li>
                        @endauth

                    </ul>

                </div>

            </div>
        </nav>

        <!-- Sidebar - Mobile -->
        <div class="offcanvas offcanvas-start" tabindex="-1" id="sidebar-mobile"
            aria-labelledby="sidebarOffcanvasLabel">

            <div class="offcanvas-header border-bottom">
                <h5 class="offcanvas-title fw-bold" id="sidebarOffcanvasLabel">MENU</h5>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="offcanvas"></button>
            </div>

            <div class="offcanvas-body p-0">

                <ul class="nav flex-column">

                    <!-- HOME -->
                    <li class="nav-item">
                        <a href="{{ route('home') }}"
                            class="nav-link px-3 py-3 d-flex align-items-center
                {{ request()->routeIs('home') ? 'text-primary fw-bold bg-light' : 'text-dark' }}">
                            <i class="bi bi-house me-3"></i> Home
                        </a>
                    </li>

                    <!-- SERVICES -->
                    <li class="nav-item">
                        <a href="{{ route('services.index') }}"
                            class="nav-link px-3 py-3 d-flex align-items-center
                {{ request()->routeIs('services.*') ? 'text-primary fw-bold bg-light' : 'text-dark' }}">
                            <i class="bi bi-flower1 me-3"></i> Our Services
                        </a>
                    </li>

                    <!-- ABOUT -->
                    <li class="nav-item">
                        <a href="{{ route('about.index') }}"
                            class="nav-link px-3 py-3 d-flex align-items-center
                {{ request()->routeIs('about.*') ? 'text-primary fw-bold bg-light' : 'text-dark' }}">
                            <i class="bi bi-info-circle me-3"></i> About Us
                        </a>
                    </li>

                    <!-- CONTACT -->
                    <li class="nav-item">
                        <a href="{{ route('contact.index') }}"
                            class="nav-link px-3 py-3 d-flex align-items-center
                {{ request()->routeIs('contact.*') ? 'text-primary fw-bold bg-light' : 'text-dark' }}">
                            <i class="bi bi-envelope me-3"></i> Contact Us
                        </a>
                    </li>

                    @auth

                        <!-- BOOKINGS -->
                        <li class="nav-item">
                            <a href="{{ route('bookings.index') }}"
                                class="nav-link px-3 py-3 d-flex align-items-center
                    {{ request()->routeIs('bookings.*') ? 'text-primary fw-bold bg-light' : 'text-dark' }}">
                                <i class="bi bi-calendar-event me-3"></i> My Bookings
                            </a>
                        </li>

                        @php
                            $unreadNotificationsCount = auth()->user()->unreadNotifications()->count();
                            $announcementsCount = \App\Models\Announcement::count();
                        @endphp

                        <!-- NOTIFICATIONS -->
                        <li class="nav-item">
                            <a href="{{ route('notifications.index') }}"
                                class="nav-link px-3 py-3 d-flex align-items-center justify-content-between
        {{ request()->routeIs('notifications.*') ? 'text-primary fw-bold bg-light' : 'text-dark' }}">

                                <div class="d-flex align-items-center">
                                    <i class="bi bi-bell me-3"></i>
                                    <span>Notifications</span>
                                </div>

                                @if ($unreadNotificationsCount > 0)
                                    <span class="badge bg-danger rounded-pill"
                                        style="font-size:10px; padding:4px 6px; min-width:18px;">
                                        {{ $unreadNotificationsCount }}
                                    </span>
                                @endif
                            </a>
                        </li>

                        <!-- ANNOUNCEMENTS -->
                        <li class="nav-item">
                            <a href="{{ route('announcements.index') }}"
                                class="nav-link px-3 py-3 d-flex align-items-center justify-content-between
        {{ request()->routeIs('announcements.*') ? 'text-primary fw-bold bg-light' : 'text-dark' }}">

                                <div class="d-flex align-items-center">
                                    <i class="bi bi-megaphone me-3"></i>
                                    <span>Announcements</span>
                                </div>

                                @if ($announcementsCount > 0)
                                    <span class="badge bg-danger rounded-pill"
                                        style="font-size:10px; padding:4px 6px; min-width:18px;">
                                        {{ $announcementsCount }}
                                    </span>
                                @endif
                            </a>
                        </li>

                        <hr class="my-2">

                        <!-- USER sILE DROPDOWN (MOBILE) -->
                        <li class="nav-item dropdown px-3">

                            <a class="nav-link d-flex align-items-center gap-2 p-2 bg-light rounded" href="#"
                                role="button" data-bs-toggle="dropdown">

                                <img src="{{ Auth::user()->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) }}"
                                    class="rounded-circle" width="32" height="32">

                                <div class="d-flex flex-column text-start">
                                    <small class="fw-bold">{{ Auth::user()->name }}</small>
                                    <small class="text-muted" style="font-size: 12px;">
                                        {{ Auth::user()->email }}
                                    </small>
                                </div>

                            </a>

                            <ul class="dropdown-menu shadow-sm border-0 w-100">

                                <li>
                                    <a class="dropdown-item" href="">
                                        <i class="bi bi-person me-2"></i> View Profile
                                    </a>
                                </li>

                                <li>
                                    <a class="dropdown-item" href="{{ route('bookings.index') }}">
                                        <i class="bi bi-calendar-event me-2"></i> My Bookings
                                    </a>
                                </li>

                                <li>
                                    <hr class="dropdown-divider">
                                </li>

                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button class="dropdown-item text-danger" type="submit">
                                            <i class="bi bi-box-arrow-right me-2"></i> Logout
                                        </button>
                                    </form>
                                </li>

                            </ul>

                        </li>

                    @endauth

                    <hr class="my-2">

                    <!-- BOOK NOW -->
                    <li class="nav-item px-3">
                        <a class="btn btn-primary w-100 mb-2" href="{{ route('bookings.create') }}">
                            Book Now
                        </a>
                    </li>

                    @guest

                        <!-- LOGIN -->
                        <li class="nav-item px-3">
                            <a class="btn btn-dark w-100 mb-2" href="{{ route('login') }}">
                                Login
                            </a>
                        </li>

                        <!-- REGISTER -->
                        <li class="nav-item px-3">
                            <a class="btn btn-outline-dark w-100" href="{{ route('register') }}">
                                Register
                            </a>
                        </li>

                    @endguest

                </ul>

            </div>

        </div>

        <!-- Main -->
        <main class="pb-5">
            <!-- Breadcrumb -->
            @hasSection('breadcrumb')
                <div class="container px-lg-5 py-3 py-lg-4">
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
                </div>
            @endif

            <!-- Page Header Area -->
            @hasSection('page-header')
                <div class="container px-lg-5">
                    <div
                        class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
                        <div>
                            <!-- Page header -->
                            @hasSection('page-header-title')
                                <h1 class="fw-bold display-6 mb-0">@yield('page-header-title')</h1>
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
                </div>
            @endif

            <!-- Filter Area -->
            @hasSection('filter-area')
                <div class="container px-lg-5">
                    <div class="card shadow-sm border mb-4">
                        <div class="card-body">
                            @yield('filter-form')
                        </div>
                    </div>
                </div>
            @endif

            <div class="container px-lg-5">
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
            </div>

            @yield('content')
        </main>


    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @yield('page-scripts')

</body>

</html>
