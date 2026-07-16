<?php
/**
 * Created by PhpStorm.
 * User: rezakia
 * Date: 29/12/2018
 * Time: 10:10 AM
 */

namespace App\Traits;


use App\Model\Tag;
use App\Model\Tagable;

trait HasTag
{
    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }
}
