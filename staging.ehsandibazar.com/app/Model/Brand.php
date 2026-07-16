<?php

namespace App\Model;

use App\Traits\HasDiscount;
use App\Traits\HasImage;
use App\Traits\HasSeo;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Cviebrock\EloquentSluggable\Sluggable;
use Iatstuti\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Brand extends Model
{
    use SoftDeletes,HasImage , HasDiscount , CascadeSoftDeletes,HasSeo;
    protected $fillable = [
        'user_id', 'title', 'slug', 'sorting', 'lang', 'status','top','new','description','latin_title'
    ];
    protected $dates = ['deleted_at'];
    protected $cascadeDeletes = ['image','products'];
//    public function sluggable()
//    {
//        return [
//            'slug' => [
//                'source' => 'latin_title'
//            ]
//        ];
//    }


    /* owner */
    public function scopeOwner($query)
    {
        if (auth()->user()->isAdmin() || auth()->user()->isSuperAdmin()) {
            return $query;
        } else {
            return $query->where('user_id', auth()->id());
        }
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function path()
    {
        return Url('brand/'.$this->slug);
    }

    public function setSlugAttribute($value)
    {
        if (empty($value) || $value == "") {
            $this->attributes['slug'] = SlugService::createSlug(__CLASS__, 'slug', $this->title);
        }
        $this->attributes['slug'] = $value;
    }

}
