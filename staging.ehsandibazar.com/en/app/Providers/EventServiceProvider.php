<?php

namespace App\Providers;

use App\Events\CheckPaymentStatusPending;
use App\Events\E_deleteSetNullForVariationInDiscount;
use App\Events\E_endCountUserDiscountInBasket;
use App\Events\E_findVariationInDiscount;
use App\Events\E_updateSetNullForVariationInDiscount;
use App\Events\EmailActivation;
use App\Events\eventSmsRegister;
use App\Events\EventUpdateDiscountPrice;
use App\Events\sendMultipleEmailEvent;
use App\Listeners\L_deleteSetNullForVariationInDiscount;
use App\Listeners\L_endCountUserDiscountInBasket;
use App\Listeners\L_findVariaitonInDiscount;
use App\Listeners\L_updateSetNullForVariationInDiscount;
use App\Listeners\listenerCheckPaymentStatusPending;
use App\Listeners\ListenerEmailActivation;
use App\Listeners\listenerSmsRegister;
use App\Listeners\ListenerUpdateDiscountPrice;
use App\Listeners\sendMultipleEmailListener;
use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\Event' => [
            'App\Listeners\EventListener',
        ],

        /* email sms*/
        sendMultipleEmailEvent::class => [
            sendMultipleEmailListener::class
        ],
        EmailActivation::class => [
            ListenerEmailActivation::class
        ],

        CheckPaymentStatusPending::class => [
            listenerCheckPaymentStatusPending::class
        ],

        EventUpdateDiscountPrice::class => [
            ListenerUpdateDiscountPrice::class
        ],

        /* start in discount controller */
        E_findVariationInDiscount::class=>[
            L_findVariaitonInDiscount::class
        ],

        E_updateSetNullForVariationInDiscount::class => [
            L_updateSetNullForVariationInDiscount::class
        ],

        E_deleteSetNullForVariationInDiscount::class => [
            L_deleteSetNullForVariationInDiscount::class
        ],
        /* end in discount controller */

        E_endCountUserDiscountInBasket::class => [
          L_endCountUserDiscountInBasket::class
        ],

        eventSmsRegister::class => [
            listenerSmsRegister::class
        ],

    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
