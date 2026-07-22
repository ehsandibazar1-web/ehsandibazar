<?php

namespace App\Filament\Resources\Tags\Tables;

use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TagsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn ($query) => $query->withCount(['articles', 'products']))
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

                TextColumn::make('lang')
                    ->label('زبان')
                    ->badge(),

                TextColumn::make('articles_count')
                    ->label('مقاله‌ها')
                    ->badge()
                    ->color('primary'),

                TextColumn::make('products_count')
                    ->label('محصولات')
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
            ->emptyStateHeading('هنوز تگی ثبت نشده')
            ->emptyStateDescription('تگ‌ها برای گروه‌بندیِ مقاله‌ها/محصولات و صفحه‌های /article/tag/… استفاده می‌شوند.');
    }
}
