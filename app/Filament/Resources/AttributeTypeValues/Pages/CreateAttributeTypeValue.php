<?php

namespace App\Filament\Resources\AttributeTypeValues\Pages;

use App\Filament\Resources\AttributeTypeValues\AttributeTypeValueResource;
use Filament\Resources\Pages\CreateRecord;

class CreateAttributeTypeValue extends CreateRecord
{
    protected static string $resource = AttributeTypeValueResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();

        return $data;
    }
}
