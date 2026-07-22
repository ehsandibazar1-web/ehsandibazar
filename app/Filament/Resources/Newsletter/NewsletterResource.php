<?php

namespace App\Filament\Resources\Newsletter;

use App\Filament\Resources\Newsletter\Pages\CreateSubscriber;
use App\Filament\Resources\Newsletter\Pages\EditSubscriber;
use App\Filament\Resources\Newsletter\Pages\ListSubscribers;
use App\Filament\Resources\Newsletter\Schemas\SubscriberForm;
use App\Filament\Resources\Newsletter\Tables\SubscribersTable;
use App\Model\NewsLatters;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

/**
 * خبرنامه — پورتِ آیتمِ «Newsletter» سایتِ انگلیسی. مدیریتِ مشترکینِ خبرنامه روی جدولِ زنده‌ی
 * newslatters (App\Model\NewsLatters: email/name/mobile). فقط ابزارِ ادمین؛ storefront دست‌نخورده.
 */
class NewsletterResource extends Resource
{
    protected static ?string $model = NewsLatters::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedEnvelope;

    protected static string|UnitEnum|null $navigationGroup = null;

    protected static ?int $navigationSort = 12;

    protected static ?string $navigationLabel = 'Newsletter';

    protected static ?string $slug = 'newsletter';

    protected static ?string $recordTitleAttribute = 'email';

    public static function form(Schema $schema): Schema
    {
        return SubscriberForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SubscribersTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSubscribers::route('/'),
            'create' => CreateSubscriber::route('/create'),
            'edit' => EditSubscriber::route('/{record}/edit'),
        ];
    }
}
