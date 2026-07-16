<?php


namespace App\Utility;


use App\Model\Systeminfmanage;

class taxCalculate
{
    public static $tax;

    public function __construct()
    {
        $this->tax = Systeminfmanage::where('id', 52)->where('status', 1)->first();
    }

    public static function taxCalculate($price, $formatNumber = 0)
    {
        $getTax = self::$tax;
        if ($getTax) {
            $calculate = ($price * $getTax->code) / 100;
            $price = $price + $calculate;

            if ($formatNumber == 0) {
                return unit::unit($price);
            } else {
                return $price;
            }
        }
    }

    public static function getTax($totalPrice, $priceCalculate, $discountUserGeneral = null)
    {
        $getTax = self::$tax;

        if (isset($totalPrice) && isset($priceCalculate) && $priceCalculate > 0) {
            $difPrice = $totalPrice - $priceCalculate;
            $difPrice = (int)round($difPrice);
            if ($difPrice > 0) {
                $tax = ($difPrice * 100) / $priceCalculate;
                $tax = (int)round($tax);
                // dd($tax);
                return $tax;
            } else {
                return $tax = 0;
            }
        }
    }
}
