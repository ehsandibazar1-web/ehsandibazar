<?php

namespace App\Filament\Resources\Attributes\Tables;

use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AttributesTable
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

                TextColumn::make('attributeGroup.name')
                    ->label('گروه')
                    ->badge(),

                IconColumn::make('is_filter')
                    ->label('فیلتر')
                    ->boolean(),

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
            ->emptyStateHeading('هنوز ویژگی‌ای ثبت نشده')
            ->emptyStateDescription('ویژگی‌ها برای دسته‌بندی و فیلترِ محصولات استفاده می‌شوند.');
    }
}
