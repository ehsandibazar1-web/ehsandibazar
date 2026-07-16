<?php
/**
 * Created by PhpStorm.
 * User: p
 * Date: 03/09/2019
 * Time: 06:16 PM
 */

namespace App\Utility;


class ProductType
{

    const AUCTION = 1;
    const SIMPLE = 0;
    const PDF = 2;
    const VOICE = 3;
    const VIDEO = 4;

    public static function productType($type)
    {
        switch ($type) {
            case self::AUCTION:
                return 'مزایده';
            case self::SIMPLE:
                return 'فیزیکی';
            case self::PDF:
                return 'پی دی اف';
            case self::VIDEO:
                return 'ویدیو';
            case self::VOICE:
                return 'ویس';
            default:
                return "فیزیکی";
        }
    }

    public static function typeEach()
    {
        return [
            self::SIMPLE => "فیزیکی",
            self::PDF => "پی دی اف",
            self::VOICE => "ویس",
            self::VIDEO => "ویدیو",
//            self::AUCTION => "مزایده"
        ];
    }

}
