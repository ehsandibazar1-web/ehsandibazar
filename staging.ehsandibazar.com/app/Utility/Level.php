<?php
/**
 * Created by PhpStorm.
 * User: p
 * Date: 12/25/2018
 * Time: 01:04 PM
 */

namespace App\Utility;


class Level
{
    const SUPER_ADMIN = 44;
    const ADMIN = 22;
    const USER = 3;
    const SELLER = 4;
    const COLLEAGUE = 5;
    const OPERATOR = 10;

    


    public static function getLevel($level)
    {
        switch ($level) {
            case self::SUPER_ADMIN:
                return 'مدیر ارشد';
            case self::ADMIN:
                return 'مدیر';
            case self::SELLER:
                return 'فروشنده';
            case self::COLLEAGUE:
                return 'همکار';
            case self::USER:
                return 'مشتریان عادی';
            case self::OPERATOR:
                return 'اپراتور';

            default:
                return "مشتریان عادی";
        }
    }

    public static function levelEach()
    {
        return [
            self::ADMIN => "مدیر",
            self::OPERATOR => "اپراتور",
            self::USER => "مشتریان عادی",
        ];
    }

    public static function AllLevelEach()
    {
        return [
            self::ADMIN => "مدیر",
            self::USER => "مشتریان عادی",
            self::COLLEAGUE => "همکار",
            self::OPERATOR => "اپراتور",
        ];
    }

    public static function levelAdmins()
    {
        return [
            self::ADMIN => 22,
            self::SUPER_ADMIN => 44,
        ];
    }


    /* get all admins site */
    public static function getAdmins()
    {
        return [
            self::ADMIN,
            self::SUPER_ADMIN,
        ];
    }

}
