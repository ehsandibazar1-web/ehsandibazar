<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShippingCost extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $fillable = ['of_weight', 'upto_weight', 'price', 'type', 'post_type', 'description'];
    public static $preventAttrSet = true;

    public const TYPES = [
        'vanguard' => 1, // پیشتاز
        'bespoke' => 2, //سفارشی
        'bikeDelivery' => 3, //پیک موتوری
    ];

    public const TYPES_OF_POST = [
        'inTown' => 1, // درون شهری
        'suburban' => 2, //برون شهری
        'digital' => 3, //دیجیتال
    ];

    public const SHIPPING_COST = [
        'Money' => 1, // پولی
        'Free' => 0, // رایگان
    ];


    public static function getTypeShippingCost($value)
    {
        if (self::$preventAttrSet) {
            return $value;
        } else {
            switch ($value) {
                case self::SHIPPING_COST['Money'] :
                    return 'پولی';
                    break;
                case  self::SHIPPING_COST['Free'] :
                    return 'رایگان';
                    break;
                default :
                    return 'پولی';
            }
        }
    }

    public static function getTypeAttribute($value)
    {
        if (self::$preventAttrSet) {
            return $value;
        } else {
            switch ($value) {
                case self::TYPES['vanguard'] :
                    return 'پیشتاز';
                    break;
                case  self::TYPES['bespoke'] :
                    return 'سفارشی';
                    break;
                default :
                    return 'پیک موتوری';
            }
        }
    }

    public static function getPosttypeAttribute($value)
    {
        if (self::$preventAttrSet) {
            return $value;
        } else {
            switch ($value) {
                case self::TYPES_OF_POST['inTown'] :
                    return 'درون شهری';
                    break;
                case  self::TYPES_OF_POST['suburban'] :
                    return 'برون شهری';
                    break;
                default :
                    return 'محصول دیجیتال';
            }
        }
    }

}
