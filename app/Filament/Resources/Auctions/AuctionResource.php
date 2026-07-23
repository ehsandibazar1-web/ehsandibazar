<?php

namespace App\Filament\Resources\Auctions;

use App\Filament\Resources\Auctions\Pages\CreateAuction;
use App\Filament\Resources\Auctions\Pages\EditAuction;
use App\Filament\Resources\Auctions\Pages\ListAuctions;
use App\Filament\Resources\Auctions\Schemas\AuctionForm;
use App\Filament\Resources\Auctions\Tables\AuctionsTable;
use App\Model\Auction;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

/**
 * مدیریتِ مزایده‌ها — ابزارِ ادمین برای CRUDِ مزایده‌های فروشگاه روی مدلِ زنده‌ی
 * App\Model\Auction (product_id/start_date/قیمت‌ها/click_count/status). storefront دست‌نخورده.
 */
class AuctionResource extends Resource
{
    protected static ?string $model = Auction::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTrophy;

    protected static string|UnitEnum|null $navigationGroup = 'فروشگاه';

    protected static ?int $navigationSort = 22;

    protected static ?string $navigationLabel = 'مزایده‌ها';

    protected static ?string $recordTitleAttribute = 'id';

    public static function form(Schema $schema): Schema
    {
        return AuctionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AuctionsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAuctions::route('/'),
            'create' => CreateAuction::route('/create'),
            'edit' => EditAuction::route('/{record}/edit'),
        ];
    }
}
