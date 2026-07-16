<?php

namespace App\Model;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderItem extends Model
{

    use SoftDeletes;


    protected $table='order_items';

    protected $fillable = [
        'product_id','order_id','amount','amount_discount','itemCount','details','discount'
    ];

    protected $dates = ['deleted_at'];

    public function order()
    {
        return $this->belongsTo(Order::class)->whereHas('user')->withTrashed();
    }

    public function product()
    {
       return $this->belongsTo(Product::class);
    }

     public function product2()
    {
       return $this->belongsTo(Product::class)->select('title');
    }
}
