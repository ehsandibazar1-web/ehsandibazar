<?php

namespace App\Cms\Contracts;

use App\Models\Media;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;

/**
 * قراردادِ سرویسِ کتابخانه‌ی رسانه (docs/CMS-CORE-CONTRACT.md، لایه ۵).
 * پیاده‌سازیِ فعلی: App\Services\Media\MediaProcessor + متدهای استاتیکِ App\Models\Media.
 * کدِ فیچرها فقط این interface را می‌بیند تا همان فیچر روی هر دو پروژه اجرا شود.
 */
interface MediaLibrary
{
    /** رکوردِ Media متناظر با تصویرِ شاخصِ یک محتوا (تطبیقِ disk_path). */
    public function forRecord(Model $record): ?Media;

    /** URLِ بهینه‌ی یک مسیر (WebP/ریسپانسیو)، با fallback به فایلِ خام. */
    public function optimizedUrl(?string $diskPath, ?int $maxWidth = null): ?string;

    /** ذخیره‌ی فایلِ آپلودشده + تولیدِ مشتقات (WebP/تامبنیل/ریسپانسیو). */
    public function store(UploadedFile $file, string $directory, string $disk = 'public', ?int $folderId = null): Media;
}
