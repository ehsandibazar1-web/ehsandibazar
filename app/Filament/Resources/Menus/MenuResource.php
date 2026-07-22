<?php

namespace App\Filament\Resources\Menus;

use App\Filament\Resources\Menus\Pages\CreateMenu;
use App\Filament\Resources\Menus\Pages\EditMenu;
use App\Filament\Resources\Menus\Pages\ListMenus;
use App\Filament\Resources\Menus\Schemas\MenuForm;
use App\Filament\Resources\Menus\Tables\MenusTable;
use App\Model\Menu;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

/**
 * تنظیماتِ منو — معادلِ «Menu Settings» سایتِ انگلیسی، اما به‌جای پورتِ کورِ SiteSetting (که فارسی
 * ندارد) روی مدلِ زنده‌ی App\Model\Menu (جدولِ menu: title/src/parent_id/lang/status/sorting) ساخته
 * شده تا با storefront یکی باشد. مدیریتِ آیتم‌های نویگیشنِ هدر توسط ادمین.
 */
class MenuResource extends Resource
{
    protected static ?string $model = Menu::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBars3;

    protected static string|UnitEnum|null $navigationGroup = null;

    protected static ?int $navigationSort = 8;

    protected static ?string $navigationLabel = 'Menu Settings';

    protected static ?string $slug = 'menu-settings';

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return MenuForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MenusTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListMenus::route('/'),
            'create' => CreateMenu::route('/create'),
            'edit' => EditMenu::route('/{record}/edit'),
        ];
    }
}
