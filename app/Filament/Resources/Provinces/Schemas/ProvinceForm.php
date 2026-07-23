<?php

namespace App\Filament\Resources\Provinces\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ProvinceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('نام')
                    ->required()
                    ->maxLength(255),

                TextInput::make('longitude')
                    ->label('طولِ جغرافیایی')
                    ->maxLength(255),

                TextInput::make('latitude')
                    ->label('عرضِ جغرافیایی')
                    ->maxLength(255),
            ]);
    }
}
