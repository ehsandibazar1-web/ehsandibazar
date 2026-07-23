<?php

namespace App\Filament\Resources\SiteConfig\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class SysteminfForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('نامِ بخش')
                    ->required()
                    ->maxLength(255),

                Textarea::make('description')
                    ->label('توضیح')
                    ->rows(2),

                Toggle::make('status')
                    ->label('فعال')
                    ->default(true),
            ]);
    }
}
