<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Actions\Booking\GetFilteredBookings;
use App\Actions\Booking\StoreBooking;
use App\Actions\Booking\UpdateBooking;
use App\Models\Booking;
use App\Models\BookingItem;
use App\Models\Service;
use App\Models\Spa;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use App\Notifications\NewBookingNotification;


class BookingController extends Controller
{

    public function index(Request $request, GetFilteredBookings $action)
    {
        $filters = $request->only([
            'search',
            'status',
            'date'
        ]);

        $bookings = $action->execute(
            $this->currentUser(),
            $filters
        );

        return view(
            $this->currentRoleView() . '.bookings.index',
            compact('bookings')
        );
    }


    public function show(Booking $booking)
    {
        $booking->load([
            'client.profile',
            'items.service',
            'items.therapist',
            'review.images',
        ]);

        return view(
            $this->currentRoleView() . '.bookings.show',
            compact('booking')
        );
    }


    public function create()
    {
        $user = $this->currentUser();

        return match ($user->role) {
            'admin', 'receptionist' => view('admin.bookings.create'), // to be implemented the admin create booking
            'client' => view('user.bookings.create', [
                'services' => Service::where('status', 'active')->latest()->get(),
            ]),
            default => abort(403, 'Unauthorized action.'),
        };
    }


    public function store(Request $request, StoreBooking $action)
    {
        $validated = $request->validate([
            'services' => 'required|array|min:1',
            'services.*.id' => 'required|exists:services,id',
            'booking_date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'notes' => 'nullable|string|max:500',
            'status' => 'pending'
        ]);

        $user = $this->currentUser();

        $booking = $action->execute($user, $validated);

        return to_route('bookings.show', $booking->id);

    }

    public function edit(Booking $booking)
    {
        $booking->load([
            'client',
            'items.service',
            'items.therapist',
        ]);

        $services = Service::where('status', 'active')->latest()->get();

        $therapists = User::where('status', 'active')
            ->where('role', 'therapist')
            ->latest()
            ->get();

        return view(
            $this->currentRoleView() . '.bookings.edit',
            compact('booking', 'services', 'therapists')
        );
    }

    public function update(Request $request, Booking $booking, UpdateBooking $action)
    {
        $validated = $request->validate([
            'booking_date' => 'required|date',
            'start_time' => 'required',
            'status' => 'required|in:pending,confirmed,active,completed,cancelled',

            'existing_items' => 'nullable|array',
            'existing_items.*.id' => 'required|exists:booking_items,id',
            'existing_items.*.service_id' => 'required|exists:services,id',
            'existing_items.*.therapist_id' => 'nullable|exists:users,id',

            'new_items' => 'nullable|array',
            'new_items.*.service_id' => 'required|exists:services,id',
            'new_items.*.therapist_id' => 'nullable|exists:users,id',
        ]);

        $action->execute($booking, $validated);

        return to_route('bookings.show', $booking->id)
            ->with('success', 'Booking updated successfully!');

    }

    /*
    public function confirm($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->update(['status' => 'confirmed']);
        return redirect()->back()->with('success', 'Booking confirmed successfully.');
    }

    public function decline($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->update(['status' => 'declined']);
        return redirect()->back()->with('success', 'Booking declined successfully.');
    }

    public function complete($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->update(['status' => 'completed']);
        return redirect()->back()->with('success', 'Booking marked as completed.');
    }

    public function cancel($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->update(['status' => 'cancelled']);
        return redirect()->back()->with('success', 'Booking cancelled successfully.');
    }
    */
}
