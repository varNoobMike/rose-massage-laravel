<?php

namespace App\Actions\Booking;

use App\Models\Booking;
use App\Models\BookingItem;
use App\Models\Service;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use App\Notifications\NewBookingNotification;
use Carbon\Carbon;

class StoreBooking
{
    public function execute(User $user, array $data): Booking
    {
        return DB::transaction(function () use ($user, $data) {

            $services = array_values($data['services'] ?? []);

            $startTime = Carbon::parse($data['start_time']);

            $totalAmount = 0;
            $totalDuration = 0;

            // Compute totals
            foreach ($services as $item) {
                $service = Service::findOrFail($item['id']);
                $totalAmount += $service->price;
                $totalDuration += $service->duration_minutes;
            }

            $endTime = $startTime->copy()->addMinutes($totalDuration);

            $firstService = Service::findOrFail($services[0]['id']);

            // Create booking
            $booking = Booking::create([
                'client_id' => $user->id,
                'spa_id' => $firstService->spa_id,
                'booking_date' => $data['booking_date'],
                'start_time' => $startTime->format('H:i:s'),
                'end_time' => $endTime->format('H:i:s'),
                'status' => Booking::STATUS_PENDING,
                'total_amount' => $totalAmount,
                'therapist_assigned' => 0,
                'notes' => $data['notes'] ?? null,
            ]);

            // Create booking items
            foreach ($services as $item) {
                $service = Service::findOrFail($item['id']);

                BookingItem::create([
                    'booking_id' => $booking->id,
                    'service_id' => $service->id,
                    'therapist_id' => null,
                    'service_name' => $service->name,
                    'service_duration_minutes' => $service->duration_minutes,
                    'service_price' => $service->price,
                    'notes' => $data['notes'] ?? null,
                ]);
            }

            // Send notification
            $recipients = User::whereIn('role', [
                User::ROLE_ADMIN,
                User::ROLE_OWNER,
                User::ROLE_RECEPTIONIST,
            ])->get();

            // include the owner(client who created the booking) only
            $recipients->push($booking->client);

            Notification::send($recipients, new NewBookingNotification($booking));

            return $booking;
        });
    }
}
