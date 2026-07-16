<?php
/**
 * Created by PhpStorm.
 * User: p
 * Date: 02/24/2019
 * Time: 01:38 PM
 */

namespace App\Utility;



use App\Model\Category;

class parentName
{

    public static function getName($parent_id)
    {

            if($parent_id == 0){
                return "اصلی";
            }else{
               $category = Categoryarticle::where('id' , $parent_id)->first();
               return $category->title;
            }


    }

    public static function getCategoryProductName($parent_id)
    {
        if($parent_id == 0){
            return "اصلی";
        }else{
            $category = Category::where('id' , $parent_id)->first();
            return $category->title;
        }
    }

}
