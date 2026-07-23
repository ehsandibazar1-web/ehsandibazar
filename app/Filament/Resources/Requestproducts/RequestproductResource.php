<?php

namespace App\Filament\Resources\Requestproducts;

use App\Filament\Resources\Requestproducts\Pages\EditRequestproduct;
use App\Filament\Resources\Requestproducts\Pages\ListRequestproducts;
use App\Filament\Resources\Requestproducts\Schemas\RequestproductForm;
use App\Filament\Resources\Requestproducts\Tables\RequestproductsTable;
use App\Model\Requestproduct;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

/**
 * مدیریتِ درخواست‌های محصولِ کاربران — نمای ادمینِ روی مدلِ زنده‌ی App\Model\Requestproduct.
 * این درخواست‌ها را کاربران در storefront ثبت می‌کنند؛ ادمین فقط مشاهده و مدیریتِ وضعیت می‌کند
 * (ساختِ درخواست از پنلِ ادمین غیرفعال است). storefront/migration/مدل دست‌نخورده‌اند.
 */
class RequestproductResource extends Resource
{
    protected static ?string $model = Requestproduct::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedInboxArrowDown;

    protected static string|UnitEnum|null $navigationGroup = 'پشتیبانی';

    protected static ?int $navigationSort = 5;

    protected static ?string $navigationLabel = 'درخواست‌های محصول';

    protected static ?string $recordTitleAttribute = 'id';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return RequestproductForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RequestproductsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListRequestproducts::route('/'),
            'edit' => EditRequestproduct::route('/{record}/edit'),
        ];
    }
}
