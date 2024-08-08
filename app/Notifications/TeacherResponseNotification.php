<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TeacherResponseNotification extends Notification implements ShouldBroadcast
{
    use Queueable;

    protected $response;

    public function __construct($response)
    {
        $this->response = $response;
    }

    public function via($notifiable)
    {
        return ['database', 'mail', 'broadcast'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('Your upload has received a response from the teacher.')
                    ->action('View Response', url('/path/to/response'))
                    ->line('Thank you for using our application!');
    }

    public function toArray($notifiable)
    {
        return [
            'message' => 'Your upload has received a response from the teacher.',
            'response_id' => $this->response->id,
            'lesson_id' => $this->response->lesson_id,
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'message' => 'Your upload has received a response from the teacher.',
            'response_id' => $this->response->id,
            'lesson_id' => $this->response->lesson_id,
        ]);
    }
}
