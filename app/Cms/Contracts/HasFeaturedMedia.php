<?php

namespace App\Cms\Contracts;

interface HasFeaturedMedia
{
    public function getImagePath(): ?string;

    public function getImageAlt(): ?string;

    /** URLِ WebPِ بهینه (از Media)، با fallback به مسیرِ خام. */
    public function getOptimizedImageUrlAttribute(): ?string;
}
