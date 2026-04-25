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
    </style>
    @yield('page-styles')
</head>
<body>

    <nav class="navbar navbar-expand-lg sticky-top bg-white shadow-sm">
        <div class="container">

            <!-- Brand -->
            <a class="navbar-brand fw-bold text-primary" href="/">
                ROSE.
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">

                <!-- CENTER LINKS -->
                <ul class="navbar-nav mx-auto align-items-center">
                    <li class="nav-item">
                        <a class="nav-link px-3" href="/">Home</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link px-3" href="{{ route('services.index') }}">Rituals</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link px-3" href="{{ route('bookings.index') }}">Reservations</a>
                    </li>
                </ul>

                <!-- RIGHT SIDE -->
                <ul class="navbar-nav align-items-center gap-2">

                    @guest
                        <!-- LOGIN -->
                        <li class="nav-item">
                            <a class="btn btn-outline-primary px-3" href="{{ route('login') }}">
                                Login
                            </a>
                        </li>
                    @endguest

                    @auth
                        <!-- BOOK BUTTON -->
                        <li class="nav-item">
                            <a class="btn btn-primary px-3" href="{{ route('bookings.create') }}">
                                Book Now
                            </a>
                        </li>

                        <!-- PROFILE DROPDOWN -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center gap-2"
                            href="#"
                            role="button"
                            data-bs-toggle="dropdown">

                                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center"
                                    style="width:32px;height:32px;font-size:14px;">
                                    {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
                                </div>

                                <span class="fw-semibold">
                                    {{ Auth::user()->name ?? 'User' }}
                                </span>

                            </a>

                            <ul class="dropdown-menu dropdown-menu-end shadow-sm">

                                <li class="px-3 py-2">
                                    <div class="small text-muted">Signed in as</div>
                                    <div class="fw-bold">{{ Auth::user()->email }}</div>
                                </li>

                                <li><hr class="dropdown-divider"></li>

                                <li>
                                    <a class="dropdown-item" href="#">
                                        <i class="bi bi-person me-2"></i> Profile
                                    </a>
                                </li>

                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button class="dropdown-item text-danger">
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

    <main id="content" class="py-5">
        <div class="container">
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
    </main>

    <footer>
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-6">
                    <h2 class="h4 fw-bold mb-3">Rose Wellness.</h2>
                    <p class="body-text small text-muted">
                        Modern restoration for the digital age. Located in San Juan, Siquijor.
                    </p>
                </div>
                <div class="col-lg-3 col-6">
                    <h6 class="fw-bold text-uppercase mb-3">Sitemap</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="{{ route('services.index') }}" class="footer-link">Our Rituals</a></li>
                        <li class="mb-2"><a href="#" class="footer-link">Support</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-6 text-lg-end">
                    <h6 class="fw-bold text-uppercase mb-3">Connect</h6>
                    <div class="d-flex justify-content-lg-end gap-3">
                        <a href="#" class="text-dark fs-4" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
                        <a href="#" class="text-dark fs-4" aria-label="Facebook"><i class="bi bi-facebook"></i></a>
                    </div>
                </div>
            </div>
            <div class="mt-5 pt-4 border-top text-center">
                <p class="text-muted small">
                    © {{ date('Y') }} ROSE WELLNESS SANCTUARY
                </p>
            </div>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    @yield('page-scripts')
</body>
</html>