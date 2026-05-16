<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\User;
use Illuminate\Http\Request;

class TherapistAssignmentController extends Controller
{
    public function index(Booking $booking)
    {
        $booking->load([
            'client',
            'items.therapist',
        ]);

        // attach available therapists per service item
        foreach ($booking->items as $item) {

            $start = $item->start_time;
            $end   = $item->end_time;
            $date  = $booking->booking_date;

            $item->available_therapists = User::where('role', User::ROLE_THERAPIST)
                ->where('status', 'active')
                ->whereDoesntHave('bookingItems', function ($q) use ($date, $start, $end) {

                    $q->whereHas('booking', function ($b) use ($date) {
                        $b->where('booking_date', $date);
                    })

                        ->where(function ($q) use ($start, $end) {

                            $q->whereBetween('start_time', [$start, $end])
                                ->orWhereBetween('end_time', [$start, $end])
                                ->orWhere(function ($q) use ($start, $end) {
                                    $q->where('start_time', '<=', $start)
                                        ->where('end_time', '>=', $end);
                                });
                        });
                })
                ->get();
        }

        return view(
            $this->currentRoleView() . '.therapist-assignment.index',
            compact('booking')
        );
    }

    public function update(Request $request, Booking $booking)
    {
        $request->validate(
            [
                'assignments' => 'required|array',
                'assignments.*' => 'required|exists:users,id',
            ],
            [
                'assignments.required' => 'Assignment data is missing.',
                'assignments.array' => 'Invalid assignment format.',

                'assignments.*.required' => 'Please assign a therapist for this service.',
                'assignments.*.exists' => 'Selected therapist is invalid.',
            ]
        );

        foreach ($request->assignments as $itemId => $therapistId) {

            $item = $booking->items()->where('id', $itemId)->first();

            if (!$item) continue;

            $item->therapist_id = $therapistId;
            $item->save();
        }

        return
            to_route('bookings.show', $booking->id)
            ->with('success', 'Therapists assigned successfully.');
    }
}
