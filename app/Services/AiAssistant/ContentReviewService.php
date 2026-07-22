<?php

namespace App\Services\AiAssistant;

use Illuminate\Database\Eloquent\Model;

/**
 * Stubِ موقتِ بازبینیِ محتوا — همان امضای عمومیِ سرویسِ کاملِ سایت انگلیسی (review/scoreCard) را
 * دارد ولی خروجیِ خنثی می‌دهد. نسخه‌ی کاملِ انگلیسی به زنجیره‌ی خدماتِ لینک‌دهیِ داخلی
 * (HtmlContentScanner / InternalLinkResolver / LinkGraphService) وابسته است که در موج ۵e منتقل
 * می‌شوند. تا آن زمان این stub اجازه می‌دهد موتورِ تولید (ContentAssistantService) کامل کار کند
 * (فقط زمینه‌ی «یافته‌های بازبینی» در پرامپت خالی است). forward-compatible: با نسخه‌ی کامل جایگزین می‌شود.
 */
class ContentReviewService
{
    /**
     * یافته‌های بازبینیِ محتوا — فعلاً خالی.
     *
     * @return array<int, array>
     */
    public function review(Model $record): array
    {
        return [];
    }

    /**
     * کارتِ امتیازِ سلامتِ محتوا — فعلاً همه‌ی دسته‌ها خنثی (۱۰۰) تا نسخه‌ی کامل در موج ۵e بیاید.
     *
     * @return array{overall: int, categories: array<string, array{label: string, score: int, issues: array<int, string>}>}
     */
    public function scoreCard(Model $record): array
    {
        $neutral = fn (string $label): array => ['label' => $label, 'score' => 100, 'issues' => []];

        return [
            'overall' => 100,
            'categories' => [
                'seo' => $neutral('SEO'),
                'readability' => $neutral('Readability'),
                'content_quality' => $neutral('Content quality'),
                'internal_linking' => $neutral('Internal linking'),
                'media_optimization' => $neutral('Media optimization'),
                'schema' => $neutral('Schema'),
            ],
        ];
    }
}
