<?php

namespace App\Filament\Resources\AttributeTypes\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class AttributeTypeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('نام')
                    ->required()
                    ->maxLength(255),

                TextInput::make('label')
                    ->label('برچسب')
                    ->maxLength(255),

                Toggle::make('status')
                    ->label('فعال')
                    ->default(true),
            ]);
    }
}
