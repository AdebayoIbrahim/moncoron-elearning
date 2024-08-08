<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LessonCompletionNotification extends Notification implements ShouldBroadcast
{
    use Queueable;

    protected $lesson;

    public function __construct($lesson)
    {
        $this->lesson = $lesson;
    }

    public function via($notifiable)
    {
        return ['database', 'mail', 'broadcast'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('You have completed a lesson.')
                    ->action('View Lesson', url('/path/to/lesson'))
                    ->line('Thank you for using our application!');
    }

    public function toArray($notifiable)
    {
        return [
            'message' => 'You have completed a lesson.',
            'lesson_id' => $this->lesson->id,
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'message' => 'You have completed a lesson.',
            'lesson_id' => $this->lesson->id,
        ]);
    }
}
