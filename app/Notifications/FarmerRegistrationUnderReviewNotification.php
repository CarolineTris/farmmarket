<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class FarmerRegistrationUnderReviewNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $targetUrl = route('farmer.dashboard');

        return (new MailMessage)
            ->subject('FarmMarket: Farmer Registration Under Review')
            ->greeting("Hello {$notifiable->name},")
            ->line('Your farmer registration has been submitted successfully.')
            ->line('Your account is currently under admin review.')
            ->line('We will notify you by email once your application is approved or rejected.')
            ->line('Please sign in to check your dashboard status.')
            ->action('Sign In to Dashboard', route('login', ['redirect' => $targetUrl]));
    }
}
