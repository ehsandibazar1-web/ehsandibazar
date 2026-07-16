<?php

namespace App\Listeners;

use App\Events\sendMultipleEmailEvent;
use App\Mail\sendMultipleMail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class sendMultipleEmailListener implements ShouldQueue
{
    use InteractsWithQueue;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  sendMultipleEmailEvent  $event
     * @return void
     */
    public function handle(sendMultipleEmailEvent $event)
    {
        if (isset($event->user) && !empty($event->user)){
            foreach ($event->user as $user) {
                if (isset($user->email)) {
                    Mail::to($user->email)->send(new sendMultipleMail($event,$user));
                }
            }
        }

    }
}
