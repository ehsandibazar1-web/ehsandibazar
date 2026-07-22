<?php

namespace App\Filament\Pages;

use App\Filament\Resources\Articles\ArticleResource;
use App\Filament\Resources\Pages\PageResource;
use App\Services\Seo\InternalLinkSuggester;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Collection;
use UnitEnum;

/**
 * مرکزِ لینک‌سازیِ داخلی (موج ۵e) — فرصت‌های لینکِ داخلی را نمایش می‌دهد: جاهایی که یک محتوا عنوانِ
 * محتوای دیگری را ذکر کرده اما به آن لینک نداده است. کاملاً فقط-خواندنی؛ ادمین لینکِ آماده را کپی و
 * دستی در ویرایشگر اعمال می‌کند. هیچ بدنه‌ای خودکار تغییر نمی‌کند (خط قرمزِ سئو رعایت می‌شود).
 */
class AiInternalLinking extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedLink;

    protected static string|UnitEnum|null $navigationGroup = 'AI Studio';

    protected static ?int $navigationSort = 5;

    protected static ?string $navigationLabel = 'Internal Linking';

    protected static ?string $title = 'مرکزِ لینک‌سازیِ داخلی';

    protected string $view = 'filament.pages.ai-internal-linking';

    /** @var Collection<int, array<string, mixed>>|null */
    private ?Collection $cached = null;

    /**
     * پیشنهادها گروه‌بندی‌شده بر اساسِ محتوای مبدأ، همراه با لینکِ ویرایشِ همان محتوا.
     *
     * @return array<int, array{
     *     source_title:string, source_type:string, edit_url:?string, view_url:string,
     *     links: array<int, array{anchor:string, target_url:string, link_html:string, target_type:string}>
     * }>
     */
    public function getGroupedSuggestionsProperty(): array
    {
        $this->cached ??= app(InternalLinkSuggester::class)->suggestions(300);

        return $this->cached
            ->groupBy(fn (array $s): string => $s['source_type'].':'.$s['source_id'])
            ->map(function (Collection $group): array {
                $first = $group->first();

                return [
                    'source_title' => $first['source_title'],
                    'source_type' => $first['source_type'],
                    'edit_url' => $this->editUrl($first['source_type'], $first['source_id']),
                    'view_url' => $first['source_url'],
                    'links' => $group->map(fn (array $s): array => [
                        'anchor' => $s['anchor'],
                        'target_url' => $s['target_url'],
                        'link_html' => $s['link_html'],
                        'target_type' => $s['target_type'],
                    ])->values()->all(),
                ];
            })
            ->values()
            ->all();
    }

    public function getTotalProperty(): int
    {
        $this->cached ??= app(InternalLinkSuggester::class)->suggestions(300);

        return $this->cached->count();
    }

    private function editUrl(string $type, int $id): ?string
    {
        try {
            return $type === 'article'
                ? ArticleResource::getUrl('edit', ['record' => $id])
                : PageResource::getUrl('edit', ['record' => $id]);
        } catch (\Throwable) {
            return null;
        }
    }
}
