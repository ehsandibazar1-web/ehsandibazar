<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Discountable extends Model
{
    use SoftDeletes;

    protected $fillable=[
        'discount_id','discountable_id','discountable_type'
    ];

    protected $table='discountable';
    protected $dates = ['deleted_at'];


    public function discountable()
    {
        return $this->morphTo();
    }

    public function discount()
    {
        return $this->belongsTo(Discount::class);
    }

}
