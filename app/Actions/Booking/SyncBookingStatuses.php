<?php

namespace App\Actions\Booking;

use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SyncBookingStatuses
{
    public function execute(): int
    {
        $updated = 0;

        DB::transaction(function () use (&$updated) {

            $now = now();

            $bookings = Booking::query()
                ->whereIn('status', ['confirmed', 'active'])
                ->get();

            foreach ($bookings as $booking) {

                $start = Carbon::parse(
                    $booking->booking_date . ' ' . $booking->start_time
                );

                $end = Carbon::parse(
                    $booking->booking_date . ' ' . $booking->end_time
                );

                $newStatus = null;

                if ($booking->status === 'confirmed' && $now->gte($start)) {
                    $newStatus = 'active';
                }

                if (in_array($booking->status, ['confirmed', 'active']) && $now->gte($end)) {
                    $newStatus = 'completed';
                }

                if ($newStatus && $newStatus !== $booking->status) {
                    $booking->status = $newStatus;
                    // Prevent observer/event firing
                    $booking->saveQuietly();
                    $updated++;
                }
            }
        });

        return $updated;
    }
}
