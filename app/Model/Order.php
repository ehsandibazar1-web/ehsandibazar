<?php

namespace App\Model;

use App\User;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{

    use SoftDeletes, CascadeSoftDeletes;

    protected $table = 'orders';

    protected $fillable = [
        'user_id', 'user_info','shipping_code',
        'total_amount', 'credit_count',
        'payment_method_id', 'shipping_method_id',
        'tracking_code', 'rest_number',
        'status', 'expire','item_count',
        'total_discount', 'coupon',
        'shippingCost' ,'ref_id','created_at'

    ];

    protected $cascadeDeletes = ['orderItem'];

    protected $dates = ['deleted_at'];

    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function orderItem()
    {
        return $this->hasMany(OrderItem::class)->withTrashed();
    }

    public function payment()
    {
        return $this->hasMany(Payment::class);
    }

    /* scope */
    public function scopeOwner($query)
    {
        if (auth()->user()->isAdmin() || auth()->user()->isSuperAdmin()) {
            return $query;
        } else {
            return $query->where('user_id', auth()->id());
        }
    }

    public function shippingMethod()
    {
        return $this->belongsTo(Systeminfmanage::class);
    }


    /* scope delete */
    public function scopeOwnerdelete($query)
    {
        if (auth()->user()->isAdmin()) {
            return $query;
        }
    }

    public function getCreatedAtAttribute($value)
    {
        $v = verta($value);
        switch (app()->getLocale()) {
            case('fa');
                return $this->attributes['created_at'] = $v->format('%d %B %Y H:i');
            case('en');
                return $this->attributes['created_at'] = $v->formatGregorian('d m Y');
        }
    }

}
