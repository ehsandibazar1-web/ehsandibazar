<?php

namespace App\Filament\Resources\AttributeTypeValues;

use App\Filament\Resources\AttributeTypeValues\Pages\CreateAttributeTypeValue;
use App\Filament\Resources\AttributeTypeValues\Pages\EditAttributeTypeValue;
use App\Filament\Resources\AttributeTypeValues\Pages\ListAttributeTypeValues;
use App\Filament\Resources\AttributeTypeValues\Schemas\AttributeTypeValueForm;
use App\Filament\Resources\AttributeTypeValues\Tables\AttributeTypeValuesTable;
use App\Model\AttributeTypeValue;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

/**
 * مدیریتِ مقادیرِ ویژگی — پورتِ آیتمِ «Attribute Type Value» پنلِ قدیمی (زیرِ فروشگاه). CRUDِ سبک روی مدلِ
 * زنده‌ی App\Model\AttributeTypeValue (attribute_type_id/value/label/color/lang/status). storefront و
 * migration دست‌نخورده؛ فقط ابزارِ مدیریتِ ادمین. user_id هنگامِ ساخت از کاربرِ واردشده پر می‌شود.
 */
class AttributeTypeValueResource extends Resource
{
    protected static ?string $model = AttributeTypeValue::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTag;

    protected static string|UnitEnum|null $navigationGroup = 'فروشگاه';

    protected static ?int $navigationSort = 13;

    protected static ?string $navigationLabel = 'مقادیرِ ویژگی';

    protected static ?string $recordTitleAttribute = 'value';

    public static function form(Schema $schema): Schema
    {
        return AttributeTypeValueForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AttributeTypeValuesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAttributeTypeValues::route('/'),
            'create' => CreateAttributeTypeValue::route('/create'),
            'edit' => EditAttributeTypeValue::route('/{record}/edit'),
        ];
    }
}
