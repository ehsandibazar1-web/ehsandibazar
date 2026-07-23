<?php

namespace App\Filament\Resources\ShippingCosts;

use App\Filament\Resources\ShippingCosts\Pages\CreateShippingCost;
use App\Filament\Resources\ShippingCosts\Pages\EditShippingCost;
use App\Filament\Resources\ShippingCosts\Pages\ListShippingCosts;
use App\Filament\Resources\ShippingCosts\Schemas\ShippingCostForm;
use App\Filament\Resources\ShippingCosts\Tables\ShippingCostsTable;
use App\Model\ShippingCost;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

/**
 * مدیریتِ هزینه‌های ارسال — پورتِ آیتمِ «هزینه‌ی ارسال» پنلِ قدیمی (زیرِ فروش). CRUDِ سبک روی مدلِ زنده‌ی
 * App\Model\ShippingCost. storefront و migrations و خودِ مدل دست‌نخورده؛ فقط ابزارِ مدیریتِ ادمین.
 */
class ShippingCostResource extends Resource
{
    protected static ?string $model = ShippingCost::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTruck;

    protected static string|UnitEnum|null $navigationGroup = 'فروش';

    protected static ?int $navigationSort = 5;

    protected static ?string $navigationLabel = 'هزینه‌ی ارسال';

    protected static ?string $recordTitleAttribute = 'id';

    public static function form(Schema $schema): Schema
    {
        return ShippingCostForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ShippingCostsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListShippingCosts::route('/'),
            'create' => CreateShippingCost::route('/create'),
            'edit' => EditShippingCost::route('/{record}/edit'),
        ];
    }
}
