<?php

namespace App\Filament\Resources\Exams\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ExamForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('نام')
                    ->required()
                    ->maxLength(255),

                TextInput::make('mobile')
                    ->label('موبایل')
                    ->tel()
                    ->maxLength(255),

                Textarea::make('description')
                    ->label('توضیحات')
                    ->rows(5)
                    ->columnSpanFull(),
            ]);
    }
}
