<?php

namespace App\Http\Controllers;

use App\Actions\Review\ApproveReview;
use App\Actions\Review\GetFilteredReviews;
use App\Actions\Review\DestroyReview;
use App\Actions\Review\RejectReview;
use App\Actions\Review\StoreReview;
use App\Actions\Review\UpdateReview;
use App\Models\Booking;
use App\Models\Review;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function index(
        Request $request,
        GetFilteredReviews $action
    ) {

        $filters = $request->only([
            'search',
            'date_from',
            'date_to',
            'rating',
            'status',
        ]);

        // fetch filtered reviews
        $reviews = $action->execute(
            $filters,
            Auth::user()
        );

        // basic filters state
        $hasBasicFilters =
            !empty($filters['search']) ||
            !empty($filters['date_from']) ||
            !empty($filters['date_to']) ||
            !empty($filters['rating']) ||
            !empty($filters['status']);

        // global filters state
        $hasFilters = $hasBasicFilters;

        return view(
            $this->currentRoleView() . '.reviews.index',
            compact('reviews', 'hasFilters', 'filters')
        );
    }

    public function show(Review $review)
    {
        $review->load(['images', 'booking']);

        // mark related notifications as read
        Auth::user()
            ->unreadNotifications
            ->where('data.review_id', $review->id)
            ->markAsRead();

        return view(
            $this->currentRoleView() . '.reviews.show',
            compact('review')
        );
    }

    public function create(Booking $booking)
    {
        abort_if($booking->client_id !== Auth::id(), 403);

        return view(
            $this->currentRoleView() . '.reviews.create',
            compact('booking')
        );
    }

    public function store(Request $request, Booking $booking, StoreReview $action)
    {
        abort_if($booking->client_id !== Auth::id(), 403);

        $validated = $request->validate([
            'rating'  => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:2000',
            'images.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
        ]);

        $action->execute(
            $validated,
            $booking,
            Auth::user()
        );

        return to_route('bookings.show', $booking->id)
            ->with('success', 'Review submitted successfully. Please wait for admin approval to publish your review.');
    }

    public function edit(Review $review)
    {
        abort_if($review->user_id !== Auth::id(), 403);

        $review->load(['images', 'booking']);

        return view(
            $this->currentRoleView() . '.reviews.edit',
            [
                'review' => $review,
                'booking' => $review->booking,
            ]
        );
    }

    public function update(Request $request, Review $review, UpdateReview $action)
    {
        abort_if($review->user_id !== Auth::id(), 403);

        $validated = $request->validate([
            'rating'  => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:2000',
            'images.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'existing_images' => 'array',
            'existing_images.*' => 'integer|exists:review_images,id',
        ]);

        $validated['images'] = $request->file('images', []);
        $validated['existing_images'] = $request->input('existing_images', []);

        $review = $action->execute(
            $validated,
            $review,
            Auth::user()
        );

        return to_route('bookings.show', $review->booking_id)
            ->with('success', 'Review updated successfully!');
    }

    public function destroy(Review $review, DestroyReview $action)
    {
        $action->execute(
            $review,
            Auth::id()
        );

        $userRole = $this->currentUserRole();

        if (in_array($userRole, [User::ROLE_ADMIN, User::ROLE_OWNER, User::ROLE_RECEPTIONIST,])) {
            return to_route('reviews.index')
                ->with('success', 'Review deleted successfully!');
        }

        return to_route('bookings.show', $review->booking_id)
            ->with('success', 'Review deleted successfully!');
    }

    public function approve(Review $review, ApproveReview $action)
    {
        $action->execute($review);
        return to_route('reviews.show', $review->id)
            ->with('success', 'Review approved successfully!');
    }

    public function reject(Review $review, RejectReview $action)
    {
        $action->execute($review);
        return to_route('reviews.show', $review->id)
            ->with('success', 'Review rejected successfully!');
    }
}
