<?php

namespace App\Filament\Resources\ContentPlans\Pages;

use App\Filament\Resources\ContentPlans\ContentPlanResource;
use Filament\Resources\Pages\CreateRecord;

class CreateContentPlan extends CreateRecord
{
    protected static string $resource = ContentPlanResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // نویسنده پیش‌فرض = کاربرِ فعلی اگر خالی باشد.
        $data['author_id'] = $data['author_id'] ?? auth()->id();

        return $data;
    }
}
