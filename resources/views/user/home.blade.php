@extends('layouts.user')

@section('title', 'Rose Sanctuary | A Journey to Peace')

@section('page-styles')
    <style>
        #hero {
            position: relative;
            min-height: 75vh;
            background: linear-gradient(rgba(93, 68, 107, 0.5), rgba(45, 41, 48, 0.6)),
                url('https://images.unsplash.com/photo-1544161515-4ab6ce6db874?auto=format&fit=crop&q=80&w=2000');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }
    </style>
@endsection

@section('content')
    <!-- Hero -->
    <section id="hero" class="d-flex align-items-center justify-content-center">
        <div class="container text-center text-white">
            <h1 class="display-2 fw-bold">Relax, Refresh, Rejuvenate</h1>
            <p class="lead">Lorem ipsum dolor sit amet.</p>
        </div>
    </section>

    <!-- Services -->
    <section class="pt-5 pb-5">

        <div class="pt-3 container px-lg-5">

            <!-- Header -->
            <div class="text-center mb-4 mb-lg-5">
                <h1 class="fw-bold display-5 mb-0">Our Services</h1>
                <p class="text-muted mb-0">Browse our massage spa offers</p>
            </div>

            <!-- Card Grid -->
            <div class="row g-4 justify-content-center">

                @forelse($services as $service)
                    <div class="col-12 col-md-6 col-lg-4">

                        <div class="card h-100 border-0 shadow-sm overflow-hidden">

                            <!-- IMAGE -->
                            <div class="position-relative">

                                <img src="{{ asset('storage/' . $service->image) }}" alt="{{ $service->name }}"
                                    class="w-100" style="height: 260px; object-fit: cover;">

                                <!-- DURATION BADGE -->
                                <span class="badge bg-dark position-absolute top-0 end-0 m-3 px-3 py-2">
                                    ⏱ {{ $service->duration_minutes }} mins
                                </span>

                            </div>

                            <!-- BODY -->
                            <div class="card-body p-4 text-center">

                                <!-- NAME -->
                                <h5 class="fw-semibold mb-2">
                                    {{ $service->name }}
                                </h5>

                                <!-- PRICE -->
                                <div class="mb-3">
                                    <span class="text-primary fw-bold fs-4">
                                        ₱{{ number_format($service->price, 2) }}
                                    </span>

                                    <small class="text-muted d-block">
                                        per session
                                    </small>
                                </div>

                                <!-- CTA -->
                                <a href="{{ route('bookings.create', ['service' => $service->id]) }}"
                                    class="btn btn-primary w-100 fw-semibold">
                                    Book Now
                                </a>

                            </div>

                        </div>

                    </div>

                @empty
                    <div class="d-flex flex-column justify-content-center align-items-center">
                        {{-- EMPTY DATABASE --}}
                        <i class="bi bi-flower2 fs-1 text-muted"></i>
                        <h5 class="mt-3">No services yet</h5>
                        <p class="text-muted mb-0">
                            No services available yet.
                        </p>
                    </div>
                @endforelse

            </div>

            <!-- VIEW ALL CTA -->
            <div class="text-center mt-4 mt-lg-5">

                <a href="{{ route('services.index') }}" class="btn btn-primary shadow-sm px-4 py-2">

                    View All Services
                    <i class="bi bi-arrow-right ms-2"></i>

                </a>

            </div>

        </div>

    </section>

    <!-- Why Choose Us -->
    <section class="pt-5 pb-5">

        <div class="pt-5 container px-lg-5">

            <!-- Header -->
            <div class="text-center mb-4 mb-lg-5">
                <h2 class="fw-bold display-5 mb-0">Why Choose Us</h2>
                <p class="text-muted mb-0">
                    Professional care designed for your body and mind
                </p>
            </div>

            <!-- Card Grid -->
            <div class="row g-4 justify-content-center">

                <!-- Card 1 -->
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card h-100 border-0 shadow-sm text-center p-4">

                        <i class="bi bi-person-check fs-1 text-primary mb-3"></i>

                        <h5 class="fw-semibold mb-2">Expert Massage Therapists</h5>

                        <p class="text-muted mb-0">
                            Skilled professionals trained to deliver safe and effective massage techniques.
                        </p>

                    </div>
                </div>

                <!-- Card 2 -->
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card h-100 border-0 shadow-sm text-center p-4">

                        <i class="bi bi-flower1 fs-1 text-primary mb-3"></i>

                        <h5 class="fw-semibold mb-2">Premium Oils & Products</h5>

                        <p class="text-muted mb-0">
                            We use high-quality natural oils that nourish your skin and enhance relaxation.
                        </p>

                    </div>
                </div>

                <!-- Card 3 -->
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card h-100 border-0 shadow-sm text-center p-4">

                        <i class="bi bi-house-heart fs-1 text-primary mb-3"></i>

                        <h5 class="fw-semibold mb-2">Relaxing Spa Environment</h5>

                        <p class="text-muted mb-0">
                            A calm, clean, and peaceful space designed to help you fully unwind.
                        </p>

                    </div>
                </div>

            </div>

        </div>

    </section>

    <!-- How It Works -->
    <section class="pt-5 pb-5 bg-light">

        <div class="pt-5 container px-lg-5">

            <!-- Header -->
            <div class="text-center mb-4 mb-lg-5">
                <h2 class="fw-bold display-5 mb-0">How It Works</h2>
                <p class="text-muted mb-0">
                    Simple steps to book your relaxing experience
                </p>
            </div>

            <!-- Card Grid -->
            <div class="row g-4 justify-content-center">

                <!-- Step 1 -->
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card h-100 border-0 shadow-sm text-center p-4">

                        <i class="bi bi-calendar2-plus fs-1 text-primary mb-3"></i>

                        <h5 class="fw-semibold mb-2">Book a Service</h5>

                        <p class="text-muted mb-0">
                            Choose your preferred massage service from our available treatments.
                        </p>

                    </div>
                </div>

                <!-- Step 2 -->
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card h-100 border-0 shadow-sm text-center p-4">

                        <i class="bi bi-clock fs-1 text-primary mb-3"></i>

                        <h5 class="fw-semibold mb-2">Pick Date & Time</h5>

                        <p class="text-muted mb-0">
                            Select a convenient schedule that fits your availability.
                        </p>

                    </div>
                </div>

                <!-- Step 3 -->
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card h-100 border-0 shadow-sm text-center p-4">

                        <i class="bi bi-check2-circle fs-1 text-primary mb-3"></i>

                        <h5 class="fw-semibold mb-2">Get Confirmation</h5>

                        <p class="text-muted mb-0">
                            Receive instant confirmation and get ready to relax and unwind.
                        </p>

                    </div>
                </div>

            </div>

        </div>

    </section>

    <!-- About Us -->
    <section class="pt-5 pb-5">

        <div class="pt-5 container px-lg-5">

            <div class="row align-items-center g-4 g-lg-5">

                <!-- LEFT: CAROUSEL -->
                <div class="col-12 col-lg-6">

                    <div id="aboutCarousel" class="carousel slide shadow-sm overflow-hidden" data-bs-ride="carousel">

                        <div class="carousel-inner">

                            <div class="carousel-item active">
                                <img src="https://images.unsplash.com/photo-1544161515-4ab6ce6db874?auto=format&fit=crop&q=80&w=1200"
                                    class="d-block w-100" style="height: 420px; object-fit: cover;" alt="Spa Image 1">
                            </div>

                            <div class="carousel-item">
                                <img src="https://images.unsplash.com/photo-1519823551278-64ac92734fb1?auto=format&fit=crop&q=80&w=1200"
                                    class="d-block w-100" style="height: 420px; object-fit: cover;" alt="Spa Image 2">
                            </div>

                            <div class="carousel-item">
                                <img src="https://images.unsplash.com/photo-1540555700478-4be289fbecef?auto=format&fit=crop&q=80&w=1200"
                                    class="d-block w-100" style="height: 420px; object-fit: cover;" alt="Spa Image 3">
                            </div>

                        </div>

                        <button class="carousel-control-prev" type="button" data-bs-target="#aboutCarousel"
                            data-bs-slide="prev">
                            <span class="carousel-control-prev-icon"></span>
                        </button>

                        <button class="carousel-control-next" type="button" data-bs-target="#aboutCarousel"
                            data-bs-slide="next">
                            <span class="carousel-control-next-icon"></span>
                        </button>

                    </div>

                </div>

                <!-- RIGHT: CONTENT -->
                <div class="col-12 col-lg-6">

                    <h2 class="fw-bold text-center text-lg-start display-5 mb-3">About Rose Sanctuary</h2>

                    <p class="text-muted text-center text-lg-start mb-3">
                        We are dedicated to creating a peaceful escape where you can relax, refresh, and rejuvenate.
                        Our spa blends expert massage techniques with a calming atmosphere designed for total wellness.
                    </p>

                    <p class="text-muted text-center text-lg-start mb-4">
                        Every treatment is carefully crafted to relieve stress, restore balance, and bring comfort to your
                        body and mind.
                        Your relaxation is our priority.
                    </p>

                    <div class="text-center text-lg-start">
                        <a href="/about" class="btn btn-primary shadow-sm px-4 py-2">
                            Know Our Story
                            <i class="bi bi-arrow-right ms-2"></i>
                        </a>
                    </div>

                </div>

            </div>

        </div>

    </section>

    <!-- Location -->
    <section class="pt-5 pb-5">

        <div class="pt-5 container px-lg-5">

            <div class="row align-items-center g-4 g-lg-5">

                <!-- Left -->
                <div class="col-12 col-lg-5">

                    <h2 class="fw-bold display-5 mb-3 text-center text-lg-start">
                        Our Location
                    </h2>

                    <p class="text-muted text-center text-lg-start mb-3">
                        We are located in a peaceful and relaxing environment designed for your comfort and wellness.
                        Come visit us and experience a true moment of relaxation.
                    </p>

                    <p class="text-muted text-center text-lg-start mb-4">
                        <i class="bi bi-geo-alt-fill me-2"></i>
                        San Juan, Siquijor, Philippines
                        <br>
                    </p>

                    <div class="text-center text-lg-start">
                        <a href="https://maps.app.goo.gl/PP9bM9URwncqCESK9" target="_blank"
                            class="btn btn-primary shadow-sm px-4 py-2">

                            Get Directions
                            <i class="bi bi-geo-alt ms-2"></i>

                        </a>
                    </div>

                </div>

                <!-- Right -->
                <div class="col-12 col-lg-7">

                    <div class="shadow-sm overflow-hidden">

                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3939.097380190728!2d123.50755467483452!3d9.145677390920445!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x33ab3f92da2555d7%3A0x4b123cfa04584c4!2sRose%20Massage%20Services!5e0!3m2!1sen!2sph!4v1777212474000!5m2!1sen!2sph"
                            width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"></iframe>

                    </div>

                </div>

            </div>

        </div>

    </section>

    <!-- Contact -->
    <section class="pt-5 pb-5 bg-light">

        <div class="pt-5 container px-lg-5">

            <!-- Header -->
            <div class="text-center mb-4 mb-lg-5">
                <h2 class="fw-bold display-5 mb-0">Get In Touch</h2>
                <p class="text-muted mb-0">
                    We’re here to answer your questions and help you book your relaxation experience
                </p>
            </div>

            <div class="row justify-content-center">

                <!-- Contact Card -->
                <div class="col-12 col-lg-6">

                    <div class="card border-0 shadow-sm p-4 p-lg-5 text-center">

                        <!-- Icon -->
                        <div class="mb-3">
                            <i class="bi bi-envelope-paper-heart fs-1 text-primary"></i>
                        </div>

                        <h4 class="fw-bold mb-2">Send Us a Message</h4>

                        <p class="text-muted mb-4">
                            Have questions or special requests? Send us an email and we’ll respond as soon as possible.
                        </p>

                        <!-- Email Button -->
                        <a href="mailto:rosesanctuary@gmail.com?subject=Inquiry%20from%20Website"
                            class="btn btn-primary shadow-sm px-4 py-2">

                            Get In Touch
                            <i class="bi bi-send ms-2"></i>

                        </a>

                        <hr class="my-4">

                        <!-- Extra contact info -->
                        <p class="text-muted mb-0 small">

                            <i class="bi bi-telephone-fill me-2"></i>
                            +63 9XX XXX XXXX

                            <br>

                            <i class="bi bi-envelope-fill me-2"></i>
                            rosesanctuary@gmail.com

                        </p>

                    </div>

                </div>

            </div>

        </div>

    </section>

    <!-- Reviews -->
    <section class="pt-5 pb-5 bg-light">

        <div class="pt-5 container px-lg-5">

            <!-- Header -->
            <div class="text-center mb-4 mb-lg-5">
                <h2 class="fw-bold display-5 mb-0">Customer Reviews</h2>
                <p class="text-muted mb-0">
                    Real experiences from our happy clients
                </p>
            </div>

            <!-- Reviews Grid -->
            <div class="row d-flex justify-content-center g-4">

                @forelse ($reviews as $review)
                    <div class="col-12 col-md-6 col-lg-4">

                        <a href="{{ route('reviews.show', $review->id) }}"
                            class="text-decoration-none text-reset d-block h-100">

                            <div class="card border-0 shadow-sm h-100 p-4">

                                <!-- HEADER -->
                                <div class="d-flex align-items-center mb-3">

                                    {{-- Avatar --}}
                                    @if ($review->user?->profile?->avatar)
                                        <img src="{{ asset('storage/' . $review->user?->profile->avatar) }}"
                                            class="rounded-circle me-3 object-fit-cover" width="48" height="48">
                                    @else
                                        <div class="bg-light text-muted rounded-circle d-flex align-items-center justify-content-center me-3"
                                            style="width:48px; height:48px;">
                                            <i class="bi bi-person"></i>
                                        </div>
                                    @endif

                                    {{-- Name + Date --}}
                                    <div class="flex-grow-1">
                                        <div class="fw-semibold">
                                            @php
                                                $isMe = $review->user_id === auth()->id();
                                            @endphp

                                            {{ $review->user?->name }}
                                            @if ($isMe)
                                                <span class="text-muted">(You)</span>
                                            @endif
                                        </div>

                                        <small class="text-muted">
                                            {{ $review->created_at->format('M d, Y • h:i A') }}
                                        </small>
                                    </div>

                                </div>

                                <!-- RATING -->
                                <div class="text-warning mb-2">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <i class="bi bi-star{{ $i <= $review->rating ? '-fill' : '' }}"></i>
                                    @endfor
                                </div>

                                <!-- COMMENT -->
                                <p class="text-muted mb-3" style="line-height: 1.5;">
                                    {{ Str::limit($review->comment, 120) }}
                                </p>

                                <!-- IMAGES -->
                                @if ($review->images && $review->images->count())
                                    <div class="d-flex flex-wrap gap-2 mb-3">
                                        @foreach ($review->images->take(3) as $image)
                                            <img src="{{ asset('storage/' . $image->path) }}" class="rounded"
                                                style="width: 70px; height: 70px; object-fit: cover;">
                                        @endforeach

                                        @if ($review->images->count() > 3)
                                            <div class="d-flex align-items-center justify-content-center bg-light text-muted rounded"
                                                style="width:70px; height:70px; font-size: 12px;">
                                                +{{ $review->images->count() - 3 }}
                                            </div>
                                        @endif
                                    </div>
                                @endif

                                <!-- CTA -->
                                <div class="mt-auto d-flex align-items-center text-primary fw-semibold small">
                                    <span>Read full review</span>
                                    <i class="bi bi-arrow-right ms-1"></i>
                                </div>

                            </div>

                        </a>

                    </div>
                @empty
                    <div class="d-flex flex-column justify-content-center align-items-center">
                        {{-- EMPTY DATABASE --}}
                        <i class="bi bi-star fs-1 text-muted"></i>
                        <h5 class="mt-3">No reviews yet</h5>
                        <p class="text-muted mb-0">
                            No reviews available yet.
                        </p>
                    </div>
                @endforelse

            </div>

            <!-- VIEW ALL CTA -->
            <div class="text-center mt-4 mt-lg-5">

                <a href="{{ route('reviews.index') }}" class="btn btn-primary shadow-sm px-4 py-2">

                    View All Reviews
                    <i class="bi bi-arrow-right ms-2"></i>

                </a>

            </div>

        </div>

    </section>

@endsection
