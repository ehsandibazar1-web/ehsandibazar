<?php

namespace App\Filament\Resources\Contacts\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ContactForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('نام کاربر')
                    ->disabled(),

                TextInput::make('email')
                    ->label('ایمیل')
                    ->disabled(),

                Textarea::make('body')
                    ->label('متن پیام')
                    ->rows(6)
                    ->columnSpanFull()
                    ->disabled(),

                TextInput::make('ip')
                    ->label('IP')
                    ->disabled(),

                Select::make('status')
                    ->label('وضعیت')
                    ->options([
                        0 => 'غیر فعال',
                        1 => 'فعال',
                    ])
                    ->default(0)
                    ->required(),
            ]);
    }
}
