<?php


namespace App\Utility;


class CategoryProductLocation
{
    const right = 1;
    const middle = 2;
    const left = 3;

    public static function categoryLocation($location)
    {
        switch ($location)
        {
            case self::right:
                return 'ستون راست';
            case self::middle:
                return 'ستون وسط';
            case self::left:
                return 'ستون چپ';
            default:
                return "یافت نشد";
        }
    }


    public static function typeEach()
    {
        return [
            self::right => "ستون راست",
            self::middle => "ستون وسط",
            self::left => "ستون چپ",
        ];
    }

}