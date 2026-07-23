<?php

namespace App\Filament\Resources\DiscountCodes\Tables;

use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DiscountCodesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')
                    ->label('کدِ تخفیف')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('discount.title')
                    ->label('تخفیف')
                    ->badge()
                    ->placeholder('—'),

                TextColumn::make('created_at')
                    ->label('تاریخِ ایجاد')
                    ->dateTime('Y/m/d')
                    ->toggleable(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make()
                    ->requiresConfirmation(),
            ])
            ->defaultSort('id', 'desc')
            ->emptyStateHeading('هنوز کدِ تخفیفی ثبت نشده')
            ->emptyStateDescription('کدهای تخفیف به یک تخفیف متصل می‌شوند و هنگامِ خرید توسطِ مشتری وارد می‌شوند.');
    }
}
