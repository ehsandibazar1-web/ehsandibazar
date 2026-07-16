<?php
/**
 * Created by PhpStorm.
 * User: p
 * Date: 02/23/2019
 * Time: 01:37 PM
 */

namespace App\Services\ImageServices;


class ImageServices
{
    public static function delete_images($findID)
    {
        $findID->image()->delete([
            'imageable_id' => $findID->id,
            'imageable_type' => get_class($findID)
        ]);
    }

    public static function create_images($findID, $url, $user)
    {
        $findID->image()->create([
            'url' => str_replace(env('APP_URL'),"",$url->input('filepath')),
            'user_id' => $user,
            'imageable_id' => $findID->id,
            'imageable_type ' => get_class($findID)
        ]);
    }

    public static function create_images_register($findID, $url, $user)
    {
        $findID->image()->create([
            'url' => $url,
            'user_id' => $user,
            'imageable_id' => $findID->id,
            'imageable_type ' => get_class($findID)
        ]);
    }

    public static function update_images($findID, $url, $user)
    {
        $update = $findID->image()->update([
            'url' => str_replace(env('APP_URL'),"",$url->input('filepath')),
            'user_id' => $user,
            'imageable_id' => $findID->id,
        ]);
        return $update;
    }

    //=================================== array image =======================================

    /* create array image */
    public static function arrayCreate_images($findID, $url, $user)
    {
        $findID->image()->create([
            'url' => $url,
            'user_id' => $user,
            'imageable_id' => $findID->id,
            'imageable_type ' => get_class($findID)
        ]);
    }

    /* update array image */
    public static function arrayUpdate_images($findID, $url, $user)
    {
        $update = $findID->image()->update([
            'url' => $url,
            'user_id' => $user,
            'imageable_id' => $findID->id,
        ]);
        return $update;
    }
}
