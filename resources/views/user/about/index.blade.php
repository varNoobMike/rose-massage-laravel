@extends('layouts.user')

@section('page-title', 'About')

@section('breadcrumb', true)

@section('page-header', true)
@section('page-header-title', 'About Us')
@section('page-header-subtitle', 'Know our story')

@section('content')
    <div class="container px-lg-5">
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

                

            </div>

        </div>
    </div>


@endsection
