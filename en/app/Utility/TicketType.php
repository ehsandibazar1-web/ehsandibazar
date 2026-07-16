<?php


namespace App\Utility;


class TicketType
{
    /* Ticket Status*/
    const OPEN = 3;
    const CLOSE = 4;
    const WAITTING = 0;
    const OBSERVE = 1;
    const ANEWERED = 2;

    // Priority
    const Low = 1;
    const Medium = 2;
    const High = 3;

    public static function TicketStatus()
    {
        return [
            self::OPEN => "باز",
            self::WAITTING => "در انتظار پاسخ",
            self::OBSERVE => "مشاهده شده",
            self::ANEWERED => "پاسخ داده شده",
            self::CLOSE => "بسته شده",
        ];
    }

    public static function TicketAnswerStatus()
    {
        return [
            self::WAITTING => "در انتظار پاسخ",
            self::OBSERVE => "مشاهده شده",
            self::ANEWERED => "پاسخ داده شده",
        ];
    }

    public static function TicketPriority()
    {
        return [
            self::Low => "کم",
            self::Medium => "متوسط",
            self::High => "زیاد",
        ];
    }

    public static function GetTicketPriority($priority)
    {
        switch ($priority) {
            case self::Low:
                echo "<label class='label label-xs label-default'>کم</label>";
                break;
            case self::Medium:
                echo "<label class='label label-xs label-warning'>متوسط</label>";
                break;
            case self::High:
                echo "<label class='label label-xs label-danger'>زیاد</label>";
                break;
        }

    }


    public static function GetTicketStatus($status, $answer = 1)
    {
        switch ($status) {
            case self::OPEN:
                echo "<label class='label label-xs label-success'>باز</label>";
                break;
            case self::CLOSE:
                echo "<label class='label label-xs label-danger'>بسته شده</label>";
                break;
            case self::WAITTING:
                echo "<label class='btn btn-xs btn-warning'>در انتظار پاسخ</label>";
                break;
            case self::OBSERVE:
                echo "<label class='btn btn-xs btn-default'>مشاهده شده</label>";
                break;
            case self::ANEWERED:
                echo "<label class='btn btn-xs btn-success'>پاسخ داده شده</label>";
                break;

        }

    }
}
