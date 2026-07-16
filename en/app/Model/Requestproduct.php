<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Requestproduct extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'image',
        'video',
        'catalog',
        'description',
        'details',
        'status',
        'user_id',
        'product_id'
    ];

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

    /* if product_id not zero relation connect */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

}
