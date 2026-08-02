<?php

namespace Modules\CarModel\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class CarEvent implements ShouldBroadcast
{
    use SerializesModels;

    public $site_id;
    public $data;

    public function __construct($site_id, $data)
    {
        $this->data = $data;
        $this->site_id = $site_id;
    }

    public function broadcastOn(): Channel
    {
        return new Channel("cars.$this->site_id");
    }

    public function broadcastAs(): string
    {
        return 'CarEvent';
    }
}
