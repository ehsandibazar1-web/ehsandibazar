<?php

namespace App\Filament\Resources\Requestgifts\Pages;

use App\Filament\Resources\Requestgifts\RequestgiftResource;
use Filament\Resources\Pages\ListRecords;

class ListRequestgifts extends ListRecords
{
    protected static string $resource = RequestgiftResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
