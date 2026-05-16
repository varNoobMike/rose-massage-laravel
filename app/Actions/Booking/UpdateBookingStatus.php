<?php

namespace App\Actions\Booking;

use App\Models\Booking;
use App\Models\User;
use App\Notifications\BookingStatusNotification;
use Illuminate\Support\Facades\Notification;
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

            // send notification
            $recipients = User::whereIn('role', [
                User::ROLE_ADMIN,
                User::ROLE_OWNER,
                User::ROLE_RECEPTIONIST,
            ])->get();

            // include the owner(client who created the booking) only
            $recipients->push($booking->client);

            Notification::send($recipients, new BookingStatusNotification($booking, $status));

            return $booking;
        });
    }
}
