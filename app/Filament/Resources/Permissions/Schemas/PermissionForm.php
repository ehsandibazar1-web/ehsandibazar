<?php

namespace App\Filament\Resources\Permissions\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PermissionForm
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

                TextInput::make('method')
                    ->label('متد/عملیات')
                    ->maxLength(255),
            ]);
    }
}
