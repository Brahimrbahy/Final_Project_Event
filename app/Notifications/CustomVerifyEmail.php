<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail as VerifyEmailBase;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;

class CustomVerifyEmail extends VerifyEmailBase
{
    use Queueable;

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable): MailMessage
    {
        $verificationUrl = $this->verificationUrl($notifiable);

        return (new MailMessage)
            ->subject('🎫 Verify Your EventHub Account')
            ->greeting('Welcome to EventHub!')
            ->line('Thank you for registering with EventHub! We\'re excited to have you join our community of event enthusiasts.')
            ->line('To complete your registration and start booking amazing events, please verify your email address by clicking the button below:')
            ->action('Verify Email Address', $verificationUrl)
            ->line('This verification link will expire in 60 minutes for security reasons.')
            ->line('If you did not create an account with EventHub, no further action is required.')
            ->line('Once verified, you\'ll be able to:')
            ->line('• Browse and book tickets for thousands of events')
            ->line('• Manage your tickets digitally')
            ->line('• Receive event reminders and updates')
            ->line('• Access exclusive member benefits')
            ->salutation('Happy event hunting!')
            ->line('The EventHub Team')
            ->line('---')
            ->line('If you\'re having trouble clicking the "Verify Email Address" button, copy and paste the URL below into your web browser:')
            ->line($verificationUrl);
    }

    /**
     * Get the verification URL for the given notifiable.
     */
    protected function verificationUrl($notifiable): string
    {
        return URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
            [
                'id' => $notifiable->getKey(),
                'hash' => sha1($notifiable->getEmailForVerification()),
            ]
        );
    }
}
