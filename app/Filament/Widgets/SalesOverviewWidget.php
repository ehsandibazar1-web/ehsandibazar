<?php

namespace App\Filament\Widgets;

use App\Model\Order;
use App\Utility\Status;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

/**
 * جمعِ فروش (امروز / ۷ روز / ۳۰ روز) — چیزی که پنلِ قدیمی نداشت و همیشه دستی حساب می‌شد.
 * مبنا: سفارش‌های واقعاً موفق (تسویه‌شده یا ارسال‌شده) روی ستونِ خامِ created_at
 * (اکسسورِ شمسیِ مدل روی کوئریِ SQL اثری ندارد). فقط-خواندنی.
 */
class SalesOverviewWidget extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $sum = fn ($from) => (float) Order::query()
            ->whereIn('status', [Status::PAID, Status::SENDING])
            ->where('created_at', '>=', $from)
            ->sum('total_amount');

        return [
            Stat::make('فروشِ امروز', number_format($sum(now()->startOfDay())).' تومان')
                ->color('success')
                ->icon('heroicon-o-banknotes'),

            Stat::make('فروشِ ۷ روزِ اخیر', number_format($sum(now()->subDays(7)->startOfDay())).' تومان')
                ->color('success')
                ->icon('heroicon-o-chart-bar'),

            Stat::make('فروشِ ۳۰ روزِ اخیر', number_format($sum(now()->subDays(30)->startOfDay())).' تومان')
                ->color('primary')
                ->icon('heroicon-o-presentation-chart-line'),
        ];
    }
}
