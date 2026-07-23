<?php

namespace App\Filament\Resources\DigitalProducts\Tables;

use App\Utility\ProductType;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DigitalProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('عنوان')
                    ->searchable()
                    ->sortable()
                    ->limit(60),

                TextColumn::make('type')
                    ->label('نوع')
                    ->badge()
                    ->formatStateUsing(fn ($state) => ProductType::productType($state)),

                TextColumn::make('brand.title')
                    ->label('برند')
                    ->badge()
                    ->color('gray')
                    ->placeholder('—')
                    ->toggleable(),

                TextColumn::make('code')
                    ->label('کد')
                    ->placeholder('—')
                    ->toggleable(),

                IconColumn::make('status')
                    ->label('فعال')
                    ->boolean(),

                // مدلِ Product دارای accessorِ جلالیِ created_at است؛ بدونِ ->dateTime()/->date().
                TextColumn::make('created_at')
                    ->label('تاریخِ ثبت')
                    ->toggleable(),
            ])
            ->recordActions([
                ViewAction::make(),
            ])
            ->defaultSort('id', 'desc')
            ->emptyStateHeading('هنوز محصولِ دیجیتالی ثبت نشده')
            ->emptyStateDescription('محصولاتِ دیجیتال، محصولاتی با نوعِ پی‌دی‌اف، ویس یا ویدیو هستند.');
    }
}
