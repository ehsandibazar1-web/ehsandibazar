<?php


namespace App\Traits;


use App\Model\Category;

trait HasCategory
{
    public function categories()
    {
        return $this->morphToMany(Category::class, 'categorizable');
    }
}
