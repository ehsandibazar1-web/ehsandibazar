<?php

namespace App\Filament\Pages;

use App\Filament\Resources\Articles\ArticleResource;
use App\Filament\Resources\Pages\PageResource;
use App\Model\Article;
use App\Model\Page as ContentPage;
use App\Services\Seo\InternalLinkApplier;
use App\Services\Seo\InternalLinkSuggester;
use BackedEnum;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Model;
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

    protected static string|UnitEnum|null $navigationGroup = null;

    protected static ?int $navigationSort = 6;

    protected static ?string $navigationLabel = 'Internal Linking';

    protected static ?string $title = 'مرکزِ لینک‌سازیِ داخلی';

    protected string $view = 'filament.pages.ai-internal-linking';

    /** @var Collection<int, array<string, mixed>>|null */
    private ?Collection $cached = null;

    /**
     * پیشنهادِ در انتظارِ تأیید (پیش‌نمایشِ قبل/بعد). null یعنی هیچ پیش‌نمایشی باز نیست.
     *
     * @var array<string, mixed>|null
     */
    public ?array $pending = null;

    /** @return Collection<int, array<string, mixed>> */
    private function items(): Collection
    {
        return $this->cached ??= app(InternalLinkSuggester::class)->suggestions(300);
    }

    /**
     * پیش‌نمایشِ اعمالِ یک پیشنهاد (بر اساسِ اندیسِ پایدار در فهرستِ مسطح). چیزی ذخیره نمی‌کند.
     */
    public function preview(int $index): void
    {
        $s = $this->items()->values()->get($index);

        if (! $s) {
            return;
        }

        $model = $this->resolveModel($s['source_type'], $s['source_id']);

        if (! $model) {
            Notification::make()->danger()->title('محتوای مبدأ پیدا نشد')->send();

            return;
        }

        $plan = app(InternalLinkApplier::class)->plan((string) $model->body, $s['anchor'], $s['target_url']);

        if (! $plan) {
            Notification::make()->warning()
                ->title('اعمالِ خودکار ممکن نیست')
                ->body('عبارت به‌صورتِ متنِ سادهٔ یکپارچه در بدنه نیست (شاید داخلِ تگ/لینکِ دیگری است). لطفاً دستی اعمال کنید.')
                ->send();

            return;
        }

        $this->pending = [
            'index' => $index,
            'source_type' => $s['source_type'],
            'source_id' => $s['source_id'],
            'source_title' => $s['source_title'],
            'anchor' => $s['anchor'],
            'target_url' => $s['target_url'],
            'preview_before' => $plan['preview_before'],
            'preview_after' => $plan['preview_after'],
        ];
    }

    public function cancelPreview(): void
    {
        $this->pending = null;
    }

    /**
     * اعمالِ نهاییِ پیشنهادِ در انتظار: بدنه را با نسخهٔ لینک‌دار به‌روزرسانی می‌کند (از طریقِ Eloquent
     * تا در صورتِ فعال‌بودنِ activitylog ثبت شود). فقط همان یک عبارت تغییر می‌کند.
     */
    public function confirmApply(): void
    {
        if (! $this->pending) {
            return;
        }

        $p = $this->pending;
        $model = $this->resolveModel($p['source_type'], $p['source_id']);

        if (! $model) {
            $this->pending = null;
            Notification::make()->danger()->title('محتوای مبدأ پیدا نشد')->send();

            return;
        }

        $newBody = app(InternalLinkApplier::class)->buildLinkedBody((string) $model->body, $p['anchor'], $p['target_url']);

        if ($newBody === null) {
            $this->pending = null;
            Notification::make()->warning()->title('عبارت دیگر در بدنه پیدا نشد')->body('شاید بدنه بین پیش‌نمایش و تأیید تغییر کرده. دوباره تلاش کنید.')->send();

            return;
        }

        $model->update(['body' => $newBody]);

        Notification::make()->success()
            ->title('لینکِ داخلی اضافه شد')
            ->body('«'.$p['anchor'].'» در «'.$p['source_title'].'» لینک شد.')
            ->send();

        $this->pending = null;
        $this->cached = null; // بازمحاسبهٔ پیشنهادها (این مورد دیگر نباید بیاید)
    }

    private function resolveModel(string $type, int $id): ?Model
    {
        return $type === 'article' ? Article::find($id) : ContentPage::find($id);
    }

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
        return $this->items()
            ->values()
            // اندیسِ پایدارِ مسطح را نگه می‌داریم تا دکمهٔ «اعمال» بتواند دقیقاً همان پیشنهاد را بیابد.
            ->map(fn (array $s, int $i): array => $s + ['index' => $i])
            ->groupBy(fn (array $s): string => $s['source_type'].':'.$s['source_id'])
            ->map(function (Collection $group): array {
                $first = $group->first();

                return [
                    'source_title' => $first['source_title'],
                    'source_type' => $first['source_type'],
                    'edit_url' => $this->editUrl($first['source_type'], $first['source_id']),
                    'view_url' => $first['source_url'],
                    'links' => $group->map(fn (array $s): array => [
                        'index' => $s['index'],
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
        return $this->items()->count();
    }

    /**
     * محتوای یتیم (بدونِ لینکِ ورودی) همراه با لینکِ ویرایش/نمایش.
     *
     * @return array<int, array{title:string, type:string, url:string, edit_url:?string}>
     */
    public function getOrphansProperty(): array
    {
        return app(InternalLinkSuggester::class)->orphans()
            ->map(fn (array $o): array => [
                'title' => $o['title'],
                'type' => $o['type'],
                'url' => $o['url'],
                'edit_url' => $this->editUrl($o['type'], $o['id']),
            ])
            ->all();
    }

    /**
     * لینک‌های داخلیِ شکسته همراه با لینکِ ویرایشِ محتوای مبدأ.
     *
     * @return array<int, array{source_title:string, source_type:string, target_type:string, target_slug:string, edit_url:?string}>
     */
    public function getBrokenLinksProperty(): array
    {
        return app(InternalLinkSuggester::class)->brokenInternalLinks()
            ->map(fn (array $b): array => [
                'source_title' => $b['source_title'],
                'source_type' => $b['source_type'],
                'target_type' => $b['target_type'],
                'target_slug' => $b['target_slug'],
                'edit_url' => $this->editUrl($b['source_type'], $b['source_id']),
            ])
            ->all();
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
