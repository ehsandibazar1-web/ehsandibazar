<?php

namespace App\Filament\Resources\Auctions\Tables;

use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AuctionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('شناسه')
                    ->sortable(),

                TextColumn::make('product.title')
                    ->label('محصول')
                    ->placeholder('—')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('start_date')
                    ->label('شروع')
                    ->dateTime()
                    ->sortable(),

                TextColumn::make('start_price')
                    ->label('قیمتِ شروع')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('end_price')
                    ->label('قیمتِ پایان')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('participant_count')
                    ->label('شرکت‌کنندگان')
                    ->sortable(),

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
            ->emptyStateHeading('هنوز مزایده‌ای ثبت نشده')
            ->emptyStateDescription('مزایده‌ها برای برگزاریِ حراجیِ کلیکیِ محصولات استفاده می‌شوند.');
    }
}
