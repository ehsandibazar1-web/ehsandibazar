<?php

namespace App\Filament\Resources\Contacts;

use App\Filament\Resources\Contacts\Pages\EditContact;
use App\Filament\Resources\Contacts\Pages\ListContacts;
use App\Filament\Resources\Contacts\Schemas\ContactForm;
use App\Filament\Resources\Contacts\Tables\ContactsTable;
use App\Model\Contact;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

/**
 * مدیریتِ پیام‌های تماس — نمایش و مدیریتِ وضعیتِ پیام‌هایی که بازدیدکنندگان از فرمِ «تماس با ما»
 * ثبت کرده‌اند. روی مدلِ زنده‌ی App\Model\Contact کار می‌کند. فقط ابزارِ ادمین است؛ storefront و
 * مدل و مهاجرت‌ها دست‌نخورده می‌مانند. چون محتوا را بازدیدکننده ثبت می‌کند، ایجادِ رکورد غیرفعال است
 * و فقط وضعیت قابل ویرایش است.
 */
class ContactResource extends Resource
{
    protected static ?string $model = Contact::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedEnvelope;

    protected static string|UnitEnum|null $navigationGroup = 'پشتیبانی';

    protected static ?int $navigationSort = 4;

    protected static ?string $navigationLabel = 'پیام‌های تماس';

    protected static ?string $recordTitleAttribute = 'name';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return ContactForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ContactsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListContacts::route('/'),
            'edit' => EditContact::route('/{record}/edit'),
        ];
    }
}
