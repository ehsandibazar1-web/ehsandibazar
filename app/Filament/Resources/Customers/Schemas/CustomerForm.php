<?php

namespace App\Filament\Resources\Customers\Schemas;

use App\Utility\Level;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;

class CustomerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('نام')
                    ->required()
                    ->maxLength(190),

                TextInput::make('family')
                    ->label('نام خانوادگی')
                    ->maxLength(190),

                // ستونِ mobile در دیتابیس NOT NULL است، بنابراین اجباری.
                TextInput::make('mobile')
                    ->label('موبایل')
                    ->required()
                    ->maxLength(13),

                TextInput::make('email')
                    ->label('ایمیل')
                    ->email()
                    ->maxLength(190),

                TextInput::make('tell')
                    ->label('تلفن')
                    ->maxLength(255),

                TextInput::make('national_code')
                    ->label('کدِ ملی')
                    ->maxLength(255),

                Textarea::make('full_address')
                    ->label('آدرس')
                    ->rows(3)
                    ->columnSpanFull(),

                // سطحِ کاربر (integer)؛ مقادیرِ واقعی از App\Utility\Level.
                Select::make('level')
                    ->label('سطحِ کاربری')
                    ->options(Level::AllLevelEach())
                    ->default(Level::USER),

                // ستونِ sex در دیتابیس integer با پیش‌فرضِ 0 است و هیچ نگاشتی به مرد/زن در کد ندارد،
                // بنابراین به‌جای Select با مقادیرِ حدسی، ورودیِ عددیِ خام استفاده می‌شود.
                TextInput::make('sex')
                    ->label('جنسیت (کدِ عددی)')
                    ->numeric(),

                TextInput::make('discount_percent')
                    ->label('درصدِ تخفیف')
                    ->numeric(),

                TextInput::make('age')
                    ->label('سن')
                    ->numeric(),

                // موجودیِ کیفِ پول نباید دستی ویرایش شود؛ فقط برای مشاهده.
                TextInput::make('wallet')
                    ->label('کیفِ پول')
                    ->numeric()
                    ->disabled()
                    ->dehydrated(false),

                Toggle::make('active')
                    ->label('فعال'),

                Toggle::make('block')
                    ->label('مسدود'),

                TextInput::make('password')
                    ->label('رمز عبور')
                    ->password()
                    ->revealable()
                    ->maxLength(180)
                    ->required(fn (string $operation): bool => $operation === 'create')
                    ->dehydrated(fn ($state): bool => filled($state))
                    ->dehydrateStateUsing(fn ($state): string => Hash::make($state))
                    ->helperText('برای تغییرِ رمز پر کنید؛ خالی بگذارید تا رمزِ فعلی بماند'),

                Select::make('roles')
                    ->label('نقش‌ها')
                    ->relationship('roles', 'name')
                    ->multiple()
                    ->searchable()
                    ->preload(),
            ]);
    }
}
