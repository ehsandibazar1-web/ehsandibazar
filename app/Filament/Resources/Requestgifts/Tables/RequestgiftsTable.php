<?php

namespace App\Filament\Resources\Requestgifts\Tables;

use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class RequestgiftsTable
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
                    ->placeholder('—'),

                TextColumn::make('gift.name')
                    ->label('هدیه')
                    ->placeholder('—'),

                IconColumn::make('used')
                    ->label('استفاده‌شده')
                    ->boolean(),

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
            ->emptyStateHeading('هنوز درخواست هدیه‌ای ثبت نشده')
            ->emptyStateDescription('درخواست‌های هدیه توسط کاربران در سایت ثبت می‌شوند و اینجا برای مدیریت نمایش داده می‌شوند.');
    }
}
