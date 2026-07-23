<?php

namespace App\Filament\Resources\AttributeGroups\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class AttributeGroupForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('نام (کلید)')
                    ->required()
                    ->maxLength(255),

                TextInput::make('label')
                    ->label('برچسبِ نمایشی')
                    ->maxLength(255),

                Toggle::make('status')
                    ->label('فعال')
                    ->default(true),
            ]);
    }
}
