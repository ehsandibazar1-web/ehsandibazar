<?php

namespace App\Filament\Resources\Payments\Tables;

use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PaymentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('شناسه')
                    ->sortable(),

                TextColumn::make('user.name')
                    ->label('کاربر')
                    ->searchable()
                    ->placeholder('—'),

                TextColumn::make('price')
                    ->label('مبلغ')
                    ->numeric()
                    ->sortable(),

                // ستونِ boolean دیتابیس: boolean('payment')->default(false) — پرچمِ پرداخت‌شده/نشده.
                IconColumn::make('payment')
                    ->label('پرداخت‌شده')
                    ->boolean(),

                // payment_type یک tinyInteger است. تنها جایی که Payment ساخته می‌شود
                // (CustomOrderController) آن را با ثابت‌های App\Utility\PaymentStatus پر می‌کند،
                // اما آن کلاس در کل مخزن تعریف نشده و هیچ نگاشتِ برچسبِ زنده‌ای برایش وجود ندارد؛
                // بنابراین مقدارِ خام به‌صورتِ badge نمایش داده می‌شود (بدونِ نگاشتِ ساختگی).
                TextColumn::make('payment_type')
                    ->label('نوع/درگاه')
                    ->badge()
                    ->color('gray')
                    ->placeholder('—'),

                TextColumn::make('tracking_code')
                    ->label('کدِ رهگیری')
                    ->searchable()
                    ->toggleable()
                    ->placeholder('—'),

                TextColumn::make('resnumber')
                    ->label('شماره‌ی مرجع')
                    ->toggleable()
                    ->placeholder('—'),

                TextColumn::make('created_at')
                    ->label('تاریخ')
                    ->dateTime('Y/m/d')
                    ->sortable(),
            ])
            ->recordActions([])
            ->defaultSort('id', 'desc')
            ->emptyStateHeading('هنوز پرداختی ثبت نشده')
            ->emptyStateDescription('پرداخت‌ها هنگامِ تسویه توسطِ درگاهِ پرداخت ساخته می‌شوند.');
    }
}
