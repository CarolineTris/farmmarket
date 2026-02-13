<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class FarmerNewPaidOrderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Order $order,
        public int $itemCount
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $targetUrl = route('farmer.orders');

        return (new MailMessage)
            ->subject("FarmMarket: New Paid Order #{$this->order->id}")
            ->greeting("Hello {$notifiable->name},")
            ->line("A buyer has completed payment for order #{$this->order->id}.")
            ->line("This order contains {$this->itemCount} of your item(s).")
            ->line('Please sign in to manage this order.')
            ->action('Sign In to Manage Orders', route('login', ['redirect' => $targetUrl]));
    }
}
