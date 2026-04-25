<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('page-title', 'Rose Massage Services')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootswatch@5.3.3/dist/pulse/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap"
          rel="stylesheet">

    <style>
        body {
            background: #f8f9fc;
            font-family: 'Plus Jakarta Sans', sans-serif;
            overflow-x: hidden;
        }

        .icon-btn {
            position: relative;
            font-size: 1.2rem;
        }

        .badge-dot {
            position: absolute;
            top: -5px;
            right: -8px;
            font-size: 10px;
        }
    </style>

    @yield('page-styles')
</head>

<body>

<!-- ================= OFFCANVAS ================= -->
<div class="offcanvas offcanvas-start" tabindex="-1" id="mobileSidebar">

    <div class="offcanvas-header">
        <h5 class="fw-bold text-primary mb-0">ROSE.</h5>
        <button class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>

    <div class="offcanvas-body">

        <div class="list-group mb-3">
            <a href="/" class="list-group-item">Home</a>
            <a href="{{ route('services.index') }}" class="list-group-item">Rituals</a>
            <a href="{{ route('bookings.index') }}" class="list-group-item">Reservations</a>
        </div>

        @auth

        <h6 class="fw-bold mt-3">Notifications</h6>
        <div class="list-group mb-3">
            @forelse(auth()->user()->notifications->take(5) as $notif)
                <a href="#" class="list-group-item small">
                    {{ $notif->data['message'] ?? 'New notification' }}
                </a>
            @empty
                <div class="text-muted small p-2">No notifications</div>
            @endforelse
        </div>

        <h6 class="fw-bold">Announcements</h6>
        <div class="list-group">
            <div class="list-group-item small">🎉 Promo this week</div>
            <div class="list-group-item small">📅 Updated schedule</div>
        </div>

        @endauth

    </div>
</div>

<!-- ================= NAVBAR ================= -->
<nav class="navbar navbar-expand-lg bg-white shadow-sm sticky-top">
    <div class="container">

        <!-- BRAND -->
        <a class="navbar-brand fw-bold text-primary" href="/">ROSE.</a>

        <!-- ONLY ONE MOBILE TOGGLER (OFFCANVAS) -->
        <button class="btn btn-outline-primary d-lg-none ms-auto me-2"
                data-bs-toggle="offcanvas"
                data-bs-target="#mobileSidebar">
            <i class="bi bi-list"></i>
        </button>

        <!-- NAV COLLAPSE (desktop menu) -->
        <button class="navbar-toggler" type="button"
                data-bs-toggle="collapse"
                data-bs-target="#nav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="nav">

            <!-- CENTER LINKS -->
            <ul class="navbar-nav mx-auto">
                <li class="nav-item"><a class="nav-link" href="/">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('services.index') }}">Rituals</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('bookings.index') }}">Reservations</a></li>
            </ul>

            <!-- RIGHT SIDE -->
            <div class="d-flex align-items-center gap-3">

                @auth

                <!-- ANNOUNCEMENTS -->
                <div class="d-none d-lg-block position-relative">

                    @php
                        $announcementCount = \App\Models\Announcement::where('is_active', true)->count();
                    @endphp

                    <a href="{{ route('announcements.index') }}"
                    class="icon-btn nav-link">

                        <i class="bi bi-megaphone"></i>

                        @if($announcementCount > 0)
                            <span class="badge bg-danger badge-dot">
                                {{ $announcementCount }}
                            </span>
                        @endif

                    </a>

                </div>

                <!-- NOTIFICATIONS -->
                <div class="d-none d-lg-block position-relative">

                    <a href="{{ route('notifications.index') }}" class="icon-btn nav-link">
                        <i class="bi bi-bell"></i>

                        @php
                            $unreadCount = auth()->user()->unreadNotifications->count();
                        @endphp

                        @if($unreadCount > 0)
                            <span class="badge bg-danger badge-dot">
                                {{ $unreadCount }}
                            </span>
                        @endif
                    </a>

                </div>

                <!-- BOOK -->
                <a href="{{ route('bookings.create') }}"
                   class="btn btn-primary d-none d-lg-block">
                    Book
                </a>

                <!-- PROFILE -->
                <div class="dropdown">
                    <a class="d-flex align-items-center gap-2 nav-link dropdown-toggle"
                       data-bs-toggle="dropdown">

                        <div class="rounded-circle bg-primary text-white d-flex justify-content-center align-items-center"
                             style="width:32px;height:32px;">
                            {{ strtoupper(substr(Auth::user()->name ?? 'U',0,1)) }}
                        </div>

                        <span class="d-none d-lg-inline">
                            {{ Auth::user()->name }}
                        </span>

                    </a>

                    <ul class="dropdown-menu dropdown-menu-end">
                        <li class="px-3 py-2 small text-muted">
                            {{ Auth::user()->email }}
                        </li>
                        <li><hr class="dropdown-divider"></li>

                        <li><a class="dropdown-item" href="#">Profile</a></li>

                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button class="dropdown-item text-danger">Logout</button>
                            </form>
                        </li>
                    </ul>
                </div>

                @endauth

            </div>

        </div>
    </div>
</nav>

<!-- ================= CONTENT ================= -->
<!-- Breadcrumb -->
<div class="bg-white border-bottom py-2">
    <div class="container">

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 bg-transparent p-0">

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

    </div>
</div>
<main class="py-5">
    <div class="container">
        @yield('content')
    </div>
</main>

<!-- ================= FOOTER ================= -->
<footer class="border-top py-4">
    <div class="container text-center text-muted small">
        © {{ date('Y') }} ROSE MASSAGE SERVICES
    </div>
</footer>

<!-- ================= SCRIPTS ================= -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

@yield('page-scripts')

</body>
</html>