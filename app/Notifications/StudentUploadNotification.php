<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class StudentUploadNotification extends Notification implements ShouldBroadcast
{
    use Queueable;

    protected $media;

    public function __construct($media)
    {
        $this->media = $media;
    }

    public function via($notifiable)
    {
        return ['database', 'mail', 'broadcast'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('A student has uploaded new media.')
                    ->action('View Media', url('/path/to/media'))
                    ->line('Thank you for using our application!');
    }

    public function toArray($notifiable)
    {
        return [
            'message' => 'A student has uploaded new media.',
            'media_id' => $this->media->id,
            'lesson_id' => $this->media->lesson_id,
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'message' => 'A student has uploaded new media.',
            'media_id' => $this->media->id,
            'lesson_id' => $this->media->lesson_id,
        ]);
    }
}
