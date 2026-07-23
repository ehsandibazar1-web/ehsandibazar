<?php

namespace App\Jobs;

use App\Models\KnowledgeEntry;
use App\Models\KnowledgeEntryAttachment;
use App\Services\Rag\IndexingService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\SerializesModels;
use Throwable;

/**
 * ایندکس‌کردنِ صف‌شده‌ی یک KnowledgeEntry (فیلد content خودش) یا یک KnowledgeEntryAttachment —
 * همان الگویی که یک تماس API واقعی (embedding) را از چرخه‌ی درخواست وب/ذخیره‌سازیِ فرم بیرون
 * می‌کشد. قلاب‌های چرخه‌ی عمرِ مدل‌ها این جاب را دیسپچ می‌کنند.
 *
 * در نسخه‌ی فارسی، App\Services\Rag\IndexingService یک stub (no-op) است، پس handle() هم عملاً
 * کاری انجام نمی‌دهد؛ ولی جاب دیسپچ‌پذیر می‌ماند تا وقتی embedding واقعی آمد فعال شود. صفِ sync
 * کاملاً بی‌خطر است چون مسیر هیچ تماس شبکه‌ای ندارد.
 *
 * هیچ‌گاه throw نمی‌کند: نبودِ ارائه‌دهنده‌ی embedding یک وضعیتِ عادی است و نباید ذخیره‌ی هیچ
 * KnowledgeEntry‌ای را با خطا بترکاند.
 */
class IndexKnowledgeContent implements ShouldQueue
{
    use Queueable, SerializesModels;

    public int $tries = 1;

    public function __construct(private readonly KnowledgeEntry|KnowledgeEntryAttachment $subject) {}

    public function handle(IndexingService $service): void
    {
        try {
            if ($this->subject instanceof KnowledgeEntry) {
                $service->indexKnowledgeEntry($this->subject);

                return;
            }

            $service->indexAttachment($this->subject);
        } catch (Throwable $e) {
            report($e);
        }
    }
}
