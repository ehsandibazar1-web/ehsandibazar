<?php


namespace App\Utility;


use App\Model\Order;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use SoapClient;

class zarinPall
{
    public static function zarinPal($merchantId, $urlPay, $urlCheck, $email, $callBackUrl, $amount,
                                    $order = null, $order_id = null)
    {

        if ($order_id == null && !empty($order)) {
            /* seller payment */

            $data = array("merchant_id" => $merchantId,
                "amount" => ($amount * 10),
                "callback_url" => $callBackUrl,
                "description" => env('DESCRIPTION_PAYMENT'),
                "metadata" => ['email' => $email, 'mobile' => Auth::user()->mobile],
            );

            $jsonData = json_encode($data);
            $ch = curl_init('https://api.zarinpal.com/pg/v4/payment/request.json');
            curl_setopt($ch, CURLOPT_USERAGENT, 'ZarinPal Rest Api v1');
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
            curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($jsonData)
            ));
            $result = curl_exec($ch);
            $err = curl_error($ch);
            $result = json_decode($result, true, JSON_PRETTY_PRINT);

            curl_close($ch);

            if ($err) {
                toast()->error($err, Lang::get('cms.error'));
                return redirect()->route('site.index');
            } else {
                if (empty($result['errors'])) {
                    if ($result['data']['code'] == 100) {
                        $findOrder = Order::where('id', $order)->update(['rest_number' => $result['data']["authority"]]);
                        if ($findOrder > 0) {
                            return redirect($urlPay . $result['data']["authority"]);
                        } else {
                            toast()->error(Message::illegalError, Lang::get('cms.error'));
                            return redirect()->route('panel.payment.index');
                        }

                    }
                } else {
                    $msg = $result['errors']['message'];
                    toast()->error("خطا : $msg", Lang::get('cms.error'));
                    return redirect()->route('site.index');
                }
            }


        }

    }
}
