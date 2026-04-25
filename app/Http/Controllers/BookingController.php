<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Booking;
use App\Models\BookingItem;
use App\Models\Service;
use App\Models\Spa;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BookingController extends Controller
{
    public function index(Request $request)
    {

        $user = $this->currentUser();
        $search = $request->search;

        return match ($user->role) {

            // ================= ADMIN =================
            'admin', 'owner', 'receptionist' => view('admin.bookings.index', [
                'bookings' => Booking::with('client')

                    // SEARCH (booking id OR client name/email/id)
                    ->when($search, function ($query, $search) {
                        $query->where(function ($q) use ($search) {

                            // booking id search
                            $q->where('id', $search)

                                // OR client search
                                ->orWhereHas('client', function ($client) use ($search) {
                                    $client->where('name', 'like', "%{$search}%")
                                        ->orWhere('email', 'like', "%{$search}%")
                                        ->orWhere('id', $search);
                                });
                        });
                    })

                    // STATUS FILTER
                    ->when($request->status, function ($query, $status) {
                        return match ($status) {
                            'active', 'pending', 'confirmed', 'completed', 'cancelled'
                            => $query->where('status', $status),

                            'all' => $query,

                            default => $query,
                        };
                    })

                    // DATE FILTER
                    ->when($request->date, function ($query, $date) {
                        $query->whereDate('booking_date', $date);
                    })

                    ->latest()
                    ->paginate(10)
                    ->withQueryString(),
            ]),

            // ================= CLIENT =================
            'client' => view('user.bookings.index', [
                'bookings' => Booking::with(['items.service'])

                    ->where('client_id', $user->id)

                    // SEARCH (ONLY booking id for client)
                    ->when($search, function ($query, $search) {
                        $query->where('id', $search);
                    })

                    // STATUS FILTER
                    ->when($request->status, function ($q, $status) {
                        return $status === 'all'
                            ? $q
                            : $q->where('status', $status);
                    })

                    // DATE FILTER
                    ->when($request->date, function ($q, $date) {
                        $q->whereDate('booking_date', $date);
                    })

                    ->latest()
                    ->paginate(10)
                    ->withQueryString(),
            ]),

            default => abort(403),
        };
    }

    public function show(Booking $booking)
    {
        return view(
            $this->currentRoleView() . '.bookings.show',
            ['booking' => $booking]
        );
    }

    public function create()
    {
        $user = $this->currentUser();

        return match ($user->role) {
            'admin, receptionist' => view('admin.bookings.create'), // to be implemented the admin create booking
            'client' => view('user.bookings.create', [
                'services' => Service::where('status', 'active')->latest()->get(),
            ]),
            default => abort(403),
        };
    }


    public function store(Request $request)
    {

        $request->validate([
            'services' => 'required|array|min:1',
            'services.*.id' => 'required|exists:services,id',
            'booking_date' => 'required|date|after_or_equal:today',
            'start_time' => 'required',
            'notes' => 'nullable|string|max:500',
        ]);

        try {
            $user = $this->currentUser();

            $services = $request->services;

            $startTime = \Carbon\Carbon::parse($request->start_time);

            $totalAmount = 0;
            $totalDuration = 0;

            // Calculate total first
            foreach ($services as $item) {
                $service = Service::findOrFail($item['id']);

                $totalAmount += $service->price;
                $totalDuration += $service->duration_minutes;
            }

            // Final end time
            $endTime = $startTime->copy()->addMinutes($totalDuration);

            $booking = DB::transaction(function () use (
                $request,
                $user,
                $services,
                $startTime,
                $endTime,
                $totalAmount
            ) {

                $firstService = Service::findOrFail($services[0]['id']);

                $booking = Booking::create([
                    'client_id' => $user->id,
                    'spa_id' => $firstService->spa_id,
                    'booking_date' => $request->booking_date,
                    'start_time' => $startTime->format('H:i:s'),
                    'end_time' => $endTime->format('H:i:s'),
                    'status' => 'pending',
                    'total_amount' => $totalAmount,
                    'therapist_assigned' => 0,
                    'notes' => $request->notes,
                ]);

                foreach ($services as $item) {

                    $service = Service::findOrFail($item['id']);

                    BookingItem::create([
                        'booking_id' => $booking->id,
                        'service_id' => $service->id,
                        'therapist_id' => null,
                        'service_name' => $service->name,
                        'service_duration_minutes' => $service->duration_minutes,
                        'service_price' => $service->price,
                        'notes' => $request->notes,
                    ]);
                }

                return $booking;
            });

            return redirect()
                ->route('bookings.show', $booking->id)
                ->with('success', 'Booking created successfully!');
        } catch (\Exception $e) {

            Log::error($e->getMessage());

            return back()
                ->withInput()
                ->with('error', 'Booking failed. Please try again.');
        }
    }

    public function edit(Booking $booking)
    {
        return view(
            $this->currentRoleView() . '.bookings.edit',
            [
                'booking' => $booking,
                'services' => Service::where('status', 'active')->latest()->get(),
                'therapists' => User::where('status', 'active')->where('role', 'therapist')->latest()->get()
            ]
        );
    }

    public function update(Request $request, Booking $booking)
    {
        $request->validate([
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

        try {

            DB::transaction(function () use ($request, $booking) {

                $startTime = \Carbon\Carbon::parse($request->start_time);

                $totalAmount = 0;
                $totalDuration = 0;

                /**
                 * update booking basic info
                 */
                $booking->update([
                    'booking_date' => $request->booking_date,
                    'start_time' => $startTime->format('H:i:s'),
                    'status' => $request->status,
                ]);

                // keep track of updated item IDs
                $keepItemIds = [];

                /**
                 * update existing items
                 */
                if ($request->existing_items) {

                    foreach ($request->existing_items as $itemData) {

                        $item = BookingItem::findOrFail($itemData['id']);

                        $service = Service::findOrFail($itemData['service_id']);

                        $item->update([
                            'service_id' => $service->id,
                            'therapist_id' => $itemData['therapist_id'] ?? null,
                            'service_name' => $service->name,
                            'service_duration_minutes' => $service->duration_minutes,
                            'service_price' => $service->price,
                        ]);

                        $totalAmount += $service->price;
                        $totalDuration += $service->duration_minutes;

                        $keepItemIds[] = $item->id;
                    }
                }

                /**
                 * add new items
                 */
                if ($request->new_items) {

                    foreach ($request->new_items as $newItem) {

                        $service = Service::findOrFail($newItem['service_id']);

                        $created = BookingItem::create([
                            'booking_id' => $booking->id,
                            'service_id' => $service->id,
                            'therapist_id' => $newItem['therapist_id'] ?? null,
                            'service_name' => $service->name,
                            'service_duration_minutes' => $service->duration_minutes,
                            'service_price' => $service->price,
                        ]);

                        $totalAmount += $service->price;
                        $totalDuration += $service->duration_minutes;

                        $keepItemIds[] = $created->id;
                    }
                }

                /**
                 * delete removed items
                 */
                BookingItem::where('booking_id', $booking->id)
                    ->whereNotIn('id', $keepItemIds)
                    ->delete();


                /**
                 * recalculated end time
                 */
                $endTime = $startTime->copy()->addMinutes($totalDuration);

                /**
                 * update totals
                 */
                $booking->update([
                    'end_time' => $endTime->format('H:i:s'),
                    'total_amount' => $totalAmount,
                ]);
            });

            return redirect()
                ->route('bookings.show', $booking->id)
                ->with('success', 'Booking updated successfully!');
        } catch (\Exception $e) {

            Log::error($e->getMessage());

            return back()
                ->with('error', 'Failed to update booking. Please try again.');
        }
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
