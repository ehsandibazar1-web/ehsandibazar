<?php

namespace App\Filament\Resources\Gifts\Tables;

use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class GiftsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('نام')
                    ->searchable(),

                TextColumn::make('product.title')
                    ->label('محصول')
                    ->placeholder('—'),

                TextColumn::make('score')
                    ->label('امتیازِ لازم')
                    ->numeric()
                    ->sortable(),

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
            ->emptyStateHeading('هنوز هدیه‌ای ثبت نشده')
            ->emptyStateDescription('هدایای امتیازی به کاربران در ازای امتیازشان اهدا می‌شوند.');
    }
}
