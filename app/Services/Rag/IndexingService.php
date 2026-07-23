<?php

namespace App\Services\Rag;

use App\Models\KnowledgeEntry;
use App\Models\KnowledgeEntryAttachment;

/**
 * Stubِ لایه‌ی orchestration پایپ‌لاین RAG (استخراج → تکه‌تکه‌کردن → embedding → VectorStore).
 *
 * در سایتِ انگلیسی این سرویس سه سرویسِ TextExtractionService/ChunkingService/ProviderManager::embed
 * را کنار هم صدا می‌زند و بردارهای embedding را در knowledge_chunks می‌نویسد. اما در نسخه‌ی فارسی
 * RAG/embedding فعلاً موکول است (همان تصمیمی که در App\Services\KnowledgeBase\KnowledgeBaseService
 * هم گرفته شده) — پس این نسخه یک no-op امن است:
 *
 *   • هیچ‌گاه یک تماس شبکه‌ای/embedding انجام نمی‌دهد.
 *   • هیچ‌گاه throw نمی‌کند (نبودِ ارائه‌دهنده‌ی embedding یک وضعیتِ کاملاً عادی است).
 *   • هیچ‌گاه درخواستِ وب/ذخیره‌ی فرم را بلاک نمی‌کند.
 *   • هیچ constructor dependency ندارد تا از container به‌سادگی resolve شود.
 *
 * امضای عمومی دقیقاً مثلِ نسخه‌ی کاملِ انگلیسی است (indexKnowledgeEntry / indexAttachment) تا وقتی
 * embedding واقعی منتقل شد، این فایل با پیاده‌سازیِ کامل جایگزین شود بدونِ نیاز به تغییرِ صدازننده‌ها
 * (App\Jobs\IndexKnowledgeContent و قلاب‌های چرخه‌ی عمرِ مدل‌ها) — forward-compatible.
 */
class IndexingService
{
    /**
     * ایندکس‌کردنِ فیلد content خودِ یک KnowledgeEntry — فعلاً no-op (RAG غیرفعال).
     */
    public function indexKnowledgeEntry(KnowledgeEntry $entry): void
    {
        // عمداً خالی: embedding موکول است.
    }

    /**
     * استخراج + ایندکسِ یک پیوست — فعلاً no-op (RAG غیرفعال). فایل/URL ذخیره می‌شود ولی متنِ آن
     * استخراج یا embed نمی‌شود؛ extraction_status روی مقدارِ پیش‌فرضِ pending می‌ماند.
     */
    public function indexAttachment(KnowledgeEntryAttachment $attachment): void
    {
        // عمداً خالی: استخراج/embedding موکول است.
    }
}
