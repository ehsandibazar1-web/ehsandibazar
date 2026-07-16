<?php

namespace App\Model;

use Iatstuti\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DiscountCode extends Model
{
    use SoftDeletes,CascadeSoftDeletes;
    protected $table='discount_code';

    protected $fillable=[
        'discount_id','code'
    ];


    protected $cascadeDeletes = ['discountCodeTime'];
    protected $dates = ['deleted_at'];


    public function discount()
    {
        return $this->belongsTo(Discount::class);
    }

    public function discountCodeTime()
    {
        return $this->hasMany(DiscountCodeTime::class);
    }

}
