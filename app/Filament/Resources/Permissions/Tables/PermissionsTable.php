<?php

namespace App\Filament\Resources\Permissions\Tables;

use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PermissionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('نام (کلید)')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('label')
                    ->label('برچسبِ نمایشی'),

                TextColumn::make('method')
                    ->label('متد/عملیات')
                    ->badge(),

                TextColumn::make('created_at')
                    ->label('تاریخِ ایجاد')
                    ->dateTime()
                    ->toggleable(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make()
                    ->requiresConfirmation(),
            ])
            ->defaultSort('id', 'desc')
            ->emptyStateHeading('هنوز دسترسی‌ای ثبت نشده')
            ->emptyStateDescription('دسترسی‌ها مسیرها/عملیاتِ قابل‌کنترل را برای نقش‌ها تعریف می‌کنند.');
    }
}
