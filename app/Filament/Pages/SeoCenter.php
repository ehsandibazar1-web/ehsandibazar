<?php

namespace App\Filament\Pages;

use App\Services\Seo\SeoAuditService;
use BackedEnum;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\StreamedResponse;
use UnitEnum;

/**
 * مرکزِ سئو — داشبوردِ فقط-خواندنیِ ممیزیِ سئو. پورتِ «SEO Center» سایتِ انگلیسی، روی
 * App\Services\Seo\SeoAuditService (که run() آن اکنون واقعی است). هیچ محتوایی را تغییر نمی‌دهد.
 */
class SeoCenter extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMagnifyingGlassCircle;

    protected static string|UnitEnum|null $navigationGroup = null;

    protected static ?int $navigationSort = 9;

    protected static ?string $navigationLabel = 'SEO Center';

    protected static ?string $title = 'مرکزِ سئو';

    protected string $view = 'filament.pages.seo-center';

    /** برچسبِ هر دسته (ترتیبِ نمایش در سایدبار). */
    public const CATEGORIES = [
        'missing_titles' => 'عنوانِ متای غایب',
        'missing_descriptions' => 'توضیحاتِ متای غایب',
        'missing_canonicals' => 'Canonicalِ غایب',
        'missing_alt' => 'متنِ ALTِ غایب',
        'untranslated_alt' => 'ALTِ ترجمه‌نشده',
        'missing_schema' => 'اسکیمای غایب',
        'duplicate_titles' => 'عنوان‌های تکراری',
        'duplicate_descriptions' => 'توضیحاتِ تکراری',
        'broken_internal_links' => 'لینک‌های داخلیِ شکسته',
        'broken_external_links' => 'لینک‌های خارجیِ شکسته',
        'orphan_pages' => 'صفحاتِ یتیم',
    ];

    /** @var array<string, array<int, array<string, mixed>>> */
    public array $results = [];

    /** @var array<int, array<string, mixed>> */
    public array $externalLinkFindings = [];

    public bool $hasScannedExternalLinks = false;

    public string $activeCategory = 'missing_titles';

    public string $localeFilter = 'all';

    public string $typeFilter = 'all';

    public string $search = '';

    public function mount(): void
    {
        $this->runAudit(false);
    }

    public function runAudit(bool $notify = true): void
    {
        $this->results = app(SeoAuditService::class)->run();

        if ($notify) {
            Notification::make()->success()->title('ممیزیِ سئو به‌روزرسانی شد')->send();
        }
    }

    // عمداً دستی — تماس‌های HTTP واقعی به سایت‌های بیرونی می‌زند و کند است.
    public function scanExternalLinks(): void
    {
        $this->externalLinkFindings = app(SeoAuditService::class)->checkExternalLinks();
        $this->hasScannedExternalLinks = true;

        Notification::make()->success()
            ->title(count($this->externalLinkFindings).' لینکِ خارجیِ شکسته یافت شد')
            ->send();
    }

    public function setCategory(string $category): void
    {
        $this->activeCategory = $category;
        $this->typeFilter = 'all';
        $this->search = '';
    }

    public function getCategoryCountsProperty(): array
    {
        $counts = [];
        foreach (self::CATEGORIES as $key => $label) {
            $counts[$key] = $key === 'broken_external_links'
                ? count($this->externalLinkFindings)
                : count($this->results[$key] ?? []);
        }

        return $counts;
    }

    public function getTotalIssuesProperty(): int
    {
        return array_sum($this->categoryCounts);
    }

    private function rawFindingsForCategory(string $category): array
    {
        return $category === 'broken_external_links'
            ? $this->externalLinkFindings
            : ($this->results[$category] ?? []);
    }

    public function getFilteredFindingsProperty(): Collection
    {
        return $this->applyFilters(collect($this->rawFindingsForCategory($this->activeCategory)));
    }

    private function applyFilters(Collection $findings): Collection
    {
        return $findings
            ->when($this->localeFilter !== 'all', fn (Collection $c) => $c->filter(fn ($f) => ($f['locale'] ?? '') === $this->localeFilter))
            ->when($this->typeFilter !== 'all', fn (Collection $c) => $c->filter(fn ($f) => ($f['type'] ?? '') === $this->typeFilter))
            ->when($this->search !== '', function (Collection $c) {
                $needle = mb_strtolower($this->search);

                return $c->filter(fn ($f) => str_contains(mb_strtolower(($f['title'] ?? '').' '.($f['detail'] ?? '')), $needle));
            })
            ->values();
    }

    public function getAvailableTypesProperty(): array
    {
        return collect($this->rawFindingsForCategory($this->activeCategory))
            ->pluck('type')->unique()->filter()->sort()->values()->all();
    }

    public function exportCategoryCsv(): StreamedResponse
    {
        return $this->streamCsv(
            $this->filteredFindings,
            'seo-audit-'.$this->activeCategory.'-'.now()->format('Ymd-His').'.csv'
        );
    }

    public function exportFullReportCsv(): StreamedResponse
    {
        $all = collect(self::CATEGORIES)->keys()
            ->flatMap(fn ($category) => $this->rawFindingsForCategory($category));

        return $this->streamCsv($all, 'seo-audit-full-report-'.now()->format('Ymd-His').'.csv');
    }

    private function streamCsv(Collection $rows, string $filename): StreamedResponse
    {
        return response()->streamDownload(function () use ($rows) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF"); // BOM برای اکسلِ فارسی
            fputcsv($out, ['Category', 'Type', 'Locale', 'Item', 'Issue', 'Edit URL']);

            foreach ($rows as $row) {
                fputcsv($out, [
                    self::CATEGORIES[$row['category']] ?? ($row['category'] ?? ''),
                    $row['type'] ?? '',
                    ($row['locale'] ?? '') ? strtoupper($row['locale']) : '—',
                    $row['title'] ?? '',
                    $row['detail'] ?? '',
                    $row['edit_url'] ?? '',
                ]);
            }

            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }
}
