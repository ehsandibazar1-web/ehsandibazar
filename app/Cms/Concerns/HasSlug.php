<?php

namespace App\Cms\Concerns;

use Illuminate\Support\Str;

/**
 * پیاده‌سازیِ App\Cms\Contracts\Sluggable — ساختِ خودکارِ slug از عنوان هنگام ایجاد.
 */
trait HasSlug
{
    public static function bootHasSlug(): void
    {
        static::creating(function ($model): void {
            if (blank($model->slug) && filled($model->title ?? null)) {
                $model->slug = static::makeSlug($model->title, $model->locale ?? null);
            }
        });
    }

    public function getSlug(): string
    {
        return (string) $this->slug;
    }

    public static function makeSlug(string $title, ?string $locale = null): string
    {
        // Str::slug با فارسی حروف را حذف می‌کند؛ برای محتوای فارسی، اسلاگِ فارسی حفظ می‌شود.
        return $locale === 'fa'
            ? trim(preg_replace('/\s+/u', '-', trim($title)), '-')
            : Str::slug($title);
    }
}
