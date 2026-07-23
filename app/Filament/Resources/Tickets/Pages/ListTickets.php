<?php

namespace App\Filament\Resources\Tickets\Pages;

use App\Filament\Resources\Tickets\TicketResource;
use Filament\Resources\Pages\ListRecords;

class ListTickets extends ListRecords
{
    protected static string $resource = TicketResource::class;

    // ساختِ تیکت از پنلِ ادمین وجود ندارد — تیکت‌ها را مشتری می‌سازد.
    protected function getHeaderActions(): array
    {
        return [];
    }
}
