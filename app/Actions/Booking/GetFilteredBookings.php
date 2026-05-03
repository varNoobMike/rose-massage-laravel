<?php

namespace App\Actions\Booking;

use App\Models\Booking;
use App\Models\User;

class GetFilteredBookings
{
    public function execute($user, array $filters = [])
    {
        $search = $filters['search'] ?? null;
        $status = $filters['status'] ?? null;
        $therapist_assignment_status = $filters['therapist_assignment_status'] ?? null;
        $from   = $filters['from'] ?? null;
        $to = $filters['to'] ?? null;


        // Spa Insider
        if (in_array($user->role, [User::ROLE_ADMIN, User::ROLE_OWNER, User::ROLE_RECEPTIONIST])) {

            return Booking::with('client')

                // Search booking or client
                ->when($search, function ($query, $search) {
                    $query->where(function ($q) use ($search) {
                        $q->where('id', $search)
                          ->orWhereHas('client', function ($client) use ($search) {
                              $client->where('name', 'like', "%{$search}%")
                                     ->orWhere('email', 'like', "%{$search}%")
                                     ->orWhere('id', $search);
                          });
                    });
                })

                // Status filter
                ->when($status, function ($query, $status) {
                    return $status === 'all'
                        ? $query
                        : $query->where('status', $status);
                })

                // therapist assignment
                ->when($therapist_assignment_status, function ($query, $status) {
                    if ($status === 'assigned') {
                        $query->whereDoesntHave('items', function ($q) {
                            $q->whereNull('therapist_id');
                        });
                    }

                    if ($status === 'unassigned') {
                        $query->whereHas('items', function ($q) {
                            $q->whereNull('therapist_id');
                        });
                    }
                })
                                

                // Date from
                ->when($from, function ($q, $from) {
                    $q->whereDate('booking_date', '>=', $from);
                })

                // Date to
                ->when($to, function ($q, $to) {
                    $q->whereDate('booking_date', '<=', $to);
                })

                ->latest()
                ->paginate(10)
                ->withQueryString();
        }


        // Client/Customer
        if ($user->role === User::ROLE_CLIENT) {

            return Booking::with(['items.service'])

                // Own bookings only
                ->where('client_id', $user->id)

                // Search by ID
                ->when($search, function ($query, $search) {
                    $query->where('id', $search);
                })

                // Status filter
                ->when($status, function ($query, $status) {
                    return $status === 'all'
                        ? $query
                        : $query->where('status', $status);
                })

                // Date filter
                ->when($date, function ($query, $date) {
                    $query->whereDate('booking_date', $date);
                })

                ->latest()
                ->paginate(10)
                ->withQueryString();
        }

        throw new \Exception('Unauthorized action.');
    }
}