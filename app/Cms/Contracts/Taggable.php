<?php

namespace App\Cms\Contracts;

use Illuminate\Database\Eloquent\Relations\MorphToMany;

interface Taggable
{
    /** برچسب‌های سازمان‌دهیِ محتوا — pivot چندریختیِ `taggables`. */
    public function tags(): MorphToMany;
}
