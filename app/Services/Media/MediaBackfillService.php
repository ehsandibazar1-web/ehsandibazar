<?php

namespace App\Services\Media;

use App\Model\Image as LegacyImage;
use App\Models\Media;
use Illuminate\Support\Facades\Storage;

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

    // پوشه‌هایی از دیسکِ public که File Manager عکس‌ها را در آن‌ها می‌گذارد (نگاه کنید به config/lfm.php).
    private const DISK_SCAN_DIRS = ['photos', 'files', 'shares'];

    /**
     * فایل‌های تصویریِ File Manager (روی دیسکِ public) را که به مدلِ Image وصل نیستند ثبت می‌کند.
     * پوشه‌ی thumbs (تامبنیل‌های خودِ فایل‌منیجر) و فایل‌های از-قبل-ثبت‌شده رد می‌شوند. هیچ فایلی
     * تغییر نمی‌کند.
     *
     * @return array{registered:int, skipped:int, scanned:int, total:int, done:bool, next_offset:int}
     */
    public function backfillFromPublicDisk(int $limit = 200, int $offset = 0): array
    {
        $disk = Storage::disk('public');

        // همه‌ی مسیرهای تصویری در پوشه‌های فایل‌منیجر (به‌جز thumbs).
        $all = [];
        foreach (self::DISK_SCAN_DIRS as $dir) {
            foreach ($disk->allFiles($dir) as $path) {
                if (preg_match('#(^|/)thumbs/#', $path)) {
                    continue;
                }
                if (! preg_match('/\.(jpe?g|png|webp|gif|bmp)$/i', $path)) {
                    continue;
                }
                $all[] = $path;
            }
        }

        $total = count($all);
        $slice = array_slice($all, $offset, $limit);

        $registered = 0;
        $skipped = 0;

        foreach ($slice as $path) {
            if (Media::query()->where('disk_path', $path)->exists()) {
                $skipped++;

                continue;
            }

            $mime = null;
            $size = null;
            try {
                $mime = $disk->mimeType($path) ?: null;
                $size = $disk->size($path);
            } catch (\Throwable $e) {
                // بی‌خیالِ متادیتا؛ ثبت خودِ ردیف مهم‌تر است.
            }

            Media::create([
                'original_name' => basename($path),
                'disk' => 'public',
                'disk_path' => $path,
                'url' => $disk->url($path),
                'type' => 'image',
                'mime_type' => $mime,
                'size' => $size,
            ]);

            $registered++;
        }

        $nextOffset = $offset + count($slice);

        return [
            'registered' => $registered,
            'skipped' => $skipped,
            'scanned' => count($slice),
            'total' => $total,
            'done' => $nextOffset >= $total,
            'next_offset' => $nextOffset,
        ];
    }
}
