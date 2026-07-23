<?php

namespace App\Filament\Resources\ShippingCosts\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ShippingCostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('type')
                    ->label('نوع پست')
                    ->options([
                        1 => 'پیشتاز',
                        2 => 'سفارشی',
                        3 => 'پیک موتوری',
                    ])
                    ->required(),

                TextInput::make('of_weight')
                    ->label('از وزن (گرم)')
                    ->numeric(),

                TextInput::make('upto_weight')
                    ->label('تا وزن (گرم)')
                    ->numeric(),

                TextInput::make('price')
                    ->label('هزینه (تومان)')
                    ->numeric()
                    ->required(),

                Select::make('post_type')
                    ->label('نوع ارسال')
                    ->options([
                        1 => 'درون شهری',
                        2 => 'برون شهری',
                        3 => 'محصول دیجیتال',
                    ]),

                Textarea::make('description')
                    ->label('توضیحات')
                    ->rows(3)
                    ->columnSpanFull(),
            ]);
    }
}
