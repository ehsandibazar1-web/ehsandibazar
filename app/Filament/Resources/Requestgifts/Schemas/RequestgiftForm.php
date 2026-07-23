<?php

namespace App\Filament\Resources\Requestgifts\Schemas;

use App\Model\Gift;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class RequestgiftForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // هدیه‌ی درخواستی — ستونِ نمایشیِ مدلِ Gift همان name است.
                Select::make('gift_id')
                    ->label('هدیه')
                    ->options(fn () => Gift::query()->pluck('name', 'id'))
                    ->searchable()
                    ->nullable()
                    ->placeholder('—'),

                // وضعیتِ استفاده‌شدنِ هدیه — تنها فیلدِ مدیریتیِ ادمین.
                Toggle::make('used')
                    ->label('استفاده‌شده'),
            ]);
    }
}
