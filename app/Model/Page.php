<?php

namespace App\Model;

use App\Cms\Contracts\Localizable;
use App\Cms\Contracts\Publishable;
use App\Traits\HasSeo;
use App\User;
use App\Traits\HasComment;
use App\Traits\HasImage;
use App\Traits\HasSearch;
use App\Traits\HasTag;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Model;
use Spatie\Searchable\Searchable;
use App\Traits\Rateable;

/**
 * موج ۴d: Page مثلِ Article قراردادِ Localizable/Publishable را با «پل» به ستون‌های legacyِ زنده
 * (lang، statusِ بولین) پیاده می‌کند. storefront (`/page/{slug}` با where('status',1)) دست‌نخورده.
 */
class Page extends Model implements Localizable, Publishable
{
    use SoftDeletes, HasImage, HasTag, HasComment,Rateable,HasSeo;

    protected $guarded = ['id'];

    protected $casts = [
        'faqs' => 'array',
        'published_at' => 'datetime',
    ];

    public function getLocale(): string
    {
        return (string) ($this->lang ?? 'fa');
    }

    public function translation(): BelongsTo
    {
        return $this->belongsTo(self::class, 'translation_of');
    }

    public function translations(): HasMany
    {
        return $this->hasMany(self::class, 'translation_of');
    }

    public function isPublished(): bool
    {
        return (int) $this->status === 1;
    }

    public function getPublishedAt(): ?\DateTimeInterface
    {
        return $this->published_at;
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 1);
    }

    protected static function boot()
    {
        parent::boot();
        static::deleting(function ($offer) {
            $offer->image()->delete();
            $offer->comments()->delete();
            $offer->tags()->delete();
        });
    }

//    public function sluggable(): array
//    {
//        return [
//            'slug' => [
//                'source' => 'title'
//            ]
//        ];
//    }

    public function path()
    {
        return config('app.short_url')."/page/$this->slug";
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
