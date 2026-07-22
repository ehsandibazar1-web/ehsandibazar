<?php

namespace Tests\Unit;

use App\Services\Seo\SeoRegressionChecker;
use Tests\TestCase;

/**
 * تستِ منطقِ خالصِ دروازه‌ی خط قرمز SEO (بدون شبکه) — parse/extract/compare.
 */
class SeoRegressionCheckerTest extends TestCase
{
    private SeoRegressionChecker $checker;

    protected function setUp(): void
    {
        parent::setUp();
        $this->checker = new SeoRegressionChecker;
    }

    public function test_parses_sitemap_locs(): void
    {
        $xml = '<urlset><url><loc>https://ehsandibazar.com/article/a</loc></url>'
            .'<url><loc> https://ehsandibazar.com/page/b </loc></url>'
            .'<url><loc>https://ehsandibazar.com/article/a</loc></url></urlset>';

        $locs = $this->checker->parseSitemapLocs($xml);

        $this->assertSame([
            'https://ehsandibazar.com/article/a',
            'https://ehsandibazar.com/page/b',
        ], $locs);
    }

    public function test_extracts_seo_fields_from_html(): void
    {
        $html = '<html><head><title>عنوان تست</title>'
            .'<meta name="description" content="توضیح متا">'
            .'<link rel="canonical" href="https://ehsandibazar.com/x">'
            .'<meta name="robots" content="index,follow">'
            .'<meta property="og:title" content="OG عنوان">'
            .'</head><body><h1>سرتیتر</h1></body></html>';

        $snap = $this->checker->extractFromHtml($html, 200);

        $this->assertSame('عنوان تست', $snap['title']);
        $this->assertSame('توضیح متا', $snap['meta_description']);
        $this->assertSame('https://ehsandibazar.com/x', $snap['canonical']);
        $this->assertSame('index,follow', $snap['robots']);
        $this->assertSame('OG عنوان', $snap['og_title']);
        $this->assertSame('سرتیتر', $snap['h1']);
    }

    public function test_identical_pages_have_no_issues(): void
    {
        $snap = $this->checker->extractFromHtml('<title>یکسان</title>', 200);

        $this->assertSame([], $this->checker->compareSnapshots($snap, $snap));
    }

    public function test_lost_url_is_critical(): void
    {
        $base = $this->checker->extractFromHtml('<title>x</title>', 200);
        $cand = $this->checker->extractFromHtml(null, 404);

        $issues = $this->checker->compareSnapshots($base, $cand);

        $this->assertCount(1, $issues);
        $this->assertSame('status', $issues[0]['field']);
        $this->assertSame(SeoRegressionChecker::CRITICAL, $issues[0]['severity']);
    }

    public function test_changed_canonical_is_critical(): void
    {
        $base = $this->checker->extractFromHtml('<link rel="canonical" href="https://a.com/x">', 200);
        $cand = $this->checker->extractFromHtml('<link rel="canonical" href="https://a.com/y">', 200);

        $issues = $this->checker->compareSnapshots($base, $cand);

        $this->assertContains('canonical', array_column($issues, 'field'));
        $canonical = collect($issues)->firstWhere('field', 'canonical');
        $this->assertSame(SeoRegressionChecker::CRITICAL, $canonical['severity']);
    }

    public function test_new_noindex_is_critical_but_removing_it_is_not(): void
    {
        $indexable = $this->checker->extractFromHtml('<meta name="robots" content="index,follow">', 200);
        $noindex = $this->checker->extractFromHtml('<meta name="robots" content="noindex">', 200);

        // مرجعِ قابل‌ایندکس → کاندیدای noindex = بحرانی
        $gained = $this->checker->compareSnapshots($indexable, $noindex);
        $robots = collect($gained)->firstWhere('field', 'robots');
        $this->assertNotNull($robots);
        $this->assertSame(SeoRegressionChecker::CRITICAL, $robots['severity']);

        // مرجعِ noindex → کاندیدای قابل‌ایندکس = بحرانی نیست (بهبود)
        $removed = $this->checker->compareSnapshots($noindex, $indexable);
        $this->assertNull(collect($removed)->firstWhere('severity', SeoRegressionChecker::CRITICAL));
    }

    public function test_changed_title_is_only_a_warning(): void
    {
        $base = $this->checker->extractFromHtml('<title>عنوان قدیم</title>', 200);
        $cand = $this->checker->extractFromHtml('<title>عنوان جدید</title>', 200);

        $issues = $this->checker->compareSnapshots($base, $cand);

        $this->assertCount(1, $issues);
        $this->assertSame('title', $issues[0]['field']);
        $this->assertSame(SeoRegressionChecker::WARNING, $issues[0]['severity']);
    }

    public function test_non_200_baseline_is_skipped(): void
    {
        // اگر خودِ مرجع ۲۰۰ نیست، این URL معیار نیست → هیچ مشکلی گزارش نشود
        $base = $this->checker->extractFromHtml(null, 500);
        $cand = $this->checker->extractFromHtml('<title>x</title>', 200);

        $this->assertSame([], $this->checker->compareSnapshots($base, $cand));
    }
}
