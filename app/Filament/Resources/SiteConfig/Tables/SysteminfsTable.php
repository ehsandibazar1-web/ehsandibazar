<?php

namespace App\Filament\Resources\SiteConfig\Tables;

use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SysteminfsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn ($query) => $query->withCount('systeminfmanage'))
            ->columns([
                TextColumn::make('id')
                    ->label('شناسه')
                    ->sortable(),

                TextColumn::make('name')
                    ->label('نامِ بخش')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('description')
                    ->label('توضیح')
                    ->limit(50)
                    ->toggleable(),

                TextColumn::make('systeminfmanage_count')
                    ->label('آیتم‌ها')
                    ->badge()
                    ->color('primary'),

                IconColumn::make('status')
                    ->label('فعال')
                    ->boolean(),
            ])
            ->recordActions([
                EditAction::make()
                    ->label('ویرایش و آیتم‌ها'),
            ])
            ->defaultSort('id', 'asc')
            ->emptyStateHeading('بخشی یافت نشد')
            ->emptyStateDescription('بخش‌های تنظیماتِ سایت (اسلایدر، درباره، فوتر، تماس…) اینجا فهرست می‌شوند.');
    }
}
