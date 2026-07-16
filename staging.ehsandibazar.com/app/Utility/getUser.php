<?php


namespace App\Utility;


use App\User;

class getUser
{
    public static function getUser($user_id)
    {
        if(isset($user_id)){
            $findUser = User::whereActive(1)->findOrFail($user_id);
            return $findUser;
        }
    }
}
