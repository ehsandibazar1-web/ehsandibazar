<?php

namespace App\Filament\Resources\Pages\Pages;

use App\Filament\Resources\Pages\PageResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePage extends CreateRecord
{
    protected static string $resource = PageResource::class;

    /** ستونِ user_id (NOT NULL) را فرم نمی‌پرسد — به کاربرِ واردشده تنظیم می‌شود. */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] ??= auth()->id();

        return $data;
    }
}
