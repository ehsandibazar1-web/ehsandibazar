<?php

namespace App\Filament\Resources\AttributeGroups;

use App\Filament\Resources\AttributeGroups\Pages\CreateAttributeGroup;
use App\Filament\Resources\AttributeGroups\Pages\EditAttributeGroup;
use App\Filament\Resources\AttributeGroups\Pages\ListAttributeGroups;
use App\Filament\Resources\AttributeGroups\Schemas\AttributeGroupForm;
use App\Filament\Resources\AttributeGroups\Tables\AttributeGroupsTable;
use App\Model\AttributeGroup;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

/**
 * مدیریتِ گروه‌های ویژگی — CRUDِ سبک روی مدلِ زنده‌ی App\Model\AttributeGroup
 * (name/label/status، به‌همراه شمارشِ attributes). storefront و مایگریشن و مدل دست‌نخورده؛
 * فقط ابزارِ مدیریتِ ادمین.
 */
class AttributeGroupResource extends Resource
{
    protected static ?string $model = AttributeGroup::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleGroup;

    protected static string|UnitEnum|null $navigationGroup = 'فروشگاه';

    protected static ?int $navigationSort = 10;

    protected static ?string $navigationLabel = 'گروه‌های ویژگی';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return AttributeGroupForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AttributeGroupsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAttributeGroups::route('/'),
            'create' => CreateAttributeGroup::route('/create'),
            'edit' => EditAttributeGroup::route('/{record}/edit'),
        ];
    }
}
