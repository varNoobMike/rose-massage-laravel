@extends('layouts.user')

@section('page-title', 'Review Details')

@section('breadcrumb', true)
@section('breadcrumb-parent', 'Booking #' . ($review->booking->id ?? ''))
@section('breadcrumb-parent-url', route('bookings.show', $review->booking_id))

@section('page-header', true)
@section('page-header-title', 'Your Review')
@section('page-header-subtitle', 'Booking #' . $review->booking_id)

@section('content')

    <div class="container px-lg-5">

        <div class="row g-4">

            <!-- LEFT: BOOKING SUMMARY -->
            <div class="col-md-4 order-2 order-md-1">

                <div class="card shadow-sm border sticky-top" style="top: 100px;">
                    <div class="card-body">

                        <h5 class="fw-bold mb-3">Booking Summary</h5>

                        <div class="mb-2">
                            <small class="text-muted">Booking ID</small>
                            <div class="fw-bold">
                                #{{ $review->booking->id }}
                            </div>
                        </div>

                        <div class="mb-2">
                            <small class="text-muted">Date</small>
                            <div class="fw-bold">
                                {{ \Carbon\Carbon::parse($review->booking->booking_date)->format('M d, Y') }}
                            </div>
                        </div>

                        <div class="mb-2">
                            <small class="text-muted">Time</small>
                            <div class="fw-bold">
                                {{ \Carbon\Carbon::parse($review->booking->start_time)->format('h:i A') }}
                            </div>
                        </div>

                        <div class="mb-2">
                            <small class="text-muted">Total</small>
                            <div class="fw-bold text-primary">
                                ₱{{ number_format($review->booking->total_amount, 2) }}
                            </div>
                        </div>

                    </div>
                </div>

            </div>

            <!-- RIGHT: REVIEW DETAILS -->
            <div class="col-md-8 order-1 order-md-2">

                <!-- REVIEW CARD -->
                <div class="card shadow-sm border">

                    <div class="card-body">

                        <!-- HEADER -->
                        <div class="d-flex justify-content-between align-items-start mb-3">

                            <div>
                                <h5 class="fw-bold mb-1">Your Review</h5>

                                <small class="text-muted">
                                    Booking #{{ $review->booking_id }}
                                </small>
                            </div>

                            <div class="text-end">

                                <span class="badge
                                    @if ($review->status === 'approved') bg-success
                                    @elseif ($review->status === 'rejected') bg-danger
                                    @elseif ($review->status === 'hidden') bg-dark
                                    @else bg-warning text-dark @endif">

                                    {{ ucfirst($review->status) }}
                                </span>

                                <div class="mt-1">
                                    <small class="text-muted">
                                        {{ $review->created_at->format('M d, Y') }}
                                    </small>
                                </div>

                            </div>

                        </div>

                        <!-- STARS -->
                        <div class="text-warning mb-3 fs-5">

                            @for ($i = 1; $i <= 5; $i++)
                                <i class="bi {{ $i <= $review->rating ? 'bi-star-fill' : 'bi-star' }}"></i>
                            @endfor

                        </div>

                        <!-- COMMENT -->
                        <div class="mb-3">

                            <h6 class="fw-bold">Comment</h6>

                            <p class="mb-0" style="white-space: pre-line;">
                                {{ $review->comment }}
                            </p>

                        </div>

                        <!-- IMAGES -->
                        @if ($review->images && $review->images->count())
                            <div class="mb-3">

                                <h6 class="fw-bold">Images</h6>

                                <div class="d-flex flex-wrap gap-2">

                                    @foreach ($review->images as $img)
                                        <a href="{{ asset('storage/' . $img->path) }}" target="_blank">
                                            <img src="{{ asset('storage/' . $img->path) }}" class="rounded border"
                                                style="width:100px;height:100px;object-fit:cover;">
                                        </a>
                                    @endforeach

                                </div>

                            </div>
                        @endif

                        <hr>

                        <!-- FOOTER -->
                        <div class="d-flex justify-content-between align-items-center">

                            <small class="text-muted">
                                Posted {{ $review->created_at->diffForHumans() }}
                            </small>

                            <div class="d-flex gap-2">

                                <a href="{{ route('bookings.show', $review->booking_id) }}"
                                    class="btn btn-outline-secondary btn-sm">
                                    Back
                                </a>

                                <a href="{{ route('reviews.edit', $review->id) }}"
                                    class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-pencil me-1"></i> Edit
                                </a>

                                <!-- DELETE -->
                                <form action="{{ route('reviews.destroy', $review->id) }}"
                                      method="POST"
                                      onsubmit="return confirm('Delete this review? This cannot be undone.')">

                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="btn btn-outline-danger btn-sm">
                                        <i class="bi bi-trash me-1"></i> Delete
                                    </button>

                                </form>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

@endsection
