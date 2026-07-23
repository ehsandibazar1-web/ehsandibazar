<?php

namespace App\Filament\Resources\Roles\Tables;

use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class RolesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn ($query) => $query->withCount('permissions'))
            ->columns([
                TextColumn::make('name')
                    ->label('نام (کلید)')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('label')
                    ->label('برچسبِ نمایشی'),

                TextColumn::make('permissions_count')
                    ->label('دسترسی‌ها')
                    ->badge()
                    ->color('primary'),

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
            ->emptyStateHeading('هنوز نقشی ثبت نشده')
            ->emptyStateDescription('نقش‌ها مجموعه‌ای از دسترسی‌ها را به کاربران اختصاص می‌دهند.');
    }
}
