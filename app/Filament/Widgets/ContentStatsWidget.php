<?php

namespace App\Filament\Widgets;

use App\Model\Article;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

/**
 * کارت‌های آمارِ محتوا روی داشبورد — پورتِ کارت‌های سایتِ انگلیسی (Drafts/Scheduled/Published/
 * This week/This month/Next up)، نگاشته به اسکیمای زنده‌ی فارسی (statusِ بولین، published_at).
 * فقط-خواندنی؛ هیچ داده‌ای تغییر نمی‌دهد.
 */
class ContentStatsWidget extends StatsOverviewWidget
{
    protected static ?int $sort = 2;

    protected function getStats(): array
    {
        $drafts = Article::query()->where('status', 0)->count();
        $published = Article::query()->where('status', 1)->count();
        $scheduled = Article::query()
            ->whereNotNull('published_at')
            ->where('published_at', '>', now())
            ->count();

        // created_at ستونِ خام است (accessorِ شمسی روی کوئری اثر ندارد).
        $thisWeek = Article::query()->where('created_at', '>=', now()->startOfWeek())->count();
        $thisMonth = Article::query()->where('created_at', '>=', now()->startOfMonth())->count();

        $next = Article::query()
            ->whereNotNull('published_at')
            ->where('published_at', '>', now())
            ->orderBy('published_at')
            ->first();

        $nextValue = $next ? Str::limit((string) $next->title, 28) : '—';
        $nextDesc = ($next && $next->published_at)
            ? Carbon::parse($next->published_at)->format('Y-m-d H:i').' · '.($next->lang ?? '')
            : 'موردی زمان‌بندی نشده';

        return [
            Stat::make('پیش‌نویس‌ها', number_format($drafts))
                ->description('منتشرنشده')
                ->color('gray')
                ->icon('heroicon-o-document'),

            Stat::make('زمان‌بندی‌شده', number_format($scheduled))
                ->description('برای آینده')
                ->color('warning')
                ->icon('heroicon-o-clock'),

            Stat::make('منتشرشده', number_format($published))
                ->description('روی سایت')
                ->color('success')
                ->icon('heroicon-o-check-circle'),

            Stat::make('مقالاتِ این هفته', number_format($thisWeek))
                ->color('primary')
                ->icon('heroicon-o-calendar-days'),

            Stat::make('مقالاتِ این ماه', number_format($thisMonth))
                ->color('primary')
                ->icon('heroicon-o-calendar'),

            Stat::make('بعدی', $nextValue)
                ->description($nextDesc)
                ->color('info')
                ->icon('heroicon-o-rocket-launch'),
        ];
    }
}
