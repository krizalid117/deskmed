<?php

namespace App\Events;

use App\ChatRoom;
use App\Http\Controllers\GlobalController;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ChatRoomNewMessage implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    /**
     * @var mixed
     */
    public $hora_id;

    /**
     * Create a new event instance.
     *
     * @param mixed $hora_id
     *
     * @return void
     */
    public function __construct($hora_id)
    {
        $this->hora_id = $hora_id;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        $channel = new PrivateChannel('chatroom.' . $this->hora_id);

        return $channel;
    }
}
