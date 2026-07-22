<?php

namespace App\Cms\Contracts;

use Illuminate\Database\Eloquent\Relations\MorphMany;

interface SeoOptimizable
{
    /** کلیدواژه‌های هدفِ سئو — چندریختیِ `keywordable`. */
    public function keywords(): MorphMany;

    public function getSeoTitle(): ?string;

    public function getMetaDescription(): ?string;

    /** null ⇒ خودِ path() به‌عنوان canonical. */
    public function getCanonicalUrl(): ?string;

    /** null ⇒ index,follow. */
    public function getRobots(): ?string;
}
