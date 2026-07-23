<?php

namespace App\Filament\Resources\DigitalProducts\Schemas;

use App\Utility\ProductType;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

/**
 * این طرح فقط برای صفحه‌ی «نمایش» استفاده می‌شود (Resource حالتِ read-oriented دارد و صفحه‌ی
 * ویرایش/ساخت ندارد؛ Filament این کامپوننت‌ها را در ViewRecord به‌صورتِ غیرقابلِ‌ویرایش نمایش می‌دهد).
 */
class DigitalProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->label('عنوان'),

                Select::make('type')
                    ->label('نوع')
                    ->options(ProductType::typeEach()),

                TextInput::make('code')
                    ->label('کد'),

                TextInput::make('brand.title')
                    ->label('برند'),

                Toggle::make('status')
                    ->label('فعال'),

                Textarea::make('description')
                    ->label('توضیحات')
                    ->rows(6)
                    ->columnSpanFull(),
            ]);
    }
}
