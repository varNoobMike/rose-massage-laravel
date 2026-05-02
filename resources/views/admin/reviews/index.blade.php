@extends('layouts.admin')

@section('page-title', 'Reviews')

@section('page-header', true)
@section('page-header-title-indexpage', 'Reviews')
@section('page-header-subtitle', 'Customer feedback and ratings')

@section('page-header-actions')
    <a href="{{ route('reviews.index') }}" class="btn btn-outline-secondary px-4 shadow-sm">
        <i class="bi bi-arrow-repeat me-2"></i> Sync
    </a>
@endsection

@section('filter-area', true)
@section('filter-form')
    <form action="{{ route('reviews.index') }}" method="GET">
        <div class="row g-3">

            <!-- Search -->
            <div class="col-md-6">
                <input type="text" name="search" class="form-control"
                    placeholder="Search review, client name..."
                    value="{{ request('search') }}">
            </div>

            <!-- Rating Filter -->
            <div class="col-md-3">
                @php
                    $rating = request('rating', 'all');
                @endphp

                <select name="rating" class="form-select">
                    <option value="all" {{ $rating == 'all' ? 'selected' : '' }}>
                        All Ratings
                    </option>

                    <option value="5" {{ $rating == '5' ? 'selected' : '' }}>5 Stars</option>
                    <option value="4" {{ $rating == '4' ? 'selected' : '' }}>4 Stars</option>
                    <option value="3" {{ $rating == '3' ? 'selected' : '' }}>3 Stars</option>
                    <option value="2" {{ $rating == '2' ? 'selected' : '' }}>2 Stars</option>
                    <option value="1" {{ $rating == '1' ? 'selected' : '' }}>1 Star</option>
                </select>
            </div>

            <!-- Actions -->
            <div class="col-md-3 d-flex gap-2">
                <button class="btn btn-dark w-100">
                    Filter
                </button>

                <a href="{{ route('reviews.index') }}" class="btn btn-outline-secondary w-100">
                    Clear
                </a>
            </div>

        </div>
    </form>
@endsection

@section('content')
<div class="card shadow-sm border">

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">

            <thead class="table-light">
                <tr>
                    <th>Review</th>
                    <th class="text-center">Rating</th>
                    <th class="text-center">Client</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">Date</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>

            <tbody>
                @forelse($reviews as $review)
                    <tr>

                        <!-- Review -->
                        <td>
                            <div class="d-flex align-items-center">

                                <div class="bg-light rounded d-flex align-items-center justify-content-center me-3"
                                    style="width:50px; height:50px;">
                                    <i class="bi bi-chat-square-text text-primary fs-5"></i>
                                </div>

                                <div>
                                    <div class="fw-bold">
                                        {{ Str::limit($review->comment, 20) }}
                                    </div>

                                    <small class="text-muted">
                                        Booking #{{ $review->booking_id }}
                                    </small>
                                </div>

                            </div>
                        </td>

                        <!-- Rating -->
                        <td class="text-center">
                            @for ($i = 1; $i <= 5; $i++)
                                <i class="bi bi-star{{ $i <= $review->rating ? '-fill text-warning' : '' }}"></i>
                            @endfor
                        </td>

                        <!-- Client -->
                        <td class="text-center">
                            <div class="d-flex align-items-center justify-content-center">

                                <!-- Avatar -->
                                @if ($review->user?->profile?->avatar)
                                    <img src="{{ asset('storage/' . $review->user->profile->avatar) }}"
                                        class="rounded-circle me-3 object-fit-cover"
                                        width="45"
                                        height="45">
                                @else
                                    <div class="bg-light text-muted rounded-circle d-flex align-items-center justify-content-center me-3"
                                        style="width:45px;height:45px;">
                                        <i class="bi bi-person"></i>
                                    </div>
                                @endif

                                <!-- Name + Email -->
                                <div class="text-start">
                                    <div class="fw-semibold">
                                        {{ $review->user->name ?? 'N/A' }}
                                    </div>

                                    <small class="text-muted">
                                        {{ $review->user->email ?? '' }}
                                    </small>
                                </div>

                            </div>
                        </td>

                        <!-- Status -->
                        <td class="text-center">
                            @if ($review->status === 'approved')
                                <span class="badge bg-success">Approved</span>
                            @elseif ($review->status === 'rejected')
                                <span class="badge bg-danger">Rejected</span>
                            @else
                                <span class="badge bg-warning text-dark">Pending</span>
                            @endif
                        </td>

                        <!-- Date -->
                        <td class="text-center">
                            <div class="fw-semibold">
                                {{ $review->created_at->format('M d, Y') }}
                            </div>
                            <small class="text-muted">
                                {{ $review->created_at->format('h:i A') }}
                            </small>
                        </td>

                        <!-- Actions -->
                        <td class="text-end">
                            <div class="btn-group gap-2">

                                <!-- VIEW -->
                                <a href="{{ route('reviews.show', $review->id) }}"
                                    class="btn btn-sm btn-secondary">
                                    <i class="bi bi-eye"></i>
                                </a>

                                <!-- APPROVE -->
                                @if ($review->status !== 'approved')
                                    <form action="{{ route('reviews.approve', $review->id) }}" method="POST"
                                        onsubmit="return confirm('Approve this review?')">
                                        @csrf
                                        <button class="btn btn-sm btn-success">
                                            <i class="bi bi-check-lg"></i>
                                        </button>
                                    </form>
                                @endif

                                <!-- REJECT -->
                                @if ($review->status !== 'rejected')
                                    <form action="{{ route('reviews.reject', $review->id) }}" method="POST"
                                        onsubmit="return confirm('Reject this review?')">
                                        @csrf
                                        <button class="btn btn-sm btn-warning">
                                            <i class="bi bi-x-lg"></i>
                                        </button>
                                    </form>
                                @endif

                                <!-- DELETE -->
                                <form action="{{ route('reviews.destroy', $review->id) }}" method="POST"
                                    onsubmit="return confirm('Delete this review? This action cannot be undone.')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>

                            </div>
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-5">

                            <i class="bi bi-chat-square-text fs-1 text-muted"></i>

                            <h5 class="mt-3">No reviews found</h5>

                            <p class="text-muted mb-0">
                                No customer feedback yet.
                            </p>

                        </td>
                    </tr>
                @endforelse
            </tbody>

        </table>
    </div>

    <!-- Pagination -->
    @if ($reviews->hasPages())
        <div class="card-footer bg-white">
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