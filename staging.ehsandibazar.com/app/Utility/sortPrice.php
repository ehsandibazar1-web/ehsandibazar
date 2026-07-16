<?php

namespace App\Utility;

use App\Model\Systeminfmanage;
use Illuminate\Support\Facades\Lang;

class sortPrice
{
    public static function sortPrice($product, $unit = 1)
    {
        $count = 0;


        foreach ($product->variations->toArray() as $itemVariationCount) {
            $count += $itemVariationCount['count'];
        }
  
        if ($count <= 0 || count($product->variations->toArray()) <= 0) {
               dd($count);
            return "<span class='red'>ناموجود</span></br>";
        }
        $sortPrice = collect($product->variations)->sortBy('price');
        if (count($sortPrice) > 0) {
            foreach ($sortPrice as $itemSort) {
                $price = $itemSort->price;
          
                if (isset($price) && !empty($price)) {
                    $unit == 1 ? $price = unit::unit($price) : $price;
                    if (!empty($itemSort->discountPrice)) {
                        return [
                            'price' => $price,
                            'discountPrice' => $unit == 1 ? unit::unit($itemSort->discountPrice) : $itemSort->discountPrice
                        ];
                    }
                    return $price;
                } else {
                    if ($product->type == ProductType::FREE){
                        return "<span class='red'>رایگان</span>";
                    }
                    return "<span class='red'>" . Lang::get('cms.unavailable') . "</span>";
                }
            }
        } else {
            return "<span class='red'>" . Lang::get('cms.unavailable') . "</span>";
        }

    }


    public static function totalPrice($price)
    {

        if (is_array($price)) {
            return " <div class='col-6 cost text-left'><span class='old-cost'>" . $price['price'] . "</span></div>" .
                " <div class='col-6 cost text-right'><span class='cost-total'>" . $price['discountPrice'] . "</span></div>";
        } else {
            return "<div class='col-12 text-center'>$price</div>";
        }
    }

}
