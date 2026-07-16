<?php


namespace App\Utility;


use App\Model\Address;
use App\Model\Order;
use App\User;

class serializeAndUnSerialize
{
    /* just info user */
    public static function serializeAndUnSerializeInfoUser(User $userLogin = null, Address $addressSession = null, $order_id = 0)
    {
        if (isset($userLogin) && !empty($userLogin) && isset($addressSession) && !empty($userLogin) && $order_id == 0) {
            if (isset($userLogin) && isset($addressSession) && !empty($userLogin) && !empty($addressSession)) {
                $array = [
                    'userLogin' => $userLogin,
                    'addressSession' => $addressSession
                ];
                $serializeInfoUser = serialize($array);
                return $serializeInfoUser;
            }
        } else {
            $findOrder = Order::where('id', $order_id)->first();
            if (isset($findOrder) && !empty($findOrder)) {
                $infoUser = unserialize($findOrder->user_info);
                return $infoUser;
            }
        }
    }
}
