<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class VoiceCallEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $data;
    public $type;
    public $fromUserId;
    public $toUserId;

    /**
     * Create a new event instance.
     */
    public function __construct($type, $data, $fromUserId, $toUserId)
    {
        $this->type = $type;
        $this->data = $data;
        $this->fromUserId = $fromUserId;
        $this->toUserId = $toUserId;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel
     */
    public function broadcastOn()
    {
        return new PresenceChannel('call.' . $this->toUserId);
    }

    public function broadcastAs()
    {
        return 'voice-call';
    }
}
