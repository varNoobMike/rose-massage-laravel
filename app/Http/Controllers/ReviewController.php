<?php

namespace App\Http\Controllers;

use App\Actions\Review\DestroyReview;
use App\Actions\Review\StoreReview;
use App\Actions\Review\UpdateReview;
use App\Models\Booking;
use App\Models\Review;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function index()
    {
        $reviews = Review::paginate(10);

        return view($this->currentRoleView() . '.reviews.index', compact('reviews'));
    }

    public function show(Review $review)
    {
        $review->load(['images', 'booking']);

        return view(
            $this->currentRoleView() . '.reviews.show',
            compact('review')
        );
    }

    public function create(Booking $booking)
    {
        $this->authorizeBooking($booking);

        return view(
            $this->currentRoleView() . '.reviews.create',
            compact('booking')
        );
    }

    public function store(Request $request, Booking $booking, StoreReview $action)
    {
        $this->authorizeBooking($booking);

        $validated = $request->validate([
            'rating'  => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:2000',
            'images.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
        ]);

        $action->execute(
            $validated,
            $request->file('images', []),
            $booking,
            Auth::user()
        );

        return to_route('bookings.show', $booking->id)
            ->with('success', 'Review submitted successfully!');
       
    }

    public function edit(Review $review)
    {
        $this->authorizeReview($review);

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
        $validated = $request->validate([
            'rating'  => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:2000',
            'images.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'existing_images' => 'array',
            'existing_images.*' => 'integer|exists:review_images,id',
        ]);

        $action->execute(
            $validated,
            $review,
            Auth::id(),
            $request->input('existing_images', []),
            $request->file('images', [])
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

        if(in_array($userRole, [User::ROLE_ADMIN, User::ROLE_OWNER, User::ROLE_RECEPTIONIST,])) { 
            return to_route('reviews.index')
                ->with('success', 'Review deleted successfully!');
        }

        return to_route('bookings.show', $review->booking_id)
                ->with('success', 'Review deleted successfully!');
    }

    public function approve(Review $review)
    {
        $review->update(['status' => 'approved']);
        return back()->with('success', 'Review approved successfully!');
    }

    public function reject(Review $review)
    {
        $review->update(['status' => 'rejected']);
        return back()->with('success', 'Review rejected successfully!');
    }

    private function authorizeReview(Review $review): void
    {
        if ($review->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
    }

    private function authorizeBooking(Booking $booking): void
    {
        if ($booking->client_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
    }
}
