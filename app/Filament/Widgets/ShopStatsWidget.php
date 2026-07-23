<?php

namespace App\Filament\Widgets;

use App\Model\Order;
use App\User;
use App\Utility\paymentMethods;
use App\Utility\Status;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

/**
 * آمارِ فروشگاه روی داشبوردِ پنلِ جدید — پورتِ وفادارِ داشبوردِ پنلِ قدیمی
 * (Admin\AdminController@dashboard و Admin\HomeController@index که کپیِ هم بودند):
 * شمارشِ سفارش‌ها به تفکیکِ وضعیت + پرداختِ آنلاین + کلِ کاربران. فقط-خواندنی.
 */
class ShopStatsWidget extends StatsOverviewWidget
{
    protected static ?int $sort = 0;

    protected function getStats(): array
    {
        $count = fn (int $status) => Order::query()->where('status', $status)->count();

        return [
            Stat::make('تسویه‌شده', number_format($count(Status::PAID)))
                ->description('سفارش‌های پرداخت‌شده')
                ->color('success')
                ->icon('heroicon-o-check-circle'),

            Stat::make('در حالِ پردازش', number_format($count(Status::PENDING)))
                ->color('info')
                ->icon('heroicon-o-arrow-path'),

            Stat::make('در انتظارِ تأیید', number_format($count(Status::WAITING)))
                ->color('warning')
                ->icon('heroicon-o-clock'),

            Stat::make('ارسال‌شده', number_format($count(Status::SENDING)))
                ->color('success')
                ->icon('heroicon-o-truck'),

            Stat::make('پرداخت‌نشده', number_format($count(Status::UNPAID)))
                ->color('gray')
                ->icon('heroicon-o-banknotes'),

            Stat::make('لغو / مرجوعی', number_format($count(Status::CANCELED) + $count(Status::RETURNED)))
                ->description(number_format($count(Status::CANCELED)).' لغو · '.number_format($count(Status::RETURNED)).' مرجوعی')
                ->color('danger')
                ->icon('heroicon-o-x-circle'),

            Stat::make('پرداختِ آنلاین', number_format(Order::query()->where('payment_method_id', paymentMethods::ONLINE)->count()))
                ->color('info')
                ->icon('heroicon-o-credit-card'),

            Stat::make('کلِ کاربران', number_format(User::query()->count()))
                ->color('primary')
                ->icon('heroicon-o-users'),
        ];
    }
}
