<?php

namespace App\Listeners;

use App\Events\E_findVariationInDiscount;
use App\Model\Brand;
use App\Model\Category;
use App\Services\discountServices\DiscountServices;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\DB;

class L_findVariaitonInDiscount
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
     * @param  E_findVariaitonInDiscount  $event
     * @return void
     */
    public function handle(E_findVariationInDiscount $event)
    {
        $getModel = $event->model;
        $model="";
        $getModel == Category::class ? $model = Category::class : $model = Brand::class;
        $results = DiscountServices::findVariationCategoryOrBrand($model, $event->user_id, $event->item, $event->discountableType, $event->baseon, $event->cent);
        return $results;
    }
}
