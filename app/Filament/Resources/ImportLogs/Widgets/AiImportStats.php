<?php

namespace App\Filament\Resources\ImportLogs\Widgets;

use App\Model\Article;
use App\Models\ImportLog;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AiImportStats extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $topProvider = ImportLog::query()
            ->whereNotNull('ai_provider')
            ->selectRaw('ai_provider, count(*) as total')
            ->groupBy('ai_provider')
            ->orderByDesc('total')
            ->first();

        return [
            Stat::make('ایمپورت‌شده', ImportLog::query()->where('status', 'imported')->count())
                ->description('مقاله‌های ساخته‌شده با AI')
                ->color('success'),

            Stat::make('ناموفق', ImportLog::query()->where('status', 'failed')->count())
                ->description('ردشده در اعتبارسنجی')
                ->color('danger'),

            Stat::make('بازگردانده‌شده', ImportLog::query()->whereNotNull('rolled_back_at')->count())
                ->description('ایمپورت‌های لغوشده')
                ->color('warning'),

            Stat::make(
                'صفِ پیش‌نویس',
                Article::query()
                    ->where('status', 0) // پیش‌نویس (بولین)
                    ->whereIn('id', ImportLog::query()->where('status', 'imported')
                        ->whereNull('rolled_back_at')
                        ->whereNotNull('article_id')
                        ->select('article_id'))
                    ->count()
            )->description('پیش‌نویس‌های ایمپورتی در انتظارِ بررسی')
                ->color('gray'),

            Stat::make('پُرکاربردترین ارائه‌دهنده', $topProvider->ai_provider ?? '—')
                ->description($topProvider ? $topProvider->total.' ایمپورت' : 'هنوز ایمپورتی نیست'),
        ];
    }
}
