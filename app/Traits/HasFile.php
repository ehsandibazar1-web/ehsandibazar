<?php


namespace App\Traits;


use App\Model\File;

trait HasFile
{
    public function files()
    {
        return $this->morphMany(File::class, 'fileable');
    }
}
