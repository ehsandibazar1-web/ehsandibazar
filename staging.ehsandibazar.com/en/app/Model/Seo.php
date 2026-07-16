<?php

namespace App\Model;

use App\Traits\HasImage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Seo extends Model
{
    use SoftDeletes, HasImage;

    protected $table = "seo";
    protected $guarded = [];

    public function seoable()
    {
        return $this->morphTo();
    }

}
