<?php

namespace App\Filament\Resources\Menus\Schemas;

use App\Model\Menu;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class MenuForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->columns(2)
                    ->schema([
                        TextInput::make('title')
                            ->label('عنوان')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('src')
                            ->label('نشانی (لینک)')
                            ->required()
                            ->maxLength(255)
                            ->helperText('مثلاً /articles یا https://…'),

                        Select::make('parent_id')
                            ->label('والد')
                            ->options(fn (?Menu $record): array => [0 => 'منوی اصلی (بدونِ والد)']
                                + Menu::query()
                                    ->where('parent_id', 0)
                                    ->when($record, fn ($q) => $q->whereKeyNot($record->getKey())) // خودش والدِ خودش نشود
                                    ->orderBy('sorting')
                                    ->pluck('title', 'id')
                                    ->all())
                            ->default(0)
                            ->required(),

                        Select::make('lang')
                            ->label('زبان')
                            ->options(['fa' => 'فارسی', 'en' => 'English'])
                            ->default('fa')
                            ->required(),

                        TextInput::make('sorting')
                            ->label('ترتیب')
                            ->numeric()
                            ->default(0)
                            ->helperText('عددِ کوچک‌تر جلوتر نمایش داده می‌شود.'),

                        Toggle::make('status')
                            ->label('فعال (نمایش روی سایت)')
                            ->default(true),
                    ]),
            ]);
    }
}
