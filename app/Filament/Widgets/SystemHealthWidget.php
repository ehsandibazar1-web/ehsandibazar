<?php

namespace App\Filament\Widgets;

use App\Model\Article;
use App\Services\Maintenance\DatabaseBackupService;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

/**
 * سلامتِ سیستم روی داشبورد: ضربانِ کرون (آخرین اجرای schedule:run که در Kernel هر دقیقه
 * در cache مهر می‌شود)، سنِ آخرین بکاپِ دیتابیس، و مقاله‌های زمان‌بندی‌شده‌ی نزدیک.
 * اگر کرونِ cPanel از کار بیفتد، اینجا قرمز می‌شود — پایانِ «نمی‌دانستیم کرون خوابیده».
 */
class SystemHealthWidget extends StatsOverviewWidget
{
    protected static ?int $sort = 3;

    protected function getStats(): array
    {
        // ضربانِ کرون
        $lastRun = cache()->get('cron.last_run');
        $cronAge = $lastRun ? Carbon::parse($lastRun)->diffInMinutes(now()) : null;
        $cronOk = $cronAge !== null && $cronAge <= 10;

        // آخرین بکاپ
        $backup = null;
        try {
            $backup = app(DatabaseBackupService::class)->latest();
        } catch (\Throwable) {
        }
        $backupAgeH = $backup ? Carbon::createFromTimestamp($backup['time'])->diffInHours(now()) : null;
        $backupOk = $backupAgeH !== null && $backupAgeH <= 26;

        // زمان‌بندی‌شده‌های در صف
        $dueSoon = Article::query()
            ->where('is_scheduled', true)->where('status', 0)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now()->addDay())
            ->count();

        return [
            Stat::make('ضربانِ کرون', $cronAge === null ? 'هنوز اجرا نشده' : ($cronOk ? 'فعال' : $cronAge.' دقیقه پیش'))
                ->description($cronOk ? 'schedule:run سرِ وقت اجرا می‌شود' : 'کرونِ cPanel را بررسی کنید (نگه‌داریِ سیستم)')
                ->color($cronOk ? 'success' : 'danger')
                ->icon('heroicon-o-heart'),

            Stat::make('آخرین بکاپِ دیتابیس', $backup ? Carbon::createFromTimestamp($backup['time'])->diffForHumans() : 'هنوز ساخته نشده')
                ->description($backupOk ? 'بکاپِ روزانه سالم است' : 'بکاپِ تازه‌ای وجود ندارد — از نگه‌داریِ سیستم بگیرید')
                ->color($backupOk ? 'success' : 'warning')
                ->icon('heroicon-o-inbox-arrow-down'),

            Stat::make('انتشار در ۲۴ ساعتِ آینده', number_format($dueSoon))
                ->description('مقاله‌های زمان‌بندی‌شده‌ی نزدیک')
                ->color($dueSoon > 0 ? 'info' : 'gray')
                ->icon('heroicon-o-rocket-launch'),
        ];
    }
}
