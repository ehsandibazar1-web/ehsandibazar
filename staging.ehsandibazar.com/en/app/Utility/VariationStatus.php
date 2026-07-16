<?php


namespace App\Utility;


class VariationStatus
{
    public static function variationStatus($product_id)
    {
        return  \App\Model\Variation::where('status' , 0)->where('product_id' , $product_id)->count();
    }
}
