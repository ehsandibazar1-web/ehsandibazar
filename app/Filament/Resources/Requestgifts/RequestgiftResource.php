<?php

namespace App\Filament\Resources\Requestgifts;

use App\Filament\Resources\Requestgifts\Pages\EditRequestgift;
use App\Filament\Resources\Requestgifts\Pages\ListRequestgifts;
use App\Filament\Resources\Requestgifts\Schemas\RequestgiftForm;
use App\Filament\Resources\Requestgifts\Tables\RequestgiftsTable;
use App\Model\Requestgift;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

/**
 * مدیریتِ درخواست‌های هدیه‌ی کاربران — نمای ادمینِ روی مدلِ زنده‌ی App\Model\Requestgift.
 * کاربران هدیه‌ها را از storefront درخواست می‌کنند؛ ادمین فقط مشاهده و مدیریتِ وضعیتِ «استفاده‌شده»
 * را انجام می‌دهد (ساختِ درخواست از پنلِ ادمین غیرفعال است). storefront/migration/مدل دست‌نخورده‌اند.
 */
class RequestgiftResource extends Resource
{
    protected static ?string $model = Requestgift::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedGift;

    protected static string|UnitEnum|null $navigationGroup = 'پشتیبانی';

    protected static ?int $navigationSort = 6;

    protected static ?string $navigationLabel = 'درخواست‌های هدیه';

    protected static ?string $recordTitleAttribute = 'id';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return RequestgiftForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RequestgiftsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListRequestgifts::route('/'),
            'edit' => EditRequestgift::route('/{record}/edit'),
        ];
    }
}
