<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SubscriptionCodeNotification extends Notification
{
    use Queueable;

    public $subscriptionCode;

    public function __construct($subscriptionCode)
    {
        $this->subscriptionCode = $subscriptionCode;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Your Subscription Code')
                    ->line('Thank you for subscribing!')
                    ->line('Your subscription code is: ' . $this->subscriptionCode);
    }

    // ->mailer('smtp')
    // ->subject($this->subject)
    // ->greeting('Welcome '.$notifiable->tenantname)
    // ->line($this->message);
    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
