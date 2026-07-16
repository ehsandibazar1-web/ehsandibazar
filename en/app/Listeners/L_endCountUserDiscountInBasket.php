<?php

namespace App\Listeners;

use App\Events\E_endCountUserDiscountInBasket;
use App\Model\Brand;
use App\Model\Discount;
use App\Model\Product;
use App\Model\Variation;
use App\Utility\DiscountType;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\DB;

class L_endCountUserDiscountInBasket
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
     * @param E_endCountUserDiscountInBasket $event
     * @return void
     */
    public function handle(E_endCountUserDiscountInBasket $event)
    {
        $variation = $event->variation;
        $findProduct = $this->findDiscount($variation);
        if (isset($findProduct) && !empty($findProduct) && isset($findProduct['type']) && isset($findProduct['find']) && !empty($findProduct['type']) && !empty($findProduct['find'])) {
            if ($findProduct['type'] == DiscountType::brand) {
                foreach ($findProduct['find'] as $itemDiscountAble) {
                    foreach ($itemDiscountAble as $itemProduct) {
                        if (isset($itemProduct->variations)) {
                            foreach ($itemProduct->variations as $itemVariation) {
                                if (count($itemVariation->discount) <= 0) {
                                    $itemVariation->update(
                                        [
                                            'discountPrice' => null,
                                            'discountActive' => null
                                        ]
                                    );
                                }
                            }
                        }
                    }
                }
            } elseif ($findProduct['type'] == DiscountType::category) {
                foreach ($findProduct['find'] as $itemDiscountAble) {
                    foreach ($itemDiscountAble as $itemProduct) {
                        if (isset($itemProduct->variations)) {
                            foreach ($itemProduct->variations as $itemVariation) {
                                if (count($itemVariation->discount) <= 0) {
                                    $itemVariation->update(
                                        [
                                            'discountPrice' => null,
                                            'discountActive' => null
                                        ]
                                    );
                                }
                            }
                        }
                    }
                }
            } elseif ($findProduct['type'] == DiscountType::product) {

                foreach ($findProduct['find'] as $variation) {
                    $variation->discountable->update(
                        [
                            'discountPrice' => null,
                            'discountActive' => null
                        ]
                    );
                }

            }
        }
    }

    private function findDiscount($variation)
    {

        $findVariation = $variation;
        $productNumber = DiscountType::product;
        $brandNumber = DiscountType::brand;
        $categoryNumber = DiscountType::category;
        $find = "";

        if (isset($findVariation) && !empty($findVariation)) {

            $discount = null;
            switch ($findVariation->discountActive) {
                case $productNumber:
                    $find = self::findAndSetNullVariation($findVariation, $productNumber);
                    break;
                case $brandNumber:
                    $find = self::findProductForBrand($findVariation, $brandNumber);
                    break;
                case $categoryNumber:
                    $find = self::findProductForCategory($findVariation, $categoryNumber);
                    break;
            }
        }

        return $find;

    }

    public static function findAndSetNullVariation($findVariation, $typeDiscount)
    {
        $discount = Discount::with('disable')->
        where('user_id', $findVariation->user_id)->
        where('id', $findVariation->discount[0]->discount_id)->
        where('discountable_type', $typeDiscount)->first();
        $findProduct = $discount->disable;
        return [
            'type' => DiscountType::product,
            'find' => $findProduct
        ];
    }

    public static function findProductForBrand($findVariation, $typeDiscount)
    {
        $findProduct = [];
        $discount = Discount::with('disable')->
        where('user_id', $findVariation->user_id)->
        where('id', $findVariation->discount[0]->discount_id)->
        where('discountable_type', $typeDiscount)->first();

        $userIdDiscount = $discount->user_id;
        $discountAbleType = $typeDiscount == DiscountType::brand ? 'brand_id' : 'category_id';
        $discountAbleBrand = $discount->disable;

        foreach ($discountAbleBrand as $itemDiscountable) {
            $discountAble = $itemDiscountable->discountable;
            $findProduct [] = Product::with(['variations' => function ($query) use ($userIdDiscount) {
                $query->where('user_id', $userIdDiscount);
            }])->where($discountAbleType, $discountAble->id)->get();
        }


        return [
            'type' => DiscountType::brand,
            'find' => $findProduct
        ];
    }

    public static function findProductForCategory($findVariation, $typeDiscount)
    {
        $findProduct = [];
        $discount = Discount::with('disable')->
        where('user_id', $findVariation->user_id)->
        where('id', $findVariation->discount[0]->discount_id)->
        where('discountable_type', $typeDiscount)->first();

        $userIdDiscount = $discount->user_id;
        $discountAbleType = $typeDiscount == DiscountType::brand ? 'brand_id' : 'category_id';
        $discountAbleCategory = $discount->disable;
        foreach ($discountAbleCategory as $itemDiscountable) {
            $discountAble = $itemDiscountable->discountable;
            $findProduct [] = Product::with(['variations' => function ($query) use ($userIdDiscount) {
                $query->where('user_id', $userIdDiscount);
            }])->where($discountAbleType, $discountAble->id)->get();
        }
        return [
            'type' => DiscountType::category,
            'find' => $findProduct
        ];
    }
}
