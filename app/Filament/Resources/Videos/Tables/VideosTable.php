<?php

namespace App\Filament\Resources\Videos\Tables;

use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class VideosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('عنوان')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('url')
                    ->label('آدرسِ ویدیو')
                    ->limit(40),

                TextColumn::make('videoable_type')
                    ->label('مالک')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state ? class_basename($state) : null)
                    ->placeholder('—')
                    ->toggleable(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make()
                    ->requiresConfirmation(),
            ])
            ->defaultSort('id', 'desc')
            ->emptyStateHeading('هنوز ویدیویی ثبت نشده')
            ->emptyStateDescription('ویدیوها به محتوا و محصولاتِ سایت پیوست می‌شوند.');
    }
}
