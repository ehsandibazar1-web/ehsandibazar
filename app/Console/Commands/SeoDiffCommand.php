<?php

namespace App\Console\Commands;

use App\Services\Seo\SeoRegressionChecker;
use Illuminate\Console\Command;

/**
 * دروازه‌ی خط قرمز SEO از خط فرمان/CI (نگاه کنید به docs/SEO-RED-LINE.md).
 * اگر هر مشکلِ 🔴 بحرانی باشد، با کدِ خروجیِ غیرصفر تمام می‌شود.
 */
class SeoDiffCommand extends Command
{
    protected $signature = 'seo:diff
        {--base=https://ehsandibazar.com : سایت مرجع (production) که sitemap از آن خوانده می‌شود}
        {--candidate=https://staging.ehsandibazar.com : سایت کاندیدا (استگینگ) که باید تأیید شود}
        {--limit= : حداکثر تعداد URL برای بررسی (پیش‌فرض: همه)}
        {--offset=0 : از این ایندکس شروع کن}';

    protected $description = 'مقایسه‌ی SEOِ استگینگ با production؛ رگرسیون = عبور ممنوع';

    public function handle(SeoRegressionChecker $checker): int
    {
        $base = (string) $this->option('base');
        $candidate = (string) $this->option('candidate');
        $limit = $this->option('limit') !== null ? (int) $this->option('limit') : null;
        $offset = (int) $this->option('offset');

        $this->info("مرجع:    {$base}");
        $this->info("کاندیدا: {$candidate}");
        $this->newLine();

        $report = $checker->run($base, $candidate, $limit, $offset);

        if ($report['total_urls'] === 0) {
            $this->error('sitemap خالی بود یا در دسترس نبود — بررسی انجام نشد.');

            return self::FAILURE;
        }

        foreach ($report['rows'] as $row) {
            if (empty($row['issues'])) {
                continue;
            }
            $this->line("<fg=cyan>{$row['path']}</> (base {$row['base_status']} → cand {$row['candidate_status']})");
            foreach ($row['issues'] as $issue) {
                $mark = $issue['severity'] === SeoRegressionChecker::CRITICAL ? '<fg=red>🔴</>' : '<fg=yellow>🟡</>';
                $this->line("   {$mark} {$issue['field']}: [".($issue['base'] ?? '∅').'] → ['.($issue['candidate'] ?? '∅').']');
            }
        }

        $this->newLine();
        $this->info("بررسی‌شده: {$report['checked']} از {$report['total_urls']}");
        $this->line("🔴 بحرانی: {$report['critical']}    🟡 هشدار: {$report['warning']}");

        if ($report['critical'] > 0) {
            $this->error('دروازه‌ی SEO: قرمز — عبور به production ممنوع.');

            return self::FAILURE;
        }

        if ($report['warning'] > 0) {
            $this->warn('دروازه‌ی SEO: زرد — هشدارها را دستی بازبینی کن.');

            return self::SUCCESS;
        }

        $this->info('دروازه‌ی SEO: سبز ✅');

        return self::SUCCESS;
    }
}
