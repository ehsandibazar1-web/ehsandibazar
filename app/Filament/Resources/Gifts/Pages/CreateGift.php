<?php

namespace App\Filament\Resources\Gifts\Pages;

use App\Filament\Resources\Gifts\GiftResource;
use Filament\Resources\Pages\CreateRecord;

class CreateGift extends CreateRecord
{
    protected static string $resource = GiftResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();

        return $data;
    }
}
