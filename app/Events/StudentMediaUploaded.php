<?php

namespace App\Events;

use App\Models\Media;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class StudentMediaUploaded implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $media;

    public function __construct(Media $media)
    {
        $this->media = $media;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('course.' . $this->media->lesson->course_id);
    }

    public function broadcastWith()
    {
        return [
            'message' => 'A student has uploaded new media.',
            'media_id' => $this->media->id,
            'lesson_id' => $this->media->lesson_id,
        ];
    }
}
