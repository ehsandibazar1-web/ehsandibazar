<?php


namespace App\Utility;


use Illuminate\Support\Facades\Session;

class forgetSession
{
    public static function forgetSession($flag = 0)
    {
        Session::forget('basket');
        Session::forget('coupon');
        Session::forget('percent');
        Session::forget('prices');
        Session::forget('finishPrice');
        Session::forget('address');
        if ($flag > 0) {
            \session()->forget('basket');
            \session()->forget('coupon');
            \session()->forget('percent');
            \session()->forget('prices');
            \session()->forget('finishPrice');
            \session()->forget('address');
        }
    }
}
