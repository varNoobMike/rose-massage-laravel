<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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

        // 2. Status filter (same style as your Service code)
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

    public function markAsRead($id)
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
