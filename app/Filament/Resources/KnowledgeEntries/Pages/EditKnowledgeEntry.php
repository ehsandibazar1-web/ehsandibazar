<?php

namespace App\Filament\Resources\KnowledgeEntries\Pages;

use App\Filament\Resources\KnowledgeEntries\KnowledgeEntryResource;
use App\Jobs\IndexKnowledgeContent;
use App\Models\KnowledgeEntryAttachment;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Icons\Heroicon;

class EditKnowledgeEntry extends EditRecord
{
    protected static string $resource = KnowledgeEntryResource::class;

    private array $newAttachmentPaths = [];

    private ?string $newWebsiteUrl = null;

    protected function getHeaderActions(): array
    {
        return [
            // دکمه‌ی بازسازیِ دستیِ ایندکسِ همین ورودی. جاب دیسپچ می‌شود ولی چون IndexingService یک
            // stub است، یک no-op بی‌ضرر است و هیچ تماس embedding‌ای انجام نمی‌شود.
            Action::make('reindex')
                ->label('ایندکسِ مجدد برای هوشِ مصنوعی')
                ->icon(Heroicon::OutlinedArrowPath)
                ->color('gray')
                ->action(function (): void {
                    dispatch(new IndexKnowledgeContent($this->record));

                    foreach ($this->record->attachments as $attachment) {
                        dispatch(new IndexKnowledgeContent($attachment));
                    }

                    Notification::make()
                        ->warning()
                        ->title('ایندکسِ برداری فعلاً غیرفعال است')
                        ->body('این ورودی و پیوست‌هایش صف شدند، ولی چون embedding هنوز فعال نیست، قطعه‌ی برداری ساخته نمی‌شود.')
                        ->send();
                }),
            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $this->newAttachmentPaths = $data['new_attachments'] ?? [];
        unset($data['new_attachments']);

        $this->newWebsiteUrl = $data['new_website_url'] ?? null;
        unset($data['new_website_url']);

        return $data;
    }

    protected function afterSave(): void
    {
        KnowledgeEntryAttachment::createManyFromDiskPaths($this->record, $this->newAttachmentPaths);

        if (filled($this->newWebsiteUrl)) {
            KnowledgeEntryAttachment::createFromUrl($this->record, $this->newWebsiteUrl);
        }
    }
}
