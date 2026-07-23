<?php

namespace App\Filament\Resources\AttributeGroups\Tables;

use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AttributeGroupsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn ($query) => $query->withCount('attributes'))
            ->columns([
                TextColumn::make('name')
                    ->label('نام (کلید)')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('label')
                    ->label('برچسبِ نمایشی'),

                TextColumn::make('attributes_count')
                    ->label('ویژگی‌ها')
                    ->badge()
                    ->color('primary'),

                IconColumn::make('status')
                    ->label('فعال')
                    ->boolean(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make()
                    ->requiresConfirmation(),
            ])
            ->defaultSort('id', 'desc')
            ->emptyStateHeading('هنوز گروه ویژگی‌ای ثبت نشده')
            ->emptyStateDescription('گروه‌های ویژگی برای دسته‌بندیِ ویژگی‌های محصولات استفاده می‌شوند.');
    }
}
