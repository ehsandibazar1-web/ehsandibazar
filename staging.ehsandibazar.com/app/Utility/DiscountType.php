<?php
/**
 * Created by PhpStorm.
 * User: p
 * Date: 03/09/2019
 * Time: 06:16 PM
 */

namespace App\Utility;


use App\Model\Discount;
use App\Model\Variation;
use Hekmatinasser\Verta\Verta;
use Illuminate\Support\Facades\Lang;

class DiscountType
{

    const cent = 0;
    const price = 1;

    const category = 1;
    const brand = 2;
    const product = 3;
    const user = 4;
    const role = 5;

    const prudoct = 'prudoct';

    const discountSimple = 0;
    const discountCode = 1;
    const discountCodeTime = 2;
    const discountTime = 3;
    const coupon = 4;
    const amazing = 5;
    const COUNTBUY = 6;

    const used = 1;
    const notUsed = 0;

    //Discount Base On | price => 1 | cent => 0
    public static function discountBaseOn($type)
    {
        switch ($type) {
            case self::cent:
                return Lang::get('cms.cent');
            case self::price:
                return Lang::get('cms.price');
            default:
                return Lang::get('cms.price');
        }
    }

    public static function baseOnEach()
    {
        return [
            self::price => Lang::get('cms.price'),
            self::cent => Lang::get('cms.cent')
        ];
    }

    public static function SelectedDiscountType($key, $type)
    {
        if ($key == self::category && $type == 'categories')
            return 'selected';
        elseif ($key == self::product && $type == 'prudoct')
            return 'selected';
        elseif ($key == self::brand && $type == 'brand')
            return 'selected';
        elseif ($key == self::user && $type == 'user')
            return 'selected';
        elseif ($key == self::role && $type == 'role')
            return 'selected';
        else
            return '';
    }

    public static function DiscountUserUsedEach()
    {
        return [
            self::used => Lang::get('cms.used'),
            self::notUsed => Lang::get('cms.not-used')
        ];
    }

    // discount used For User
    public static function DiscountUserUsed($use)
    {
        switch ($use) {
            case self::used:
                return Lang::get('cms.used');
            case self::notUsed:
                return Lang::get('cms.not-used');
            default:
                return Lang::get('cms.used');
        }
    }


    // type Discount => code OR Simple(sade) OR ...
    public static function DiscountType($type)
    {
        switch ($type) {
            case self::discountSimple:
                return Lang::get('cms.simple');
            case self::discountCode:
                return Lang::get('cms.code');
            case self::discountCodeTime:
                return Lang::get('cms.discount-code-time');
            case self::discountTime:
                return Lang::get('cms.discount-time');
            case self::coupon:
                return Lang::get('cms.coupon');
            case self::amazing:
                return Lang::get('cms.amazing');
            case self::COUNTBUY:
                return Lang::get('cms.count-buy');
            default:
                return Lang::get('cms.amazing');
        }
    }

    // type Discount => show in Top-header dashboard
    public static function DiscountTypeShowDateAndCode($discount)
    {
        $findDiscount = Discount::findOrFail($discount->id);
        switch ($findDiscount->type) {
            case self::discountSimple:
                return Lang::get('cms.simple');
//            case self::discountCode:
//                return ' کد :'.$findDiscount->discountCode[0]->code;
//            case self::discountCodeTime:
//                return ' کد :'.$findDiscount->discountCode[0]->code.'<br>'.'تا تاریخ :'.self::convertToJalali($findDiscount->discountCode[0]->discountCodeTime[0]->expire_date);
            case self::discountTime:
                return 'تا تاریخ :' . self::convertToJalali($findDiscount->discountTime[0]->expire_date);
            case self::coupon:
                return ' کوپن :' . $findDiscount->coupon[0]->code . '<br>' . 'تا تاریخ :' . self::convertToJalali($findDiscount->coupon[0]->expire_date);
            case self::amazing:
                return 'تا تاریخ :' . self::convertToJalali($findDiscount->discountTime[0]->expire_date);
            default:
                return Lang::get('cms.simple');
        }
    }

    public static function DiscountTypeEach()
    {
        return [
            self::discountSimple => Lang::get('cms.simple'),
//            self::discountCode => Lang::get('cms.code'),
//            self::discountCodeTime => Lang::get('cms.discount-code-time'),
//            self::discountTime => Lang::get('cms.discount-time'),
            self::coupon => Lang::get('cms.coupon'),
            self::amazing => Lang::get('cms.amazing'),
            self::COUNTBUY => Lang::get('cms.count-buy'),
        ];
    }

    //function select Discount on brand, category, product,user,role
    public static function discountOn($type)
    {
        switch ($type) {
            case self::category:
                return Lang::get('cms.category');
            case self::brand:
                return Lang::get('cms.brand');
            case self::product:
                return Lang::get('cms.products');
            case self::user:
                return Lang::get('cms.users');
            case self::role:
                return Lang::get('cms.role');
            default:
                return Lang::get('cms.products');
        }
    }

    public static function DiscountONEach($coupon = null, $timeOrSimple = null)
    {
        $array = [
            self::category => Lang::get('cms.category'),
            self::brand => Lang::get('cms.brand'),
            self::product => Lang::get('cms.products'),
            self::user => Lang::get('cms.users'),
            self::role => Lang::get('cms.role'),
        ];
        if (isset($coupon) && !empty($coupon)) {
            $array = [
                self::user => Lang::get('cms.users'),
                self::role => Lang::get('cms.role'),
            ];
        }
        if (isset($timeOrSimple) && !empty($timeOrSimple)) {
            $array = [
                self::category => Lang::get('cms.category'),
                self::brand => Lang::get('cms.brand'),
                self::product => Lang::get('cms.products'),
            ];
        }

        return $array;

    }

    public static function DiscountONAmazingEach()
    {
        return [
            self::product => Lang::get('cms.products')
        ];
    }

    //show discountable name(base On Model name)
    public static function discountableName($name)
    {
        if ($name == 'categories')
            return ' دسته بندی :';
        elseif ($name == 'prudoct')
            return 'محصول : ';
        elseif ($name == 'brand')
            return 'برند :';
        elseif ($name == 'user')
            return 'کاربر :';
        elseif ($name == 'role')
            return 'گروه کاربری :';
        else
            return '';
    }

    // get timestamp and convert to jalali
    public static function convertToJalali($date)
    {
        return Verta::createTimestamp($date)->format('j/m/Y');
    }

    /*  get all category and brand base user */
    public static function getCategoryOrBrandBaseOnUser($type, $user_id)
    {
        $type = $type == self::brand ? "brand" : "categories";
        $findVariation = Variation::with('product')->where('user_id', $user_id)->whereStatus(1)->get();
        $allId = [];
        $allData = [];
        foreach ($findVariation as $itemVariation) {
            if ($type == "categories"){
                if (isset($itemVariation->product->{$type}[0]) && !empty($itemVariation->product->{$type}[0])) {
                    if (!in_array($itemVariation->product->{$type}->id, $allId)) {
                        $allData [$itemVariation->product->{$type}[0]->id] = $itemVariation->product->{$type}[0]->title;
                    }
                    $allId [] = $itemVariation->product->{$type}[0]->id;
                }
            }else{
                if (isset($itemVariation->product->{$type}) && !empty($itemVariation->product->{$type})) {
                    if (!in_array($itemVariation->product->{$type}->id, $allId)) {
                        $allData [$itemVariation->product->{$type}->id] = $itemVariation->product->{$type}->title;
                    }
                    $allId [] = $itemVariation->product->{$type}->id;
                }
            }
        }
        return $allData;
    }


    public static function getDiscountValue($discount)
    {
        if ($discount->baseon == self::cent) {
            return $discount->cent . "%";
        } else {
            return number_format($discount->cent) . "تومان";
        }
    }

    public static function hasDiscount($product)
    {
        $flag = false;
        if (isset($product->variations) && !empty($product->variations) && count($product->variations) > 0) {
            $discountPriceArray = $product->variations()->pluck('discountPrice')->toArray();
            if (isset($discountPriceArray) && !empty($discountPriceArray)) {
                foreach ($discountPriceArray as $item) {
                    if ($item != null) {
                        $flag = true;
                    }
                }
            }

        }

        return $flag;
    }

    public static function showCentDiscount($product)
    {
        $discountVariations = $product->variations()->WhereNotNull('discountPrice')->orderBy('discountPrice', 'asc')->get();

        $discount = 0;

        if (isset($discountVariations[0]->discount)) {
            foreach ($discountVariations[0]->discount as $item) {
                if ($item->discount->status == Status::active) {
                    $discount = $item->discount;
                }
            }
        }

        if (isset($discount, $discount->type) && !empty($discount)) {
            if ($discount->type == self::amazing || $discount->type == self::discountSimple || $discount->type == self::discountTime) {

                if ($discount->baseon == self::cent) {
                    return $discount->cent . "%";
                } else {
                    $cent = (($discount->cent) * (100)) / ($discountVariations[0]->price);
                    return floor($cent) . "%";
                }
            }
        }
        return false;
    }

}
