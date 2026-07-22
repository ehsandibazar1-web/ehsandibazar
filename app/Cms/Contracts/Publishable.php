<?php

namespace App\Cms\Contracts;

use Illuminate\Database\Eloquent\Builder;

interface Publishable
{
    public function isPublished(): bool;

    public function getPublishedAt(): ?\DateTimeInterface;

    /** منتشرشده = published، یا scheduled ای که زمانش رسیده. */
    public function scopePublished(Builder $query): Builder;
}
