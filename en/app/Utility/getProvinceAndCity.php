<?php


namespace App\Utility;


use App\Model\City;
use App\Model\Province;

class getProvinceAndCity
{

    public static function getProvinceAndCity( $province , $city )
    {
        if(isset($province) && isset($city) && !empty($province) && !empty($city) ){
            $findProvince = Province::where('id' , $province)->first();
            $findCity = City::where('id' , $city)->first();

            if(isset($findProvince) && isset($findCity)){
                return "استان :‌"  . $findProvince->name . " </br> " . "شهر :" . $findCity->name;
            }else{
                return "";
            }
        }
    }

}
