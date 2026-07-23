<?php

namespace App\Filament\Pages;

use App\Services\Seo\SeoAuditService;
use BackedEnum;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

/**
 * عاملِ محتوا (AI Agent) — نمای «سلامتِ محتوا» فقط-خواندنی. برخلافِ نسخه‌ی انگلیسی که یک عاملِ
 * خودکارِ اعمال‌کننده‌ی fix است (که مقاله‌های زنده را تغییر می‌دهد — خط قرمزِ سئو)، این نسخه هیچ چیزی
 * را خودکار تغییر نمی‌دهد: مشکلاتِ سئو/محتوا را از SeoAuditService می‌گیرد، بر اساسِ «هر محتوا»
 * رتبه‌بندی می‌کند («کدام مقاله بیشترین مشکل را دارد») و ادمین را برای اصلاحِ دستی به ویرایش می‌برد.
 * مکملِ SeoCenter است (که همان داده را «بر اساسِ دسته» مرور می‌کند).
 */
class AiAgentDashboard extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCpuChip;

    protected static string|UnitEnum|null $navigationGroup = 'AI Studio';

    protected static ?int $navigationSort = 12;

    protected static ?string $navigationLabel = 'AI Agent';

    protected static ?string $title = 'عاملِ محتوا';

    protected string $view = 'filament.pages.ai-agent-dashboard';

    /** @var array<string, array<int, array<string, mixed>>> */
    public array $results = [];

    public function mount(): void
    {
        $this->refresh(false);
    }

    public function refresh(bool $notify = true): void
    {
        $this->results = app(SeoAuditService::class)->run();

        if ($notify) {
            Notification::make()->success()->title('بازبینیِ محتوا به‌روزرسانی شد')->send();
        }
    }

    /** برچسبِ فارسیِ دسته‌ها (از SeoCenter). */
    private function categoryLabels(): array
    {
        return SeoCenter::CATEGORIES;
    }

    /**
     * همه‌ی findingها گروه‌بندی‌شده بر اساسِ محتوا (edit_url یکتا)، رتبه‌بندی‌شده بر اساسِ تعدادِ مشکل.
     *
     * @return array<int, array{title:string, type:string, edit_url:?string, count:int, issues:array<int, string>}>
     */
    public function getContentHealthProperty(): array
    {
        $labels = $this->categoryLabels();
        $byContent = [];

        foreach ($this->results as $category => $findings) {
            foreach ($findings as $f) {
                $key = ($f['edit_url'] ?? '').'|'.($f['type'] ?? '').'|'.($f['title'] ?? '');
                $byContent[$key] ??= [
                    'title' => $f['title'] ?? '—',
                    'type' => $f['type'] ?? '',
                    'edit_url' => $f['edit_url'] ?? null,
                    'count' => 0,
                    'issues' => [],
                ];
                $byContent[$key]['count']++;
                $byContent[$key]['issues'][] = ($labels[$category] ?? $category).' — '.($f['detail'] ?? '');
            }
        }

        usort($byContent, fn (array $a, array $b): int => $b['count'] <=> $a['count']);

        return array_values($byContent);
    }

    public function getTotalIssuesProperty(): int
    {
        return array_sum(array_map('count', $this->results));
    }

    public function getAffectedCountProperty(): int
    {
        return count($this->contentHealth);
    }
}
