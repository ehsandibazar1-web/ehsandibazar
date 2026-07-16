<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Related extends Model
{
    use SoftDeletes;
    protected $table = "relateables";

    protected $fillable = [
       'relateable_id','relateable_type','related_id'
    ];

    public function relateable()
    {
        return $this->morphTo();
    }

    public function product()
    {
        return $this->belongsTo(Product::class,'related_id');
    }
}
