<?php
/**
 * Created by PhpStorm.
 * User: shahriar
 * Date: 02/01/2019
 * Time: 11:36 AM
 */

namespace App\Services\questionServices;


class QuestionService
{
    public static function create_answer($findID, $option)
    {
        $findID->answers()->create([
            'title' => $option
        ]);
    }

    public static function delete_answer($findID)
    {
        $findID->answers()->delete([
            'question_id' => $findID->id
        ]);
    }

    public static function create_follow($findID, $user_id)
    {
        $findID->follow()->create([
            'user_id' => $user_id,
            'followable_id' => $findID->id,
            'followable_type ' => get_class($findID)
        ]);
    }

    public static function delete_follow($findID, $user_id)
    {
         $findID->follow()->delete([
            'user_id' => $user_id,
            'followable_id' => $findID->id,
            'followable_type' => get_class($findID)
        ]);

    }

}
