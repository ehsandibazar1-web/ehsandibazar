<?php

namespace App\Filament\Resources\ShippingCosts\Pages;

use App\Filament\Resources\ShippingCosts\ShippingCostResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListShippingCosts extends ListRecords
{
    protected static string $resource = ShippingCostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
