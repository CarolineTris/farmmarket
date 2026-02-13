<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BuyerOrderStatusNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Order $order,
        public string $status,
        public ?string $reason = null,
        public ?string $updatedBy = null
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $targetUrl = route('buyer.orders');
        $statusLabel = match ($this->status) {
            'completed' => 'Completed',
            'cancelled' => 'Cancelled',
            'pending' => 'Pending Fulfillment',
            'payment_failed' => 'Payment Failed',
            default => ucfirst(str_replace('_', ' ', $this->status)),
        };

        $mail = (new MailMessage)
            ->subject("FarmMarket: Order #{$this->order->id} {$statusLabel}")
            ->greeting("Hello {$notifiable->name},")
            ->line("Order #{$this->order->id} is now {$statusLabel}.")
            ->line('Order total: UGX ' . number_format((float) $this->order->total_amount));

        if ($this->updatedBy) {
            $mail->line("Updated by: {$this->updatedBy}");
        }

        if ($this->reason) {
            $mail->line("Reason: {$this->reason}");
        }

        return $mail
            ->line('Please sign in to view your orders.')
            ->action('Sign In to View Orders', route('login', ['redirect' => $targetUrl]))
            ->line('Thank you for shopping on FarmMarket.');
    }
}
