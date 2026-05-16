<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $query = $user->notifications()->latest();

        // 1. Search (message or title inside JSON)
        $query->when($request->search, function ($q, $search) {
            return $q->where(function ($sub) use ($search) {
                $sub->where('data->message', 'like', "%{$search}%")
                    ->orWhere('data->title', 'like', "%{$search}%")
                    ->orWhere('data->booking_id', $search);
            });
        });


        // 2.Date from
        $query->when($request->from, function ($q, $from) {
            return $q->whereDate('created_at', '>=', $from);
        });

        // 3. Date to
        $query->when($request->to, function ($q, $to) {
            return $q->whereDate('created_at', '<=', $to);
        });


        // 4. Status
        $query->when($request->status, function ($q, $status) {
            return match ($status) {
                'read'   => $q->whereNotNull('read_at'),
                'unread' => $q->whereNull('read_at'),
                'all'    => $q,
                default  => $q,
            };
        }, function ($q) {
            // default behavior (optional)
            return $q;
        });

        $notifications = $query->paginate(10)->withQueryString();

        return view(
            $this->currentRoleView() . '.notifications.index',
            compact('notifications')
        );
    }

    public function open(DatabaseNotification $notification)
    {
        // ensure ownership
        if ($notification->notifiable_id !== Auth::id()) {
            abort(403);
        }

        // mark as read
        $notification->markAsRead();

        return match ($notification->type) {

            \App\Notifications\NewBookingNotification::class,
            \App\Notifications\BookingStatusNotification::class,
            \App\Notifications\PaymentSubmittedNotification::class
            => $this->openBooking($notification),

            \App\Notifications\NewBookingReviewNotification::class,
            \App\Notifications\ReviewApprovedNotification::class,
            \App\Notifications\ReviewRejectedNotification::class,
            \App\Notifications\ReviewDeletedNotification::class,
            => $this->openReview($notification),

            \App\Notifications\NewAnnouncementNotification::class
            => $this->openAnnouncement($notification),

            default => redirect()->back(),
        };
    }

    private function openBooking(DatabaseNotification $notification)
    {
        $bookingId = $notification->data['booking_id'] ?? null;

        return $bookingId
            ? redirect()->route('bookings.show', $bookingId)
            : redirect()->back();
    }

    private function openReview(DatabaseNotification $notification)
    {
        $reviewId = $notification->data['review_id'] ?? null;

        return $reviewId
            ? redirect()->route('reviews.show', $reviewId)
            : redirect()->back();
    }

    private function openAnnouncement(DatabaseNotification $notification)
    {
        $announcementId = $notification->data['announcement_id'] ?? null;

        return $announcementId
            ? redirect()->route('announcements.show', $announcementId)
            : redirect()->back();
    }

    public function markAsRead(string $id)
    {
        $user = Auth::user();

        $notification = $user->notifications()
            ->where('id', $id)
            ->firstOrFail();

        $notification->markAsRead();

        return back()->with(
            'success',
            'Notification marked as read.'
        );
    }

    public function markAllAsRead()
    {
        $user = Auth::user();

        $user->unreadNotifications()
            ->update([
                'read_at' => now()
            ]);

        return back()->with(
            'success',
            'All notifications marked as read.'
        );
    }
}
