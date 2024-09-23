<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class Generalnotify extends Notification
{
    use Queueable;

    protected $message;
    protected $course;
    protected $user;
    protected $audio;
    protected $link;

    /**
     * Create a new notification instance.
     */
    public function __construct($message, $user = null, $link = null,)
    {
        $this->message = $message;
        $this->user = $user;
        $this->link = $link;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via($notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    // public function toMail(object $notifiable): MailMessage
    // {
    //     return (new MailMessage)
    //         ->line('The introduction to the notification.')
    //         ->action('Notification Action', url('/'))
    //         ->line('Thank you for using our application!');
    // }
    public function toArray(object $notifiable): array
    {
        return [
            'message' => $this->message,
            'sender_name' => $this->isAdmincheck($this->user),
            'attached_link' => $this->link ?? null,

        ];
    }

    protected function isAdmincheck($args)
    {
        if ($args) {
            if ($args->role == 'admin') {
                return 'admin';
            } else {
                return $args->name;
            }
        } else {
            return null;
        }
    }
}
