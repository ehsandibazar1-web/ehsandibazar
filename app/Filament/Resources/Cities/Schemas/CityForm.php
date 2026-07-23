<?php

namespace App\Filament\Resources\Cities\Schemas;

use App\Model\Province;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class CityForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('نام')
                    ->required()
                    ->maxLength(255),

                Select::make('province_id')
                    ->label('استان')
                    ->options(Province::pluck('name', 'id'))
                    ->searchable(),

                Select::make('lang')
                    ->label('زبان')
                    ->options([
                        'fa' => 'فارسی',
                        'en' => 'English',
                    ])
                    ->default('fa'),

                TextInput::make('longitude')
                    ->label('طولِ جغرافیایی')
                    ->maxLength(255),

                TextInput::make('latitude')
                    ->label('عرضِ جغرافیایی')
                    ->maxLength(255),
            ]);
    }
}
