<?php

namespace App\Model;

use App\Traits\HasSeo;
use App\User;
use App\Traits\HasComment;
use App\Traits\HasImage;
use App\Traits\HasSearch;
use App\Traits\HasTag;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Model;
use Spatie\Searchable\Searchable;
use willvincent\Rateable\Rateable;

class Page extends Model
{
    use SoftDeletes, HasImage, HasTag, HasComment,Rateable,HasSeo;

    protected $guarded = ['id'];

    protected static function boot()
    {
        parent::boot();
        static::deleting(function ($offer) {
            $offer->image()->delete();
            $offer->comments()->delete();
            $offer->tags()->delete();
        });
    }

//    public function sluggable()
//    {
//        return [
//            'slug' => [
//                'source' => 'title'
//            ]
//        ];
//    }

    public function path()
    {
        return config('app.short_url')."/en/page/$this->slug";
    }

    public function shortUrl()
    {
        return showShortUrl($this);
    }

    /* owner */
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

    public function getCreatedAtAttribute($value)
    {
        $v = verta($value);
        switch (app()->getLocale()) {
            case('fa');
                return $this->attributes['created_at'] = $v->format('%d %B %Y');
            case('en');
                return $this->attributes['created_at'] = $v->formatGregorian('d m Y');
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
