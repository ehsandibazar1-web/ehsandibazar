<?php

namespace App\Cms\Concerns;

use App\Cms\Enums\ContentStatus;
use Illuminate\Database\Eloquent\Builder;

/**
 * پیاده‌سازیِ App\Cms\Contracts\Publishable — منطقِ استانداردِ انتشار/زمان‌بندی.
 * تورِ ایمنی: حتی اگر کرونِ زمان‌بندی از کار بیفتد، محتوای scheduled ای که زمانش رسیده منتشر دیده می‌شود.
 */
trait HasPublishing
{
    public function isPublished(): bool
    {
        if ($this->status === ContentStatus::Published->value) {
            return true;
        }

        return $this->status === ContentStatus::Scheduled->value
            && $this->published_at !== null
            && $this->published_at <= now();
    }

    public function getPublishedAt(): ?\DateTimeInterface
    {
        return $this->published_at;
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where(function (Builder $q): void {
            $q->where('status', ContentStatus::Published->value)
                ->orWhere(function (Builder $q2): void {
                    $q2->where('status', ContentStatus::Scheduled->value)
                        ->where('published_at', '<=', now());
                });
        });
    }
}
