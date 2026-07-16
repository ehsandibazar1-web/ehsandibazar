<?php
/**
 * Created by PhpStorm.
 * User: rezakia
 * Date: 06/12/2019
 * Time: 01:08 PM
 */

namespace App\Utility;


class methods
{
    const POST = 1 , GET = 2 , PATCH = 3 , UPDATE = 4 , DELETE = 5 , NOTHING = 6 ;

    public static function getMethods()
    {
        return [
            self::POST => "POST",
            self::GET => "GET",
            self::PATCH => "PATCH",
            self::UPDATE => "UPDATE",
            self::DELETE => "DELETE",
            self::NOTHING => "NOTHING"
        ];
    }

}
