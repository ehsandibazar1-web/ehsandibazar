<?php

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

/**
 * لاگ‌ویوئرِ فقط-خواندنی — پنلِ قدیمی اصلاً چنین چیزی نداشت. انتهای laravel.log را نشان می‌دهد
 * (آخرین ۶۴KB) تا روی هاستِ بدونِ شل بشود بدونِ File Manager خطاها را دید. هیچ دکمه‌ی حذف/پاک‌سازی
 * ندارد؛ فایلِ لاگ هرگز تغییر نمی‌کند.
 */
class LogViewer extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentMagnifyingGlass;

    protected static string|UnitEnum|null $navigationGroup = null;

    protected static ?int $navigationSort = 11;

    protected static ?string $navigationLabel = 'لاگِ سیستم';

    protected static ?string $title = 'لاگِ سیستم (laravel.log)';

    protected string $view = 'filament.pages.log-viewer';

    private const TAIL_BYTES = 65536;

    public string $content = '';

    public ?string $fileInfo = null;

    public function mount(): void
    {
        $this->refreshLog();
    }

    public function refreshLog(): void
    {
        $path = storage_path('logs/laravel.log');

        if (! is_file($path)) {
            $this->content = '';
            $this->fileInfo = null;

            return;
        }

        $size = (int) filesize($path);
        $this->fileInfo = number_format($size / 1024, 0).' KB — آخرین تغییر: '.date('Y-m-d H:i:s', (int) filemtime($path));

        $handle = fopen($path, 'rb');
        if ($handle === false) {
            $this->content = '[خواندنِ فایل ممکن نشد]';

            return;
        }

        if ($size > self::TAIL_BYTES) {
            fseek($handle, -self::TAIL_BYTES, SEEK_END);
        }
        $raw = (string) stream_get_contents($handle);
        fclose($handle);

        // از اولین خطِ کامل شروع کن تا وسطِ یک رکورد نیفتیم.
        if ($size > self::TAIL_BYTES && ($pos = strpos($raw, "\n")) !== false) {
            $raw = substr($raw, $pos + 1);
        }

        $this->content = $raw;
    }
}
