<?php

namespace App\Filament\Resources\Newsletter\Pages;

use App\Filament\Resources\Newsletter\NewsletterResource;
use App\Model\NewsLatters;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ListSubscribers extends ListRecords
{
    protected static string $resource = NewsletterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),

            Action::make('export')
                ->label('خروجی CSV')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('gray')
                ->action(fn (): StreamedResponse => $this->exportCsv()),
        ];
    }

    private function exportCsv(): StreamedResponse
    {
        $filename = 'newsletter-'.date('Ymd-His').'.csv';

        return response()->streamDownload(function (): void {
            $out = fopen('php://output', 'w');
            // BOM تا اکسل یونیکد را درست بخواند (نام‌های فارسی).
            fwrite($out, "\xEF\xBB\xBF");
            fputcsv($out, ['email', 'name', 'mobile', 'subscribed_at']);

            NewsLatters::query()
                ->orderByDesc('id')
                ->chunk(500, function ($rows) use ($out): void {
                    foreach ($rows as $r) {
                        fputcsv($out, [
                            (string) $r->email,
                            (string) $r->name,
                            (string) $r->mobile,
                            (string) $r->created_at,
                        ]);
                    }
                });

            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv']);
    }
}
