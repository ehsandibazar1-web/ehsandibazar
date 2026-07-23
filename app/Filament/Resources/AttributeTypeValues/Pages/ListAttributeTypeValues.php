<?php

namespace App\Filament\Resources\AttributeTypeValues\Pages;

use App\Filament\Resources\AttributeTypeValues\AttributeTypeValueResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAttributeTypeValues extends ListRecords
{
    protected static string $resource = AttributeTypeValueResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
