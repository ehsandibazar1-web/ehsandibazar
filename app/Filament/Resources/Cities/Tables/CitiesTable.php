<?php

namespace App\Filament\Resources\Cities\Tables;

use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CitiesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('نام')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('province.name')
                    ->label('استان')
                    ->badge()
                    ->placeholder('—'),

                TextColumn::make('lang')
                    ->label('زبان')
                    ->badge(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make()
                    ->requiresConfirmation(),
            ])
            ->defaultSort('name', 'asc')
            ->emptyStateHeading('هنوز شهری ثبت نشده')
            ->emptyStateDescription('شهرها زیرمجموعه‌ی استان‌ها هستند و در نشانی‌ها استفاده می‌شوند.');
    }
}
