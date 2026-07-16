<?php
/**
 * Created by PhpStorm.
 * User: rezakia
 * Date: 02/23/2020
 * Time: 14:37 PM
 */

namespace App\Services\relatedServices;


class relatedServices
{
    public static function delete_related($findID,$item)
    {
        $findID->related()->delete([
            'relatedable_id' => $item,
            'relatedable_type' => get_class($findID)
        ]);
    }

    public static function create_related($findID,$item)
    {
        $findID->related()->create([
            'relatedable_id' => $item,
            'relatedable_type ' => get_class($findID)
        ]);
    }

    public static function create_related_register($findID,$item)
    {
        $findID->related()->create([
            'relatedable_id' => $item,
            'relatedable_type ' => get_class($findID)
        ]);
    }

    public static function update_related($findID,$item)
    {
        $update = $findID->related()->update([
            'relatedable_id' => $item,
        ]);
        return $update;
    }

    //=================================== array image =======================================

    /* create array image */
    public static function arrayCreate_related($findID,$item)
    {
        $findID->related()->create([
            'relatedable_id' => $item,
            'relatedable_type ' => get_class($findID)
        ]);
    }

    /* update array image */
    public static function arrayUpdate_related($findID,$item)
    {
        $update = $findID->related()->update([
            'relatedable_id' => $item,
        ]);
        return $update;
    }
}
