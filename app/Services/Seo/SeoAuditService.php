<?php

namespace App\Services\Seo;

use App\Filament\Resources\Articles\ArticleResource;
use App\Filament\Resources\Pages\PageResource;
use App\Model\Article;
use App\Model\Page;
use App\Model\Seo;
use Illuminate\Support\Collection;

/**
 * موتور بررسیِ سئو (SEO Center) — روی داده‌های همین اپ (مقاله/صفحه) کار می‌کند و هیچ پیاده‌سازیِ سئوی
 * موجود (SeoController، تگ‌های master.blade، جدولِ Seo) را جایگزین نمی‌کند؛ فقط برای گزارش‌دهی می‌خواند.
 * کاملاً فقط-خواندنی. خودکفاست (بدونِ وابستگی به InternalLinkSuggester، تا حلقه‌ی DI ایجاد نشود).
 */
class SeoAuditService
{
    /**
     * تمام بررسی‌های سریع (بدونِ تماسِ شبکه‌ای) — برای اجرای خودکار در بارگذاریِ SEO Center.
     * خروجی: [category => finding[]] که هر finding کلیدهای category/type/locale/title/detail/edit_url دارد.
     *
     * @return array<string, array<int, array<string, mixed>>>
     */
    public function run(): array
    {
        $rows = $this->contentRows();

        return [
            'missing_titles' => $this->missingTitles($rows),
            'missing_descriptions' => $this->missingDescriptions($rows),
            // canonical به‌صورتِ خودکار از layout (master.blade) تولید می‌شود، پس این دسته انتظاراً ۰ است.
            'missing_canonicals' => [],
            // بررسیِ ALT/schema سطحِ قالب است و با ویرایشِ یک رکورد رفع نمی‌شود — فعلاً خالی.
            'missing_alt' => [],
            'untranslated_alt' => [],
            'missing_schema' => [],
            'duplicate_titles' => $this->duplicateTitles($rows),
            'duplicate_descriptions' => $this->duplicateDescriptions($rows),
            'broken_internal_links' => $this->auditBrokenInternalLinks(),
            'orphan_pages' => $this->auditOrphans(),
        ];
    }

    // ============================ متادیتای محتوا ============================

    /**
     * مقاله/صفحه‌های منتشرشده با فیلدهای سئو + عنوان/توضیحِ legacyِ جدولِ Seo.
     *
     * @return array<int, array<string, mixed>>
     */
    private function contentRows(): array
    {
        $seoMap = [];
        foreach (Seo::query()->whereIn('seoable_type', ['article', 'page'])->get(['seoable_type', 'seoable_id', 'title', 'description']) as $s) {
            $seoMap[$s->seoable_type.':'.$s->seoable_id] = $s;
        }

        $rows = [];

        foreach (Article::query()->where('status', 1)->get(['id', 'title', 'lang', 'seo_title', 'meta_description']) as $a) {
            $legacy = $seoMap['article:'.$a->id] ?? null;
            $rows[] = [
                'type' => 'article',
                'id' => (int) $a->id,
                'locale' => (string) ($a->lang ?? ''),
                'title' => (string) $a->title,
                'seo_title' => trim((string) ($a->seo_title ?? '')),
                'meta_description' => trim((string) ($a->meta_description ?? '')),
                'legacy_title' => trim((string) ($legacy->title ?? '')),
                'legacy_desc' => trim((string) ($legacy->description ?? '')),
            ];
        }

        foreach (Page::query()->where('status', 1)->get(['id', 'title', 'lang', 'seo_title', 'meta_description']) as $p) {
            $legacy = $seoMap['page:'.$p->id] ?? null;
            $rows[] = [
                'type' => 'page',
                'id' => (int) $p->id,
                'locale' => (string) ($p->lang ?? ''),
                'title' => (string) $p->title,
                'seo_title' => trim((string) ($p->seo_title ?? '')),
                'meta_description' => trim((string) ($p->meta_description ?? '')),
                'legacy_title' => trim((string) ($legacy->title ?? '')),
                'legacy_desc' => trim((string) ($legacy->description ?? '')),
            ];
        }

        return $rows;
    }

    /** @param array<int, array<string, mixed>> $rows */
    private function missingTitles(array $rows): array
    {
        $out = [];
        foreach ($rows as $r) {
            if ($r['seo_title'] === '' && $r['legacy_title'] === '') {
                $out[] = $this->finding('missing_titles', $r, 'بدونِ عنوانِ متای اختصاصی — عنوانِ محتوا به‌عنوان جایگزین استفاده می‌شود. افزودنِ seo_title توصیه می‌شود.');
            }
        }

        return $out;
    }

    /** @param array<int, array<string, mixed>> $rows */
    private function missingDescriptions(array $rows): array
    {
        $out = [];
        foreach ($rows as $r) {
            if ($r['meta_description'] === '' && $r['legacy_desc'] === '') {
                $out[] = $this->finding('missing_descriptions', $r, 'بدونِ توضیحاتِ متا (meta description).');
            }
        }

        return $out;
    }

    /** @param array<int, array<string, mixed>> $rows */
    private function duplicateTitles(array $rows): array
    {
        $groups = [];
        foreach ($rows as $r) {
            $key = mb_strtolower(trim($r['title']));
            if ($key === '') {
                continue;
            }
            $groups[$key][] = $r;
        }

        $out = [];
        foreach ($groups as $group) {
            if (count($group) < 2) {
                continue;
            }
            foreach ($group as $r) {
                $out[] = $this->finding('duplicate_titles', $r, count($group).' مورد عنوانِ یکسان دارند.');
            }
        }

        return $out;
    }

    /** @param array<int, array<string, mixed>> $rows */
    private function duplicateDescriptions(array $rows): array
    {
        $groups = [];
        foreach ($rows as $r) {
            $desc = $r['meta_description'] !== '' ? $r['meta_description'] : $r['legacy_desc'];
            $key = mb_strtolower(trim($desc));
            if ($key === '') {
                continue;
            }
            $groups[$key][] = $r;
        }

        $out = [];
        foreach ($groups as $group) {
            if (count($group) < 2) {
                continue;
            }
            foreach ($group as $r) {
                $out[] = $this->finding('duplicate_descriptions', $r, count($group).' مورد توضیحاتِ متای یکسان دارند.');
            }
        }

        return $out;
    }

    // ============================ لینک‌ها ============================

    /**
     * لینک‌های داخلیِ شکسته: hrefهایی که به /article|page/{slug}ِ منتشرنشده اشاره می‌کنند.
     *
     * @return array<int, array<string, mixed>>
     */
    private function auditBrokenInternalLinks(): array
    {
        $items = $this->collectContentItems();

        $valid = ['article' => [], 'page' => []];
        foreach ($items as $it) {
            $valid[$it['type']][rawurldecode($it['slug'])] = true;
        }

        $out = [];
        foreach ($items as $source) {
            if (! preg_match_all('/href\s*=\s*("|\')(.*?)\1/i', $source['body'], $hrefs)) {
                continue;
            }

            $seen = [];
            foreach ($hrefs[2] as $href) {
                if (! preg_match('~/(article|page)/([^/?#"\'\s]+)~u', $href, $m)) {
                    continue;
                }
                $targetType = $m[1];
                $slug = rawurldecode($m[2]);

                if (isset($valid[$targetType][$slug])) {
                    continue;
                }

                $key = $targetType.'|'.$slug;
                if (isset($seen[$key])) {
                    continue;
                }
                $seen[$key] = true;

                $out[] = [
                    'category' => 'broken_internal_links',
                    'type' => $source['type'],
                    'locale' => $source['locale'] ?? '',
                    'title' => $source['title'],
                    'detail' => 'لینکِ شکسته به: /'.$targetType.'/'.$slug,
                    'edit_url' => $this->editUrl($source['type'], $source['id']),
                ];
            }
        }

        return $out;
    }

    /**
     * محتوای یتیم: بدونِ لینکِ داخلیِ ورودی از بدنه‌ی محتوای دیگر (روی دیتابیسِ کامل معنادار است).
     *
     * @return array<int, array<string, mixed>>
     */
    private function auditOrphans(): array
    {
        $items = $this->collectContentItems();

        $out = [];
        foreach ($items as $target) {
            $hasIncoming = false;
            foreach ($items as $source) {
                if ($source['id'] === $target['id'] && $source['type'] === $target['type']) {
                    continue;
                }
                if (mb_stripos($source['body'], $target['url']) !== false) {
                    $hasIncoming = true;
                    break;
                }
            }

            if (! $hasIncoming) {
                $out[] = [
                    'category' => 'orphan_pages',
                    'type' => $target['type'],
                    'locale' => $target['locale'] ?? '',
                    'title' => $target['title'],
                    'detail' => 'هیچ لینکِ داخلیِ ورودی از بدنه‌ی محتوای دیگر ندارد.',
                    'edit_url' => $this->editUrl($target['type'], $target['id']),
                ];
            }
        }

        return $out;
    }

    /** @param array<string, mixed> $row */
    private function finding(string $category, array $row, string $detail): array
    {
        return [
            'category' => $category,
            'type' => $row['type'],
            'locale' => $row['locale'] ?? '',
            'title' => $row['title'],
            'detail' => $detail,
            'edit_url' => $this->editUrl($row['type'], (int) $row['id']),
        ];
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

    /**
     * لینک‌های خارجیِ یکتای یافت‌شده در محتوا — فعلاً خالی (نیازمندِ HtmlContentScanner، بعداً).
     *
     * @return Collection<int, array{url: string, sources: array<int, array<string, mixed>>}>
     */
    public function externalLinkTargets(): Collection
    {
        return collect();
    }

    /**
     * بررسیِ واقعیِ لینک‌های خارجی از طریق HTTP — فعلاً خالی (اسکن دستی، بعداً).
     *
     * @return array<int, array<string, mixed>>
     */
    public function checkExternalLinks(): array
    {
        return [];
    }

    /**
     * بررسیِ در دسترس بودنِ فهرستی از URLها — این نسخه هیچ‌کدام را «خراب» نمی‌داند تا پیشنهادهای لینکِ
     * خارجیِ دستیارِ محتوا بدونِ تماسِ شبکه‌ای نمایش داده شوند.
     *
     * @param  array<int, string>  $urls
     * @return array<string, array{broken: bool, status: int|null}>
     */
    public function checkUrls(array $urls): array
    {
        $result = [];
        foreach ($urls as $url) {
            $result[$url] = ['broken' => false, 'status' => null];
        }

        return $result;
    }

    /**
     * محتوای منتشرشده (مقاله + صفحه) به یک شکلِ یکسان. فقط-خواندنی. url نسبی (مستقل از دامنه).
     *
     * @return Collection<int, array{id:int, type:string, title:string, slug:string, url:string, body:string, locale:string}>
     */
    public function collectContentItems(): Collection
    {
        $articles = Article::query()
            ->where('status', 1)
            ->get(['id', 'title', 'slug', 'body', 'lang'])
            ->map(fn (Article $a): array => [
                'id' => (int) $a->id,
                'type' => 'article',
                'title' => (string) $a->title,
                'slug' => (string) $a->slug,
                'url' => '/article/'.$a->slug,
                'body' => (string) $a->body,
                'locale' => (string) ($a->lang ?? ''),
            ]);

        $pages = Page::query()
            ->where('status', 1)
            ->get(['id', 'title', 'slug', 'body', 'lang'])
            ->map(fn (Page $p): array => [
                'id' => (int) $p->id,
                'type' => 'page',
                'title' => (string) $p->title,
                'slug' => (string) $p->slug,
                'url' => '/page/'.$p->slug,
                'body' => (string) $p->body,
                'locale' => (string) ($p->lang ?? ''),
            ]);

        return $articles->concat($pages)->values();
    }

    /**
     * منابعِ لینک برای فهرستِ محتوا — فعلاً خالی.
     *
     * @return array<int, array<string, mixed>>
     */
    public function allLinkSources(Collection $items): array
    {
        return [];
    }
}
