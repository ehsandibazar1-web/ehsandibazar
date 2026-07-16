<?php

namespace App\Model;

use App\Traits\HasDiscount;
use App\User;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use \Askedio\SoftCascade\Traits\SoftCascadeTrait;


class Discount extends Model
{
    use SoftDeletes, CascadeSoftDeletes;

    protected $fillable = [
        'user_id', 'title', 'description', 'baseon', 'cent', 'count_buy', 'type', 'discountable_type', 'count_user', 'status'
    ];

    protected $table = 'discounts';

    protected $cascadeDeletes = ['discountCode', 'discountTime', 'coupon', 'disable'];
    protected $dates = ['deleted_at'];

    /* scope */
    public function scopeOwner($query)
    {
        if (auth()->user()->isAdmin() || auth()->user()->isSuperAdmin()) {
            return $query;
        } else {
            return $query->where('user_id', auth()->id());
        }
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function disable()
    {
        return $this->hasMany(Discountable::class);
    }

    public function discountCode()
    {
        return $this->hasMany(DiscountCode::class);
    }

    public function discountTime()
    {
        return $this->hasMany(DiscountTime::class);
    }

    public function coupon()
    {
        return $this->hasMany(Coupon::class);
    }

    public function discountUser()
    {
        return $this->belongsToMany(User::class,'discount_user');
    }


}
