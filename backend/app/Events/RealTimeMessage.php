<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class RealTimeMessage implements ShouldBroadcast
{
    use SerializesModels;

    public $user_id, $data;

    public function __construct( $user_id, $data = [])
    {
        $this->user_id = $user_id;
        $this->data = $data;
    }

    public function broadcastOn(): Channel
    {
        return new Channel("events-".$this->user_id);
    }

    public function broadcastAs(): string
    {
        return 'RealTimeMessage';
    }
}
