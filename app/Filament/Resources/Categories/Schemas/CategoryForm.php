<?php

namespace App\Filament\Resources\Categories\Schemas;

use App\Model\Category;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class CategoryForm
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
                    ->helperText('خالی بگذارید تا از عنوان خودکار ساخته شود. توجه: تغییرِ اسلاگ، نشانیِ سئوی صفحه‌ی دسته‌بندی را عوض می‌کند.'),

                Select::make('parent_id')
                    ->label('دسته‌ی والد')
                    ->options(fn (?Category $record) => Category::query()
                        ->when($record, fn ($query) => $query->whereKeyNot($record->getKey()))
                        ->orderBy('title')
                        ->pluck('title', 'id'))
                    ->searchable()
                    ->placeholder('بدون والد (سطحِ اول)'),

                // مقدارِ type در دیتابیس نامِ کاملِ کلاسِ مدل است (مثلِ App\Model\Product) و نه
                // برچسبِ ساده. برای جلوگیری از دست‌رفتنِ داده‌ها اینجا TextInput گذاشته شده تا مقدارِ
                // موجود دست‌نخورده بماند.
                TextInput::make('type')
                    ->label('نوع')
                    ->maxLength(255)
                    ->helperText('نوعِ دسته‌بندی (مقدارِ فنی). در صورتِ نامطمئن بودن، مقدارِ فعلی را تغییر ندهید.'),

                TextInput::make('sorting')
                    ->label('ترتیب')
                    ->numeric(),

                Toggle::make('is_attributable')
                    ->label('قابلِ ویژگی‌گذاری'),

                Toggle::make('status')
                    ->label('فعال')
                    ->default(true),
            ]);
    }
}
