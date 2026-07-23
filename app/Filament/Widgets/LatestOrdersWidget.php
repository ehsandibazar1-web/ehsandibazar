<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Orders\OrderResource;
use App\Model\Order;
use App\Utility\Status;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

/**
 * آخرین سفارش‌ها روی داشبورد — جایگزینِ جدولِ داشبوردِ پنلِ قدیمی. فقط-خواندنی؛ کلیک → ویرایشِ سفارش.
 * ستونِ created_at بدونِ dateTime() است چون مدلِ Order اکسسورِ شمسی دارد (Carbon پارس نمی‌کند).
 */
class LatestOrdersWidget extends TableWidget
{
    protected static ?int $sort = 4;

    protected int|string|array $columnSpan = 'full';

    protected static ?string $heading = 'آخرین سفارش‌ها';

    /** برچسب‌های وضعیتِ سفارش — عینِ App\Utility\Status::showOrderStatus پنلِ قدیمی. */
    private const STATUS_LABELS = [
        Status::UNPAID => 'پرداخت نشده',
        Status::UNPAID_DISPLAYED => 'پرداخت نشده (بررسی‌شده)',
        Status::PAID => 'تسویه شده',
        Status::CANCELED => 'لغو شده',
        Status::PENDING => 'در حال پردازش',
        Status::PENDING_DISPLAYED => 'در حال پردازش (بررسی‌شده)',
        Status::RETURNED => 'مرجوعی',
        Status::WAITING => 'در انتظار تایید',
        Status::SENDING => 'ارسال شد',
    ];

    public function table(Table $table): Table
    {
        return $table
            ->query(Order::query()->with('user')->latest('id')->limit(8))
            ->paginated(false)
            ->columns([
                TextColumn::make('id')
                    ->label('شماره'),

                TextColumn::make('user.name')
                    ->label('خریدار')
                    ->placeholder('—'),

                TextColumn::make('total_amount')
                    ->label('مبلغ (تومان)')
                    ->numeric(),

                TextColumn::make('status')
                    ->label('وضعیت')
                    ->badge()
                    ->formatStateUsing(fn ($state) => self::STATUS_LABELS[(int) $state] ?? $state)
                    ->color(fn ($state) => match ((int) $state) {
                        Status::PAID, Status::SENDING => 'success',
                        Status::PENDING, Status::PENDING_DISPLAYED, Status::WAITING => 'warning',
                        Status::CANCELED, Status::RETURNED => 'danger',
                        default => 'gray',
                    }),

                TextColumn::make('created_at')
                    ->label('تاریخ'),
            ])
            ->recordUrl(fn (Order $record) => OrderResource::getUrl('edit', ['record' => $record]));
    }
}
