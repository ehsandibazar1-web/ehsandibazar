<?php

namespace App\Filament\Resources\Requestproducts\Schemas;

use App\Model\Product;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class RequestproductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // محصولِ مرتبط — فقط برای نمایش (متنِ ثبت‌شده‌ی کاربر است). ممکن است 0 باشد یعنی محصولِ سفارشی.
                Select::make('product_id')
                    ->label('محصول')
                    ->options(fn () => Product::query()->pluck('title', 'id'))
                    ->searchable()
                    ->placeholder('محصولِ سفارشی / نامشخص')
                    ->disabled()
                    ->dehydrated(false),

                // توضیحات و جزییات، متنِ ثبت‌شده‌ی کاربر است و ادمین آن را ویرایش نمی‌کند.
                Textarea::make('description')
                    ->label('توضیحات')
                    ->rows(4)
                    ->disabled()
                    ->dehydrated(false)
                    ->columnSpanFull(),

                Textarea::make('details')
                    ->label('جزییات')
                    ->rows(4)
                    ->disabled()
                    ->dehydrated(false)
                    ->columnSpanFull(),

                // تنها فیلدِ قابلِ ویرایش برای ادمین: وضعیتِ بررسیِ درخواست (۰ در انتظار / ۱ بررسی‌شده).
                Select::make('status')
                    ->label('وضعیت')
                    ->options([
                        0 => 'در انتظار بررسی',
                        1 => 'بررسی‌شده',
                    ])
                    ->default(0)
                    ->required(),
            ]);
    }
}
