@extends('layouts.user')

@section('page-title', 'Reviews')

@section('breadcrumb', true)

@section('page-header', true)
@section('page-header-title', 'Reviews')
@section('page-header-subtitle', 'View customer feedbacks')

@section('filter-area', true)
@section('filter-form')
    <form action="{{ route('reviews.index') }}" method="GET">
        <div class="row g-3">

            <div class="col-md-9">
                <input type="text" name="search" class="form-control" placeholder="Search review author, review id..."
                    value="{{ request('search') }}">
            </div>

            <div class="col-md-3 d-flex gap-2">
                <button class="btn btn-dark w-100">
                    <i class="bi bi-funnel me-1"></i>
                    Filter
                </button>

                <a href="{{ route('reviews.index') }}" class="btn btn-outline-secondary w-100">
                    <i class="bi bi-x-circle me-1"></i>
                    Clear
                </a>
            </div>

        </div>
    </form>
@endsection

@section('content')
    <div class="container px-lg-5">


        <!-- Reviews Grid -->
            <div class="row d-flex justify-content-center g-4">

                @forelse ($reviews as $review)
                    <div class="col-12 col-md-6 col-lg-4">

                        <a href="{{ route('reviews.show',  $review->id) }}"
                            class="text-decoration-none text-reset d-block h-100">

                            <div class="card border-0 shadow-sm h-100 p-3" style="min-height: 280px;">

                                <div class="fw-semibold mb-1">
                                    {{ $review->user->name ?? 'Anonymous' }}
                                </div>

                                <!-- Rating -->
                                <div class="text-warning mb-2">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <i class="bi bi-star{{ $i <= $review->rating ? '-fill' : '' }}"></i>
                                    @endfor
                                </div>

                                <p class="text-muted mb-3">
                                    {{ Str::limit($review->comment, 100) }}
                                </p>

                                @if ($review->images && $review->images->count())
                                    <div class="d-flex flex-wrap gap-2 mb-3">
                                        @foreach ($review->images as $image)
                                            <img src="{{ asset('storage/' . $image->path) }}"
                                                style="width: 70px; height: 70px; object-fit: cover;">
                                        @endforeach
                                    </div>
                                @endif

                                <!-- UX CTA (still visible, not a button) -->
                                <div class="mt-auto pt-2 d-flex align-items-center text-primary fw-semibold small" style="line-height:1;">
                                    <span>View review</span>
                                    <i class="bi bi-arrow-right ms-1" style="display:inline-flex; align-items:center; line-height:1; vertical-align:middle;"></i>
                                </div>

                            </div>

                        </a>
                    </div>
                

                 @empty
                    <div class="col-12 text-center py-5">
                        <p class="text-muted fst-italic mb-0">No customer feedback yet</p>
                    </div>
                @endforelse

            </div>
        

        <!-- Pagination -->
        @if ($reviews->hasPages())
            <div class="card-footer bg-white shadow-sm p-3 mt-4">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">

                    <small class="text-muted">
                        Showing {{ $reviews->firstItem() }}
                        to {{ $reviews->lastItem() }}
                        of {{ $reviews->total() }} reviews
                    </small>

                    {{ $reviews->appends(request()->query())->links() }}

                </div>
            </div>
        @endif
    </div>


@endsection
