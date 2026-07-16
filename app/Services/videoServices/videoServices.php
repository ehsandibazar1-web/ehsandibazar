<?php
/**
 * Created by PhpStorm.
 * User: p
 * Date: 02/23/2019
 * Time: 01:37 PM
 */

namespace App\Services\videoServices;


class videoServices
{
    public static function delete_videos($findID)
    {
        $findID->video()->delete([
            'videoable_id' => $findID->id,
            'videoable_type' => get_class($findID)
        ]);
    }

    public static function create_videos($findID, $url, $user)
    {
        $findID->video()->create([
            'url' => $url->input('video'),
            'user_id' => $user,
            'videoable_id' => $findID->id,
            'videoable_type ' => get_class($findID)
        ]);
    }

    public static function update_videos($findID, $url, $user)
    {
        $update = $findID->video()->update([
            'url' => $url->input('video'),
            'user_id' => $user,
            'videoable_id' => $findID->id,
        ]);
        return $update;
    }

    //=================================== array video =======================================

    /* create array video */
    public static function arrayCreate_videos($findID, $url, $user,$title = null)
    {
        $findID->video()->create([
            'title' => $title,
            'url' => $url,
            'user_id' => $user,
            'videoable_id' => $findID->id,
            'videoable_type ' => get_class($findID)
        ]);
    }

    /* update array video */
    public static function arrayUpdate_videos($findID, $url, $user)
    {
        $update = $findID->video()->update([
            'url' => $url,
            'user_id' => $user,
            'videoable_id' => $findID->id,
        ]);
        return $update;
    }
}
