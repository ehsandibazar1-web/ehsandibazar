<?php

namespace App\Model;

use App\Traits\HasDiscount;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Coupon extends Model
{

    use SoftDeletes , HasDiscount;
    protected $table='coupon';

    protected $fillable=[
        'discount_id','code','expire_date'
    ];
    protected $dates = ['deleted_at'];


    public function discount()
    {
        return $this->belongsTo(Discount::class);
    }
}
