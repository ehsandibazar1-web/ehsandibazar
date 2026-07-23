<?php

namespace App\Filament\Resources\Products\Schemas;

use App\Model\Brand;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ProductForm
{
    /** نگاشتِ App\Enums\ProductType (بدونِ وابستگی مستقیم — همان مقادیرِ عددی). */
    public const TYPES = [
        0 => 'فیزیکی',
        1 => 'مزایده',
        2 => 'پی‌دی‌اف',
        3 => 'ویس',
        4 => 'ویدیو',
        5 => 'رایگان',
    ];

    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('اطلاعاتِ اصلی')
                    ->columns(2)
                    ->schema([
                        TextInput::make('title')
                            ->label('عنوانِ محصول')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),

                        TextInput::make('slug')
                            ->label('اسلاگ (نشانی)')
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->helperText('خالی بگذارید تا از عنوان خودکار ساخته شود. هشدار: تغییرِ اسلاگ نشانیِ صفحه‌ی محصول (/products/…) و در نتیجه سئو را عوض می‌کند.'),

                        TextInput::make('code')
                            ->label('کدِ محصول')
                            ->maxLength(255),

                        Select::make('type')
                            ->label('نوعِ محصول')
                            ->options(self::TYPES)
                            ->default(0)
                            ->required(),

                        Select::make('brand_id')
                            ->label('برند')
                            ->options(fn () => Brand::query()->orderBy('title')->pluck('title', 'id'))
                            ->searchable()
                            ->placeholder('بدونِ برند'),

                        RichEditor::make('description')
                            ->label('توضیحاتِ محصول')
                            ->columnSpanFull(),
                    ]),

                Section::make('دسته‌بندی و تگ')
                    ->columns(2)
                    ->schema([
                        Select::make('categories')
                            ->label('دسته‌بندی‌ها')
                            ->relationship('categories', 'title')
                            ->multiple()
                            ->searchable()
                            ->preload(),

                        Select::make('tags')
                            ->label('تگ‌ها')
                            ->relationship('tags', 'title')
                            ->multiple()
                            ->searchable()
                            ->preload(),
                    ]),

                Section::make('حمل و نقل و مشخصات')
                    ->columns(3)
                    ->schema([
                        TextInput::make('weight')
                            ->label('وزن (گرم)')
                            ->numeric(),

                        TextInput::make('shipping_cost')
                            ->label('هزینه‌ی ارسال (تومان)')
                            ->numeric(),

                        Select::make('lang')
                            ->label('زبان')
                            ->options(['fa' => 'فارسی', 'en' => 'English'])
                            ->default('fa')
                            ->required(),

                        Textarea::make('package_detail')
                            ->label('جزئیاتِ بسته‌بندی')
                            ->rows(2)
                            ->columnSpanFull(),
                    ]),

                Section::make('وضعیت و نشان‌ها')
                    ->columns(3)
                    ->schema([
                        Toggle::make('status')
                            ->label('فعال (نمایش روی سایت)')
                            ->default(true),

                        Toggle::make('special')
                            ->label('محصولِ ویژه'),

                        Toggle::make('sales')
                            ->label('حراج'),

                        Toggle::make('momentary')
                            ->label('پیشنهادِ لحظه‌ای'),

                        Toggle::make('selected_brand')
                            ->label('برندِ منتخب'),

                        TextInput::make('sorting')
                            ->label('ترتیبِ نمایش')
                            ->numeric(),
                    ]),
            ]);
    }
}
