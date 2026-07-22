<?php

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Artisan;
use UnitEnum;

/**
 * نگه‌داریِ سیستم — روی هاستِ بدون‌شل (cPanel) کارهای artisanِ زمانِ استقرار را با یک کلیک از پنل
 * اجرا می‌کند: بهینه‌سازی/پاک‌سازیِ کش، migration، storage:link. همان دستوراتِ routes/admin.php
 * (maintenance/*) را اجرا می‌کند اما داخلِ Filament. فقط ادمین (پنل خودش با auth محافظت می‌شود).
 * خروجیِ هر اجرا روی صفحه نمایش داده می‌شود. seo-check و media-backfill به‌صورتِ لینک باز می‌شوند.
 */
class SystemMaintenance extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedWrenchScrewdriver;

    // بخشِ بالای منو (بدونِ گروه)، مطابقِ سایتِ انگلیسی.
    protected static string|UnitEnum|null $navigationGroup = null;

    protected static ?int $navigationSort = 10;

    protected static ?string $navigationLabel = 'System Maintenance';

    protected static ?string $title = 'نگه‌داریِ سیستم';

    protected string $view = 'filament.pages.system-maintenance';

    /** خروجیِ آخرین اجرا (متنِ artisan). */
    public ?string $output = null;

    /** برچسبِ عملیاتی که خروجیِ فعلی مالِ اوست. */
    public ?string $lastAction = null;

    /**
     * نگاشتِ عملیاتِ مجاز به دستوراتِ artisan — آینه‌ی routes/admin.php (maintenance/{action}).
     * هر آیتم: [دستور, پارامترها].
     *
     * @return array<string, array<int, array{0:string, 1:array<string, mixed>}>>
     */
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
            'cache-clear' => [
                ['cache:clear', []],
                ['config:clear', []],
                ['view:clear', []],
            ],
            'migrate' => [
                ['migrate', ['--force' => true]],
            ],
            'storage-link' => [
                ['storage:link', []],
            ],
        ];
    }

    public function run(string $action): void
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

        // روی هاستِ OPcache-دار، بعد از pull لازم است کش ری‌ست شود تا فایل‌های تغییرکرده دیده شوند.
        if (in_array($action, ['optimize', 'cache-clear'], true) && function_exists('opcache_reset')) {
            $ok = @opcache_reset();
            $lines[] = '$ opcache_reset()  → '.($ok ? 'OK' : 'unavailable');
        }

        $this->output = implode("\n\n", $lines);
        $this->lastAction = $action;

        Notification::make()->success()->title('انجام شد: '.$action)->send();
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('optimize')
                ->label('پاک‌سازی و بهینه‌سازی')
                ->icon('heroicon-o-bolt')
                ->color('primary')
                ->action(fn () => $this->run('optimize')),

            Action::make('migrate')
                ->label('اجرای Migration')
                ->icon('heroicon-o-circle-stack')
                ->color('warning')
                ->requiresConfirmation()
                ->modalHeading('اجرای Migration')
                ->modalDescription('دستورِ migrate --force اجرا می‌شود. قبل از این روی سایتِ اصلی حتماً از دیتابیس بکاپ بگیرید.')
                ->action(fn () => $this->run('migrate')),

            Action::make('storageLink')
                ->label('ساختِ Storage Link')
                ->icon('heroicon-o-link')
                ->color('gray')
                ->action(fn () => $this->run('storage-link')),

            Action::make('cacheClear')
                ->label('پاک‌سازیِ سریعِ کش')
                ->icon('heroicon-o-trash')
                ->color('gray')
                ->action(fn () => $this->run('cache-clear')),

            Action::make('seoCheck')
                ->label('بررسیِ سئو')
                ->icon('heroicon-o-shield-check')
                ->color('success')
                ->url(url('panel/manager/maintenance/seo-check?nocache=1'))
                ->openUrlInNewTab(),

            Action::make('mediaBackfill')
                ->label('Backfillِ رسانه')
                ->icon('heroicon-o-photo')
                ->color('gray')
                ->url(url('panel/manager/maintenance/media-backfill'))
                ->openUrlInNewTab(),
        ];
    }
}
