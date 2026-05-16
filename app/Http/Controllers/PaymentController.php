<?php

namespace App\Http\Controllers;

use App\Actions\Payment\StorePayment;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\User;
use App\Notifications\PaymentRejectedNotification;
use App\Notifications\PaymentSubmittedNotification;
use App\Notifications\PaymentVerifiedNotification;
use App\Notifications\RefundProcessedNotification;
use App\Notifications\RefundRequestedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
            $booking->update(['status' => 'pending']);

            // 1. Notify the Client who owns the booking
            if ($booking->client) {
                $booking->client->notify(new PaymentRejectedNotification($booking, $payment));
            }

            // 2. Audit/Notify other management staff (Excluding the user executing the rejection)
            $adminUsers = User::whereIn('role', [User::ROLE_ADMIN, User::ROLE_OWNER])
                ->get();

            Notification::send($adminUsers, new PaymentRejectedNotification($booking, $payment));

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

            // ----------------------------------------------------
            // NOTIFICATIONS SYSTEM
            // ----------------------------------------------------

            // 1. Notify the Client who owns the booking
            if ($booking->client) {
                $booking->client->notify(new PaymentVerifiedNotification($booking, $payment));
            }

            // 2. Audit/Notify other management staff (Excluding the user executing the verification)
            $adminUsers = User::whereIn('role', [User::ROLE_ADMIN, User::ROLE_OWNER])
                ->get();

            Notification::send($adminUsers, new PaymentVerifiedNotification($booking, $payment));

            // ----------------------------------------------------

            $message = $payment->payment_method === 'cash'
                ? 'Cash payment recorded. Booking is now confirmed!'
                : 'GCash payment verified successfully!';

            return redirect()->back()->with('success', $message);
        }

        return redirect()->back()->with('error', 'Invalid action execution.');
    }

    public function requestRefund(Booking $booking)
    {
        // 1. Safety Check: Ensure the booking is actually cancelled
        if ($booking->status !== 'cancelled') {
            return redirect()->back()->with('error', 'Only cancelled bookings are eligible for a refund.');
        }

        $latestPayment = $booking->payments->last();

        // 2. Safety Check: Verify a valid successful payment exists to refund
        if (!$latestPayment || !in_array($latestPayment->status, ['successful'])) {
            return redirect()->back()->with('error', 'No valid or completed payment found to refund.');
        }

        // 3. Transition payment state
        $latestPayment->update([
            'status' => 'refund_pending'
        ]);

        // 4. Send confirmation notification to the booking owner (Client)
        if ($booking->client) {
            $booking->client->notify(new RefundRequestedNotification($booking, $latestPayment));
        }

        // 5. Send system alert notifications to Admin & Owners
        $adminUsers = User::whereIn('role', [User::ROLE_ADMIN, User::ROLE_OWNER])->get();
        Notification::send($adminUsers, new RefundRequestedNotification($booking, $latestPayment));

        return redirect()->back()->with('success', 'Your refund request has been submitted for review.');
    }

    public function approveRefund(Request $request, Payment $payment)
    {
        // 1. Ensure the payment is actually in a state waiting for a refund
        if ($payment->status !== 'refund_pending') {
            return redirect()->back()->with('error', 'This payment is not awaiting a refund.');
        }

        // 2. Validate input if the payment method used was GCash
        if ($payment->payment_method === 'gcash') {
            $request->validate([
                'refund_reference' => 'required|string|max:255|unique:payments,refund_reference',
            ], [
                'refund_reference.required' => 'The GCash transaction reference number is required.',
                'refund_reference.unique' => 'This reference number has already been used for another refund.',
            ]);
        }

        // 3. Update the transaction parameters
        $payment->update([
            'status' => 'refunded',
            'refund_reference' => $payment->payment_method === 'gcash' ? $request->input('refund_reference') : null,
        ]);

        // 4. Update the parent booking status if needed (Optional but recommended)
        // $payment->booking->update(['status' => 'refunded']);

        // 5. Fire off the confirmation notification to the client
        $booking = $payment->booking;
        if ($booking && $booking->client) {
            $booking->client->notify(new RefundProcessedNotification($booking, $payment));
        }

        // 6. Notify the Admin & Owners that the refund has been completed
        $adminUsers = User::whereIn('role', [User::ROLE_ADMIN, User::ROLE_OWNER])->get();
        if ($adminUsers->isNotEmpty()) {
            Notification::send($adminUsers, new RefundProcessedNotification($booking, $payment));
        }

        return redirect()->back()->with('success', 'Refund has been successfully processed and recorded.');
    }
}
