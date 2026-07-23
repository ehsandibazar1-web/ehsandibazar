<?php

namespace App\Filament\Resources\Orders\Tables;

use App\Filament\Resources\Orders\Schemas\OrderForm;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class OrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('شماره')
                    ->sortable(),

                TextColumn::make('user.name')
                    ->label('خریدار')
                    ->searchable()
                    ->placeholder('—'),

                TextColumn::make('total_amount')
                    ->label('مبلغ کل')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('item_count')
                    ->label('اقلام'),

                TextColumn::make('status')
                    ->label('وضعیت')
                    ->badge()
                    ->formatStateUsing(fn ($state) => OrderForm::STATUS_OPTIONS[$state] ?? $state),

                TextColumn::make('tracking_code')
                    ->label('کدِ رهگیری')
                    ->toggleable()
                    ->placeholder('—'),

                TextColumn::make('created_at')
                    ->label('تاریخ')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('وضعیت')
                    ->options(OrderForm::STATUS_OPTIONS),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->defaultSort('id', 'desc')
            ->emptyStateHeading('هنوز سفارشی ثبت نشده')
            ->emptyStateDescription('سفارش‌ها هنگامِ تسویه توسطِ مشتری‌ها ساخته می‌شوند.');
    }
}
