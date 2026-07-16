<?php


namespace App\Traits;


use App\Model\Related;

trait HasRelated
{
    public function related()
    {
        return $this->morphMany(Related::class, 'relateable');
    }
}
