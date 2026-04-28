<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Welcome') | Rose Spa</title>

    @include('partials.styles')
    
    <!-- Font Plus Jakarta Sans -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

</head>

<style>
    body {
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    #auth-left-image {
        background: linear-gradient(135deg, rgba(89, 49, 150, 0.95), rgba(15, 23, 42, 0.95)),
                        url("{{ asset('images/hero-bg-0.jpg') }}");
        background-size: cover;
        background-position: center;
    }

</style>

@yield('page-styles')

<body>

    <div id="auth-wrapper">
        <div class="row g-0 bg-light min-vh-100">
            <div id="auth-left-image" class="d-md-flex flex-column align-items-center justify-content-center d-none text-white col-lg-8">
                <h1 class="display-3 text-uppercase fw-bold mb-0">Rose</h1>
                <p class="mb-0 lead">Massage Services</p>
            </div>
            <div class="col-lg-4">
                <div class="container p-5">
                    <div class="row d-flex justify-content-center align-items-center mt-5">
                         @yield('content')
                    </div>
                </div>
            </div>
        </div>

   </div>

    @include('partials.scripts')

</body>
</html>