<?php

namespace App\Filament\Resources\AuctionResults;

use App\Filament\Resources\AuctionResults\Pages\ListAuctionResults;
use App\Filament\Resources\AuctionResults\Tables\AuctionResultsTable;
use App\Model\AuctionResult;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

/**
 * نتایجِ مزایده — نمایِ فقط‌خواندنیِ ادمین روی مدلِ زنده‌ی App\Model\AuctionResult
 * (auction_id/user_id/type: برنده یا بازنده). بدون ایجاد/ویرایش/حذف؛ storefront دست‌نخورده.
 */
class AuctionResultResource extends Resource
{
    protected static ?string $model = AuctionResult::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTrophy;

    protected static string|UnitEnum|null $navigationGroup = 'فروشگاه';

    protected static ?int $navigationSort = 23;

    protected static ?string $navigationLabel = 'نتایجِ مزایده';

    protected static ?string $recordTitleAttribute = 'id';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function table(Table $table): Table
    {
        return AuctionResultsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAuctionResults::route('/'),
        ];
    }
}
