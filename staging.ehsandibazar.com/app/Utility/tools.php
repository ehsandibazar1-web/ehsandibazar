<?php
/**
 * Created by PhpStorm.
 * User: rezakia
 * Date: 26/11/2020
 * Time: 06:55 PM
 */

namespace App\Utility;

use Hekmatinasser\Verta\Verta;
use Illuminate\Support\Facades\Auth;

class tools
{

    public static function getEstimateReadingTime($content, $wpm = 200)
    {
        $wordCount = self::mb_str_word_count(strip_tags($content));

        $minutes = (int)floor($wordCount / $wpm);
        $seconds = (int)floor($wordCount % $wpm / ($wpm / 60));

        $str_minutes = ($minutes === 1) ? 'دقیقه' : 'دقیقه';
        $str_seconds = ($seconds === 1) ? 'ثانیه' : 'ثانیه';

        if ($minutes === 0) {
            return "{$seconds} {$str_seconds}";
        } else {
            return "{$minutes} {$str_minutes}, {$seconds} {$str_seconds}";
        }
    }

    public static function separateAttributeValues($attributes = null)
    {
        if (isset($attributes) && !empty($attributes)) {
            foreach ($attributes as $attribute => $attributeValue) {
                if (!empty($attributeValue)) {
                    $attributes[$attribute] = $attributeValue;
                } else {
                    unset($attributes[$attribute]);
                }
            }
        }

        return $attributes;
    }

    public static function convertToMiladi($date)
    {
        if (isset($date) && !empty($date)) {
            $explodeDate = explode("/", $date);
            if (count($explodeDate) == 3) {
                $times = explode(" ", $explodeDate[2]);
                $year = self::convert2english($explodeDate[0]);
                $month = self::convert2english($explodeDate[1]);
                $day = self::convert2english($times[0]);

                $miladi = Verta::getGregorian($year, $month, $day); // [2015,12,25]

                $stringMiladi = $miladi[0] . "-" . $miladi[1] . "-" . $miladi[2] . " " . self::convert2english($times[1]);
                return $timestamp = strtotime($stringMiladi);
            } else {
                return false;
            }
        }
    }


    /* Start Of Extra Function */

    public static function convert2english($string)
    {
        $newNumbers = range(0, 9);
        // 1. Persian HTML decimal
        $persianDecimal = array('&#1776;', '&#1777;', '&#1778;', '&#1779;', '&#1780;', '&#1781;', '&#1782;', '&#1783;', '&#1784;', '&#1785;');
        // 2. Arabic HTML decimal
        $arabicDecimal = array('&#1632;', '&#1633;', '&#1634;', '&#1635;', '&#1636;', '&#1637;', '&#1638;', '&#1639;', '&#1640;', '&#1641;');
        // 3. Arabic Numeric
        $arabic = array('٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩');
        // 4. Persian Numeric
        $persian = array('۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹');

        $string = str_replace($persianDecimal, $newNumbers, $string);
        $string = str_replace($arabicDecimal, $newNumbers, $string);
        $string = str_replace($arabic, $newNumbers, $string);
        return str_replace($persian, $newNumbers, $string);
    }

    public static function mb_str_word_count($string, $format = 0, $charlist = '[]')
    {

        mb_internal_encoding('UTF-8');
        mb_regex_encoding('UTF-8');

        $words = mb_split('[^\x{0600}-\x{06FF}]', $string);
        switch ($format) {
            case 0:
                return count($words);
                break;
            case 1:
            case 2:
                return $words;
                break;
            default:
                return $words;
                break;
        }
    }
}
