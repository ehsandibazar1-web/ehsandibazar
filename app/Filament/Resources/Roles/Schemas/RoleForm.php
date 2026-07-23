<?php

namespace App\Filament\Resources\Roles\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class RoleForm
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

                Select::make('permissions')
                    ->label('دسترسی‌ها')
                    ->relationship('permissions', 'name')
                    ->multiple()
                    ->searchable()
                    ->preload(),
            ]);
    }
}
