<?php


namespace App\Utility;


class CommentStatus
{
    const ACCEPTET = 1;
    const NOT_ACCEPTET = 0;
    const FAILED = 2;

    public static function getStatusComment($status)
    {
        switch ($status) {
            case self::ACCEPTET:
                echo "<button class='btn btn-xs btn-success'>تایید  شده</button>";
                break;
            case self::NOT_ACCEPTET:
                echo "<button class='btn btn-xs btn-danger'>در انتظار تایید</button>";
                break;
            case self::FAILED:
                echo "<button class='btn btn-xs btn-warning'>رد شده</button>";
                break;
        }
    }


}
