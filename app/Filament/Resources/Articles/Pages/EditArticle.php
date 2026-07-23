<?php

namespace App\Filament\Resources\Articles\Pages;

use App\Filament\Resources\Articles\ArticleResource;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

// نمای سفارشی $view: سایدبار دستیار هوش مصنوعی تعبیه‌شده — نگاه کنید به
// resources/views/filament/resources/articles/pages/edit-article.blade.php
class EditArticle extends EditRecord
{
    protected static string $resource = ArticleResource::class;

    protected string $view = 'filament.resources.articles.pages.edit-article';

    protected function mutateFormDataBeforeFill(array $data): array
    {
        return ArticleResource::derivePublishState($data);
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        return ArticleResource::applyPublishState($data);
    }

    /**
     * فقط وقتی ادمین در همین ذخیره تصویرِ شاخص را عوض کرده باشد، رابطه‌ی image() (که storefront
     * می‌خواند) را هماهنگ می‌کنیم تا تغییر روی سایت اعمال شود. مقاله‌های دست‌نخورده تغییر نمی‌کنند.
     */
    protected function afterSave(): void
    {
        if ($this->record->wasChanged('image_path')) {
            ArticleResource::syncFeaturedImageRelation($this->record);
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('aiAssistant')
                ->label('دستیار هوش مصنوعی')
                ->icon('heroicon-o-sparkles')
                ->action(fn () => $this->dispatch('toggle-ai-sidebar')),
            DeleteAction::make(),
        ];
    }
}
