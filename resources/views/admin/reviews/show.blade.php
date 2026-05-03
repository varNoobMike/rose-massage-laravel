@extends('layouts.admin')

@section('page-title', 'Review #' . $review->id)

@section('breadcrumb-parent', 'Reviews')
@section('breadcrumb-parent-url', route('reviews.index'))

@section('page-header', true)
@section('page-header-title-showpage', 'Review #' . $review->id)
@section('page-header-subtitle', 'Review details and moderation')

@section('content')
<div class="row g-4">

    <!-- LEFT SIDE -->
    <div class="col-12 col-lg-8">

        <div class="card shadow-sm border">
            <div class="card-header bg-white py-3 border-bottom">
                <div class="d-flex justify-content-between align-items-center">

                    <h6 class="mb-0 fw-bold text-uppercase small text-muted">
                        Review Information
                    </h6>

                    <span class="badge bg-light text-primary border px-3 py-2">
                        Booking #{{ $review->booking_id }}
                    </span>

                </div>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">

                    <table class="table table-borderless mb-0 align-middle">
                        <tbody>

                            <!-- Client -->
                            <tr class="border-bottom border-light">
                                <td class="ps-4 py-4 text-muted small fw-bold text-uppercase" style="width:30%;">
                                    Client
                                </td>

                                <td class="py-4 pe-4">
                                    <div class="d-flex align-items-center">

                                        @if ($review->user?->profile?->avatar)
                                            <img src="{{ asset('storage/' . $review->user->profile->avatar) }}"
                                                class="rounded-circle me-3 object-fit-cover"
                                                width="45" height="45">
                                        @else
                                            <div class="bg-light text-muted rounded-circle d-flex align-items-center justify-content-center me-3"
                                                style="width:45px;height:45px;">
                                                <i class="bi bi-person"></i>
                                            </div>
                                        @endif

                                        <div>
                                            <div class="fw-bold">
                                                {{ $review->user->name ?? 'N/A' }}
                                            </div>
                                            <small class="text-muted">
                                                {{ $review->user->email ?? '' }}
                                            </small>
                                        </div>

                                    </div>
                                </td>
                            </tr>

                            <!-- Rating -->
                            <tr class="border-bottom border-light">
                                <td class="ps-4 py-4 text-muted small fw-bold text-uppercase">
                                    Rating
                                </td>
                                <td class="py-4 pe-4">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <i class="bi bi-star{{ $i <= $review->rating ? '-fill text-warning' : '' }}"></i>
                                    @endfor
                                    <span class="ms-2 fw-bold">
                                        {{ $review->rating }}/5
                                    </span>
                                </td>
                            </tr>

                            <!-- REVIEW IMAGES (MOVED HERE) -->
                            @if ($review->images && count($review->images))
                                <tr class="border-bottom border-light">
                                    <td class="ps-4 py-4 text-muted small fw-bold text-uppercase">
                                        Images
                                    </td>
                                    <td class="py-4 pe-4">

                                        <div class="d-flex flex-wrap gap-2">

                                            @foreach ($review->images as $image)
                                                <a href="{{ asset('storage/' . $image->path) }}" target="_blank">
                                                    <img src="{{ asset('storage/' . $image->path) }}"
                                                        class="shadow-sm"
                                                        style="width:80px;height:80px;object-fit:cover;">
                                                </a>
                                            @endforeach

                                        </div>

                                    </td>
                                </tr>
                            @endif

                            <!-- Comment -->
                            <tr class="border-bottom border-light">
                                <td class="ps-4 py-4 text-muted small fw-bold text-uppercase">
                                    Comment
                                </td>
                                <td class="py-4 pe-4">
                                    {{ $review->comment }}
                                </td>
                            </tr>

                            <!-- Status -->
                            <tr class="border-bottom border-light">
                                <td class="ps-4 py-4 text-muted small fw-bold text-uppercase">
                                    Status
                                </td>
                                <td class="py-4 pe-4">
                                    @if ($review->status === 'approved')
                                        <span class="badge bg-success">Approved</span>
                                    @elseif ($review->status === 'rejected')
                                        <span class="badge bg-danger">Rejected</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    @endif
                                </td>
                            </tr>

                            <!-- System Logs -->
                            <tr>
                                <td class="ps-4 py-4 text-muted small fw-bold text-uppercase">
                                    System Logs
                                </td>

                                <td class="py-4 pe-4 text-muted small">

                                    <div class="mb-1 d-flex align-items-center">
                                        <i class="bi bi-calendar-plus me-2 opacity-50"></i>
                                        Created:
                                        <strong class="ms-1">
                                            {{ $review->created_at->format('M d, Y') }}
                                        </strong>
                                    </div>

                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-arrow-repeat me-2 opacity-50"></i>
                                        Last Update:
                                        <strong class="ms-1">
                                            {{ $review->updated_at->diffForHumans() }}
                                        </strong>
                                    </div>

                                </td>
                            </tr>

                        </tbody>
                    </table>

                </div>
            </div>
        </div>

    </div>

    <!-- RIGHT SIDE -->
    <div class="col-12 col-lg-4">

        <!-- STATUS CARD -->
        <div class="card shadow-sm border mb-4 text-center">
            <div class="card-body p-4">

                <small class="text-uppercase text-muted fw-bold mb-3 d-block">
                    Review Status
                </small>

                <div
                    class="bg-{{ $review->status === 'approved' ? 'success' : ($review->status === 'rejected' ? 'danger' : 'warning') }} 
                    bg-opacity-10 
                    text-{{ $review->status === 'approved' ? 'success' : ($review->status === 'rejected' ? 'danger' : 'warning') }} 
                    rounded-pill py-2 px-4 d-inline-flex align-items-center mb-2">

                    <i class="bi bi-chat-square-text me-2"></i>

                    <span class="fw-bold text-uppercase">
                        {{ $review->status }}
                    </span>
                </div>

                <p class="text-muted small mb-0">
                    {{ $review->status === 'approved' 
                        ? 'This review is visible publicly' 
                        : ($review->status === 'rejected' 
                            ? 'This review is hidden from public' 
                            : 'Waiting for approval') }}
                </p>

            </div>
        </div>

        <!-- VIEW BOOKING -->
        <div class="card shadow-sm border mb-4">
            <div class="card-body p-3 text-center">

                <small class="text-uppercase text-muted fw-bold d-block mb-2">
                    Related Booking
                </small>

                <a href="{{ route('bookings.show', $review->booking_id) }}"
                   class="btn btn-outline-primary w-100">
                    <i class="bi bi-eye me-2"></i> View Booking
                </a>

            </div>
        </div>

        <div class="card shadow-sm border mb-4">

            <div class="card-header bg-white py-3 border-bottom text-center">
                <h6 class="mb-0 fw-bold text-uppercase small text-muted">
                    Moderation Actions
                </h6>
            </div>

            <div class="card-body p-3 d-grid gap-2">

                <!-- APPROVE -->
                @if ($review->status !== 'approved')
                    <form action="{{ route('reviews.approve', $review->id) }}" method="POST"
                        onsubmit="return confirm('Accept this review?')">
                        @csrf
                        <button class="btn btn-success w-100">
                            <i class="bi bi-check-circle me-2"></i> Accept Review
                        </button>
                    </form>
                @endif

                <!-- REJECT -->
                @if ($review->status !== 'rejected')
                    <form action="{{ route('reviews.reject', $review->id) }}" method="POST"
                        onsubmit="return confirm('Reject this review?')">
                        @csrf
                        <button class="btn btn-warning w-100">
                            <i class="bi bi-x-circle me-2"></i> Reject Review
                        </button>
                    </form>
                @endif

                <!-- DELETE -->
                <form action="{{ route('reviews.destroy', $review->id) }}" method="POST"
                      onsubmit="return confirm('Delete this review? This action cannot be undone.')">
                    @csrf
                    @method('DELETE')

                    <button class="btn btn-danger w-100">
                        <i class="bi bi-trash me-2"></i> Delete Review
                    </button>
                </form>

            </div>
        </div>


    </div>

</div>
@endsection