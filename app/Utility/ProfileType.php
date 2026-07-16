<?php


namespace App\Utility;


class ProfileType
{
    public const PARTICULAR = 1, PUBLIC = 2, FRIENDS = 3;

    public static function getType()
    {
        return [
            self::PARTICULAR => "مخصوصی",
            self::PUBLIC => "عمومی",
            self::FRIENDS => "فقط دوستان",
        ];
    }

    public static function getTypeIcon()
    {
        return [
            self::PARTICULAR => "<i class='fas fa-lock'></i> خصوصی",
            self::PUBLIC => "<i class='fas fa-globe-americas'></i> عمومی",
            self::FRIENDS => "<i class='fas fa-user-friends'></i> فقط دوستان",
        ];
    }

    public static function getStatus($type)
    {
        switch ($type) {
            case self::PARTICULAR:
                return "خصوصی";
                break;
            case self::PUBLIC:
                return "عمومی";
                break;
            case self::FRIENDS:
                return "فقط دوستان";
                break;
        }
    }
}
