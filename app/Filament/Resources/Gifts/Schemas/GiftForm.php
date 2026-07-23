<?php

namespace App\Filament\Resources\Gifts\Schemas;

use App\Model\Product;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class GiftForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('نام')
                    ->required()
                    ->maxLength(255),

                Select::make('product_id')
                    ->label('محصولِ هدیه')
                    ->options(Product::pluck('title', 'id'))
                    ->searchable(),

                TextInput::make('score')
                    ->label('امتیازِ لازم')
                    ->numeric(),

                Select::make('lang')
                    ->label('زبان')
                    ->options([
                        'fa' => 'فارسی',
                        'en' => 'English',
                    ])
                    ->default('fa'),

                Toggle::make('status')
                    ->label('فعال')
                    ->default(true),
            ]);
    }
}
