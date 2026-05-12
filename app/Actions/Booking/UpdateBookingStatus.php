<?php

namespace App\Actions\Booking;

use App\Models\Booking;
use App\Notifications\BookingStatusNotification;
use Illuminate\Support\Facades\DB;

class UpdateBookingStatus
{
    public function execute(Booking $booking, string $status): Booking
    {

        return DB::transaction(function () use ($booking, $status) {

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
