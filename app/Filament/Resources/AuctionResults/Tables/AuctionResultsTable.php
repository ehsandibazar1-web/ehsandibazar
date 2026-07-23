<?php

namespace App\Filament\Resources\AuctionResults\Tables;

use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AuctionResultsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('شناسه')
                    ->sortable(),

                TextColumn::make('auction.product.title')
                    ->label('محصولِ مزایده')
                    ->placeholder('—')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('user.name')
                    ->label('کاربر')
                    ->placeholder('—')
                    ->searchable()
                    ->sortable(),

                IconColumn::make('type')
                    ->label('برنده')
                    ->boolean(),

                // created_at accessor روی مدل، خروجیِ جلالی (رشته) می‌دهد؛ عمداً بدونِ ->dateTime().
                TextColumn::make('created_at')
                    ->label('تاریخِ ثبت'),
            ])
            ->recordActions([])
            ->defaultSort('id', 'desc')
            ->emptyStateHeading('هنوز نتیجه‌ای برای مزایده ثبت نشده')
            ->emptyStateDescription('نتایجِ مزایده پس از پایانِ هر حراجی به‌صورتِ خودکار ثبت می‌شوند.');
    }
}
