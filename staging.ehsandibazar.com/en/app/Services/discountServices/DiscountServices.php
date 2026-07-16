<?php


namespace App\Services\discountServices;


use App\Http\Controllers\Admin\DiscountController;
use App\Model\Discount;
use App\Model\Product;
use App\Model\Variation;
use App\Utility\DiscountType;

class DiscountServices
{
    public static function create_discount($user, $title, $description, $baseon, $cent, $type, $discountableType, $count_user, $count_buy, $status)
    {
        $discount = Discount::create([
            'user_id' => $user->id,
            'title' => $title,
            'description' => $description,
            'baseon' => $baseon,
            'cent' => $cent,
            'type' => $type,
            'discountable_type' => $discountableType,
            'count_user' => $count_user,
            'count_buy' => $count_buy,
            'status' => $status,
        ]);

        return $discount;
    }

    public static function create_discountable($find, $discount)
    {
        $discountable = $find->discount()->create([
            'discount_id' => $discount->id,
            'discountable_id' => $find->id,
            'discountable_type' => get_class($find),
        ]);

        return $discountable;
    }

    public static function update_discountPriceVariation($variation, $baseon, $cent, $discountableType)
    {
        $findVariation = Variation::whereStatus(1)->findOrFail($variation->id);

        if ($baseon == DiscountType::price) {
            $calDiscount = $findVariation->price - $cent;
        } else {
            $calDiscount = $findVariation->price - (($findVariation->price * $cent) / 100);
        }

//        DB::beginTransaction();
        $updateVariation = false;
        if ($calDiscount > 0) {
            $updateVariation = $findVariation->update([
                'discountPrice' => $calDiscount,
                'discountActive' => $discountableType
            ]);
        }

        if ($updateVariation) {
//            DB::commit();
            return true;
        }
//        DB::rollBack();
        return false;
    }

    public static function findVariationCategoryOrBrand($model, $user_id, $item, $changeTo, $baseon, $cent, $modeUpdate = null, $modeUpdateForUserId = null)
    {
        $find = self::findRelationCategoryOrBrandWithVariation($model, $user_id, $item, $modeUpdateForUserId);
        $apply = [];
        $tt = [];
        foreach ($find->products as $itemProduct) {
            if ($itemProduct instanceof Product) {
                foreach ($itemProduct->variations as $itemVariation) {
                    $tt  [] = $itemVariation->id;
                    $discountCountBuy = DiscountController::HasDiscountCountBuy($itemVariation->id,$changeTo);

                    if ($discountCountBuy == false) {
                        //start function check Priority discount beetwen category ,Brand , product(variation)
                        $apply [$itemVariation->id] = self::applyDiscountBaseonPriority($itemVariation, $changeTo, $baseon, $cent, $modeUpdate);
                    }
                }

            } else {
                return false;
            }
        }

        return [
            'find' => $find,
            'apply' => $apply
        ];
    }

    public static function applyDiscountBaseonPriority($variation, $changeTo, $baseon, $cent, $modeUpdate = null)
    {

        $findVariation = Variation::with('discount')->findOrfail($variation->id);
        $findBrandDsicount = $findVariation->product->brand->with('discount')->firstOrFail();
        $findCategoryDiscount = $findVariation->product->categories[0]->with('discount')->firstOrFail();

        if (isset($findVariation->discount) && isset($findVariation->discount[0]) && !empty($findVariation->discount)) {
            $discountVariation = $findVariation;
        } elseif (isset($findBrandDsicount->discount) && isset($findBrandDsicount->discount[0]) && !empty($findBrandDsicount->discount)) {
            $discountVariation = $findBrandDsicount;
        } elseif (isset($findCategoryDiscount->discount) && isset($findCategoryDiscount->discount[0]) && !empty($findCategoryDiscount->discount)) {
            $discountVariation = $findCategoryDiscount;
        }


        // variation has discount
        if (isset($discountVariation->discount) && isset($discountVariation->discount[0]) && !empty($discountVariation->discount)) {
            //todo check type discount And set discount base on priority
            //dump($discountVariation->discount[0]->discount->id);
            $discountableType = $discountVariation->discount[0]->discount->discountable_type;

            //function priority
            if ($modeUpdate != null) {
                /* mode update */
                $resultPriority = true;
            } else {
                /* mode insert */
                $resultPriority = self::priority($discountableType, $changeTo);
            }


            if ($resultPriority) {
                // apply discount
                self::update_discountPriceVariation($variation, $baseon, $cent, $changeTo);
                return true;
            } else {
                // discount not apply
                return false;
            }

        } else {
            // apply discount because not any more discount
            self::update_discountPriceVariation($variation, $baseon, $cent, $changeTo);
            return true;
        }
    }

    //function priority
    public static function priority($discountableType, $changeTo)
    {
        if ($discountableType == DiscountType::product && $changeTo == DiscountType::product) {
            // product
            return true;
        } elseif (($discountableType == DiscountType::brand && $changeTo == DiscountType::brand) || ($discountableType == DiscountType::brand && $changeTo == DiscountType::product)) {
            // brand
            return true;
        } elseif (($discountableType == DiscountType::category && $changeTo == DiscountType::category) || ($discountableType == DiscountType::category && $changeTo == DiscountType::brand) || ($discountableType == DiscountType::category && $changeTo == DiscountType::product)) {
            // category
            return true;
        } else {
            return false;
        }
    }

    /* update relation discount => category , brand */
    public static function updateRelationCategoryOrBrandWithVariation($model, $user_id, $item, $modeUpdateForUserId = null)
    {
        $find = self::findRelationCategoryOrBrandWithVariation($model, $user_id, $item, $modeUpdateForUserId);
        foreach ($find[0]->products as $itemProduct) {
            if ($itemProduct instanceof Product) {
                foreach ($itemProduct->variations as $itemVariation) {
                    $itemVariation->update(
                        [
                            'discountPrice' => null,
                            'discountActive' => null
                        ]
                    );
                }
            }
        }
        return $find;
    }

    /* find relation discount => category , brand */
    public static function findRelationCategoryOrBrandWithVariation($model, $user_id, $item, $modeUpdateForUserId = null)
    {
        /* mode update and dont see to user id */
        if ($modeUpdateForUserId == null) {
            $find = $model::with(['discount', 'products.variations' => function ($query) use ($user_id) {
                $query->where('user_id', $user_id);
            }])->findOrfail($item);

        } else {
            $find = $model::with(['discount', 'products.variations'])->findOrfail($item);
        }
        return $find;
    }

}
