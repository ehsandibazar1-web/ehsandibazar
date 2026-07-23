<?php

namespace App\Filament\Resources\ShippingCosts\Tables;

use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ShippingCostsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('type')
                    ->label('نوع پست')
                    ->badge()
                    ->formatStateUsing(fn ($state) => match ((int) $state) {
                        1 => 'پیشتاز',
                        2 => 'سفارشی',
                        3 => 'پیک موتوری',
                        default => $state,
                    }),

                TextColumn::make('of_weight')
                    ->label('از وزن (گرم)')
                    ->sortable(),

                TextColumn::make('upto_weight')
                    ->label('تا وزن (گرم)')
                    ->sortable(),

                TextColumn::make('price')
                    ->label('هزینه (تومان)')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('post_type')
                    ->label('نوع ارسال')
                    ->formatStateUsing(fn ($state) => match ((int) $state) {
                        1 => 'درون شهری',
                        2 => 'برون شهری',
                        3 => 'محصول دیجیتال',
                        default => $state,
                    })
                    ->toggleable(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make()
                    ->requiresConfirmation(),
            ])
            ->defaultSort('id', 'desc')
            ->emptyStateHeading('هنوز هزینه‌ی ارسالی ثبت نشده')
            ->emptyStateDescription('تعرفه‌های ارسال (پیشتاز/سفارشی/پیک موتوری) را برای صفحه‌ی تسویه‌حساب اینجا مدیریت کنید.');
    }
}
