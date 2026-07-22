<?php

namespace App\Jobs;

use App\Filament\Resources\Articles\ArticleResource;
use App\Filament\Resources\Pages\PageResource;
use App\Model\Article;
use App\Model\Page;
use App\Models\AiGeneration;
use App\Services\AiAssistant\ContentAssistantService;
use App\Services\Content\ContentDraftFactory;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Throwable;

/**
 * یک ترجمه‌ی «کامل» می‌سازد: نه یک پیشنهادِ متنی، بلکه یک ردیفِ Article/Pageِ تازه و لینک‌شده
 * (translation_of)، همیشه پیش‌نویس — طبقِ همان سیاستِ «محتوای تولیدشده با هوش مصنوعی هرگز
 * خودکار منتشر نمی‌شود».
 *
 * فقط محتوای متنی (title/body/excerpt/faqs) از هوش مصنوعی می‌آید
 * (ContentAssistantService::buildTranslationPayload). متادیتای غیرمحتوایی (locale،
 * translation_of، status، تصویر، نویسنده) همین‌جا از روی رکوردِ اصلی ساخته می‌شود و همه از طریقِ
 * App\Services\Content\ContentDraftFactory نوشته می‌شوند — همان تنها لایه‌ی نگاشتِ Contract.
 *
 * موقتاً: در سایتِ انگلیسی ساختِ Article از ArticleImportService و ساختِ Page از Page::makeSlug/
 * Page::locale عبور می‌کرد؛ هر دو با یک تماسِ ContentDraftFactory جایگزین شده‌اند (اسلاگِ یکتا،
 * ستون‌های canonical و برچسب‌ها همه آنجا مدیریت می‌شوند).
 */
class TranslateArticleDraft implements ShouldQueue
{
    use Queueable;

    public int $tries = 1;

    public function __construct(
        private readonly string $contentType,
        private readonly int $contentId,
        private readonly string $targetLocale,
        private readonly int $generationId,
    ) {}

    public function handle(ContentAssistantService $service, ContentDraftFactory $factory): void
    {
        $generation = AiGeneration::find($this->generationId);

        if (! $generation || $generation->status === 'cancelled') {
            return;
        }

        $generation->update(['status' => 'processing']);

        $record = $this->contentType === 'Article'
            ? Article::find($this->contentId)
            : Page::find($this->contentId);

        if (! $record) {
            $generation->update(['status' => 'failed', 'error' => 'محتوای اصلی دیگر وجود ندارد.']);

            return;
        }

        try {
            $translated = $service->buildTranslationPayload($record, $this->targetLocale);

            // اگر بینِ شروعِ تماسِ API و اینجا کنسل شده باشد، ادامه نمی‌دهیم — هنوز هیچ رکوردی
            // ساخته نشده، پس کنسل واقعاً چیزی نیمه‌کاره باقی نمی‌گذارد.
            if ($generation->fresh()->status === 'cancelled') {
                return;
            }

            $newRecord = $this->contentType === 'Article'
                ? $factory->createArticleDraft($this->articlePayload($record, $translated))
                : $factory->createPageDraft($this->pagePayload($record, $translated));

            $generation->update([
                'status' => 'completed',
                'result' => [
                    'id' => $newRecord->id,
                    'type' => $this->contentType,
                    'title' => $newRecord->title,
                    'edit_url' => $this->contentType === 'Article'
                        ? ArticleResource::getUrl('edit', ['record' => $newRecord->id])
                        : PageResource::getUrl('edit', ['record' => $newRecord->id]),
                ],
            ]);
        } catch (Throwable $e) {
            if ($generation->fresh()->status === 'cancelled') {
                return;
            }

            $generation->update(['status' => 'failed', 'error' => $e->getMessage()]);
        }
    }

    /**
     * payloadِ شکل‌گرفته‌ی Contract برای مقاله‌ی ترجمه‌شده — فقط محتوای متنی از AI، بقیه از رکوردِ اصلی.
     */
    private function articlePayload(Article $source, array $translated): array
    {
        $payload = [
            'locale' => $this->targetLocale,
            'translation_of' => $source->id,
            'title' => $translated['title'],
            'body' => $translated['body'],
            'excerpt' => $translated['excerpt'],
            'image_path' => $source->image_path,
            'author_name' => $source->author_name,
            'status' => 'draft', // همیشه پیش‌نویس
        ];

        // faqs فقط وقتی گنجانده می‌شود که واقعاً داده‌ای دارد.
        if (! empty($translated['faqs'])) {
            $payload['faqs'] = $translated['faqs'];
        }

        return $payload;
    }

    private function pagePayload(Page $source, array $translated): array
    {
        return [
            'locale' => $this->targetLocale,
            'translation_of' => $source->id,
            'title' => $translated['title'],
            'body' => $translated['body'],
            'image_path' => $source->image_path,
            'status' => 'draft',
        ];
    }
}
