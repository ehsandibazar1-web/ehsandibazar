<?php
/**
 * Created by PhpStorm.
 * User: p
 * Date: 02/23/2019
 * Time: 01:37 PM
 */

namespace App\Services\catalogServices;


class catalogServices
{
    public static function delete_catalog($findID)
    {
        $findID->catalog()->delete([
            'catalogable_id' => $findID->id,
            'catalogable_type' => get_class($findID)
        ]);
    }
    public static function create_catalog($findID, $catalog , $user)
    {
        return $findID->catalog()->create([
            'url' => $catalog,
            'user_id' => $user,
            'catalogable_id' => $findID->id,
            'catalogable_type ' => get_class($findID)
        ]);
    }
    public static function update_catalog($findID, $catalog , $user)
    {
        $update =  $findID->catalog()->update([
            'url' => $catalog,
            'user_id' => $user,
            'catalogable_id' => $findID->id,
        ]);
        return $update;
    }

}
