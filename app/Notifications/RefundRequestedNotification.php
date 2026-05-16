<?php

namespace App\Notifications;

use App\Models\Booking;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Notifications\Notification;

class RefundRequestedNotification extends Notification
{
    public Booking $booking;
    public Payment $payment;

    /**
     * Create a new notification instance.
     */
    public function __construct(Booking $booking, Payment $payment)
    {
        $this->booking = $booking;
        $this->payment = $payment;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable): array
    {
        // Check if the recipient reading this notification is the client
        $isClient = $notifiable->role === User::ROLE_CLIENT;

        return [
            'booking_id'     => $this->booking->id,
            'payment_id'     => $this->payment->id,
            'client_name'    => $this->booking->client->name ?? 'Guest',
            'amount'         => $this->payment->amount,
            'payment_method' => ucfirst($this->payment->payment_method),

            // Dynamic message based on who reads it
            'message' => $isClient
                ? 'Your refund request for Booking #' . $this->booking->id . ' has been submitted and is currently under review.'
                : 'Refund requested for Booking #' . $this->booking->id . ' by ' . ($this->booking->client->name ?? 'Guest') . '. Amount: ₱' . number_format($this->payment->amount, 2),
        ];
    }
}
