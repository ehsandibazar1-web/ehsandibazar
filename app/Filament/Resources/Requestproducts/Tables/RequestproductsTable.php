<?php

namespace App\Filament\Resources\Requestproducts\Tables;

use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class RequestproductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('شناسه')
                    ->sortable(),

                // مدلِ Requestproduct رابطه‌ی user() ندارد؛ نام را از روی user_id بازیابی می‌کنیم.
                TextColumn::make('user_id')
                    ->label('کاربر')
                    ->formatStateUsing(fn ($state) => optional(\App\User::find($state))->name ?? $state)
                    ->placeholder('—'),

                TextColumn::make('product.title')
                    ->label('محصول')
                    ->placeholder('—'),

                TextColumn::make('description')
                    ->label('توضیحات')
                    ->limit(40)
                    ->wrap(),

                TextColumn::make('status')
                    ->label('وضعیت')
                    ->badge()
                    ->formatStateUsing(fn ($state) => (int) $state === 1 ? 'بررسی‌شده' : 'در انتظار بررسی')
                    ->color(fn ($state) => (int) $state === 1 ? 'success' : 'warning'),

                TextColumn::make('created_at')
                    ->label('تاریخ ثبت')
                    ->dateTime('Y/m/d')
                    ->sortable(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make()
                    ->requiresConfirmation(),
            ])
            ->defaultSort('id', 'desc')
            ->emptyStateHeading('هنوز درخواست محصولی ثبت نشده')
            ->emptyStateDescription('درخواست‌های محصول توسط کاربران در سایت ثبت می‌شوند و اینجا برای بررسی نمایش داده می‌شوند.');
    }
}
