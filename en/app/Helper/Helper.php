<?php
function persianNumberToEnglish($string)
{
    $newNumbers = range(0, 9);
    // 1. Persian HTML decimal
    $persianDecimal = array('&#1776;', '&#1777;', '&#1778;', '&#1779;', '&#1780;', '&#1781;', '&#1782;', '&#1783;', '&#1784;', '&#1785;');
    // 2. Arabic HTML decimal
    $arabicDecimal = array('&#1632;', '&#1633;', '&#1634;', '&#1635;', '&#1636;', '&#1637;', '&#1638;', '&#1639;', '&#1640;', '&#1641;');
    // 3. Arabic Numeric
    $arabic = array('٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩');
    // 4. Persian Numeric
    $persian = array('۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹');

    $string = str_replace($persianDecimal, $newNumbers, $string);
    $string = str_replace($arabicDecimal, $newNumbers, $string);
    $string = str_replace($arabic, $newNumbers, $string);
    return str_replace($persian, $newNumbers, $string);
}

function generateShortUrl($entity)
{
    $shortURLs = \AshAllenDesign\ShortURL\Model\ShortURL::findByDestinationURL($entity->path());
    if (isset($shortURLs[0]) && !empty($shortURLs[0])) {
        return $shortURLs;
    } else {
        $builder = new \AshAllenDesign\ShortURL\Classes\Builder();
        return $builder->destinationUrl($entity->path())->singleUse()->make();
    }
}

function showShortUrl($entity)
{
    $shortURLs = \AshAllenDesign\ShortURL\Model\ShortURL::findByDestinationURL($entity->path());
    if (isset($shortURLs[0]) && !empty($shortURLs[0])) {
        return $shortURLs[0]->default_short_url;
    } else {
        return false;
    }
}

function createMetaSite($model)
{
    if (isset($model->seo) && !empty($model->seo)){
        $meta = [
            empty($model->seo->title) ? $model->title : $model->seo->title => "setTitle",
            $model->seo->description => "setDescription",
            $model->seo->keyword => "setKeywords",
            $model->seo->canonical => "setCanonical",
        ];
        foreach ($meta as $key => $item){
            if (isset($key) && !empty($key)){
                \Artesaos\SEOTools\Facades\SEOMeta::{$item}($key);
            }
        }
    }
}
