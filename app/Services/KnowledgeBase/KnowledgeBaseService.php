<?php

namespace App\Services\KnowledgeBase;

use Illuminate\Support\Collection;

/**
 * Stubِ موقتِ Knowledge Base — همان امضای عمومیِ سرویسِ کاملِ سایت انگلیسی را دارد، ولی چون
 * RAG/embedding فعلاً موکول است (تصمیمِ کاربر)، خروجیِ خالی می‌دهد. این باعث می‌شود
 * ContentAssistantService بدونِ هیچ تغییری کار کند (تولید با حافظه‌ی برند، بدونِ بازیابیِ دانش).
 *
 * وقتی Knowledge Base واقعی منتقل شد (موج بعد از embedding)، این فایل با نسخه‌ی کامل جایگزین
 * می‌شود — بدونِ نیاز به تغییرِ موتور (forward-compatible، مطابقِ docs/CMS-CORE-CONTRACT.md).
 */
class KnowledgeBaseService
{
    /**
     * تکه‌های دانشِ مرتبط با یک پرس‌وجو — فعلاً خالی (RAG غیرفعال).
     *
     * @return array<int, array>
     */
    public function retrieveChunks(string $query, string $locale, int $limit = 5): array
    {
        return [];
    }

    /**
     * ورودی‌های دانشِ مرتبط — فعلاً خالی.
     */
    public function retrieveRelevant(string $query, string $locale, int $limit = 5): Collection
    {
        return collect();
    }
}
