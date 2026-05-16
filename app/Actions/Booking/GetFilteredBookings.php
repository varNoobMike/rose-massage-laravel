<?php

namespace App\Actions\Booking;

use App\Models\Booking;
use App\Models\User;

class GetFilteredBookings
{
    public function execute(array $filters, User $user)
    {
        return match ($user->role) {

            User::ROLE_ADMIN,
            User::ROLE_OWNER,
            User::ROLE_RECEPTIONIST => $this->adminQuery($filters),

            User::ROLE_CLIENT => $this->clientQuery($filters, $user),

            default => throw new \Exception('unauthorized action'),
        };
    }

    private function adminQuery(array $filters)
    {
        $search = $filters['search'] ?? null;
        $dateFrom = $filters['date_from'] ?? null;
        $dateTo = $filters['date_to'] ?? null;
        $status = $filters['status'] ?? null;
        $assignment = $filters['therapist_assignment_status'] ?? null;
        $service = $filters['service'] ?? null;
        $therapist = $filters['therapist'] ?? null;
        $paymentStatus = $filters['payment_status'] ?? null;

        $query = Booking::with([
            'client.profile',
            'items.therapist',
            'payments',
        ]);

        // search
        $query->when($search, function ($q, $search) {

            $q->where(function ($sub) use ($search) {

                if (is_numeric($search)) {
                    $sub->orWhere('id', (int) $search);
                }

                $sub->orWhereHas('client', function ($client) use ($search) {
                    $client->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            });
        });

        // date from
        $query->when($dateFrom, fn($q) => $q->whereDate('booking_date', '>=', $dateFrom));

        // date to
        $query->when($dateTo, fn($q) => $q->whereDate('booking_date', '<=', $dateTo));

        // status
        $query->when($status, fn($q) => $q->where('status', $status));

        // service filter
        $query->when($service, function ($q, $service) {
            $q->whereHas('items', fn($i) => $i->where('service_id', $service));
        });

        // therapist filter
        $query->when($therapist, function ($q, $therapist) {
            $q->whereHas('items', fn($i) => $i->where('therapist_id', $therapist));
        });

        // assignment status
        $query->when($assignment, function ($q, $assignment) {

            // unassigned
            if ($assignment === 'unassigned') {
                $q->whereDoesntHave('items', fn($i) => $i->whereNotNull('therapist_id'));
            }

            // partial assignment
            if ($assignment === 'partial') {
                $q->whereHas('items', fn($i) => $i->whereNull('therapist_id'))
                    ->whereHas('items', fn($i) => $i->whereNotNull('therapist_id'));
            }

            // fully assigned
            if ($assignment === 'fully_assigned') {
                $q->whereDoesntHave('items', fn($i) => $i->whereNull('therapist_id'));
            }
        });

        // payment status
        $query->when($paymentStatus, function ($q, $paymentStatus) {
            if ($paymentStatus === 'paid') {
                $q->whereHas('payments', fn($p) => $p->where('status', 'successful'));
            }

            if ($paymentStatus === 'refund_pending') {
                $q->whereHas('payments', fn($p) => $p->where('status', 'refund_pending'));
            }

            if ($paymentStatus === 'refunded') {
                $q->whereHas('payments', fn($p) => $p->where('status', 'refunded'));
            }

            if ($paymentStatus === 'unpaid') {
                $q->whereDoesntHave('payments', fn($p) => $p->where('status', 'successful'))
                 ->whereDoesntHave('payments', fn($p) => $p->where('status', 'refund_pending'))
                 ->whereDoesntHave('payments', fn($p) => $p->where('status', 'refunded'));;
            }
        });

        // final result
        return $query->latest()
            ->paginate(10)
            ->withQueryString();
    }

    private function clientQuery(array $filters, User $user)
    {
        $search = $filters['search'] ?? null;
        $dateFrom = $filters['date_from'] ?? null;
        $dateTo = $filters['date_to'] ?? null;
        $status = $filters['status'] ?? null;
        $service = $filters['service'] ?? null;
        $paymentStatus = $filters['payment_status'] ?? null;

        $query = Booking::with([
            'client.profile',
            'items.service',
            'payments',
        ])->where('client_id', $user->id);

        // search
        $query->when($search, function ($q, $search) {

            $q->where(function ($sub) use ($search) {

                // booking id search
                if (is_numeric($search)) {
                    $sub->orWhere('id', (int) $search);
                }

                // service name search
                $sub->orWhereHas('items.service', function ($s) use ($search) {
                    $s->where('name', 'like', "%{$search}%");
                });
            });
        });

        // date from
        $query->when($dateFrom, fn($q) => $q->whereDate('booking_date', '>=', $dateFrom));

        // date to
        $query->when($dateTo, fn($q) => $q->whereDate('booking_date', '<=', $dateTo));

        // status
        $query->when($status, fn($q) => $q->where('status', $status));

        // service filter
        $query->when($service, function ($q, $service) {
            $q->whereHas('items', fn($i) => $i->where('service_id', $service));
        });

        // payment status
        $query->when($paymentStatus, function ($q, $paymentStatus) {
            if ($paymentStatus === 'paid') {
                $q->whereHas('payments', fn($p) => $p->where('status', 'successful'));
            }

            if ($paymentStatus === 'refund_pending') {
                $q->whereHas('payments', fn($p) => $p->where('status', 'refund_pending'));
            }

            if ($paymentStatus === 'refunded') {
                $q->whereHas('payments', fn($p) => $p->where('status', 'refunded'));
            }

            if ($paymentStatus === 'unpaid') {
                $q->whereDoesntHave('payments', fn($p) => $p->where('status', 'successful'))
                ->whereDoesntHave('payments', fn($p) => $p->where('status', 'refund_pending'))
                ->whereDoesntHave('payments', fn($p) => $p->where('status', 'refunded'));
            }
        });

        // final result
        return $query->latest()
            ->paginate(10)
            ->withQueryString();
    }
}
