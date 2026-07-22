<?php

namespace App\Services\Seo;

use App\Model\Article;
use App\Model\Page;
use Illuminate\Support\Collection;

/**
 * موتور بررسیِ سئو — بخشی از زنجیره‌ی موج ۵e. collectContentItems() اکنون واقعی است و محتوای
 * منتشرشده (مقاله/صفحه) را به یک شکلِ یکسان نرمال می‌کند تا InternalLinkSuggester رویش کار کند.
 * checkUrls() برای تأییدِ لینک‌های خارجیِ پیشنهادیِ دستیار به‌کار می‌رود و هیچ URLی را بدونِ تماسِ
 * واقعی «خراب» علامت نمی‌زند. متدهای externalLinkTargets/checkExternalLinks/allLinkSources هنوز
 * خنثی‌اند (بررسیِ لینکِ خارجی جداگانه منتقل می‌شود). هیچ‌کدام محتوای زنده را تغییر نمی‌دهند.
 */
class SeoAuditService
{
    /**
     * تمام بررسی‌های سریع — فعلاً خالی.
     *
     * @return array<string, array<int, array<string, mixed>>>
     */
    public function run(): array
    {
        return [];
    }

    /**
     * لینک‌های خارجیِ یکتای یافت‌شده در محتوا — فعلاً خالی.
     *
     * @return Collection<int, array{url: string, sources: array<int, array<string, mixed>>}>
     */
    public function externalLinkTargets(): Collection
    {
        return collect();
    }

    /**
     * بررسی واقعی لینک‌های خارجی از طریق HTTP — فعلاً خالی.
     *
     * @return array<int, array<string, mixed>>
     */
    public function checkExternalLinks(): array
    {
        return [];
    }

    /**
     * بررسی در دسترس بودن فهرستی از URLها — نسخه‌ی کامل HEAD می‌زند؛ این stub هیچ‌کدام را خراب
     * نمی‌داند (broken=false) تا پیشنهادهای لینک خارجیِ دستیار محتوا بدون تماس شبکه‌ای نمایش داده شوند.
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
     * محتوای منتشرشده (مقاله + صفحه) را به یک شکلِ یکسان نرمال می‌کند. فقط-خواندنی؛ هیچ چیزی را
     * ذخیره/تغییر نمی‌دهد. url به‌صورتِ نسبی (/article/... , /page/...) تولید می‌شود تا مستقل از
     * دامنه (staging/production) باشد.
     *
     * @return Collection<int, array{id:int, type:string, title:string, slug:string, url:string, body:string}>
     */
    public function collectContentItems(): Collection
    {
        $articles = Article::query()
            ->where('status', 1)
            ->get(['id', 'title', 'slug', 'body'])
            ->map(fn (Article $a): array => [
                'id' => (int) $a->id,
                'type' => 'article',
                'title' => (string) $a->title,
                'slug' => (string) $a->slug,
                'url' => '/article/'.$a->slug,
                'body' => (string) $a->body,
            ]);

        $pages = Page::query()
            ->where('status', 1)
            ->get(['id', 'title', 'slug', 'body'])
            ->map(fn (Page $p): array => [
                'id' => (int) $p->id,
                'type' => 'page',
                'title' => (string) $p->title,
                'slug' => (string) $p->slug,
                'url' => '/page/'.$p->slug,
                'body' => (string) $p->body,
            ]);

        return $articles->concat($pages)->values();
    }

    /**
     * منابع لینک برای فهرست محتوا — فعلاً خالی.
     *
     * @return array<int, array<string, mixed>>
     */
    public function allLinkSources(Collection $items): array
    {
        return [];
    }
}
