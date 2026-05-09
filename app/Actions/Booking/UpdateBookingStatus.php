<?php

namespace App\Actions\Booking;

use App\Exceptions\BookingTooEarlyToActivateException;
use App\Exceptions\TherapistNotAssignedException;
use App\Models\Booking;
use App\Notifications\BookingStatusNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class UpdateBookingStatus
{
    public function execute(Booking $booking, string $status): Booking
    {

        return DB::transaction(function () use ($booking, $status) {

            /*
            $hasUnassigned = $booking->items()
                ->whereNull('therapist_id')
                ->exists();

            if ($hasUnassigned) {
                throw new TherapistNotAssignedException(
                    'Complete massage therapist assignment first.'
                );
            }*/


            // update status
            $booking->update([
                'status' => $status,
            ]);

            // notify client
            if ($booking->client) {
                $booking->client->notify(
                    new BookingStatusNotification($booking, $status)
                );
            }

            return $booking;
        });
    }
}
