<?php

namespace App\Filament\Widgets;

use App\Models\AiUsageLog;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

/**
 * مصرف و هزینه‌ی هوشِ مصنوعی روی داشبورد — از جدولِ ai_usage_logs. فقط-خواندنی؛ کمک می‌کند
 * هزینه‌ی ماهانه‌ی AI از کنترل خارج نشود. اگر جدول هنوز خالی/موجود نباشد، بی‌صدا صفر نشان می‌دهد.
 */
class AiUsageWidget extends StatsOverviewWidget
{
    protected static ?int $sort = 5;

    protected function getStats(): array
    {
        try {
            $month = AiUsageLog::query()->where('created_at', '>=', now()->startOfMonth());

            $monthCost = (float) (clone $month)->sum('estimated_cost_usd');
            $monthCalls = (clone $month)->count();
            $monthTokens = (int) (clone $month)->sum('total_tokens');
            $allCost = (float) AiUsageLog::query()->sum('estimated_cost_usd');
            $failed = (clone $month)->where('status', '!=', 'success')->count();
        } catch (\Throwable) {
            // جدول هنوز مهاجرت نشده یا در دسترس نیست.
            return [
                Stat::make('هزینه‌ی AI این ماه', '—')->description('هنوز داده‌ای ثبت نشده')->color('gray'),
            ];
        }

        return [
            Stat::make('هزینه‌ی AI این ماه', '$'.number_format($monthCost, 2))
                ->description('مجموعِ کل: $'.number_format($allCost, 2))
                ->color('primary')
                ->icon('heroicon-o-currency-dollar'),

            Stat::make('درخواست‌های AI این ماه', number_format($monthCalls))
                ->description($failed > 0 ? number_format($failed).' ناموفق' : 'همه موفق')
                ->color($failed > 0 ? 'warning' : 'success')
                ->icon('heroicon-o-sparkles'),

            Stat::make('توکن‌های مصرف‌شده این ماه', number_format($monthTokens))
                ->description('prompt + completion')
                ->color('info')
                ->icon('heroicon-o-cpu-chip'),
        ];
    }
}
