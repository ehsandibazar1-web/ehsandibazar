<?php

namespace App\Filament\Resources\AttributeTypes\Tables;

use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AttributeTypesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn ($query) => $query->withCount('attributeTypeValue'))
            ->columns([
                TextColumn::make('name')
                    ->label('نام')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('label')
                    ->label('برچسب')
                    ->badge()
                    ->color('gray')
                    ->limit(30),

                TextColumn::make('attribute_type_value_count')
                    ->label('مقادیر')
                    ->badge()
                    ->color('info'),

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
            ->emptyStateHeading('هنوز نوعِ ویژگی‌ای ثبت نشده')
            ->emptyStateDescription('انواعِ ویژگی برای تعریفِ ویژگی‌های محصولات (مثلِ رنگ، اندازه) استفاده می‌شوند.');
    }
}
