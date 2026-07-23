<?php

namespace App\Filament\Resources\Tickets\Schemas;

use App\Utility\TicketType;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class TicketForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // متنِ مشتری — فقط-خواندنی؛ ادمین آن را بازنویسی نمی‌کند.
                TextInput::make('subject')
                    ->label('موضوع')
                    ->disabled(),

                TextInput::make('tracking_code')
                    ->label('کدِ پیگیری')
                    ->disabled(),

                Textarea::make('body')
                    ->label('متنِ تیکت')
                    ->rows(6)
                    ->columnSpanFull()
                    ->disabled(),

                // فیلدهای قابلِ ویرایش توسطِ ادمین:
                Select::make('status')
                    ->label('وضعیت')
                    ->options(TicketType::TicketStatus())
                    ->native(false)
                    ->required(),

                Select::make('priority')
                    ->label('اولویت')
                    ->options(TicketType::TicketPriority())
                    ->native(false)
                    ->required(),

                Select::make('departeman')
                    ->label('دپارتمان')
                    ->relationship('departemans', 'name')
                    ->searchable()
                    ->preload()
                    ->placeholder('—'),
            ]);
    }
}
