<?php


namespace App\Utility;


class customerLevel
{
    const GENERAL_CUSTOMER = 1;
    const BRONZE_CUSTOMER = 2;
    const SILVER_CUSTOMER = 3;
    const GOLD_CUSTOMER = 4;
    const DIAMOND_CUSTOMER = 5;

    public static function customerLevel($level_customer)
    {
        switch ($level_customer) {
            case self::GENERAL_CUSTOMER:
                return \Illuminate\Support\Facades\Lang::get('cms.general-user');
            case self::BRONZE_CUSTOMER:
                return \Illuminate\Support\Facades\Lang::get('cms.bronze-user');
            case self::SILVER_CUSTOMER:
                return \Illuminate\Support\Facades\Lang::get('cms.silver-user');
            case self::GOLD_CUSTOMER:
                return \Illuminate\Support\Facades\Lang::get('cms.gold-user');
            case self::DIAMOND_CUSTOMER:
                return \Illuminate\Support\Facades\Lang::get('cms.diamond-user');
            default:
                return \Illuminate\Support\Facades\Lang::get('cms.general-user');
        }
    }
}
