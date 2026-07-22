<?php

namespace App\Cms\Concerns;

use App\Models\Media;

/**
 * پیاده‌سازیِ App\Cms\Contracts\HasFeaturedMedia — تصویرِ شاخص + نسخه‌ی WebPِ بهینه.
 * از همان الگوی سایت انگلیسی: تطبیقِ disk_path با جدولِ media (نه کلید خارجی).
 */
trait ProvidesFeaturedMedia
{
    public function getImagePath(): ?string
    {
        return $this->image_path;
    }

    public function getImageAlt(): ?string
    {
        return $this->image_alt;
    }

    public function getOptimizedImageUrlAttribute(): ?string
    {
        return Media::forRecord($this)?->webp_url;
    }
}
