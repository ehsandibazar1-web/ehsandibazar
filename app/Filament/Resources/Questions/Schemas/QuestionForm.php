<?php

namespace App\Filament\Resources\Questions\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class QuestionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->label('عنوان')
                    ->required()
                    ->maxLength(255),

                Textarea::make('question')
                    ->label('متنِ پرسش')
                    ->rows(5)
                    ->columnSpanFull(),

                Select::make('state')
                    ->label('وضعیت')
                    ->options([
                        0 => 'در انتظار تایید',
                        1 => 'تایید شده',
                    ])
                    ->default(0)
                    ->required(),
            ]);
    }
}
