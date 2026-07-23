<?php

namespace App\Filament\Resources\Provinces\Tables;

use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ProvincesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn ($query) => $query->withCount('city'))
            ->columns([
                TextColumn::make('name')
                    ->label('نام')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('city_count')
                    ->label('شهرها')
                    ->badge()
                    ->color('primary'),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make()
                    ->requiresConfirmation(),
            ])
            ->defaultSort('name', 'asc')
            ->emptyStateHeading('هنوز استانی ثبت نشده')
            ->emptyStateDescription('استان‌ها برای گروه‌بندیِ شهرها و نشانی‌ها استفاده می‌شوند.');
    }
}
