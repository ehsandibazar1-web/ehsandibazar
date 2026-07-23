<?php

namespace App\Filament\Resources\Orders\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class OrderForm
{
    /**
     * نگاشتِ وضعیتِ سفارش — دقیقاً مطابقِ App\Utility\Status::getOrderStatus() (حالتِ بدونِ آرگومان).
     * کلیدها همان ثابت‌های Status هستند (UNPAID=2, UNPAID_DISPLAYED=22, PAID=3, CANCELED=4,
     * SENDING=8, PENDING=5, PENDING_DISPLAYED=55, RETURNED=6, WAITING=7).
     */
    public const STATUS_OPTIONS = [
        2 => 'پرداخت نشده',
        22 => 'پرداخت نشده بررسی شده',
        3 => 'تسویه شده',
        4 => 'لغو شده',
        8 => 'ارسال شد',
        5 => 'در حال پردازش',
        55 => 'در حال پردازش بررسی شده',
        6 => 'مرجوعی',
        7 => 'در انتظار تایید',
    ];

    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // مدیریتی — قابلِ ویرایش
                Select::make('status')
                    ->label('وضعیت')
                    ->options(self::STATUS_OPTIONS),

                TextInput::make('tracking_code')
                    ->label('کدِ رهگیری')
                    ->maxLength(255),

                TextInput::make('shipping_code')
                    ->label('کدِ ارسال')
                    ->maxLength(255),

                // هویتی و مالی — فقط-خواندنی تا ادمین اطلاعاتِ سفارش را خراب نکند
                TextInput::make('total_amount')
                    ->label('مبلغ کل')
                    ->disabled(),

                TextInput::make('total_discount')
                    ->label('تخفیف')
                    ->disabled(),

                TextInput::make('item_count')
                    ->label('تعداد اقلام')
                    ->disabled(),

                TextInput::make('coupon')
                    ->label('کدِ تخفیف')
                    ->disabled(),

                TextInput::make('ref_id')
                    ->label('کدِ پیگیریِ پرداخت')
                    ->disabled(),

                Textarea::make('user_info')
                    ->label('اطلاعاتِ خریدار (نام/آدرس)')
                    ->rows(4)
                    ->disabled()
                    ->columnSpanFull(),
            ]);
    }
}
