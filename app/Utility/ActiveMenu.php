<?php
/**
 * Created by PhpStorm.
 * User: shahriar
 * Date: 24/11/2019
 * Time: 11:01 AM
 */

namespace App\Utility;


class ActiveMenu
{

    public static function ActiveMenu($value, $bool = null, $active = null)
    {
        $url = \Illuminate\Support\Facades\URL::current();
        $explode = explode("/", $url);
        $end = end($explode);
        if (is_array($value)){
            if (in_array($end, $value)) {
                if ($active == null) {
                    $active = $bool == 1 ? true : "active open";
                }
                return $active;
            }
        }

    }

    public static function ActiveMenuUserArea($value, $bool = null, $active = null,$class = "active-menu")
    {
        $url = \Illuminate\Support\Facades\URL::current();
        $explode = explode("/", $url);
        $end = end($explode);

        if (is_array($value)){
            if (in_array($end, $value)) {
                if ($active == null) {
                    $active = $bool == 1 ? true : $class;
                }
                return $active;
            }
        }

    }


}
