<?php

namespace App\Filament\Resources\AttributeTypeValues\Tables;

use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AttributeTypeValuesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('value')
                    ->label('مقدار')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('label')
                    ->label('برچسبِ نمایشی')
                    ->placeholder('—'),

                ColorColumn::make('color')
                    ->label('رنگ'),

                TextColumn::make('attributeType.name')
                    ->label('نوع')
                    ->badge()
                    ->color('primary'),

                TextColumn::make('lang')
                    ->label('زبان')
                    ->badge(),

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
            ->emptyStateHeading('هنوز مقداری ثبت نشده')
            ->emptyStateDescription('مقادیرِ ویژگی (مثلِ رنگ/سایز) برای ساختِ تنوع‌های محصول استفاده می‌شوند.');
    }
}
