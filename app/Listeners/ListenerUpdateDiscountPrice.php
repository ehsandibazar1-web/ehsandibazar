<?php

namespace App\Listeners;

use App\Events\EventUpdateDiscountPrice;
use App\Utility\DiscountType;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ListenerUpdateDiscountPrice
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
     * @param  EventUpdateDiscountPrice  $event
     * @return void
     */
    public function handle(EventUpdateDiscountPrice $event)
    {
        $discount = $event->discount;


        $type = DiscountType::prudoct;

        foreach ($discount->disable as $itemDiscountable) {

            if ($itemDiscountable->discountable_type == $type) {
                /* variation discountPrice is update => set null */
                $updateDiscountPriceVariation =  $itemDiscountable->discountable->update(['discountPrice' => null]);
            }
        }

    }
}
