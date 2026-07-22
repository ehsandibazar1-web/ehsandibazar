<?php

namespace App\Cms;

use Illuminate\Database\Eloquent\Relations\Relation;

/**
 * نقشه‌ی morph — منبعِ حقیقتِ واحد برای aliasهای چندریختی (docs/CMS-CORE-CONTRACT.md، لایه ۴).
 *
 * چرا مهم است: ستون‌های چندریختی (taggable_type, keywordable_type, subject_type) نامِ کلاس را
 * ذخیره می‌کنند. سایت فارسی App\Model\* (مفرد) و انگلیسی App\Models\* (جمع) است. با aliasِ رشته‌ای
 * ثابت، دادهٔ چندریختی بینِ دو دیتابیس قابل‌انتقال می‌ماند و اختلافِ namespace مانعِ پورت نمی‌شود.
 *
 * ⚠️ هنوز register() نمی‌شود. فعال‌سازیِ enforceMorphMap روی دادهٔ زنده‌ی موجود (taggables قدیمیِ
 * فروشگاه با taggable_type=App\Model\Article، و activity_log با subject_type=App\Models\BrandMemoryValue)
 * بدونِ backfill، لوکاپ‌های چندریختیِ فعلی را می‌شکند (مثلاً تاریخچه‌ی Brand Memory و تگ‌های مقاله).
 * پس فعال‌سازی در موج ۴ همراه با یک مهاجرتِ backfill انجام می‌شود که مقادیرِ نامِ-کلاسِ کاملِ موجود
 * را به alias تبدیل کند. تا آن زمان این کلاس فقط «منبعِ حقیقت» است، نه رفتارِ فعال.
 */
class MorphMap
{
    /**
     * aliasِ پایدار → کلاسِ همگرا. برخی کلاس‌ها (Article/Page/Tag/Keyword/...) در موج‌های بعد
     * ساخته می‌شوند؛ ::class فقط یک رشته است و کلاس را autoload نمی‌کند، پس فهرست‌کردنشان امن است.
     *
     * @return array<string, class-string>
     */
    public static function map(): array
    {
        return [
            // محتوای همگرا (موج ۴)
            'article' => \App\Models\Article::class,
            'page' => \App\Models\Page::class,
            'tag' => \App\Models\Tag::class,
            'keyword' => \App\Models\Keyword::class,
            // ابزارهای منتقل‌شده (موجود)
            'media' => \App\Models\Media::class,
            'media_folder' => \App\Models\MediaFolder::class,
            'workflow_stage' => \App\Models\WorkflowStage::class,
            'brand_memory_section' => \App\Models\BrandMemorySection::class,
            'brand_memory_value' => \App\Models\BrandMemoryValue::class,
            // موتورِ محتوا (موج ۴)
            'content_plan' => \App\Models\ContentPlan::class,
            'knowledge_entry' => \App\Models\KnowledgeEntry::class,
        ];
    }

    /**
     * فعال‌سازی — عمداً از AppServiceProvider فراخوانی نمی‌شود تا موج ۴ (بعد از backfill).
     * فعلاً enforce=false نگه داشته می‌شود تا دادهٔ نامِ-کلاسِ کاملِ موجود همچنان خوانده شود.
     */
    public static function register(bool $enforce = false): void
    {
        Relation::morphMap(self::map());

        if ($enforce) {
            Relation::requireMorphMap();
        }
    }
}
