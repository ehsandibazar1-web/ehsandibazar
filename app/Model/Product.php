<?php

namespace App\Model;

use App\Traits\HasCatalog;
use App\Traits\HasCategory;
use App\Traits\HasComment;
use App\Traits\HasFavorite;
use App\Traits\HasImage;
use App\Traits\HasRelated;
use App\Traits\HasSeo;
use App\Traits\HasTag;
use App\Traits\HasVideo;
use App\User;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Cviebrock\EloquentSluggable\Sluggable;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Rateable;

class Product extends Model
{
    use SoftDeletes, HasImage,HasVideo, HasCatalog, HasTag, HasComment,
        HasFavorite, CascadeSoftDeletes, Rateable, HasRelated,HasCategory,HasSeo;

    protected $appends = ['prices'];

    protected $fillable = [
        'user_id',
        'type',
        'title',
        'slug',
        'description',
        'viewCount',
        'commentCount',
        'soldCount',
        'offerCount',
        'expire_date',
        /* 'price',*/
        'code',
        'weight',
        'shipping_cost',
        /*'guaranty' ,
        'date_delivery' ,*/
        'lang',
        'status',
        'sales',
        'special',
        'momentary',
        'brand_id',
        'sorting',
        'selected_brand',
        'package_detail'
    ];

    protected $table = 'products';

    protected $dates = ['deleted_at'];

//    protected $cascadeDeletes = ['variations', 'Requestproducts', 'image', 'video', 'catalog', 'comments'];

//    public function sluggable(): array
//    {
//        return [
//            'slug' => [
//                'source' => 'title'
//            ]
//        ];
//    }


    public function getPricesAttribute()
    {
 
        $allPrice = \App\Utility\sortPrice::sortPrice($this, 1);
        return \App\Utility\sortPrice::totalPrice($allPrice);
    }


    // this is a recommended way to declare event handlers
    public static function boot()
    {
        parent::boot();
        static::deleting(function ($product) {
            // before delete() method call this
            $product->variations()->delete();
            $product->attributevalues()->delete();
            $product->comments()->delete();
            $product->image()->delete();
            $product->video()->delete();
            $product->catalog()->delete();
            $product->tags()->delete();
            $product->favorites()->delete();
            $product->auction()->delete();
            // do the rest of the cleanup...
        });
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

    /* many to many relation by attribute_values pTable :attribute_value_product */
    public function attributevalues()
    {
        return $this->belongsToMany(AttributeValue::class, 'attribute_value_product', 'product_id', 'attribute_value_id');
        //return $this->belongsToMany(AttributeValue::class ,'attribute_value_product','attribute_value_id','product_id');
    }


    public function variations()
    {
        return $this->hasMany(Variation::class);
    }


    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function guaranties()
    {
        return $this->belongsToMany(Guaranty::class, 'guaranty_product', 'product_id', 'guaranty_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /* if product_id not zero relation connect */
    public function Requestproducts()
    {
        return $this->hasMany(Requestproduct::class);
    }

    public function path()
    {
        return Url('products/' . $this->slug);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function auction()
    {
        return $this->hasOne(Auction::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }


    public function getCreatedAtAttribute($value)
    {
        $v = verta($value);
        switch (app()->getLocale()) {
            case('fa');
                return $v->format('%d %B %Y H:i:s');
            case('en');
                return $v->formatGregorian('d m Y H:i:s');
        }
    }

    public function getUpdatedAtAttribute($value)
    {
        $v = verta($value);
        switch (app()->getLocale()) {
            case('fa');
                return $v->format('%d %B %Y H:i:s');
            case('en');
                return $v->formatGregorian('d m Y H:i:s');
        }
    }

    public function setSlugAttribute($value)
    {
        if (empty($value) || $value == "") {
            $this->attributes['slug'] = SlugService::createSlug(__CLASS__, 'slug', $this->title);
        }
        $this->attributes['slug'] = $value;
    }


}
