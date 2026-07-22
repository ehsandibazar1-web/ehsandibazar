<?php

namespace App\Services\Seo;

use Illuminate\Support\Collection;

/**
 * Stubِ موقتِ موتور بررسی سئو — همان امضاهای عمومیِ نسخه‌ی کاملِ سایت انگلیسی را دارد ولی خروجیِ
 * خنثی/خالی می‌دهد. نسخه‌ی کامل به زنجیره‌ی سنگینی (HtmlContentScanner / InternalLinkResolver /
 * SiteSetting / منابع محتوا) وابسته است که در موجِ سئو منتقل می‌شود. تنها متدی که دستیار محتوا
 * فعلاً واقعاً صدا می‌زند checkUrls() است (برای تأییدِ لینک‌های خارجیِ پیشنهادی) — این stub هیچ
 * URLی را «خراب» علامت نمی‌زند تا رفتار پنل شکسته نشود. forward-compatible: با نسخه‌ی کامل جایگزین می‌شود.
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
     * نرمال‌سازیِ مقاله/صفحه به یک شکل یکسان — فعلاً خالی.
     *
     * @return Collection<int, array<string, mixed>>
     */
    public function collectContentItems(): Collection
    {
        return collect();
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
