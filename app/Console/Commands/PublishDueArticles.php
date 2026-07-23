<?php

namespace App\Console\Commands;

use App\Model\Article;
use App\Models\ContentPlan;
use App\Models\WorkflowStage;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

/**
 * انتشارِ خودکارِ مقاله‌های زمان‌بندی‌شده‌ای که زمانشان رسیده. پورت از سایتِ انگلیسی، تطبیق‌یافته به
 * statusِ بولینِ فارسی: «زمان‌بندی‌شده» = is_scheduled=true (نشانگرِ صریح) + status=0 (هنوز منتشر
 * نشده) + published_at در گذشته. فقط همین‌ها منتشر می‌شوند؛ پیش‌نویسِ عادی (is_scheduled=false)
 * هرگز لمس نمی‌شود. روی هاستِ بدون‌شل، از طریقِ روتِ maintenance/publish-due صدا زده می‌شود (بدونِ cron).
 */
class PublishDueArticles extends Command
{
    protected $signature = 'articles:publish-due';

    protected $description = 'انتشارِ خودکارِ مقاله‌های زمان‌بندی‌شده‌ای که زمانشان رسیده است';

    public function handle(): int
    {
        $due = Article::query()
            ->where('is_scheduled', true)
            ->where('status', 0)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->get();

        $publishedStage = WorkflowStage::findBySlug(WorkflowStage::STAGE_PUBLISHED);

        foreach ($due as $article) {
            $article->update(['status' => 1, 'is_scheduled' => false]);
            $this->info("Published: [{$article->lang}] {$article->title}");

            // اگر مقاله از یک کارتِ برنامه‌ریز مادیت پیدا کرده، مرحله‌ی آن کارت را هم به «منتشرشده»
            // می‌بریم تا در کانبان همگام بماند. (بدونِ افزودنِ رابطه به مدلِ storefront — مستقیم کوئری.)
            if ($publishedStage) {
                $plan = ContentPlan::query()
                    ->where('contentable_type', $article->getMorphClass())
                    ->where('contentable_id', $article->id)
                    ->first();
                optional($plan)->moveToStage($publishedStage);
            }
        }

        if ($due->isNotEmpty()) {
            Artisan::call('cache:clear');
            Artisan::call('view:clear');
            $this->info('Cache cleared after publishing '.$due->count().' article(s).');
        } else {
            $this->info('No due articles.');
        }

        return self::SUCCESS;
    }
}
