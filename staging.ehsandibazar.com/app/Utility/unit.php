<?php


namespace App\Utility;


use App\Model\Systeminfmanage;
use Illuminate\Support\Facades\Lang;

class unit
{
    public static function unit($price)
    {
        $unit = Systeminfmanage::where('systeminf_id', 18)->where('status', 1)->first();
        if ($unit && !empty($unit)) {
            if ($unit->id == 50) {
                if ($price > 0) {
                    if (!empty($unit->name)) {
                        return number_format($price) . " " . $unit->name;
                    } else {
                        return number_format($price) . " " . Lang::get('cms.tooman');
                    }
                } else {
                    return 'محصول رایگان میباشد';

                }

            } else {
                if (!empty($unit->name)) {
                    $price = $price . "0";
                    return number_format($price) . " " . $unit->name;
                } else {
                    $price = $price . "0";
                    return number_format($price) . " " . Lang::get('cms.rial');
                }
            }
        } else {

            if ($price > 0) {
                return number_format($price) . " " . Lang::get('cms.tooman');
            }
            return 'محصول رایگان میباشد';

        }
    }
}
