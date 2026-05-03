@extends('layouts.user')

@section('page-title', 'Reviews')

@section('breadcrumb', true)

@section('page-header', true)
@section('page-header-title', 'Reviews')
@section('page-header-subtitle', 'View customer feedbacks')

@section('page-header-actions')
    <a href="{{ route('reviews.index') }}" class="btn btn-outline-secondary px-4 shadow-sm">
        <i class="bi bi-arrow-repeat me-2"></i> Sync
    </a>
@endsection

@section('filter-area', true)
@section('filter-form')

    {{-- MOBILE TOGGLE --}}
    <button class="btn btn-outline-dark d-md-none w-100 mb-3" type="button" data-bs-toggle="collapse"
        data-bs-target="#reviewFilters">
        <i class="bi bi-funnel me-1"></i>
        Show Filters
    </button>

    <div class="collapse d-md-block" id="reviewFilters">

        <form action="{{ route('reviews.index') }}" method="GET">

            <div class="row g-3">

                {{-- SEARCH --}}
                <div class="col-12 col-md-5">
                    <input type="text" name="search" class="form-control"
                        placeholder="Search review author, review id..." value="{{ request('search') }}">
                </div>

                {{-- DATE FROM --}}
                <div class="col-md-2">
                    <div class="input-group">
                        <span class="input-group-text">From</span>
                        <input type="date" name="from" class="form-control" value="{{ request('from') }}">
                    </div>
                </div>

                {{-- DATE TO --}}
                <div class="col-md-2">
                    <div class="input-group">
                        <span class="input-group-text">To</span>
                        <input type="date" name="to" class="form-control" value="{{ request('to') }}">
                    </div>
                </div>

                {{-- RATING --}}
                <div class="col-12 col-md-2">
                    @php $rating = request('rating', 'all'); @endphp

                    <select name="rating" class="form-select">

                        <option value="" {{ $rating == 'all' ? 'selected' : '' }}>
                            All Ratings
                        </option>

                        <option value="5" {{ $rating == '5' ? 'selected' : '' }}>5 Stars</option>
                        <option value="4" {{ $rating == '4' ? 'selected' : '' }}>4 Stars</option>
                        <option value="3" {{ $rating == '3' ? 'selected' : '' }}>3 Stars</option>
                        <option value="2" {{ $rating == '2' ? 'selected' : '' }}>2 Stars</option>
                        <option value="1" {{ $rating == '1' ? 'selected' : '' }}>1 Star</option>

                    </select>
                </div>

                {{-- ACTIONS --}}
                <div class="col-12 col-md-3 d-flex gap-2">

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

    </div>

@endsection

@section('content')
    <div class="container px-lg-5">

        @php
            $hasFilters =
                request()->filled('search') ||
                request()->filled('rating') ||
                request()->filled('status') ||
                request()->filled('from') ||
                request()->filled('to');
        @endphp

        @if ($hasFilters)
            <div class="alert alert-info d-flex justify-content-between align-items-center py-2 px-3 mb-3">

                <div class="d-flex flex-wrap gap-2 align-items-center">

                    <strong class="me-2">
                        <i class="bi bi-funnel-fill"></i> Filters applied:
                    </strong>

                    @if (request('search'))
                        <span class="badge bg-dark">
                            Search: {{ request('search') }}
                        </span>
                    @endif

                    @if (request('from') || request('to'))
                        <span class="badge bg-secondary">
                            Date:
                            {{ request('from') ?? '...' }}
                            →
                            {{ request('to') ?? '...' }}
                        </span>
                    @endif

                    @if (request('rating'))
                        <span class="badge bg-warning text-dark">
                            Rating: {{ request('rating') . '★' }}
                        </span>
                    @endif

                </div>

            </div>
        @endif


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
                                    <img src="{{ asset('storage/' . $review->user->profile->avatar) }}"
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
                    @if ($hasFilters)
                        {{-- EMPTY DUE TO FILTERS --}}
                        <i class="bi bi-star fs-1 text-muted"></i>
                        <h5 class="mt-3">No results found</h5>
                        <p class="text-muted mb-3">
                            No reviews match your filters.
                        </p>

                        <a href="{{ route('reviews.index') }}" class="btn btn-outline-dark">
                            <i class="bi bi-x-circle me-1"></i>
                            Clear Filters
                        </a>
                    @else
                        {{-- EMPTY DATABASE --}}
                        <i class="bi bi-star fs-1 text-muted"></i>
                        <h5 class="mt-3">No reviews yet</h5>
                        <p class="text-muted mb-0">
                            No reviews available yet.
                        </p>
                    @endif
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
