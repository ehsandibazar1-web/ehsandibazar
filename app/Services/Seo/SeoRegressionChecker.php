<?php

namespace App\Services\Seo;

use DOMDocument;
use DOMXPath;
use Illuminate\Http\Client\Pool;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

/**
 * دروازه‌ی خط قرمز SEO (نگاه کنید به docs/SEO-RED-LINE.md).
 *
 * sitemap.xmlِ سایتِ مرجع (production) را می‌خواند و برای هر URL، نسخه‌ی کاندیدا (استگینگ) را
 * واکشی و مقایسه می‌کند تا هیچ رگرسیونِ SEO از استگینگ به production نرود.
 *
 * منطقِ استخراج/مقایسه از منطقِ شبکه جدا شده تا بدون شبکه هم قابلِ unit-test باشد
 * (extractFromHtml / compareSnapshots / parseSitemapLocs خالص‌اند).
 */
class SeoRegressionChecker
{
    public const CRITICAL = 'critical';   // 🔴 عبور ممنوع

    public const WARNING = 'warning';     // 🟡 بازبینی دستی

    public function __construct(
        private readonly int $timeout = 8,
        private readonly int $connectTimeout = 5,
    ) {}

    /**
     * همه‌ی <loc>های یک sitemap.xml را استخراج می‌کند.
     *
     * @return list<string>
     */
    public function parseSitemapLocs(string $xml): array
    {
        if (! preg_match_all('/<loc>\s*(.*?)\s*<\/loc>/is', $xml, $m)) {
            return [];
        }

        return array_values(array_unique(array_map(
            fn (string $loc): string => html_entity_decode(trim($loc), ENT_QUOTES | ENT_HTML5),
            $m[1],
        )));
    }

    /**
     * فیلدهای SEOِ مهم را از HTMLِ یک صفحه بیرون می‌کشد. خالص و بدون شبکه (تست‌پذیر).
     *
     * @return array{status:int, title:?string, meta_description:?string, canonical:?string, robots:?string, og_title:?string, h1:?string}
     */
    public function extractFromHtml(?string $html, int $status): array
    {
        $base = [
            'status' => $status,
            'title' => null,
            'meta_description' => null,
            'canonical' => null,
            'robots' => null,
            'og_title' => null,
            'h1' => null,
        ];

        if (blank($html)) {
            return $base;
        }

        $doc = new DOMDocument;
        $previous = libxml_use_internal_errors(true);
        // پیشوندِ encoding تا کاراکترهای فارسی درست خوانده شوند
        $doc->loadHTML('<?xml encoding="UTF-8">'.$html);
        libxml_clear_errors();
        libxml_use_internal_errors($previous);

        $xpath = new DOMXPath($doc);

        $text = function (string $query) use ($xpath): ?string {
            $node = $xpath->query($query)->item(0);

            return $node ? $this->normalize($node->textContent) : null;
        };

        $attr = function (string $query) use ($xpath): ?string {
            $node = $xpath->query($query)->item(0);

            return $node ? $this->normalize($node->nodeValue) : null;
        };

        return [
            'status' => $status,
            'title' => $text('//head/title'),
            'meta_description' => $attr('//meta[translate(@name,"DESCRIPTION","description")="description"]/@content'),
            'canonical' => $attr('//link[translate(@rel,"CANONICAL","canonical")="canonical"]/@href'),
            'robots' => $attr('//meta[translate(@name,"ROBOTS","robots")="robots"]/@content'),
            'og_title' => $attr('//meta[@property="og:title"]/@content'),
            'h1' => $text('(//h1)[1]'),
        ];
    }

    /**
     * یک URL را واکشی و snapshotِ SEO‌اش را برمی‌گرداند. status=0 یعنی خطای شبکه.
     */
    public function fetchSnapshot(string $url): array
    {
        try {
            $response = Http::withHeaders(['User-Agent' => 'SeoRegressionChecker/1.0 (+red-line gate)'])
                ->timeout($this->timeout)
                ->retry(1, 500)
                ->get($url);

            return $this->extractFromHtml($response->body(), $response->status());
        } catch (\Throwable $e) {
            return $this->extractFromHtml(null, 0);
        }
    }

    /**
     * دو snapshot (مرجع vs کاندیدا) را مقایسه و فهرستِ مشکلات را با شدت برمی‌گرداند.
     * خالص و تست‌پذیر.
     *
     * @return list<array{field:string, severity:string, base:mixed, candidate:mixed}>
     */
    public function compareSnapshots(array $base, array $candidate): array
    {
        $issues = [];

        $baseOk = $base['status'] >= 200 && $base['status'] < 300;
        $candOk = $candidate['status'] >= 200 && $candidate['status'] < 300;

        // 🔴 URLِ ایندکس‌شده‌ای که در کاندیدا دیگر ۲۰۰ نیست = گم‌شدن/شکستنِ صفحه
        if ($baseOk && ! $candOk) {
            $issues[] = ['field' => 'status', 'severity' => self::CRITICAL, 'base' => $base['status'], 'candidate' => $candidate['status']];

            // وقتی صفحه اصلاً ۲۰۰ نیست، مقایسه‌ی متا بی‌معنی است
            return $issues;
        }

        // اگر مرجع خودش ۲۰۰ نبوده، این URL معیارِ سنجش نیست
        if (! $baseOk) {
            return $issues;
        }

        // 🔴 canonical نباید عوض شود (اشتباهِ canonical رتبه را نابود می‌کند)
        if ($this->differs($base['canonical'], $candidate['canonical'])) {
            $issues[] = ['field' => 'canonical', 'severity' => self::CRITICAL, 'base' => $base['canonical'], 'candidate' => $candidate['canonical']];
        }

        // 🔴 noindex/none تصادفی
        if ($this->gainedNoindex($base['robots'], $candidate['robots'])) {
            $issues[] = ['field' => 'robots', 'severity' => self::CRITICAL, 'base' => $base['robots'], 'candidate' => $candidate['robots']];
        }

        // 🟡 تغییراتِ محتواییِ متا — بازبینی دستی (بهبود مجاز، رگرسیون نه)
        foreach (['title', 'meta_description', 'og_title', 'h1'] as $field) {
            if ($this->differs($base[$field], $candidate[$field])) {
                $issues[] = ['field' => $field, 'severity' => self::WARNING, 'base' => $base[$field], 'candidate' => $candidate[$field]];
            }
        }

        return $issues;
    }

    /**
     * کلِ دیف را اجرا می‌کند: sitemapِ مرجع → واکشیِ هر URL روی مرجع و کاندیدا → مقایسه.
     *
     * @return array{verdict:string, checked:int, critical:int, warning:int, rows:list<array>}
     */
    public function run(string $baseUrl, string $candidateUrl, ?int $limit = null, int $offset = 0): array
    {
        $baseUrl = rtrim($baseUrl, '/');
        $candidateUrl = rtrim($candidateUrl, '/');

        $sitemap = $this->fetchBody($baseUrl.'/sitemap.xml');
        $locs = $this->parseSitemapLocs($sitemap ?? '');

        $slice = array_slice($locs, $offset, $limit);
        $paths = array_map(fn (string $loc): string => $this->pathOf($loc), $slice);

        // همه‌ی URLها (مرجع + کاندیدا) را موازی واکشی می‌کنیم تا یک درخواستِ مرورگر به‌جای
        // صدها واکشیِ پشت‌سرهم، در چند ثانیه تمام شود.
        $urls = [];
        foreach ($paths as $path) {
            $urls[] = $baseUrl.$path;
            $urls[] = $candidateUrl.$path;
        }
        $snaps = $this->fetchMany(array_values(array_unique($urls)));

        $rows = [];
        $critical = 0;
        $warning = 0;

        foreach ($paths as $path) {
            $baseSnap = $snaps[$baseUrl.$path] ?? $this->extractFromHtml(null, 0);
            $candSnap = $snaps[$candidateUrl.$path] ?? $this->extractFromHtml(null, 0);
            $issues = $this->compareSnapshots($baseSnap, $candSnap);

            $rowCritical = count(array_filter($issues, fn ($i) => $i['severity'] === self::CRITICAL));
            $rowWarning = count($issues) - $rowCritical;
            $critical += $rowCritical;
            $warning += $rowWarning;

            $rows[] = [
                'path' => $path,
                'base_status' => $baseSnap['status'],
                'candidate_status' => $candSnap['status'],
                'issues' => $issues,
            ];
        }

        return [
            'verdict' => $critical > 0 ? self::CRITICAL : ($warning > 0 ? self::WARNING : 'ok'),
            'total_urls' => count($locs),
            'checked' => count($slice),
            'offset' => $offset,
            'critical' => $critical,
            'warning' => $warning,
            'rows' => $rows,
        ];
    }

    /**
     * چند URL را به‌صورت موازی (در دسته‌های ۱۰تایی) واکشی می‌کند.
     *
     * @param  list<string>  $urls
     * @return array<string, array> نگاشتِ url → snapshot
     */
    private function fetchMany(array $urls): array
    {
        $out = [];

        foreach (array_chunk($urls, 10) as $chunk) {
            try {
                $responses = Http::pool(fn (Pool $pool) => array_map(
                    fn (string $u) => $pool->as($u)
                        ->withHeaders(['User-Agent' => 'SeoRegressionChecker/1.0 (+red-line gate)'])
                        ->connectTimeout($this->connectTimeout)
                        ->timeout($this->timeout)
                        ->get($u),
                    $chunk,
                ));
            } catch (\Throwable $e) {
                $responses = [];
            }

            foreach ($chunk as $u) {
                $r = $responses[$u] ?? null;
                $out[$u] = $r instanceof Response
                    ? $this->extractFromHtml($r->body(), $r->status())
                    : $this->extractFromHtml(null, 0);
            }
        }

        return $out;
    }

    private function fetchBody(string $url): ?string
    {
        try {
            $response = Http::withHeaders(['User-Agent' => 'SeoRegressionChecker/1.0'])
                ->timeout($this->timeout)
                ->retry(1, 500)
                ->get($url);

            return $response->successful() ? $response->body() : null;
        } catch (\Throwable $e) {
            return null;
        }
    }

    /** مسیر (path + query) را از یک URLِ کامل بیرون می‌کشد تا روی هاستِ کاندیدا سوار شود. */
    private function pathOf(string $url): string
    {
        $parts = parse_url($url);
        $path = $parts['path'] ?? '/';
        if (! empty($parts['query'])) {
            $path .= '?'.$parts['query'];
        }

        return $path;
    }

    private function normalize(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }
        $value = trim(preg_replace('/\s+/u', ' ', $value));

        return $value === '' ? null : $value;
    }

    private function differs(?string $a, ?string $b): bool
    {
        return $this->normalize($a) !== $this->normalize($b);
    }

    /** آیا کاندیدا noindex/none گرفته در حالی که مرجع نداشت؟ */
    private function gainedNoindex(?string $base, ?string $candidate): bool
    {
        $has = fn (?string $v): bool => $v !== null
            && preg_match('/\b(noindex|none)\b/i', $v) === 1;

        return $has($candidate) && ! $has($base);
    }
}
