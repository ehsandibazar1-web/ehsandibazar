<?php


namespace App\Traits;



use App\Model\favorite;

trait HasFavorite
{
    public function favorites()
    {
        return $this->morphMany(favorite::class, 'favoriteable');
    }
}