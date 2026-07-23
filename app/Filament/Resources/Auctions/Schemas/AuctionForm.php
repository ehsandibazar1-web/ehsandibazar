<?php

namespace App\Filament\Resources\Auctions\Schemas;

use App\Model\Product;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class AuctionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('product_id')
                    ->label('محصول')
                    ->options(fn () => Product::pluck('title', 'id'))
                    ->searchable()
                    ->required(),

                DateTimePicker::make('start_date')
                    ->label('شروع'),

                TextInput::make('start_price')
                    ->label('قیمتِ شروع')
                    ->numeric(),

                TextInput::make('end_price')
                    ->label('قیمتِ پایان')
                    ->numeric(),

                TextInput::make('every_click_price')
                    ->label('قیمتِ هر کلیک')
                    ->numeric(),

                TextInput::make('every_click_price_for_pay')
                    ->label('قیمتِ هر کلیک برای پرداخت')
                    ->numeric(),

                TextInput::make('participant_count')
                    ->label('تعدادِ شرکت‌کنندگان')
                    ->numeric(),

                TextInput::make('click_count')
                    ->label('تعدادِ کلیک‌ها (شمارنده‌ی سیستمی)')
                    ->numeric()
                    ->disabled(),

                Toggle::make('status')
                    ->label('فعال')
                    ->default(true),
            ]);
    }
}
