<?php

namespace App\Filament\Resources\DigitalProducts\Pages;

use App\Filament\Resources\DigitalProducts\DigitalProductResource;
use Filament\Resources\Pages\ListRecords;

class ListDigitalProducts extends ListRecords
{
    protected static string $resource = DigitalProductResource::class;

    // Resource حالتِ read-oriented دارد؛ اکشنِ ساخت عمداً حذف شده تا روی جدولِ products نوشته نشود.
    protected function getHeaderActions(): array
    {
        return [];
    }
}
