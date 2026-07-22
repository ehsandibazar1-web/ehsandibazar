<?php

namespace App\Filament\Resources\Newsletter\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class SubscriberForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('email')
                    ->label('ایمیل')
                    ->email()
                    ->required()
                    ->maxLength(255),

                TextInput::make('name')
                    ->label('نام')
                    ->maxLength(255),

                TextInput::make('mobile')
                    ->label('موبایل')
                    ->tel()
                    ->maxLength(20),
            ]);
    }
}
