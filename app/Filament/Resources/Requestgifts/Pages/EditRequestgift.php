<?php

namespace App\Filament\Resources\Requestgifts\Pages;

use App\Filament\Resources\Requestgifts\RequestgiftResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditRequestgift extends EditRecord
{
    protected static string $resource = RequestgiftResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
