<?php

namespace App\Filament\Resources\Brands\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class BrandForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->label('عنوان')
                    ->required()
                    ->maxLength(255),

                TextInput::make('latin_title')
                    ->label('عنوانِ لاتین')
                    ->maxLength(255),

                TextInput::make('slug')
                    ->label('اسلاگ (نشانی)')
                    ->maxLength(255)
                    ->unique(ignoreRecord: true)
                    ->helperText('خالی بگذارید تا از عنوان خودکار ساخته شود. توجه: تغییرِ اسلاگ، نشانیِ صفحه‌ی برند (/brand/…) را عوض می‌کند.'),

                Textarea::make('description')
                    ->label('توضیحات')
                    ->rows(4),

                Select::make('lang')
                    ->label('زبان')
                    ->options([
                        'fa' => 'فارسی',
                        'en' => 'English',
                    ])
                    ->default('fa')
                    ->required(),

                TextInput::make('sorting')
                    ->label('ترتیبِ نمایش')
                    ->numeric(),

                Toggle::make('status')
                    ->label('فعال (نمایش روی سایت)')
                    ->default(true),

                Toggle::make('top')
                    ->label('برندِ برتر'),

                Toggle::make('new')
                    ->label('جدید'),
            ]);
    }
}
