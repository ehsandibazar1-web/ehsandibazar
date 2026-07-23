<?php

namespace App\Filament\Resources\AuctionResults\Pages;

use App\Filament\Resources\AuctionResults\AuctionResultResource;
use Filament\Resources\Pages\ListRecords;

class ListAuctionResults extends ListRecords
{
    protected static string $resource = AuctionResultResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
