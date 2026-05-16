<?php

namespace App\Http\Controllers;

use App\Actions\Payment\StorePayment;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\User;
use App\Notifications\PaymentSubmittedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class PaymentController extends Controller
{
    /**
     * Handle Cash / Over-the-Counter payment selection.
     */
    public function payAtCounter(Booking $booking, StorePayment $action)
    {
        // Guard check: Only confirmed bookings can accept billing setups
        if ($booking->status !== Booking::STATUS_CONFIRMED) {
            return redirect()
                ->back()
                ->with('error', 'This booking is not eligible for payment processing.');
        }

        try {
            // Execute the action with your custom payload data
            $action->execute($booking, [
                'amount'         => $booking->total_amount,
                'gateway_fee'    => 0.00,
                'payment_method' => 'cash',
                'status'         => 'pending',
            ]);

            return redirect()->route('bookings.show', $booking->id)
                ->with('success', 'Over-the-counter payment method locked! Please pay at the front desk when you arrive.');
        } catch (\Exception $e) {
            Log::error('Payment Action Failed for Booking ID ' . $booking->id . ': ' . $e->getMessage());

            return redirect()->back()->with('error', 'Something went wrong while processing your payment. Please try again or contact support.');
        }
    }

    public function submitGcash(Request $request, Booking $booking, StorePayment $action)
    {
        $request->validate([
            'reference_number' => 'required|digits:13',
            'receipt' => 'nullable|image|max:2048',
        ]);

        $path = $request->hasFile('receipt')
            ? $request->file('receipt')->store('receipts', 'public')
            : null;

        try {
            $payment = $action->execute($booking, [
                'payment_method'   => 'gcash',
                'status'           => 'pending',
                'reference_number' => $request->reference_number,
                'receipt_path'     => $path,
            ]);

            // 1. Notify the Client who just paid
            $booking->client->notify(new PaymentSubmittedNotification($booking, $payment));

            // 2. Notify Admins, Owners, and Receptionists
            $adminUsers = User::whereIn('role', [User::ROLE_ADMIN, User::ROLE_OWNER, 'receptionist'])->get();
            Notification::send($adminUsers, new PaymentSubmittedNotification($booking, $payment));

            return redirect()->back()->with('success', 'GCash proof submitted! Waiting for validation.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Verify and update booking payment status (GCash or Cash Counter).
     */
    public function verify(Payment $payment, $action)
    {
        // Load the related booking
        $booking = $payment->booking;

        // 1. Handle REJECT action (Only applicable to GCash pending states)
        if ($action === 'reject') {
            if ($payment->payment_method !== 'gcash') {
                return redirect()->back()->with('error', 'Only GCash payments can be rejected.');
            }

            $payment->update(['status' => 'failed']);

            // Optional: Update booking status back to 'pending_payment' or 'cancelled'
            $booking->update(['status' => 'pending_payment']);

            return redirect()->back()->with('warning', 'Payment reference has been rejected.');
        }

        // 2. Handle APPROVE action (Applies to both GCash validation and Cash counter)
        if ($action === 'approve') {

            // Use a database transaction to ensure both records update successfully
            DB::transaction(function () use ($payment, $booking) {

                // Update payment status
                $payment->update([
                    'status' => 'successful',
                    // If it was cash, it might not have an external reference, so we can generate one
                    'reference_number' => $payment->payment_method === 'cash'
                        ? 'CASH-' . strtoupper(uniqid())
                        : $payment->reference_number
                ]);

                // Update booking status to confirmed/paid
                $booking->update([
                    'status' => 'confirmed' // or 'paid' depending on your booking status workflow
                ]);
            });

            $message = $payment->payment_method === 'cash'
                ? 'Cash payment recorded. Booking is now confirmed!'
                : 'GCash payment verified successfully!';

            return redirect()->back()->with('success', $message);
        }

        return redirect()->back()->with('error', 'Invalid action execution.');
    }
}
