<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class E_findVariationInDiscount
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public  $model;
    public  $user_id;
    public  $item;
    public  $discountableType;
    public  $baseon;
    public  $cent;
    public function __construct($model , $user_id , $item , $discountableType , $baseon , $cent)
    {
        $this->model = $model;
        $this->user_id = $user_id;
        $this->item = $item;
        $this->discountableType = $discountableType;
        $this->baseon = $baseon;
        $this->cent = $cent;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
