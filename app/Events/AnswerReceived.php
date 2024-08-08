<?php
namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class AnswerReceived implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $answer;

    public function __construct($answer)
    {
        $this->answer = $answer;
    }

    public function broadcastOn()
    {
        return new Channel('webrtc-channel');
    }
}