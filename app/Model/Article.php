<?php

namespace App\Model;

use App\Http\Controllers\Admin\ArticleController;
use App\Traits\HasCategory;
use App\Traits\HasComment;
use App\Traits\HasImage;
use App\Traits\HasSeo;
use App\Traits\HasTag;
use App\User;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use willvincent\Rateable\Rateable;

class Article extends Model
{
    use SoftDeletes, HasImage, HasComment, HasTag, Rateable, HasCategory,HasSeo;

    public static $preventAttrSet = false;

    protected $guarded = ['id'];
    protected $dates = ['deleted_at'];

//    public function sluggable()
//    {
//        return [
//            'slug' => [
//                'source' => 'title'
//            ]
//        ];
//    }

    protected $casts = [
        'faq' => 'array',
    ];
	

    public function path()
    {
        return config('app.short_url') . "/article/$this->slug";
    }

    public function shortUrl()
    {
        return showShortUrl($this);
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeOwner($query)
    {
        if (auth()->user()->isAdmin() || auth()->user()->isSuperAdmin()) {
            return $query;
        } else {
            return $query->where('user_id', auth()->id());
        }
    }

    public function getCreatedAtAttribute($value)
    {
        if (self::$preventAttrSet) {
            return $this->attributes['created_at'] = Carbon::createFromFormat('Y-m-d H:i:s', $value)->format('Y/m/d');
        } else {
            $v = verta($value);
            return $this->attributes['created_at'] = $v->format('%Y/%m/%d');

        }

    }

    protected static function boot()
    {
        parent::boot();
        static::deleting(function ($item) {
            $item->image()->delete();
            $item->comments()->delete();
            $item->tags()->detach();
        });
    }
    public function setSlugAttribute($value)
    {
        if (empty($value) || $value == "") {
            $this->attributes['slug'] = SlugService::createSlug(__CLASS__, 'slug', $this->title);
        }
        $this->attributes['slug'] = $value;
    }

}
