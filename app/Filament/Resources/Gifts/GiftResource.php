<?php

namespace App\Filament\Resources\Gifts;

use App\Filament\Resources\Gifts\Pages\CreateGift;
use App\Filament\Resources\Gifts\Pages\EditGift;
use App\Filament\Resources\Gifts\Pages\ListGifts;
use App\Filament\Resources\Gifts\Schemas\GiftForm;
use App\Filament\Resources\Gifts\Tables\GiftsTable;
use App\Model\Gift;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

/**
 * مدیریتِ هدایای امتیازی — CRUDِ سبک روی مدلِ زنده‌ی App\Model\Gift
 * (user_id/product_id/name/score/lang/status). storefront دست‌نخورده؛ فقط ابزارِ مدیریتِ ادمین.
 */
class GiftResource extends Resource
{
    protected static ?string $model = Gift::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedGift;

    protected static string|UnitEnum|null $navigationGroup = 'فروشگاه';

    protected static ?int $navigationSort = 20;

    protected static ?string $navigationLabel = 'هدایا (امتیازی)';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return GiftForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return GiftsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListGifts::route('/'),
            'create' => CreateGift::route('/create'),
            'edit' => EditGift::route('/{record}/edit'),
        ];
    }
}
