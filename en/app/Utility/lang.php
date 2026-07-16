<?php


namespace App\Utility;


class lang
{
    const PERSIAN = "fa", ENGLISH = "en", ARABIC = "ar";

    public static function getLang($lang)
    {
        switch ($lang) {
            case self::PERSIAN:
                return 'fa';
            case self::ENGLISH:
                return 'en';
            case self::ARABIC:
                return 'fa';
            default:
                return "fa";
        }
    }

    public static function langEach()
    {
        return [
            self::PERSIAN => "fa",
            self::ENGLISH => "en",
            self::ARABIC => "ar",
        ];
    }


    public static function validationLang($lang)
    {
        if(isset($lang) && !empty($lang)){
            if(!in_array($lang , self::langEach())){
                return false;
            }
            return true;
        }
        return false;
    }

}
