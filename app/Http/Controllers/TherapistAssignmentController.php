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
            'items.therapist'
        ]);

        $therapists = User::where('role', User::ROLE_THERAPIST)
            ->where('status', 'active')
            ->get();

        return view(
            $this->currentRoleView() . '.therapist-assignment.index',
            compact('booking', 'therapists')
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

        return redirect()
            ->back()
            ->with('success', 'Therapists assigned successfully.');
    }
}
