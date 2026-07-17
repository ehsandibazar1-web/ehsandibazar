<?php


namespace App\Utility;


use SoapClient;
use SoapFault;

class  SendSms
{
    public static function sms($code, $smsId, $receivers)
    {
        ini_set("soap.wsdl_cache_enabled", "0");
        try {
            $client = new SoapClient('http://api.payamak-panel.com/post/send.asmx?wsdl', array('encoding' => 'UTF-8'));
            // TODO: after MELIPAYAMAK_* keys are added to the server .env,
            // remove these hardcoded fallbacks and rotate the panel password.
            $data = array(
                "username" => env('MELIPAYAMAK_USERNAME', "9128936406"),
                "password" => env('MELIPAYAMAK_PASSWORD', "FHDZY"),
                "text" => $code,
                "to" => $receivers,
                "bodyId" => $smsId);
            $send_Result = $client->SendByBaseNumber($data)->SendByBaseNumberResult;
            // echo $status;
        } catch (SoapFault $ex) {
            echo $ex->faultstring;
        }
        
        
    }
}
