<?php

namespace App\Filament\Resources\DigitalProducts;

use App\Filament\Resources\DigitalProducts\Pages\ListDigitalProducts;
use App\Filament\Resources\DigitalProducts\Pages\ViewDigitalProduct;
use App\Filament\Resources\DigitalProducts\Schemas\DigitalProductForm;
use App\Filament\Resources\DigitalProducts\Tables\DigitalProductsTable;
use App\Model\Product;
use App\Utility\ProductType;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

/**
 * محصولاتِ دیجیتال. در این کدپایه مدلِ جداگانه‌ی App\Model\DigitalProduct وجود ندارد؛ «محصولِ دیجیتال»
 * همان App\Model\Product با type از مجموعه‌ی {PDF, VOICE, VIDEO} است (مطابقِ DigitalProductController).
 * بنابراین این Resource فقط برای «مشاهده» ساخته شده (فهرست + نمایش) تا با نوشتن روی جدولِ products
 * به storefront آسیب نرسد. توجه: مدلِ Product دارای accessorِ جلالیِ getCreatedAtAttribute است،
 * پس ستونِ created_at بدونِ ->dateTime()/->date() نمایش داده می‌شود.
 */
class DigitalProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedArrowDownTray;

    protected static string|UnitEnum|null $navigationGroup = 'فروشگاه';

    protected static ?int $navigationSort = 21;

    protected static ?string $navigationLabel = 'محصولاتِ دیجیتال';

    protected static ?string $recordTitleAttribute = 'title';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->whereIn('type', [ProductType::PDF, ProductType::VOICE, ProductType::VIDEO]);
    }

    public static function form(Schema $schema): Schema
    {
        return DigitalProductForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DigitalProductsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDigitalProducts::route('/'),
            'view' => ViewDigitalProduct::route('/{record}'),
        ];
    }
}
