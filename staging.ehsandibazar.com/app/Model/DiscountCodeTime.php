<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DiscountCodeTime extends Model
{
    use SoftDeletes;
    protected $table='discount_code_time';

    protected $fillable=[
        'discount_code_id','expire_date'
    ];

    protected $dates = ['deleted_at'];


    public function discountCode()
    {
        return $this->belongsTo(DiscountCode::class);
    }

}
