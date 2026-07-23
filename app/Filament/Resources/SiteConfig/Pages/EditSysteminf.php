<?php

namespace App\Filament\Resources\SiteConfig\Pages;

use App\Filament\Resources\SiteConfig\SysteminfResource;
use Filament\Resources\Pages\EditRecord;

class EditSysteminf extends EditRecord
{
    protected static string $resource = SysteminfResource::class;

    // بدونِ حذف — بخش‌ها ساختاری‌اند و حذفشان محتوای زنده‌ی سایت را می‌شکند.
    protected function getHeaderActions(): array
    {
        return [];
    }
}
