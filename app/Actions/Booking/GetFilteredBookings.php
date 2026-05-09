<?php

namespace App\Actions\Booking;

use App\Models\Booking;
use App\Models\User;

class GetFilteredBookings
{
    public function __construct(
        protected SyncBookingStatuses $syncBookingStatuses
    ) {}

    public function execute(User $user, array $filters = [])
    {
        $this->syncBookingStatuses->execute();

        $search = $filters['search'] ?? null;
        $dateFrom   = $filters['date_from'] ?? null;
        $dateTo = $filters['date_to'] ?? null;
        $status = $filters['status'] ?? null;
        $therapist_assignment_status = $filters['therapist_assignment_status'] ?? null;
        $service = $filters['service'] ?? null;
        $therapist = $filters['therapist'] ?? null;

        /**
         * Admin, Staff
         */
        if (in_array($user->role, [User::ROLE_ADMIN, User::ROLE_OWNER, User::ROLE_RECEPTIONIST])) {

            return Booking::with([
                'client.profile',
                'items',
                'items.therapist',
            ])

                // Search booking id, client name or email
                ->when($search, function ($query, $search) {

                    $query->where(function ($q) use ($search) {

                        // booking id
                        if (is_numeric($search)) {
                            $q->orWhere('id', $search);
                        }
                        
                        // client name or email
                        $q->orWhereHas('client', function ($client) use ($search) {
                            $client->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                        });
                    });
                })

                // Date from
                ->when($dateFrom, function ($query, $from) {
                    $query->whereDate('booking_date', '>=', $from);
                })

                // Date to
                ->when($dateTo, function ($query, $to) {
                    $query->whereDate('booking_date', '<=', $to);
                })

                // Status
                ->when($status, function ($query, $status) {
                    $query->where('status', $status);
                })

                // Therapist assignment status
                ->when($therapist_assignment_status, function ($query, $status) {

                    if ($status === 'unassigned') {
                        $query->whereHas('items')
                            ->whereDoesntHave('items', function ($q) {
                                $q->whereNotNull('therapist_id');
                            });
                    }

                    if ($status === 'partial') {
                        $query->whereHas('items', function ($q) {
                            $q->whereNull('therapist_id');
                        })
                            ->whereHas('items', function ($q) {
                                $q->whereNotNull('therapist_id');
                            });
                    }

                    if ($status === 'completed') {
                        $query->whereHas('items')
                            ->whereDoesntHave('items', function ($q) {
                                $q->whereNull('therapist_id');
                            });
                    }
                })

                // Service
                ->when($service, function ($query, $service_id) {
                    $query->whereHas('items', function ($q) use ($service_id) {
                        $q->where('service_id', $service_id);
                    });
                })

                // Therapist
                ->when($therapist, function ($query, $therapist_id) {
                    $query->whereHas('items', function ($q) use ($therapist_id) {
                        $q->where('therapist_id', $therapist_id);
                    });
                })

                // Final result 
                ->latest()
                ->paginate(10)
                ->withQueryString();
        }


        /**
         * Client
         */
        if ($user->role === User::ROLE_CLIENT) {

            return Booking::with([
                'client.profile',
                'items',
                'items.service',
                'items.therapist',
            ])

                // Search
                ->when($search, function ($query, $search) {

                    $query->where(function ($q) use ($search) {

                        // booking id
                        if (is_numeric($search)) {
                            $q->orWhere('id', $search);
                        }

                        // client name/email
                        $q->orWhereHas('client', function ($client) use ($search) {
                            $client->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                        });

                        // service name 
                        $q->orWhereHas('items.service', function ($service) use ($search) {
                            $service->where('name', 'like', "%{$search}%");
                        });
                        
                    });
                })

                // Date from
                ->when($dateFrom, function ($query, $from) {
                    $query->whereDate('booking_date', '>=', $from);
                })

                // Date to
                ->when($dateTo, function ($query, $to) {
                    $query->whereDate('booking_date', '<=', $to);
                })

                // Status
                ->when($status, function ($query, $status) {
                    return $query->where('status', $status);
                })

                // Service
                ->when($service, function ($query, $service_id) {
                    $query->whereHas('items', function ($q) use ($service_id) {
                        $q->where('service_id', $service_id);
                    });
                })

                // Final result 
                ->latest()
                ->paginate(10)
                ->withQueryString();
        }

        throw new \Exception('Unauthorized action.');
    }
}
