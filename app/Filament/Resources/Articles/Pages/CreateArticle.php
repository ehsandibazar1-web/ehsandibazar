<?php

namespace App\Filament\Resources\Articles\Pages;

use App\Filament\Resources\Articles\ArticleResource;
use Filament\Resources\Pages\CreateRecord;

class CreateArticle extends CreateRecord
{
    protected static string $resource = ArticleResource::class;

    /**
     * جدولِ articles ستونِ user_id (کلید خارجی، NOT NULL) دارد که فرم آن را نمی‌پرسد — پس
     * مالکِ مقاله را به کاربرِ واردشده‌ی فعلی تنظیم می‌کنیم (مثلِ ادمینِ قدیمی).
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] ??= auth()->id();

        return ArticleResource::applyPublishState($data);
    }

    /**
     * مقاله‌ی تازه با تصویرِ شاخص: رابطه‌ی image() را می‌سازیم تا هیرو روی سایت (که رابطه را
     * می‌خواند) نمایش داده شود — نه فقط در OG. اگر تصویری انتخاب نشده باشد، کاری نمی‌کنیم.
     */
    protected function afterCreate(): void
    {
        if (filled($this->record->image_path)) {
            ArticleResource::syncFeaturedImageRelation($this->record);
        }
    }
}
