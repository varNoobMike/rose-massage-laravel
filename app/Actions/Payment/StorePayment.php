<?php

namespace App\Actions\Payment;

use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;

class StorePayment
{
    public function execute(Booking $booking, array $data): Payment
    {
        // Allow updating or making a new attempt unless an entry is already explicitly marked 'paid'
        if ($booking->payments()->where('status', 'paid')->exists()) {
            throw new \Exception('This booking has already been settled and marked as paid.');
        }

        // Wrap the creation inside a database transaction block
        return DB::transaction(function () use ($booking, $data) {

            // Optional: Clean up older, unverified pending payment logs 
            // if they are changing methods (e.g., Cash to GCash)
            $booking->payments()->where('status', 'pending')->delete();

            return $booking->payments()->create([
                'amount'           => $data['amount'] ?? $booking->total_amount,
                'gateway_fee'      => $data['gateway_fee'] ?? 0.00,
                'payment_method'   => $data['payment_method'] ?? 'cash',
                'status'           => $data['status'] ?? 'pending',
                'transaction_id'   => $data['transaction_id'] ?? null,
                'gateway_response' => $data['gateway_response'] ?? null,

                // Added for manual GCash processing flow
                'reference_number' => $data['reference_number'] ?? null,
                'receipt_path'     => $data['receipt_path'] ?? null,
            ]);
        });
    }
}
