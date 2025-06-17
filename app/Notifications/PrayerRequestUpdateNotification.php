<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\PrayerRequestUpdate;

class PrayerRequestUpdateNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $update;

    public function __construct(PrayerRequestUpdate $update)
    {
        $this->update = $update;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Prayer Request Update')
            ->line('There has been an update to a prayer request you\'re following:')
            ->line($this->update->content)
            ->action('View Request', url('/requests/' . $this->update->prayer_request_id))
            ->line('Thank you for your continued prayers!');
    }
} 