<?php

namespace App\Listeners;

use App\Events\E_updateSetNullForVariationInDiscount;
use App\Model\Brand;
use App\Model\Category;
use App\Services\discountServices\DiscountServices;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class L_updateSetNullForVariationInDiscount
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
     * @param  E_updateSetNullForVariationInDiscount  $event
     * @return void
     */
    public function handle(E_updateSetNullForVariationInDiscount $event)
    {
        $getModel = $event->model;
        $model="";
        $getModel == Category::class ? $model = Category::class : $model = Brand::class;
        $findVariation = DiscountServices::updateRelationCategoryOrBrandWithVariation($model,
            $event->user_id, $event->item, null);
//        $event->updateMode;

    }
}
