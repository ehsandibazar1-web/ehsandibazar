<?php

namespace App\Filament\Resources\Tags\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class TagForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->label('عنوان')
                    ->required()
                    ->maxLength(255),

                TextInput::make('slug')
                    ->label('اسلاگ (نشانی)')
                    ->maxLength(255)
                    ->unique(ignoreRecord: true)
                    ->helperText('خالی بگذارید تا از عنوان خودکار ساخته شود. توجه: تغییرِ اسلاگ، نشانیِ صفحه‌ی تگ (/article/tag/…) را عوض می‌کند.'),

                Select::make('lang')
                    ->label('زبان')
                    ->options([
                        'fa' => 'فارسی',
                        'en' => 'English',
                    ])
                    ->default('fa')
                    ->required(),

                Toggle::make('status')
                    ->label('فعال (نمایش روی سایت)')
                    ->default(true),
            ]);
    }
}
