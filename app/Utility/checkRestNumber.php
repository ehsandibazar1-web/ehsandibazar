<?php


namespace App\Utility;


use SoapClient;

class checkRestNumber
{
    public static function checkRestNumber($total_amount, $restNumber)
    {
        $MerchantID = env('MERCHANTID');
        $client = new SoapClient('https://sandbox.zarinpal.com/pg/services/WebGate/wsdl', ['encoding' => 'UTF-8']);
        $result = $client->PaymentVerification(
            [
                'MerchantID' => $MerchantID,
                'Authority' => $restNumber,
                'Amount' => $total_amount,
            ]
        );
        if ($result->Status == 100) {
            return true;
        } else {
            return false;
        }
    }
}
