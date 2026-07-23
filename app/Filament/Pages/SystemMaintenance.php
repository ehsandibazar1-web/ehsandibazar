<?php

namespace App\Filament\Pages;

use App\Services\Maintenance\DatabaseBackupService;
use BackedEnum;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use UnitEnum;

/**
 * نگه‌داریِ سیستم — پورتِ کاربردیِ صفحه‌ی «System Maintenance» سایتِ انگلیسی، با چهار بخش:
 * ابزارهای استقرار (migrate/clear-cache/publish-assets)، پشتیبان‌گیریِ دیتابیس (بکاپ/دانلود/وضعیت)،
 * چکِ سلامتِ لینکِ رسانه، و چکِ پشتیبانیِ WebP. روی هاستِ بدون‌شل با یک کلیک از پنل اجرا می‌شود.
 * فقط ادمین (پنل با auth محافظت می‌شود). چک‌ها فقط-خواندنی‌اند و چیزی را تغییر نمی‌دهند.
 */
class SystemMaintenance extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedWrenchScrewdriver;

    protected static string|UnitEnum|null $navigationGroup = null;

    protected static ?int $navigationSort = 10;

    protected static ?string $navigationLabel = 'System Maintenance';

    protected static ?string $title = 'نگه‌داریِ سیستم';

    protected string $view = 'filament.pages.system-maintenance';

    /** خروجیِ متنیِ آخرین دستورِ artisan. */
    public ?string $output = null;

    public ?string $lastAction = null;

    // --- وضعیتِ چک‌های سلامت (در mount محاسبه می‌شوند تا هر کلیک دوباره اجرا نشوند) ---
    public bool $webpOk = false;

    public bool $mediaLinkOk = false;

    public ?string $mediaLinkUrl = null;

    public ?int $mediaLinkStatus = null;

    /** @var array{last:?array{name:string,size:int,time:int}, count:int, keep:int} */
    public array $backupInfo = ['last' => null, 'count' => 0, 'keep' => 14];

    public function mount(): void
    {
        $this->webpOk = function_exists('imagewebp');
        $this->refreshBackupInfo();
        $this->checkMediaLink();
    }

    // ============================ ابزارهای استقرار ============================

    /** @var array<string, array<int, array{0:string,1:array<string,mixed>}>> */
    private function commandMap(): array
    {
        return [
            'optimize' => [
                ['clear-compiled', []],
                ['package:discover', []],
                ['config:clear', []],
                ['view:clear', []],
                ['view:cache', []],
                ['cache:clear', []],
            ],
            'migrate' => [
                ['migrate', ['--force' => true]],
            ],
            // انتشارِ فایل‌های طراحی: assetهای پنل (CSS/JS) دوباره منتشر و symlinkِ storage ساخته می‌شود
            // تا فایل‌های طراحی/رسانه به بازدیدکننده برسند. هر دو idempotent‌اند.
            'publish-design' => [
                ['filament:assets', []],
                ['storage:link', []],
            ],
            // انتشارِ دستیِ مقاله‌های زمان‌بندی‌شده‌ای که زمانشان رسیده.
            'publish-due' => [
                ['articles:publish-due', []],
            ],
        ];
    }

    /** آدرسِ توکن‌دارِ pinger برای انتشارِ خودکار (به سرویسِ uptime-pinger بدهید). */
    public function pingerUrl(): string
    {
        $token = hash_hmac('sha256', 'publish-due', (string) config('app.key'));

        return url('/cron/publish-due').'?token='.$token;
    }

    private function run(string $action): void
    {
        $map = $this->commandMap();

        if (! array_key_exists($action, $map)) {
            Notification::make()->danger()->title('عملیاتِ نامعتبر')->send();

            return;
        }

        $lines = [];
        foreach ($map[$action] as [$cmd, $params]) {
            try {
                Artisan::call($cmd, $params);
                $lines[] = '$ artisan '.$cmd."\n".trim(Artisan::output());
            } catch (\Throwable $e) {
                $lines[] = '$ artisan '.$cmd."\n[ERROR] ".$e->getMessage();
            }
        }

        if ($action === 'optimize' && function_exists('opcache_reset')) {
            $lines[] = '$ opcache_reset()  → '.(@opcache_reset() ? 'OK' : 'unavailable');
        }

        $this->output = implode("\n\n", $lines);
        $this->lastAction = $action;

        Notification::make()->success()->title('انجام شد')->send();
    }

    public function runMigrate(): void
    {
        $this->run('migrate');
    }

    public function runOptimize(): void
    {
        $this->run('optimize');
    }

    public function publishDesign(): void
    {
        $this->run('publish-design');
        $this->checkMediaLink(); // ممکن است symlink تازه ساخته شده باشد
    }

    public function runPublishDue(): void
    {
        $this->run('publish-due');
    }

    // ============================ پشتیبان‌گیریِ دیتابیس ============================

    public function backupNow(): void
    {
        try {
            $result = app(DatabaseBackupService::class)->backup();
            $this->refreshBackupInfo();
            Notification::make()->success()
                ->title('پشتیبان ساخته شد')
                ->body($result['filename'].' — '.$this->humanSize($result['size']))
                ->send();
        } catch (\Throwable $e) {
            Notification::make()->danger()->title('خطا در پشتیبان‌گیری')->body($e->getMessage())->send();
        }
    }

    public function downloadLatest(): mixed
    {
        $svc = app(DatabaseBackupService::class);
        $last = $svc->latest();

        if (! $last || ! ($path = $svc->pathFor($last['name']))) {
            Notification::make()->warning()->title('هیچ پشتیبانی موجود نیست')->send();

            return null;
        }

        return response()->download($path, $last['name']);
    }

    private function refreshBackupInfo(): void
    {
        $svc = app(DatabaseBackupService::class);
        $this->backupInfo = [
            'last' => $svc->latest(),
            'count' => $svc->count(),
            'keep' => $svc->keep(),
        ];
    }

    // ============================ چکِ لینکِ رسانه ============================

    public function checkMediaLink(): void
    {
        try {
            $disk = Storage::disk('public');
            $probe = 'health/probe.txt';
            $token = 'ok-'.date('YmdHis');
            $disk->put($probe, $token);
            $url = $disk->url($probe);

            $resp = Http::timeout(8)->get($url);

            $this->mediaLinkUrl = $url;
            $this->mediaLinkStatus = $resp->status();
            $this->mediaLinkOk = $resp->successful() && str_starts_with($resp->body(), 'ok-');
        } catch (\Throwable $e) {
            $this->mediaLinkOk = false;
            $this->mediaLinkStatus = null;
        }
    }

    public function makeStorageLink(): void
    {
        try {
            Artisan::call('storage:link');
            $this->output = '$ artisan storage:link'."\n".trim(Artisan::output());
            $this->lastAction = 'storage-link';
            $this->checkMediaLink();
            Notification::make()->success()->title('لینکِ storage ساخته شد')->send();
        } catch (\Throwable $e) {
            Notification::make()->danger()->title('خطا')->body($e->getMessage())->send();
        }
    }

    // ============================ کمک‌ها ============================

    public function humanSize(?int $bytes): string
    {
        if ($bytes === null) {
            return '—';
        }
        $units = ['B', 'KB', 'MB', 'GB'];
        $i = 0;
        $n = (float) $bytes;
        while ($n >= 1024 && $i < count($units) - 1) {
            $n /= 1024;
            $i++;
        }

        return round($n, 1).' '.$units[$i];
    }
}
