<?php

namespace App\Cms\Concerns;

use Illuminate\Support\Str;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

/**
 * پوششِ استانداردِ spatie/activitylog برای محتوا — فقط تغییراتِ واقعی (dirty) ثبت می‌شود و
 * نامِ لاگ = snake_case نامِ کلاس. مدل می‌تواند getActivitylogOptions را برای رفتارِ خاص override کند.
 */
trait LogsContentActivity
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName(Str::snake(class_basename($this)));
    }
}
