<?php

namespace App\Actions\Booking;

use App\Models\Booking;
use App\Models\BookingItem;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class UpdateBooking
{
    public function execute(Booking $booking, array $data): Booking
    {
        return DB::transaction(function () use ($booking, $data) {

            $startTime = Carbon::parse($data['start_time']);

            $totalAmount = 0;
            $totalDuration = 0;

            // Update booking base info
            $booking->update([
                'booking_date' => $data['booking_date'],
                'start_time' => $startTime->format('H:i:s'),
                'status' => $data['status'],
            ]);

            $keepItemIds = [];

            /**
             * Update existing items
             */
            foreach ($data['existing_items'] ?? [] as $itemData) {

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

            /**
             * Add new items
             */
            foreach ($data['new_items'] ?? [] as $newItem) {

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

            /**
             * Remove deleted items
             */
            BookingItem::where('booking_id', $booking->id)
                ->whereNotIn('id', $keepItemIds)
                ->delete();

            /**
             * Recalculate end time
             */
            $endTime = $startTime->copy()->addMinutes($totalDuration);

            /**
             * Update totals
             */
            $booking->update([
                'end_time' => $endTime->format('H:i:s'),
                'total_amount' => $totalAmount,
            ]);

            return $booking;
        });
    }
}