<?php

namespace App\Filament\Resources\Discounts\Tables;

use App\Utility\DiscountType;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DiscountsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('عنوان')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('type')
                    ->label('نوع')
                    ->badge()
                    ->formatStateUsing(fn ($state): string => match ((int) $state) {
                        DiscountType::discountSimple => 'ساده',
                        DiscountType::discountCode => 'کد دار',
                        DiscountType::discountCodeTime => 'کدار-زمانی',
                        DiscountType::discountTime => 'زمانی',
                        DiscountType::coupon => 'کوپن',
                        DiscountType::amazing => 'شگفت انگیز',
                        DiscountType::COUNTBUY => 'تعداد خرید',
                        default => (string) $state,
                    }),

                TextColumn::make('cent')
                    ->label('مقدار'),

                TextColumn::make('count_buy')
                    ->label('حداقل تعداد خرید'),

                IconColumn::make('status')
                    ->label('فعال')
                    ->boolean(),

                TextColumn::make('created_at')
                    ->label('تاریخ ایجاد')
                    ->dateTime('Y/m/d')
                    ->sortable()
                    ->toggleable(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make()
                    ->requiresConfirmation(),
            ])
            ->defaultSort('id', 'desc')
            ->emptyStateHeading('هنوز تخفیفی ثبت نشده')
            ->emptyStateDescription('برای ساختِ کمپینِ تخفیف، از دکمه‌ی «ایجاد» استفاده کنید.');
    }
}
