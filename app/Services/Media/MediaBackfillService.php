<?php

namespace App\Services\Media;

use App\Model\Image as LegacyImage;
use App\Models\Media;

/**
 * عکس‌های موجودِ سایت (مدلِ قدیمیِ App\Model\Image — تصاویرِ محصولات/مقاله‌ها/…) را در جدولِ
 * `media` ثبت می‌کند تا در Media Library دیده شوند. فقط یک ردیفِ Media با همان آدرسِ عمومیِ
 * فعلی می‌سازد (بدونِ تولیدِ WebP و بدونِ جابه‌جاییِ فایل) — پس هیچ فایلی روی سرور تغییر نمی‌کند و
 * صفر ریسک برای storefront دارد. WebP را می‌توان بعداً per-image از دکمه‌ی «بازتولید» ساخت.
 */
class MediaBackfillService
{
    /**
     * @return array{registered:int, skipped:int, scanned:int, total:int, done:bool, next_offset:int}
     */
    public function backfillFromLegacyImages(int $limit = 100, int $offset = 0): array
    {
        $total = LegacyImage::query()->count();

        $images = LegacyImage::query()
            ->orderBy('id')
            ->offset($offset)
            ->limit($limit)
            ->get();

        $registered = 0;
        $skipped = 0;

        foreach ($images as $img) {
            // getRawOriginal: مقدارِ خامِ ذخیره‌شده (بدونِ APP_URL که accessor اضافه می‌کند).
            $raw = ltrim((string) $img->getRawOriginal('url'), '/');
            $fullUrl = (string) $img->url; // accessor → APP_URL + raw

            if ($raw === '' || $fullUrl === '') {
                $skipped++;

                continue;
            }

            // اگر قبلاً ثبت شده (backfillِ دوباره یا آپلودِ مستقیم) رد شو.
            if (Media::query()->where('url', $fullUrl)->orWhere('disk_path', $raw)->exists()) {
                $skipped++;

                continue;
            }

            Media::create([
                'original_name' => basename($raw),
                'disk' => 'public',
                'disk_path' => $raw,
                'url' => $fullUrl,
                'type' => 'image',
                'mime_type' => null,
                'size' => null,
            ]);

            $registered++;
        }

        $nextOffset = $offset + $images->count();

        return [
            'registered' => $registered,
            'skipped' => $skipped,
            'scanned' => $images->count(),
            'total' => $total,
            'done' => $nextOffset >= $total,
            'next_offset' => $nextOffset,
        ];
    }
}
