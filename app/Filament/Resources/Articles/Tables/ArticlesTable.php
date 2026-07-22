<?php

namespace App\Filament\Resources\Articles\Tables;

use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class ArticlesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image_path')
                    ->label('')
                    ->disk('public')
                    ->height(40)
                    ->square(),

                TextColumn::make('title')
                    ->label('عنوان')
                    ->searchable()
                    ->limit(50)
                    ->sortable(),

                TextColumn::make('lang')
                    ->label('زبان')
                    ->badge()
                    ->color('gray'),

                IconColumn::make('status')
                    ->label('منتشر شده')
                    ->boolean(),

                // accessorِ getCreatedAtAttributeِ legacy مقدار را به شمسی فرمت و attribute را
                // بازنویسی می‌کند؛ خواندنِ دوباره‌اش در جدول خطا می‌دهد. با getRawOriginal مقدارِ
                // خامِ دیتابیس را می‌گیریم تا accessor اصلاً فعال نشود.
                TextColumn::make('created')
                    ->label('تاریخ')
                    ->state(fn ($record) => ($raw = $record->getRawOriginal('created_at'))
                        ? \Illuminate\Support\Carbon::parse($raw)->format('Y-m-d')
                        : '—'),
            ])
            ->filters([
                TernaryFilter::make('status')
                    ->label('وضعیت')
                    ->placeholder('همه')
                    ->trueLabel('منتشرشده')
                    ->falseLabel('پیش‌نویس'),

                SelectFilter::make('lang')
                    ->label('زبان')
                    ->options(['fa' => 'فارسی', 'en' => 'English', 'tr' => 'Türkçe']),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->defaultSort('id', 'desc');
    }
}
