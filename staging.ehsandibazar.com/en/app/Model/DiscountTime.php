<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DiscountTime extends Model
{
    use SoftDeletes;
    protected $table='discount_time';

    protected $fillable=[
        'discount_id','expire_date','start_date'
    ];

    protected $dates = ['deleted_at'];


    public function discount()
    {
        return $this->belongsTo(Discount::class);
    }
}
