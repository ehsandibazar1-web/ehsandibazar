<?php

namespace App\Filament\Resources\AttributeTypeValues\Schemas;

use App\Model\AttributeType;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class AttributeTypeValueForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('attribute_type_id')
                    ->label('نوعِ ویژگی')
                    ->options(fn () => AttributeType::pluck('name', 'id'))
                    ->searchable(),

                TextInput::make('value')
                    ->label('مقدار')
                    ->required()
                    ->maxLength(255),

                TextInput::make('label')
                    ->label('برچسبِ نمایشی')
                    ->maxLength(255),

                ColorPicker::make('color')
                    ->label('رنگ'),

                Select::make('lang')
                    ->label('زبان')
                    ->options([
                        'fa' => 'فارسی',
                        'en' => 'English',
                    ])
                    ->default('fa')
                    ->required(),

                Toggle::make('status')
                    ->label('فعال')
                    ->default(true),
            ]);
    }
}
