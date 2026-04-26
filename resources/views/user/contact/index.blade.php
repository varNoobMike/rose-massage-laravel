@extends('layouts.user')

@section('page-title', 'Contact')

@section('breadcrumb', true)

@section('page-header', true)
@section('page-header-title-indexpage', 'Contact Us')
@section('page-header-subtitle', 'We’re here to answer your questions and help you book your relaxation experience')

@section('content')
    <div class="container px-lg-5">
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


@endsection
