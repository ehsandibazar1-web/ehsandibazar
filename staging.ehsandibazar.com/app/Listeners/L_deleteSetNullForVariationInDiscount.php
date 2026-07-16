<?php

namespace App\Listeners;

use App\Events\E_deleteSetNullForVariationInDiscount;
use App\Model\Product;
use App\Utility\Message;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;

class L_deleteSetNullForVariationInDiscount
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
     * @param E_deleteSetNullForVariationInDiscount $event
     * @return void
     */
    public function handle(E_deleteSetNullForVariationInDiscount $event)
    {
        $error = false;
        foreach ($event->findVariation->products as $itemProduct) {
            if ($itemProduct instanceof Product) {
                foreach ($itemProduct->variations as $itemVariation) {
                    if (!empty($itemVariation->discountActive) && !is_null($itemVariation->discountActive) && ($itemVariation->discountActive == $event->discountActivity)) {
                        DB::beginTransaction();
                        $itemVariation->update(
                            [
                                'discountPrice' => null,
                                'discountActive' => null
                            ]
                        );
                        if (!$itemVariation) {
                            // todo transaction
                            DB::rollBack();
                            $error = true;
                        } else {
                            DB::commit();
                        }
                    }
                }
            }
        }

        if($error == true){
            return false;
        }else{
            return true;
        }
    }
}
