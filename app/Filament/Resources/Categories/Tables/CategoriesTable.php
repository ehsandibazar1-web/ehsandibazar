<?php

namespace App\Filament\Resources\Categories\Tables;

use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CategoriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn ($query) => $query->withCount(['products', 'article']))
            ->columns([
                TextColumn::make('title')
                    ->label('عنوان')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('parent.title')
                    ->label('والد')
                    ->badge()
                    ->color('gray')
                    ->placeholder('—'),

                TextColumn::make('type')
                    ->label('نوع')
                    ->badge(),

                TextColumn::make('products_count')
                    ->label('محصولات')
                    ->badge()
                    ->color('info'),

                TextColumn::make('article_count')
                    ->label('مقاله‌ها')
                    ->badge()
                    ->color('primary'),

                IconColumn::make('status')
                    ->label('فعال')
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
            ->defaultSort('sorting', 'asc')
            ->emptyStateHeading('هنوز دسته‌بندی‌ای ثبت نشده')
            ->emptyStateDescription('دسته‌بندی‌ها برای گروه‌بندیِ محصولات و مقاله‌ها استفاده می‌شوند.');
    }
}
