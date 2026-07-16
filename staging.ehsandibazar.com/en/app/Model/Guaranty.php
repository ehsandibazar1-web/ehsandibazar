<?php

namespace App\Model;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Guaranty extends Model
{
    use SoftDeletes, Sluggable;

    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'description',
        'lang',
        'status',
    ];

    protected $dates = ['deleted_at'];

    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    /* scope */
    public function scopeOwner($query)
    {
        if (auth()->user()->isAdmin()) {
            return $query;
        } else {
            return $query->where('user_id', auth()->id());
        }
    }

    public function products()
    {
        return $this->belongsToMany(Product::class,'guaranty_product','guaranty_id','product_id') ;
    }
}
