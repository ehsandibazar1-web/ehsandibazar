<?php

namespace App\Filament\Resources\Discounts\Schemas;

use App\Utility\DiscountType;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class DiscountForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->label('عنوان')
                    ->required()
                    ->maxLength(255),

                Textarea::make('description')
                    ->label('توضیحات')
                    ->rows(3)
                    ->columnSpanFull(),

                Select::make('type')
                    ->label('نوع تخفیف')
                    ->options([
                        DiscountType::discountSimple => 'ساده',
                        DiscountType::discountCode => 'کد دار',
                        DiscountType::discountCodeTime => 'کدار-زمانی',
                        DiscountType::discountTime => 'زمانی',
                        DiscountType::coupon => 'کوپن',
                        DiscountType::amazing => 'شگفت انگیز',
                        DiscountType::COUNTBUY => 'تعداد خرید',
                    ])
                    ->native(false),

                Select::make('baseon')
                    ->label('مبنای تخفیف')
                    ->options([
                        DiscountType::cent => 'درصد',
                        DiscountType::price => 'قیمت (مبلغ ثابت)',
                    ])
                    ->native(false)
                    ->helperText('«درصد» یعنی cent به‌صورت درصد اعمال می‌شود؛ «قیمت» یعنی مبلغِ ثابت.'),

                TextInput::make('cent')
                    ->label('درصد/مقدار')
                    ->numeric(),

                TextInput::make('count_buy')
                    ->label('حداقل تعداد خرید')
                    ->numeric(),

                TextInput::make('count_user')
                    ->label('سقفِ استفاده به‌ازای کاربر')
                    ->numeric(),

                Toggle::make('status')
                    ->label('فعال')
                    ->default(true),
            ]);
    }
}
