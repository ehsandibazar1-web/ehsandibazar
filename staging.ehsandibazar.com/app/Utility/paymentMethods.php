<?php


namespace App\Utility;


class paymentMethods
{
    const ONLINE = 0, DELIVARY = 1 , WALLET=2,CARDTOCARD=3;

    public static function eachPayment()
    {
        return [
            self::ONLINE => 'پرداخت آنلاین',
            self::DELIVARY => 'پرداخت در محل',
            self::WALLET => 'کیف پول',
            self::CARDTOCARD => 'کارت به کارت'
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
                case self::CARDTOCARD:
                    return 'کارت به کارت';
                default:
                    return self::ONLINE;
            }
    }


}
