<?php
/**
 * Created by PhpStorm.
 * User: shahriar
 * Date: 08/01/2019
 * Time: 01:12 PM
 */

namespace App\Utility;


class location
{

    /* 1 */
    const bgSearch = 1;

    /* 2 */
    const subSearch1 = 2;
    const subSearch2 = 3;
    const subSearch3 = 4;

    /* 3 */
    const subProduct1 = 5;
    const subProduct2 = 6;

    public static function location()
    {
        return [
            self::bgSearch => 'تصویر زمینه فرم جستجو',
            self::subSearch1 => 'پایین فرم جستجو1',
            self::subSearch2 => 'پایین فرم جستجو2',
            self::subSearch3 => 'پایین فرم جستجو3',
            self::subProduct1 => 'پایین پرفروش ترین ها1',
            self::subProduct2 => 'پایین پرفروش ترین ها2',
        ];
    }

    public static function getLocation($id)
    {
        switch ($id) {
            case  self::bgSearch:
                echo "<span class='icon-bullhorn'></span> <span> تصویر زمینه فرم جستجو</span>";
                break;

            case  self::subSearch1:
                echo "<span class='icon-bullhorn'></span> <span>پایین فرم جستجو1</span>";
                break;

            case  self::subSearch2:
                echo "<span class='icon-bullhorn'></span> <span>پایین فرم جستجو2</span>";
                break;

            case  self::subSearch3:
                echo "<span class='icon-bullhorn'></span> <span>پایین فرم جستجو3</span>";
                break;

            case  self::subProduct1:
                echo "<span class='icon-bullhorn'></span> <span>پایین پرفروش ترین ها1</span>";
                break;

            case  self::subProduct2:
                echo "<span class='icon-bullhorn'></span> <span>پایین پرفروش ترین ها2</span>";
                break;
        }
    }
}
