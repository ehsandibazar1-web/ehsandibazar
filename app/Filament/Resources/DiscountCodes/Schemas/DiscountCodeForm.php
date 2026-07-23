<?php

namespace App\Filament\Resources\DiscountCodes\Schemas;

use App\Model\Discount;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class DiscountCodeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('discount_id')
                    ->label('تخفیفِ مرتبط')
                    ->options(fn () => Discount::pluck('title', 'id'))
                    ->searchable(),

                TextInput::make('code')
                    ->label('کدِ تخفیف')
                    ->required()
                    ->maxLength(255),
            ]);
    }
}
