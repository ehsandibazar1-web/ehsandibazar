<?php

namespace App\Filament\Resources\Requestproducts\Pages;

use App\Filament\Resources\Requestproducts\RequestproductResource;
use Filament\Resources\Pages\ListRecords;

class ListRequestproducts extends ListRecords
{
    protected static string $resource = RequestproductResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
