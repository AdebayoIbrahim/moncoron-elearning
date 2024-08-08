<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use App\Models\Message;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    public $message;
    public $callType;
    public $signalData;

    /**
     * Create a new event instance.
     *
     * @param  User  $user
     * @param  Message  $message
     * @param  string|null  $callType
     * @param  array|null  $signalData
     * @return void
     */
    public function __construct(User $user, Message $message, $callType = null, $signalData = null)
    {
        $this->user = $user;
        $this->message = $message;
        $this->callType = $callType;
        $this->signalData = $signalData;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('chat');
    }

    /**
     * Get the name of the event to broadcast.
     *
     * @return string
     */
    public function broadcastAs()
    {
        return 'message.sent';
    }

    /**
     * Get the data to broadcast.
     *
     * @return array
     */
    public function broadcastWith()
    {
        return [
            'user' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
            ],
            'message' => [
                'id' => $this->message->id,
                'content' => $this->message->content,
                'created_at' => $this->message->created_at->toDateTimeString(),
            ],
            'callType' => $this->callType,
            'signalData' => $this->signalData,
        ];
    }
}
