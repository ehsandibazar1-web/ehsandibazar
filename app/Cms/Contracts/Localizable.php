<?php

namespace App\Cms\Contracts;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

interface Localizable
{
    /** 'en' | 'tr' | 'fa' — نگاه کنید به App\Cms\Enums\Locale */
    public function getLocale(): string;

    /** نسخه‌ی هم‌زبانِ مرجع (translation_of). */
    public function translation(): BelongsTo;

    /** ترجمه‌های وابسته به این رکورد. */
    public function translations(): HasMany;
}
