<?php

namespace App\Cms\Concerns;

use Illuminate\Database\Eloquent\Relations\MorphToMany;

/**
 * پیاده‌سازیِ App\Cms\Contracts\Taggable — برچسب‌های محتوا از طریقِ pivot چندریختیِ `taggables`.
 *
 * توجه: App\Models\Tag (نسخه‌ی همگرا با name/color) در موج ۴ ساخته می‌شود؛ این trait تا آن
 * زمان توسط هیچ مدلی use نمی‌شود، پس ارجاعِ کلاس اینجا فقط یک رشته است و مشکلی ایجاد نمی‌کند.
 */
trait HasContentTags
{
    public function tags(): MorphToMany
    {
        return $this->morphToMany(\App\Models\Tag::class, 'taggable');
    }
}
