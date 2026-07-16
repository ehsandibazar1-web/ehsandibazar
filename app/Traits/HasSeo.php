<?php


namespace App\Traits;


use App\Model\Seo;

trait HasSeo
{
    public function seo()
    {
        return $this->morphOne(Seo::class, 'seoable');
    }
}