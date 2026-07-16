<?php
/**
 * Created by PhpStorm.
 * User: p
 * Date: 12/25/2018
 * Time: 03:54 PM
 */

namespace App\Utility;


use Carbon\Carbon;

class UploadImages
{
    public static function uploadImage($file , $path = 'slider')
    {
        $year = Carbon::now()->year;
        $imagePath = "upload/".$path."/images{$year}/";
        $filename = floor(microtime(true)).".".$file->getClientOriginalExtension();
        $file = $file->move(public_path($imagePath),$filename);
        if($file){
            $explode = explode("/" , $imagePath);
            $fileReturn = $explode[1]."/".$explode[2]."/".trim($filename);
            return $fileReturn;
        }else{
            return false;
        }
    }
}
