<?php
/**
 * Created by PhpStorm.
 * User: shahriar
 * Date: 29/12/2018
 * Time: 10:10 AM
 */

namespace App\Traits;
use App\Model\Image;

trait HasImage
{
    public function image()
    {
        return $this->morphMany(Image::class, 'imageable');
    }
}
