<?php

namespace App\Model;

use App\Cms\Contracts\Localizable;
use App\Cms\Contracts\Publishable;
use App\Http\Controllers\Admin\ArticleController;
use App\Traits\HasCategory;
use App\Traits\HasComment;
use App\Traits\HasImage;
use App\Traits\HasSeo;
use App\Traits\HasTag;
use App\User;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use App\Traits\Rateable;

/**
 * موج ۴: Article قراردادِ CMS Core را (به‌تدریج) پیاده می‌کند. Localizable و Publishable اینجا
 * با «پل» به ستون‌های legacyِ زنده (lang و statusِ بولین) پیاده شده‌اند — نه با traitهای Core که
 * ستونِ locale/statusِ رشته‌ای فرض می‌کنند. storefront دست‌نخورده می‌ماند (نگاه کنید به docs/WAVE-4-PLAN.md).
 * Taggable/SeoOptimizable/CmsContentِ کامل در ۴c (همراه با همگراییِ tags و مدلِ Keyword) اضافه می‌شوند.
 */
class Article extends Model implements Localizable, Publishable
{
    use SoftDeletes, HasImage, HasComment, HasTag, Rateable, HasCategory,HasSeo;

    public static $preventAttrSet = false;

    protected $guarded = ['id'];
    protected $dates = ['deleted_at'];

//    public function sluggable(): array
//    {
//        return [
//            'slug' => [
//                'source' => 'title'
//            ]
//        ];
//    }

    protected $casts = [
        'faq' => 'array',
        'faqs' => 'array',           // موج ۴: ستونِ canonicalِ FAQ (جدا از faqِ قدیمی)
        'published_at' => 'datetime',
        'is_scheduled' => 'boolean',  // نشانگرِ انتشارِ زمان‌بندی‌شده (articles:publish-due)
    ];

    // --- App\Cms\Contracts\Localizable (پل به ستونِ legacyِ lang) ---
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

    // --- App\Cms\Contracts\Publishable (پل به ستونِ legacyِ statusِ بولین — storefront همان where('status',1)) ---
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
        // بدونِ نوشتنِ دوباره در attributes: قبلاً مقدارِ فرمت‌شده را برمی‌گرداند «و» در
        // $this->attributes می‌نوشت؛ این باعث می‌شد بارِ دومِ خواندن (مثلاً در رندرِ Filament)
        // روی رشته‌ی از-قبل-فرمت‌شده اجرا شود و خطای پارس بدهد — و در صورتِ save پس از خواندن،
        // مقدارِ شمسی را در DB می‌نوشت. حالا فقط return می‌کند (خروجی دقیقاً همان قبلی).
        if (self::$preventAttrSet) {
            return Carbon::createFromFormat('Y-m-d H:i:s', $value)->format('Y/m/d');
        }

        return verta($value)->format('%Y/%m/%d');
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
