<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class RefundProcessedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $booking;
    public $payment;

    /**
     * Create a new notification instance.
     */
    public function __construct($booking, $payment)
    {
        $this->booking = $booking;
        $this->payment = $payment;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable): array
    {
        // Only saving to the system database notifications table
        return ['database'];
    }

    /**
     * Get the array representation of the notification for database logging.
     */
    public function toArray($notifiable): array
    {
        $amount = number_format($this->booking->total_amount, 2);
        $isAdmin = in_array($notifiable->role, ['admin', 'owner']); // Adjust roles based on your User model constants

        // Custom contextual message depending on who reads the DB notification
        if ($isAdmin) {
            $msg = "Refund processed for Booking #{$this->booking->id} (" . strtoupper($this->payment->payment_method) . "). Total: ₱{$amount}.";
            if ($this->payment->payment_method === 'gcash') {
                $msg .= " Ref: {$this->payment->refund_reference}";
            }
        } else {
            $msg = $this->payment->payment_method === 'gcash'
                ? "Your refund of ₱{$amount} for Booking #{$this->booking->id} has been credited to your GCash. Ref: {$this->payment->refund_reference}."
                : "Your cash refund of ₱{$amount} for Booking #{$this->booking->id} has been handed over at the counter.";
        }

        return [
            'booking_id'       => $this->booking->id,
            'payment_id'       => $this->payment->id,
            'amount'           => $this->booking->total_amount,
            'payment_method'   => $this->payment->payment_method,
            'refund_reference' => $this->payment->refund_reference,
            'message'          => $msg,
        ];
    }
}
