<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\PrayerRequest;

class NewPrayerRequestNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $prayerRequest;

    public function __construct(PrayerRequest $prayerRequest)
    {
        $this->prayerRequest = $prayerRequest;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('New Prayer Request')
            ->line('A new prayer request has been submitted:')
            ->line($this->prayerRequest->title)
            ->line($this->prayerRequest->description)
            ->action('View Request', url('/requests/' . $this->prayerRequest->id))
            ->line('Thank you for being part of our prayer community!');
    }
} 