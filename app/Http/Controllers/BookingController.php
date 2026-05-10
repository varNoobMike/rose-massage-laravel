<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Actions\Booking\GetFilteredBookings;
use App\Actions\Booking\SyncBookingStatuses;
use App\Actions\Booking\StoreBooking;
use App\Actions\Booking\UpdateBooking;
use App\Actions\Booking\UpdateBookingStatus;
use App\Models\Booking;
use App\Models\Service;
use App\Models\User;

class BookingController extends Controller
{

    public function index(
        Request $request,
        GetFilteredBookings $action,
        SyncBookingStatuses $syncStatuses
    ) {
        // sync booking statuses
        $syncStatuses->execute();

        // get filters from request
        $filters = $request->only([
            'search',
            'date_from',
            'date_to',
            'status',
            'therapist_assignment_status',
            'service',
            'therapist',
        ]);

        // fetch filtered bookings
        $bookings = $action->execute($filters, $this->currentUser());

        // load filter options
        $services = Service::get();
        $therapists = User::where('role', User::ROLE_THERAPIST)->get();

        // basic filters state
        $hasBasicFilters =
            !empty($filters['search']) ||
            !empty($filters['date_from']) ||
            !empty($filters['date_to']) ||
            !empty($filters['status']);

        // advanced filters state
        $hasAdvancedFilters =
            !empty($filters['service']) ||
            !empty($filters['therapist']) ||
            !empty($filters['therapist_assignment_status']);

        // global filters state
        $hasFilters = $hasBasicFilters || $hasAdvancedFilters;

        // selected values
        $serviceId = $filters['service'] ?? null;
        $therapistId = $filters['therapist'] ?? null;

        // selected models
        $selectedService = $serviceId
            ? $services->firstWhere('id', $serviceId)
            : null;

        $selectedTherapist = $therapistId
            ? $therapists->firstWhere('id', $therapistId)
            : null;

        // render view
        return view(
            $this->currentRoleView() . '.bookings.index',
            compact(
                'bookings',
                'services',
                'therapists',
                'filters',
                'hasFilters',
                'hasBasicFilters',
                'hasAdvancedFilters',
                'selectedService',
                'selectedTherapist'
            )
        );
    }

    public function show(Booking $booking, SyncBookingStatuses $syncStatuses)
    {
        $syncStatuses->execute();

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
        ]);

        $user = $this->currentUser();

        $booking = $action->execute($user, $validated);

        return to_route('bookings.show', $booking->id)
            ->with('info', 'Your booking request has been sent. Please wait for confirmation.');
    }

    public function edit(Booking $booking, SyncBookingStatuses $syncStatuses)
    {
        $syncStatuses->execute();

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

    public function confirm(Booking $booking, UpdateBookingStatus $action)
    {
        $action->execute($booking, 'confirmed');
        return to_route('bookings.show', $booking->id)
            ->with('success', 'Booking confirmed successfully.');
    }

    public function reject(Booking $booking, UpdateBookingStatus $action)
    {
        $action->execute($booking, 'rejected');
        return to_route('bookings.show', $booking->id)
            ->with('success', 'Booking rejected successfully.');
    }

    public function cancel(Booking $booking, UpdateBookingStatus $action)
    {
        $action->execute($booking, 'cancelled');
        return to_route('bookings.show', $booking->id)
            ->with('success', 'Booking cancelled successfully.');
    }

    /*
    public function syncStatuses(SyncBookingStatuses $action)
    {
        $updated = $action->execute();

        return response()->json([
            'success' => true,
            'message' => 'Booking statuses synced successfully.',
            'data' => [
                'updated_count' => $updated,
            ],
        ]);
    }
    */
}
