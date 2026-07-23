<?php

namespace App\Filament\Resources\Tickets;

use App\Filament\Resources\Tickets\Pages\EditTicket;
use App\Filament\Resources\Tickets\Pages\ListTickets;
use App\Filament\Resources\Tickets\RelationManagers\AnswersRelationManager;
use App\Filament\Resources\Tickets\Schemas\TicketForm;
use App\Filament\Resources\Tickets\Tables\TicketsTable;
use App\Model\Ticket;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

/**
 * مدیریتِ تیکت‌های پشتیبانی — روی مدلِ زنده‌ی App\Model\Ticket (جدولِ ticket).
 * تیکت‌ها از سمتِ مشتری ساخته می‌شوند؛ اینجا فقط دیدن، تغییرِ وضعیت/اولویت/دپارتمان و پاسخ‌دادن است.
 * ساختِ تیکت غیرفعال است. storefront/مدل/مهاجرت‌ها دست‌نخورده.
 */
class TicketResource extends Resource
{
    protected static ?string $model = Ticket::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChatBubbleBottomCenterText;

    protected static string|UnitEnum|null $navigationGroup = 'پشتیبانی';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationLabel = 'تیکت‌ها';

    protected static ?string $recordTitleAttribute = 'subject';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return TicketForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TicketsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            AnswersRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTickets::route('/'),
            'edit' => EditTicket::route('/{record}/edit'),
        ];
    }
}
