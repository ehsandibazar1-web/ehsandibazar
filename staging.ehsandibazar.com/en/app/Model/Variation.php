<?php

namespace App\Model;

use App\Traits\HasDiscount;
use App\Traits\HasImage;
use App\User;
use Iatstuti\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Variation extends Model
{
    use SoftDeletes, HasDiscount, CascadeSoftDeletes;
    protected $fillable = [
        'user_id',
        'product_id',
        'attribute_type_value_id',
        'price',
        'discountPrice',
        'discountActive',
        'count',
        'description',
        'deleted_at',
        'status'
    ];


    // this is a recommended way to declare event handlers
    public static function boot()
    {
        parent::boot();
        static::deleting(function ($user) { // before delete() method call this
            $user->relatedVariations()->delete();
            $user->discount()->delete();
            // do the rest of the cleanup...
        });
    }

    public function relatedVariations()
    {
        return $this->hasMany(Relatedvariation::class, 'variation_id', 'id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function attributeTypeValue()
    {
        return $this->belongsTo(AttributeTypeValue::class, 'attribute_type_value_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
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

    public function path()
    {
        return $this->product->path();
    }


    public function getPriceAttribute($price)
    {
        if (Auth::check() && Auth::user()->isColleague()){
            $price = ($price - (($price * Auth::user()->discount_percent) / 100));
        }
        return $price;
    }

}
