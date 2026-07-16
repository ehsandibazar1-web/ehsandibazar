<?php

namespace App\Listeners;

use App\Events\CheckPaymentStatusPending;
use App\Model\Order;
use App\User;
use App\Utility\checkRestNumber;
use App\Utility\incrementVariation;
use App\Utility\Status;
use Carbon\Carbon;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class listenerCheckPaymentStatusPending
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
     * @param CheckPaymentStatusPending $event
     * @return void
     */
    public function handle(CheckPaymentStatusPending $event)
    {
        $user = $event->user;

    }

    /* foreach */
    private function eachItemOrder($orderPending)
    {
        foreach ($orderPending as $itemStatusPending) {
            if (!empty($itemStatusPending->rest_number)) {

                /* che e */
                $resultCheckRestNumber = checkRestNumber::checkRestNumber($itemStatusPending->total_amount, $itemStatusPending->rest_number);
                /* pardakht shode bud */
                if ($resultCheckRestNumber) {
                    Order::where('id', $itemStatusPending->id)->update([
                        'status' => Status::PAID
                    ]);
                } else {
                    /* pardakht nashode bud */
                    $updateOrder = Order::where('id', $itemStatusPending->id)->update([
                        'status' => Status::UNPAID
                    ]);
                    if ($updateOrder) {
                        incrementVariation::incrementVariations($itemStatusPending->id);
                    }
                }

            } else {
                // when rest number is empty
                incrementVariation::incrementVariations($itemStatusPending->id);
                Order::where('id', $itemStatusPending->id)->update([
                    'status' => Status::UNPAID
                ]);
            }
        }
    }
}
