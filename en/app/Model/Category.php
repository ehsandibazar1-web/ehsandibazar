<?php

namespace App\Model;

use App\Traits\HasDiscount;
use App\Traits\HasImage;
use App\Traits\HasSeo;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes, HasImage,HasDiscount,HasSeo;

    protected $table = "categories";
    protected $fillable = [
        'title', 'slug', 'user_id', 'parent_id', 'type', 'sorting', 'status', 'is_attributable'
    ];
    protected $dates = ['deleted_at'];
    public static $preventAttrSet = false;


//    public function sluggable()
//    {
//        return [
//            'slug' => [
//                'source' => 'title'
//            ]
//        ];
//    }

    protected static function boot()
    {
        parent::boot();
        static::deleting(function ($item) {
//            $item->categorized()->detach();
            // $item->news()->delete();
            $item->article()->delete();
            $item->products()->delete();
            // $item->job()->delete();
            // $item->event()->delete();
            // $item->video()->delete();
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function categorized($related = Article::class)
    {
        return $this->morphedByMany($related, 'categorizable');
    }


    public function article()
    {
        return $this->morphedByMany(Article::class, 'categorizable');
    }
    
     public function products()
    {
        return $this->morphedByMany(Product::class, 'categorizable');
    }



    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function categories()
    {
        return $this->hasMany(Category::class, 'parent_id')->orderBy('sorting', 'ASC');
    }

    public function subCategory()
    {
        return $this->hasMany(Category::class, 'parent_id')->orderBy('sorting', 'ASC');
    }

    public function path($entity = 'category')
    {
        return url($entity . "/" . $this->slug);
    }

    public function attributes()
    {
        return $this->belongsToMany(Attribute::class)->
        withPivot('is_filterable', 'is_searchable');
    }

    public function attributevalue()
    {
        return $this->hasMany(AttributeValue::class, 'category_id', 'id');
    }

    public function getTypeAttribute($value)
    {
        if (self::$preventAttrSet) {
            switch ($value) {
                case get_class(new Article()) :
                    return 'دسته بندی مقالات';
                    break;
                case get_class(new Product()) :
                    return 'دسته بندی محصولات';
                    break;
                case get_class(new Page()) :
                    return 'دسته بندی صفحات';
                    break;
            }
        } else {
            return $value;
        }
    }

    public function isAttributable()
    {
        return $this->is_attributable == 1 ? true : false;
    }
    public function setSlugAttribute($value)
    {
        if (empty($value) || $value == "") {
            $this->attributes['slug'] = SlugService::createSlug(__CLASS__, 'slug', $this->title);
        }
        $this->attributes['slug'] = $value;
    }


}
