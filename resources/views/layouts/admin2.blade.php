<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('page-title', 'Rose Massage Services')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootswatch@5.3.3/dist/pulse/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --sidebar-width: 260px;
            --topbar-height: 70px;
            --sidebar-bg: #ffffff;
            --body-bg: #f9fafb;
            --accent-color: #5f4be6; /* Pulse Primary */
            --border-color: #f1f3f5;
            --text-main: #1a202c;
            --text-muted: #718096;
        }

        body {
            background-color: var(--body-bg);
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: var(--text-main);
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
        }

        /* --- SIDEBAR --- */
        .sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1050;
            background: var(--sidebar-bg);
            border-right: 1px solid var(--border-color);
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .sidebar-logo {
            height: var(--topbar-height);
            display: flex;
            align-items: center;
            padding: 0 1.5rem;
            border-bottom: 1px solid var(--border-color);
        }

        .nav-group { padding: 1.25rem 0.75rem; }

        .nav-link {
            color: var(--text-muted);
            padding: 0.75rem 1rem;
            margin-bottom: 0.25rem;
            border-radius: 8px;
            font-weight: 500;
            font-size: 0.925rem;
            display: flex;
            align-items: center;
            transition: all 0.2s ease;
        }

        .nav-link i { 
            font-size: 1.2rem; 
            margin-right: 12px;
            transition: transform 0.2s;
        }

        .nav-link:hover {
            background: #f8f9fa;
            color: var(--accent-color);
        }

        .nav-link:hover i { transform: translateX(3px); }

        .nav-link.active {
            background: var(--accent-color) !important;
            color: #ffffff !important;
            box-shadow: 0 4px 12px rgba(95, 75, 230, 0.2);
        }

        /* --- LAYOUT WRAPPER --- */
        .main-wrapper {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: margin-left 0.3s ease;
        }

        .top-navbar {
            height: var(--topbar-height);
            position: fixed;
            top: 0;
            right: 0;
            left: var(--sidebar-width);
            z-index: 1040;
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border-color);
            transition: left 0.3s ease;
        }

      

        /* --- RESPONSIVE BREAKPOINTS --- */
        @media (min-width: 992px) {
            .content-body { padding: 2rem 2.5rem; } /* Professional Desktop Spacing */
        }

        @media (max-width: 991.98px) {
            .sidebar { transform: translateX(-100%); }
            .main-wrapper { margin-left: 0; }
            .top-navbar { left: 0; }
        }

        /* --- UI ELEMENTS --- */
        .btn-icon {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            background: #fff;
            border: 1px solid var(--border-color);
            color: var(--text-muted);
            transition: all 0.2s;
        }

        .btn-icon:hover {
            background: var(--accent-color);
            color: #fff;
            border-color: var(--accent-color);
        }

        .avatar-wrapper {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            overflow: hidden;
            border: 2px solid #fff;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }

    </style>
</head>
<body>

<aside class="sidebar d-none d-lg-block">
    <div class="sidebar-logo">
        <span class="fw-bold fs-5 d-flex align-items-center gap-2">
            <i class="bi bi-flower1 text-primary fs-3"></i>
            <span class="tracking-tight">Rose Admin</span>
        </span>
    </div>

    <div class="nav-group">
        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="bi bi-grid-1x2"></i> Dashboard
        </a>

        @if(auth()->user()->role === 'admin')
            <a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.index') ? 'active' : '' }}">
                <i class="bi bi-people"></i> Users
            </a>
        @endif

        <a href="{{ route('bookings.index') }}" class="nav-link {{ request()->routeIs('bookings*') ? 'active' : '' }}">
            <i class="bi bi-calendar-event"></i> Bookings
        </a>

        <a href="{{ route('services.index') }}" class="nav-link {{ request()->routeIs('services.*') ? 'active' : '' }}">
            <i class="bi bi-stars"></i> Services
        </a>

        @if(auth()->user()->role === 'owner')
            <a href="{{ route('receptionists.index') }}" class="nav-link {{ request()->routeIs('receptionists.*') ? 'active' : '' }}">
                <i class="bi bi-person-vcard"></i> Receptionists
            </a>
        @endif

        @if(auth()->user()->role === 'owner' || auth()->user()->role === 'receptionist')
            <a href="{{ route('therapists.index') }}" class="nav-link {{ request()->routeIs('therapists*') ? 'active' : '' }}">
                <i class="bi bi-heart-pulse"></i> Therapists
            </a>
        @endif
    </div>
</aside>

<div class="main-wrapper">
    
    <header class="top-navbar d-flex align-items-center">
        <div class="container-fluid px-3 px-lg-4 d-flex align-items-center justify-content-between">
            
            <div class="d-flex align-items-center gap-3">
                <button class="btn-icon d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileMenu">
                    <i class="bi bi-list fs-4"></i>
                </button>
                <div class="d-none d-md-block">
                    <h5 class="mb-0 fw-bold">@yield('page-title', 'Overview')</h5>
                </div>
            </div>

            <div class="d-flex align-items-center gap-2 gap-md-3">
                <a href="{{ route('announcements.index') }}" class="btn-icon position-relative">
                    <i class="bi bi-megaphone"></i>
                    @php $aCount = \App\Models\Announcement::where('is_active', true)->count(); @endphp
                    @if($aCount > 0)
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-primary border border-white" style="font-size: 0.5rem;">
                            {{ $aCount }}
                        </span>
                    @endif
                </a>

                <a href="{{ route('notifications.index') }}" class="btn-icon position-relative">
                    <i class="bi bi-bell"></i>
                    @php $nCount = auth()->user()->unreadNotifications->count(); @endphp
                    @if($nCount > 0)
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger border border-white" style="font-size: 0.5rem;">
                            {{ $nCount }}
                        </span>
                    @endif
                </a>

                <div class="vr mx-2 text-black-50 opacity-25 d-none d-md-block" style="height: 30px;"></div>

                <div class="dropdown">
                    <div class="d-flex align-items-center gap-2 ps-2 cursor-pointer" data-bs-toggle="dropdown">
                        <div class="text-end d-none d-md-block lh-1">
                            <p class="mb-1 small fw-bold">{{ auth()->user()->name }}</p>
                            <p class="mb-0 text-muted" style="font-size: 0.7rem;">{{ ucfirst(auth()->user()->role) }}</p>
                        </div>
                        <div class="avatar-wrapper">
                            @if(auth()->user()->profile?->avatar)
                                <img src="{{ asset('storage/' . auth()->user()->profile->avatar) }}" class="w-100 h-100 object-fit-cover">
                            @else
                                <div class="w-100 h-100 bg-primary d-flex align-items-center justify-content-center text-white">
                                    <i class="bi bi-person-fill"></i>
                                </div>
                            @endif
                        </div>
                    </div>
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 mt-2 p-2" style="min-width: 200px;">
                        <li><a class="dropdown-item rounded py-2" href="#"><i class="bi bi-person me-2"></i> Profile</a></li>
                        <li><a class="dropdown-item rounded py-2" href="#"><i class="bi bi-gear me-2"></i> Settings</a></li>
                        <li><hr class="dropdown-divider opacity-50"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button class="dropdown-item text-danger rounded py-2"><i class="bi bi-box-arrow-right me-2"></i> Logout</button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </header>

    <main class="content-body">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="/" class="text-decoration-none text-muted small fw-medium">Home</a></li>
                @hasSection('breadcrumb-parent')
                    <li class="breadcrumb-item small fw-medium text-muted">@yield('breadcrumb-parent')</li>
                @endif
                <li class="breadcrumb-item active small fw-bold text-primary">@yield('page-title', 'Dashboard')</li>
            </ol>
        </nav>

        <div class="fade-in">
            @yield('content')
        </div>
    </main>
</div>

<div class="offcanvas offcanvas-start" tabindex="-1" id="mobileMenu" style="width: 280px; border-right: none;">
    <div class="offcanvas-header border-bottom">
        <h5 class="offcanvas-title fw-bold d-flex align-items-center gap-2">
            <i class="bi bi-flower1 text-primary"></i> Rose Admin
        </h5>
        <button type="button" class="btn-close shadow-none" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body p-2">
        <nav class="nav flex-column">
            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-1x2"></i> Dashboard
            </a>
            @if(auth()->user()->role === 'admin')
                <a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.index') ? 'active' : '' }}">
                    <i class="bi bi-people"></i> Users
                </a>
            @endif
            <a href="{{ route('bookings.index') }}" class="nav-link {{ request()->routeIs('bookings*') ? 'active' : '' }}">
                <i class="bi bi-calendar-event"></i> Bookings
            </a>
            <a href="{{ route('services.index') }}" class="nav-link {{ request()->routeIs('services.*') ? 'active' : '' }}">
                <i class="bi bi-stars"></i> Services
            </a>
            @if(auth()->user()->role === 'owner')
                <a href="{{ route('receptionists.index') }}" class="nav-link {{ request()->routeIs('receptionists.*') ? 'active' : '' }}">
                    <i class="bi bi-person-vcard"></i> Receptionists
                </a>
            @endif
            @if(auth()->user()->role === 'owner' || auth()->user()->role === 'receptionist')
                <a href="{{ route('therapists.index') }}" class="nav-link {{ request()->routeIs('therapists*') ? 'active' : '' }}">
                    <i class="bi bi-heart-pulse"></i> Therapists
                </a>
            @endif
        </nav>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@yield('page-scripts')

</body>
</html>