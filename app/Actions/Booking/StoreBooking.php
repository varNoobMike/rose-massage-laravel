<?php

namespace App\Actions\Booking;

use App\Exceptions\BookingDomainException;
use App\Models\Booking;
use App\Models\BookingItem;
use App\Models\OperatingHour;
use App\Models\Service;
use App\Models\User;
use App\Notifications\NewBookingNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class StoreBooking
{
    public function execute(User $user, array $data): Booking
    {
        return DB::transaction(function () use ($user, $data) {

            // =========================
            // SAFE PARSING (NO FORMAT CRASH)
            // =========================
            $startTime = Carbon::parse("{$data['booking_date']} {$data['start_time']}");
            $bookingDateTime = $startTime->copy();

            if ($bookingDateTime->isPast()) {
                throw new BookingDomainException('You cannot book a time in the past.');
            }

            // =========================
            // OPERATING HOURS
            // =========================
            $day = $startTime->format('l');

            $operatingHour = OperatingHour::where('day_of_week', $day)->first();

            if (!$operatingHour || $operatingHour->is_closed) {
                throw new BookingDomainException("Spa is closed on {$day}.");
            }

            if (!$operatingHour->start_time || !$operatingHour->end_time) {
                throw new BookingDomainException("Operating hours not configured for {$day}.");
            }

            $openingTime = Carbon::parse("{$data['booking_date']} {$operatingHour->start_time}");
            $closingTime = Carbon::parse("{$data['booking_date']} {$operatingHour->end_time}");

            // =========================
            // SERVICES VALIDATION
            // =========================
            $services = array_values($data['services'] ?? []);

            if (empty($services)) {
                throw new BookingDomainException('No services selected.');
            }

            $serviceModels = [];
            $totalAmount = 0;
            $totalDuration = 0;

            foreach ($services as $item) {

                $service = Service::find($item['id']);

                if (!$service) {
                    throw new BookingDomainException("One or more selected services are invalid.");
                }

                if ($service->duration_minutes <= 0) {
                    throw new BookingDomainException("Invalid service duration detected.");
                }

                $serviceModels[] = $service;
                $totalAmount += $service->price;
                $totalDuration += $service->duration_minutes;
            }

            $endTime = $startTime->copy()->addMinutes($totalDuration);

            // =========================
            // OPERATING HOURS CHECK
            // =========================
            if ($startTime->lt($openingTime) || $endTime->gt($closingTime)) {
                throw new BookingDomainException(
                    "Booking must be within {$openingTime->format('h:i A')} - {$closingTime->format('h:i A')}."
                );
            }


            // =========================
            // CREATE BOOKING
            // =========================
            $firstService = $serviceModels[0];

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

            // =========================
            // CREATE ITEMS (TIME-SAFE SEQUENCE)
            // =========================
            $cursor = $startTime->copy();

            foreach ($serviceModels as $service) {

                $itemStart = $cursor->copy();
                $itemEnd = $cursor->copy()->addMinutes($service->duration_minutes);

                if ($itemEnd->gt($endTime)) {
                    throw new BookingDomainException('Service scheduling overflow detected.');
                }

                BookingItem::create([
                    'booking_id' => $booking->id,
                    'service_id' => $service->id,
                    'therapist_id' => null,
                    'service_name' => $service->name,
                    'service_duration_minutes' => $service->duration_minutes,
                    'service_price' => $service->price,
                    'start_time' => $itemStart->format('H:i:s'),
                    'end_time' => $itemEnd->format('H:i:s'),
                    'notes' => $data['notes'] ?? null,
                ]);

                $cursor = $itemEnd;
            }

            // =========================
            // NOTIFICATIONS
            // =========================
            $recipients = User::whereIn('role', [
                User::ROLE_ADMIN,
                User::ROLE_OWNER,
                User::ROLE_RECEPTIONIST,
            ])->get();

            $recipients->push($booking->client);

            Notification::send($recipients, new NewBookingNotification($booking));

            return $booking;
        });
    }
}
