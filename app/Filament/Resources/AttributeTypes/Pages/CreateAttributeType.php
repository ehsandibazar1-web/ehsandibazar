<?php

namespace App\Filament\Resources\AttributeTypes\Pages;

use App\Filament\Resources\AttributeTypes\AttributeTypeResource;
use Filament\Resources\Pages\CreateRecord;

class CreateAttributeType extends CreateRecord
{
    protected static string $resource = AttributeTypeResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();

        return $data;
    }
}
