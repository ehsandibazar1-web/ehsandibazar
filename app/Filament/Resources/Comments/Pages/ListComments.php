<?php

namespace App\Filament\Resources\Comments\Pages;

use App\Filament\Resources\Comments\CommentResource;
use Filament\Resources\Pages\ListRecords;

class ListComments extends ListRecords
{
    protected static string $resource = CommentResource::class;

    protected function getHeaderActions(): array
    {
        // دیدگاه‌ها توسطِ بازدیدکنندگان ساخته می‌شوند؛ این‌جا فقط مُدِریت می‌شوند.
        return [];
    }
}
