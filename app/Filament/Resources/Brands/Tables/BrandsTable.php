<?php

namespace App\Filament\Resources\Brands\Tables;

use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class BrandsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn ($query) => $query->withCount('products'))
            ->columns([
                TextColumn::make('title')
                    ->label('عنوان')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('slug')
                    ->label('اسلاگ')
                    ->badge()
                    ->color('gray')
                    ->limit(30),

                TextColumn::make('products_count')
                    ->label('محصولات')
                    ->badge()
                    ->color('info'),

                TextColumn::make('lang')
                    ->label('زبان')
                    ->badge(),

                IconColumn::make('status')
                    ->label('فعال')
                    ->boolean(),

                IconColumn::make('top')
                    ->label('برتر')
                    ->boolean(),

                TextColumn::make('sorting')
                    ->label('ترتیب')
                    ->sortable(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make()
                    ->requiresConfirmation(),
            ])
            ->defaultSort('id', 'desc')
            ->emptyStateHeading('هنوز برندی ثبت نشده')
            ->emptyStateDescription('برندها برای گروه‌بندیِ محصولات و صفحه‌های /brand/… استفاده می‌شوند.');
    }
}
