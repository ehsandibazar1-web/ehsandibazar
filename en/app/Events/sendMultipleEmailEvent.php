<?php

namespace App\Events;

use App\Model\NewsLatters;
use App\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class sendMultipleEmailEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */

    public $user;
    public $title;
    public $body;
    public $newsLatters;

    public function __construct($ids, $title, $body,$newsLatters)
    {
        if($newsLatters == 0){
            $this->user = User::select('id','email','name','family')->whereIn('id',$ids)->get();
        }else{
            $this->user = NewsLatters::find($ids);
        }

        $this->title = $title;
        $this->body = $body;
        $this->newsLatters = $newsLatters;
//        dd($this->user);
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
