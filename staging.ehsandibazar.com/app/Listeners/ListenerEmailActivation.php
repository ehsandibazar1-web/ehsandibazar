<?php

namespace App\Listeners;

use App\Events\EmailActivation;
use App\Mail\activationUserAccount;
use App\Mail\MailActivationUser;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class ListenerEmailActivation
{
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
     * @param  EmailActivation  $event
     * @return void
     */
    public function handle(EmailActivation $event)
    {
        Mail::to($event->user)->send(new MailActivationUser($event->user, $event->activationCode));
    }
}
