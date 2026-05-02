@extends('layouts.user')

@section('page-title', 'Write Review')

@section('breadcrumb', true)
@section('breadcrumb-parent', 'Booking #' . $booking->id)
@section('breadcrumb-parent-url', route('bookings.show', $booking->id))

@section('page-header', true)
@section('page-header-title', 'Write a Review')
@section('page-header-subtitle', 'Share your experience for Booking #' . $booking->id)

@section('content')

    <div class="container px-lg-5">

        <div class="row g-4">

            <!-- LEFT: SUMMARY -->
            <div class="col-md-4 order-2 order-md-1">
                <div class="card shadow-sm border sticky-top" style="top: 100px;">
                    <div class="card-body">

                        <h5 class="fw-bold mb-3">Booking Summary</h5>

                        <div class="mb-2">
                            <small class="text-muted">Booking ID</small>
                            <div class="fw-bold">#{{ $booking->id }}</div>
                        </div>

                        <div class="mb-2">
                            <small class="text-muted">Date</small>
                            <div class="fw-bold">
                                {{ \Carbon\Carbon::parse($booking->booking_date)->format('M d, Y') }}
                            </div>
                        </div>

                        <div class="mb-2">
                            <small class="text-muted">Time</small>
                            <div class="fw-bold">
                                {{ \Carbon\Carbon::parse($booking->start_time)->format('h:i A') }}
                            </div>
                        </div>

                        <div class="mb-2">
                            <small class="text-muted">Total</small>
                            <div class="fw-bold text-primary">
                                ₱{{ number_format($booking->total_amount, 2) }}
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <!-- RIGHT: FORM -->
            <div class="col-md-8 order-1 order-md-2">

                <form id="reviewForm" action="{{ route('reviews.store', $booking->id) }}" method="POST"
                    enctype="multipart/form-data" novalidate>

                    @csrf

                    <div class="card shadow-sm border">
                        <div class="card-body">

                            <h5 class="fw-bold mb-3">Write Your Review</h5>

                            <!-- ⭐ RATING -->
                            <div class="mb-3">
                                <label class="form-label fw-bold">Rating</label>

                                <div id="starRating" class="d-flex gap-1 fs-3 text-warning">

                                    @for ($i = 1; $i <= 5; $i++)
                                        <i class="bi bi-star" data-value="{{ $i }}"></i>
                                    @endfor

                                </div>

                                <input type="hidden" name="rating" id="ratingInput" value="{{ old('rating', 0) }}">

                                @error('rating')
                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- COMMENT -->
                            <div class="mb-3">
                                <label class="form-label fw-bold">Comment</label>

                                <textarea name="comment" class="form-control @error('comment') is-invalid @enderror" rows="5" required>{{ old('comment') }}</textarea>

                                @error('comment')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- IMAGES -->
                            <div class="mb-3">

                                <label class="form-label fw-bold">Upload Images</label>

                                <input type="file" id="imageInput" name="images[]" class="form-control" multiple
                                    accept="image/*">

                                <div id="previewContainer" class="d-flex flex-wrap gap-2 mt-3"></div>

                            </div>

                            <!-- SUBMIT -->
                            <button type="submit" class="btn btn-primary w-100">
                                Submit Review
                            </button>

                            <a href="{{ route('bookings.show', $booking->id) }}"
                                class="btn btn-outline-secondary w-100 mt-2">
                                Cancel
                            </a>

                        </div>
                    </div>

                </form>

            </div>

        </div>

    </div>

@endsection

@section('page-scripts')
    <script>
        $(document).ready(function() {

            function renderStars(value) {
                $('#starRating i').each(function() {
                    if ($(this).data('value') <= value) {
                        $(this).removeClass('bi-star').addClass('bi-star-fill');
                    } else {
                        $(this).removeClass('bi-star-fill').addClass('bi-star');
                    }
                });
            }

            // ⭐ INIT (this fixes your validation issue)
            let rating = parseInt($('#ratingInput').val() || 0);
            renderStars(rating);

            // ⭐ CLICK
            $('#starRating i').on('click', function() {
                let value = $(this).data('value');
                $('#ratingInput').val(value);
                renderStars(value);
            });

            // 🖼 IMAGE PREVIEW
            let filesArray = [];

            $('#imageInput').on('change', function(e) {
                let files = Array.from(e.target.files);

                files.forEach((file) => {

                    let index = filesArray.push(file) - 1;

                    let reader = new FileReader();

                    reader.onload = function(e) {
                        $('#previewContainer').append(`
                    <div class="position-relative" data-index="${index}">
                        <img src="${e.target.result}"
                             class="rounded border"
                             style="width: 90px; height: 90px; object-fit: cover;">

                        <button type="button"
                                class="btn btn-danger btn-sm position-absolute top-0 end-0 remove-image">
                            <i class="bi bi-x"></i>
                        </button>
                    </div>
                `);
                    };

                    reader.readAsDataURL(file);
                });
            });

            // ❌ REMOVE IMAGE
            $(document).on('click', '.remove-image', function() {
                let parent = $(this).closest('div');
                let index = parent.data('index');

                filesArray.splice(index, 1);
                parent.remove();
            });

            // 🔧 FIX FILE INPUT BEFORE SUBMIT
            $('#reviewForm').on('submit', function() {
                let dataTransfer = new DataTransfer();

                filesArray.forEach(file => {
                    dataTransfer.items.add(file);
                });

                $('#imageInput')[0].files = dataTransfer.files;
            });

        });
    </script>
@endsection
