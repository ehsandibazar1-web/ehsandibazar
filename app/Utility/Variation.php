<?php


namespace App\Utility;


use App\User;

class Variation
{
    const COLOR = 1, SIZE = 2, NO_ATTRIBUTE = 1;

    public static function countVariation($product_id)
    {
        $variation = \App\Model\Variation::with(['user' => function ($query) {
            $query->whereNotIn('level', Level::getAdmins());
        }])->where('product_id', $product_id)->whereStatus(1)->get();
        $variationUnique = collect($variation)->unique('user_id');
        $arrayVariation = self::eachUserNotRelation($variationUnique);
        return count($arrayVariation);
    }

    private static function eachUserNotRelation($variationUnique)
    {
        $arrayVariation = [];
        foreach ($variationUnique as $itemVariation) {
            if (!empty($itemVariation->user)) {
                $arrayVariation [] = $itemVariation->user_id;
            }
        }
        return $arrayVariation;
    }

    public static function typeOfVariation($type)
    {
        if ($type == self::COLOR) {
            return true;
        } else {
            return false;
        }
    }

    public static function countUserRequestVariation($product_id, $user_id)
    {
        $variation = \App\Model\Variation::where('product_id', $product_id)->
        where('user_id', $user_id)->get();
        return count($variation);
    }

    public static function findVariation($variation_id)
    {
        return \App\Model\Variation::where('id', $variation_id)->firstOrFail();
    }

    public static function findProduct($variation_id)
    {
        return \App\Model\Variation::findOrFail($variation_id)->product_id;
    }


    /* check relation variation */
    public static function checkVariation($variation_id)
    {
        $findVariation = \App\Model\Variation::whereId($variation_id)->get();
        if (isset($findVariation) && count($findVariation) > 0) {

        } else {
            return false;
        }
    }

    /* check relation variation */
    public static function checkRelationVariation($variation_id)
    {

        $findVariation = self::findVariation($variation_id);

        if (isset($findVariation->relatedVariations) && !empty($findVariation->relatedVariations)) {
            if (isset($findVariation->relatedVariations[0]) && !empty($findVariation->relatedVariations[0])) {
                return $findVariation->relatedVariations[0]->attributeTypeValue->value;
            }
        }
    }

    /* find all variation for admin and super admin except for another user group */
    public static function findVariationBaseOnUserGroup($user)
    {
        $findUser = User::findOrFail($user);
        if ($findUser->level == Level::SUPER_ADMIN || $findUser->level == Level::ADMIN) {
            return $AllProduct = \App\Model\Variation::with('product')->whereStatus(1)->get();
        }
        return $AllProduct = \App\Model\Variation::with('product')->where('user_id', $findUser->id)->whereStatus(1)->get();
    }

}
