<?php

namespace App\Cms\Contracts;

/**
 * هر محتوای قابل‌انتشارِ CMS (Article, Page, ...) این قابلیت‌ها را با هم دارد.
 * قابلیت‌های جدید علیه این interface نوشته می‌شوند، نه علیه مدلِ یک پروژه — تا پورت بین دو
 * پروژه با کمترین تغییر انجام شود (docs/CMS-CORE-CONTRACT.md).
 */
interface CmsContent extends Sluggable, Localizable, Publishable, Taggable, SeoOptimizable, HasFeaturedMedia
{
}
