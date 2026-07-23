<?php

namespace App\Filament\Resources\SiteConfig;

use App\Filament\Resources\SiteConfig\Pages\EditSysteminf;
use App\Filament\Resources\SiteConfig\Pages\ListSysteminfs;
use App\Filament\Resources\SiteConfig\RelationManagers\ItemsRelationManager;
use App\Filament\Resources\SiteConfig\Schemas\SysteminfForm;
use App\Filament\Resources\SiteConfig\Tables\SysteminfsTable;
use App\Model\Systeminf;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

/**
 * تنظیماتِ سایت — مدیریتِ محتوایِ زنده‌ی storefront (اسلایدرها، درباره، فوتر، اطلاعاتِ تماس،
 * اینستاگرام و…) که فروشگاه از جدول‌های Systeminf/Systeminfmanage می‌خوانَد. این همان داده‌ای است
 * که پنلِ قدیمی هم ویرایش می‌کند؛ پس تغییرات مستقیماً روی سایت اثر دارند و رندرِ storefront
 * دست‌نخورده می‌ماند (فقط ویرایشگرِ تازه روی همان داده). «بخش‌ها» ساختاری‌اند و ساخته/حذف نمی‌شوند؛
 * محتوای هر بخش در تبِ «آیتم‌ها» مدیریت می‌شود.
 */
class SysteminfResource extends Resource
{
    protected static ?string $model = Systeminf::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected static string|UnitEnum|null $navigationGroup = 'پیکربندی';

    protected static ?int $navigationSort = 5;

    protected static ?string $navigationLabel = 'تنظیماتِ سایت';

    protected static ?string $recordTitleAttribute = 'name';

    // بخش‌ها ساختاری‌اند (storefront بر اساسِ id ثابتِ آن‌ها کوئری می‌زند)؛ ساختِ بخشِ جدید
    // بی‌اثر است، پس دکمه‌ی «ایجاد» را نمایش نمی‌دهیم.
    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return SysteminfForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SysteminfsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            ItemsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSysteminfs::route('/'),
            'edit' => EditSysteminf::route('/{record}/edit'),
        ];
    }
}
