<?php

namespace App\Filament\Resources\AttributeTypeValues\Pages;

use App\Filament\Resources\AttributeTypeValues\AttributeTypeValueResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditAttributeTypeValue extends EditRecord
{
    protected static string $resource = AttributeTypeValueResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
