<?php

namespace App\Filament\Resources\ShippingCosts\Pages;

use App\Filament\Resources\ShippingCosts\ShippingCostResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditShippingCost extends EditRecord
{
    protected static string $resource = ShippingCostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
