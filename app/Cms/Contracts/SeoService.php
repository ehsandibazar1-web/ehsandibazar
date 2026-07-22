<?php

namespace App\Cms\Contracts;

/**
 * قراردادِ سرویسِ سئو (docs/CMS-CORE-CONTRACT.md، لایه ۵). پیاده‌سازی در موج ۶ (SEO Center) می‌آید.
 */
interface SeoService
{
    /** آرایه‌ی آماده‌ی <head> برای یک محتوا (title/description/canonical/robots/og). */
    public function metaFor(CmsContent $content): array;

    /** امتیازِ سئوی یک محتوا بر پایه‌ی کلیدواژه‌ها/متا. */
    public function keywordScore(CmsContent $content): int;
}
