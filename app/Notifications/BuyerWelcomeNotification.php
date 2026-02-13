<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BuyerWelcomeNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $targetUrl = route('buyer.dashboard');

        return (new MailMessage)
            ->subject('Welcome to FarmMarket')
            ->greeting("Hello {$notifiable->name},")
            ->line('Your buyer account has been created successfully.')
            ->line('You can now browse products and place orders from verified farmers.')
            ->line('Please sign in to continue to your dashboard.')
            ->action('Sign In to Dashboard', route('login', ['redirect' => $targetUrl]))
            ->line('Thank you for joining FarmMarket.');
    }
}
