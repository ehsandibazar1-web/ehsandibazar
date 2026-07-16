<?php

namespace App\Listeners;

use App\Events\eventSmsRegister;
use App\Utility\SendSms;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use SoapClient;

class listenerSmsRegister
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
     * @param eventSmsRegister $event
     * @return void
     */
    public function handle(eventSmsRegister $event)
    {
        $code = $event->activationCode;
        $mobile = $event->user->mobile;
        if (isset($code) && !empty($code) && isset($mobile) && !empty($mobile)) {
            SendSms::sms([$code], 83323, $mobile);
        }
    }
}
