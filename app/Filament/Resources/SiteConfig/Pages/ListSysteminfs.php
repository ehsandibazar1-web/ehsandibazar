<?php

namespace App\Filament\Resources\SiteConfig\Pages;

use App\Filament\Resources\SiteConfig\SysteminfResource;
use Filament\Resources\Pages\ListRecords;

class ListSysteminfs extends ListRecords
{
    protected static string $resource = SysteminfResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
