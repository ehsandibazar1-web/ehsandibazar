<?php

namespace App\Filament\Resources\Faqs\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class FaqForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->label('پرسش')
                    ->required()
                    ->maxLength(255),

                Textarea::make('description')
                    ->label('پاسخ')
                    ->rows(5)
                    ->columnSpanFull(),
            ]);
    }
}
