<?php

namespace App\Filament\Resources\KnowledgeEntries\Pages;

use App\Filament\Resources\KnowledgeEntries\KnowledgeEntryResource;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Icons\Heroicon;

class ListKnowledgeEntries extends ListRecords
{
    protected static string $resource = KnowledgeEntryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // در سایتِ انگلیسی این دکمه جابِ App\Jobs\RebuildKnowledgeIndex را دیسپچ می‌کرد (استخراج/
            // تکه/embedِ دوباره‌ی کلِ کتابخانه). آن جاب در نسخه‌ی فارسی منتقل نشده چون embedding stub
            // است — پس دکمه نگه داشته می‌شود ولی فقط یک اعلانِ «غیرفعال» نشان می‌دهد، بدونِ هیچ کاری.
            Action::make('rebuildAllIndexes')
                ->label('بازسازیِ همه‌ی ایندکس‌ها')
                ->icon(Heroicon::OutlinedArrowPath)
                ->color('gray')
                ->requiresConfirmation()
                ->modalDescription('در این نسخه ایندکسِ برداری (embedding) غیرفعال است، پس بازسازیِ ایندکس کاری انجام نمی‌دهد. وقتی embedding واقعی فعال شد، این دکمه کلِ کتابخانه را دوباره ایندکس می‌کند.')
                ->action(function (): void {
                    Notification::make()
                        ->warning()
                        ->title('ایندکسِ برداری فعلاً غیرفعال است')
                        ->body('embedding هنوز فعال نیست، پس چیزی برای بازسازی وجود ندارد.')
                        ->send();
                }),
            CreateAction::make(),
        ];
    }
}
