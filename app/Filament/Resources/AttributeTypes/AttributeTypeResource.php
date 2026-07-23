<?php

namespace App\Filament\Resources\AttributeTypes;

use App\Filament\Resources\AttributeTypes\Pages\CreateAttributeType;
use App\Filament\Resources\AttributeTypes\Pages\EditAttributeType;
use App\Filament\Resources\AttributeTypes\Pages\ListAttributeTypes;
use App\Filament\Resources\AttributeTypes\Schemas\AttributeTypeForm;
use App\Filament\Resources\AttributeTypes\Tables\AttributeTypesTable;
use App\Model\AttributeType;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

/**
 * مدیریتِ انواعِ ویژگی — پورتِ آیتمِ «attribute-type» پنلِ قدیمی (زیرِ فروشگاه). CRUDِ سبک روی مدلِ زنده‌ی
 * App\Model\AttributeType (name/label/status). storefront و مدل دست‌نخورده؛ فقط ابزارِ مدیریتِ ادمین.
 */
class AttributeTypeResource extends Resource
{
    protected static ?string $model = AttributeType::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSwatch;

    protected static string|UnitEnum|null $navigationGroup = 'فروشگاه';

    protected static ?int $navigationSort = 11;

    protected static ?string $navigationLabel = 'انواعِ ویژگی';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return AttributeTypeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AttributeTypesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAttributeTypes::route('/'),
            'create' => CreateAttributeType::route('/create'),
            'edit' => EditAttributeType::route('/{record}/edit'),
        ];
    }
}
