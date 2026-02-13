<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class FarmerVerificationStatusNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public string $status,
        public ?string $reason = null
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $targetUrl = route('farmer.dashboard');
        $statusLabel = match ($this->status) {
            'verified' => 'Verified',
            'rejected' => 'Rejected',
            'more_info' => 'More Information Required',
            default => ucfirst($this->status),
        };

        $mail = (new MailMessage)
            ->subject("FarmMarket: Farmer Verification {$statusLabel}")
            ->greeting("Hello {$notifiable->name},")
            ->line("Your farmer verification status has been updated: {$statusLabel}.");

        if ($this->reason) {
            $mail->line("Reason/Notes: {$this->reason}");
        }

        if ($this->status === 'verified') {
            $mail->line('You can now start listing products on FarmMarket.');
        } elseif ($this->status === 'rejected') {
            $mail->line('You may update your details and reapply if needed.');
        } elseif ($this->status === 'more_info') {
            $mail->line('Please update your registration details and contact support/admin.');
        }

        return $mail
            ->line('Please sign in to continue.')
            ->action('Sign In to Dashboard', route('login', ['redirect' => $targetUrl]));
    }
}
