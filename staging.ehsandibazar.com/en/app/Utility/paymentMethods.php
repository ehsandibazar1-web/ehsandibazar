<?php


namespace App\Utility;


class paymentMethods
{
    const ONLINE = 0, DELIVARY = 1 , WALLET=2;

    public static function eachPayment()
    {
        return [
            self::ONLINE => 'پرداخت آنلاین',
            self::DELIVARY => 'پرداخت در محل',
            self::WALLET => 'کیف پول'
        ];
    }

    public static function whichPaymentMethod($payment)
    {
            switch ($payment) {
                case self::ONLINE:
                    return 'پرداخت آنلاین';
                case self::DELIVARY:
                    return 'پرداخت در محل';
                case self::WALLET:
                    return 'کیف پول';
                default:
                    return self::ONLINE;
            }
    }


}
