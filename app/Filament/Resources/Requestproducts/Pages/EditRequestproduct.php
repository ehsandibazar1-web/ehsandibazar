<?php

namespace App\Filament\Resources\Requestproducts\Pages;

use App\Filament\Resources\Requestproducts\RequestproductResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditRequestproduct extends EditRecord
{
    protected static string $resource = RequestproductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
