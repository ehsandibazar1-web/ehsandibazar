<?php

namespace App\Cms\Concerns;

use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * پیاده‌سازیِ App\Cms\Contracts\SeoOptimizable — ستون‌های سئو + کلیدواژه‌های هدف.
 * App\Models\Keyword در موج ۴/۶ ساخته می‌شود (نگاه کنید به توضیحِ HasContentTags).
 */
trait HasSeoMeta
{
    public function keywords(): MorphMany
    {
        return $this->morphMany(\App\Models\Keyword::class, 'keywordable');
    }

    public function getSeoTitle(): ?string
    {
        return $this->seo_title ?: ($this->title ?? null);
    }

    public function getMetaDescription(): ?string
    {
        return $this->meta_description;
    }

    public function getCanonicalUrl(): ?string
    {
        return $this->canonical_url;
    }

    public function getRobots(): ?string
    {
        return $this->robots;
    }
}
