<?php

namespace App\Cms\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * پیاده‌سازیِ App\Cms\Contracts\Localizable — لینکِ نسخه‌های هم‌زبان از طریقِ translation_of.
 */
trait HasLocale
{
    public function getLocale(): string
    {
        return (string) ($this->locale ?? 'fa');
    }

    public function translation(): BelongsTo
    {
        return $this->belongsTo(static::class, 'translation_of');
    }

    public function translations(): HasMany
    {
        return $this->hasMany(static::class, 'translation_of');
    }

    public function scopeLocale(Builder $query, string $locale): Builder
    {
        return $query->where('locale', $locale);
    }
}
