<?php

namespace App\Livewire;

use App\Filament\Resources\Articles\ArticleResource;
use App\Filament\Resources\Pages\PageResource;
use App\Jobs\RunAiContentGeneration;
use App\Model\Article;
use App\Model\Page as PageModel;
use App\Models\AiGeneration;
use App\Models\Media;
use App\Services\AiAssistant\ActionRegistry;
use App\Services\AiAssistant\ContentReviewService;
use App\Services\AiAssistant\DiffService;
use App\Services\AiAssistant\GenerationApplier;
use App\Services\AiAssistant\ProviderManager;
use App\Services\Seo\SeoAuditService;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

/**
 * دستیار هوش مصنوعی برای یک مقاله/صفحه‌ی مشخص — یک کامپوننت Livewire ساده (نه Filament\Pages\Page).
 * دو جا mount می‌شود: (۱) درون سایدبار تعبیه‌شده در صفحه‌ی ویرایش (EditArticle/EditPage،
 * standalone=false)، (۲) درون صفحه‌ی مستقل App\Filament\Pages\AiContentAssistant (standalone=true).
 *
 * موقتاً: چت (ProcessAiChatMessage/AiChatMessage)، تصویرِ hero (GenerateHeroImage/AiImageGeneration)
 * و ترجمه (TranslateArticleDraft) هنوز منتقل نشده‌اند — دکمه‌هایشان حفظ شده ولی handler فقط یک
 * نوتیفیکیشن «در گروه بعد فعال می‌شود» نشان می‌دهد، بدون dispatch/کلاسِ گمشده. تولیدِ محتوا
 * (generate/apply/restore از مسیر RunAiContentGeneration) کامل کار می‌کند.
 */
class AiAssistantPanel extends Component
{
    public string $recordType = 'Article';

    public int $recordId;

    public bool $standalone = false;

    public ?Model $record = null;

    public string $activeTab = 'generate'; // generate | review

    public string $chatInput = '';

    public function mount(string $recordType, int $recordId, bool $standalone = false): void
    {
        $this->recordType = $recordType;
        $this->recordId = $recordId;
        $this->standalone = $standalone;

        $this->record = $recordType === 'Article' ? Article::find($recordId) : PageModel::find($recordId);

        abort_if(! $this->record, 404);
    }

    public function render()
    {
        return view('livewire.ai-assistant-panel');
    }

    public function setTab(string $tab): void
    {
        $this->activeTab = $tab;
    }

    public function getFieldsProperty(): array
    {
        $fields = array_filter(
            ActionRegistry::applicableTo($this->recordType),
            fn (array $definition) => $definition['appliable'] ?? true,
        );

        return collect($fields)->map(function (array $definition, string $key) {
            $history = AiGeneration::forField($this->recordType, $this->record->id, $key)
                ->latest()
                ->take(5)
                ->get();

            return array_merge($definition, [
                'key' => $key,
                'current_value' => in_array($key, ActionRegistry::MEDIA_BACKED_FIELDS, true)
                    ? $this->mediaForRecord()?->getAttribute($key)
                    : $this->record->getAttribute($key),
                'latest' => $history->first(),
                'history' => $history,
            ]);
        })->all();
    }

    // فیلدهای فقط-پیشنهادی (appliable=false) که در گرید اصلی Generate نشان داده نمی‌شوند — هر
    // کدام کارت خودشان را در پایین تب Generate دارند (نگاه کنید به livewire/ai-assistant-panel.blade.php)
    public function getSuggestionFieldsProperty(): array
    {
        return collect(['internal_links', 'external_links', 'schema'])
            ->mapWithKeys(function (string $key) {
                $definition = ActionRegistry::for($key);

                if (! in_array($this->recordType, $definition['applicable_to'], true)) {
                    return [];
                }

                return [$key => array_merge($definition, [
                    'key' => $key,
                    'latest' => AiGeneration::forField($this->recordType, $this->record->id, $key)->latest()->first(),
                ])];
            })->all();
    }

    private function mediaForRecord(): ?Media
    {
        return Media::forRecord($this->record);
    }

    public function getReviewFindingsProperty(): array
    {
        return app(ContentReviewService::class)->review($this->record);
    }

    public function getScoreCardProperty(): array
    {
        return app(ContentReviewService::class)->scoreCard($this->record);
    }

    // پیش‌نمایش دیف قرمز/سبز قبل از Apply — فقط برای مقادیر متنی ساده معنی دارد (عنوان/توضیحات/...)؛
    // برای مقادیر آرایه‌ای (FAQ، برچسب‌ها، پیشنهادهای لینک و ...) null برمی‌گرداند تا نمای فعلیِ
    // فهرستی/QA همان‌طور که بود نمایش داده شود
    public function diffFor(mixed $currentValue, mixed $result): ?array
    {
        if (is_array($result) || is_array($currentValue)) {
            return null;
        }

        return app(DiffService::class)->diffWords((string) $currentValue, (string) $result);
    }

    public function getReviewSummaryProperty(): ?AiGeneration
    {
        return AiGeneration::forField($this->recordType, $this->record->id, 'content_review_summary')
            ->latest()
            ->first();
    }

    public function generateReviewSummary(): void
    {
        $this->generateField('content_review_summary', 'generate');
    }

    public function getIsPollingProperty(): bool
    {
        return AiGeneration::where('content_type', $this->recordType)
            ->where('content_id', $this->record->id)
            ->whereIn('status', ['queued', 'processing'])
            ->exists();
    }

    // ============ AI Chat (موقتاً stub — ProcessAiChatMessage/AiChatMessage در گروه بعد) ============

    // بدونِ مدلِ AiChatMessage یک مجموعه‌ی خالی برمی‌گردانیم تا @forelse در بلید حالت خالی را نشان
    // دهد و هیچ ارجاعی به کلاسِ گمشده رخ ندهد
    public function getChatMessagesProperty(): Collection
    {
        return collect();
    }

    public function getIsChatPendingProperty(): bool
    {
        return false;
    }

    public function sendChatMessage(): void
    {
        $this->chatInput = '';

        $this->notifyComingSoon();
    }

    public function generateField(string $field, string $mode): void
    {
        $this->queueGeneration($field, $mode);
        $this->notifyQueued('تولید');
    }

    // چهار دکمه‌ی سریع روی متن بدنه — همان generateField روی فیلد body با یک حالت ثابت، فقط یک
    // میان‌بر برای Quick Actions
    public function quickBodyAction(string $mode): void
    {
        $this->queueGeneration('body', $mode);
        $this->notifyQueued('متن مقاله ('.$mode.')');
    }

    // میان‌بر Quick Actions «SEO Only» — فقط چهار فیلد سئو/OG و اسلاگ را صف می‌کند، نه همه‌چیز
    public function quickSeoOnly(): void
    {
        foreach (['seo_title', 'meta_description', 'og_title', 'og_description', 'slug'] as $key) {
            if (in_array($this->recordType, ActionRegistry::for($key)['applicable_to'], true)) {
                $this->queueGeneration($key, 'generate');
            }
        }

        $this->notifyQueued('فیلدهای سئو');
    }

    // میان‌بر Quick Actions «FAQ Only» — فقط برای Article معنی دارد (ActionRegistry همین را برای Page رد می‌کند)
    public function quickFaqOnly(): void
    {
        $this->queueGeneration('faq', 'generate');
        $this->notifyQueued('پرسش‌های متداول');
    }

    // دکمه‌ی اصلی «✨ Optimize Entire Article» / Quick Actions «Generate Everything» — عمداً همان
    // یک عمل است؛ هر فیلد قابل‌تولیدِ این نوع رکورد را صف می‌کند، به‌جز content_review_summary
    // (دکمه‌ی مخصوص خودش را در تب Review دارد) و بدنه (که حالت generate ندارد). هیچ‌کدام خودکار
    // Apply نمی‌شوند.
    public function optimizeEntireArticle(): void
    {
        foreach (ActionRegistry::applicableTo($this->recordType) as $key => $definition) {
            if ($key === 'content_review_summary' || ! in_array('generate', $definition['modes'], true)) {
                continue;
            }

            $this->queueGeneration($key, 'generate');
        }

        $this->notifyQueued('همه‌ی پیشنهادها برای این محتوا');
    }

    // پیشرفتِ تقریبیِ یک دسته‌ی تولید گروهی («۳ از ۱۴ انجام شد») — بدون ستون batch_id، فقط از
    // تعداد تولیدهای صف‌شده/در‌حال‌اجرا در برابر تعداد کل تولیدهای همین رکورد در ۵ دقیقه‌ی اخیر
    public function getGenerationProgressProperty(): ?string
    {
        $pending = AiGeneration::where('content_type', $this->recordType)
            ->where('content_id', $this->record->id)
            ->whereIn('status', ['queued', 'processing'])
            ->count();

        if ($pending === 0) {
            return null;
        }

        $recentTotal = AiGeneration::where('content_type', $this->recordType)
            ->where('content_id', $this->record->id)
            ->where('created_at', '>=', now()->subMinutes(5))
            ->count();

        return max(0, $recentTotal - $pending).' از '.$recentTotal.' انجام شد';
    }

    // ============ Translate (موقتاً stub — TranslateArticleDraft در گروه بعد) ============

    public function translate(string $targetLocale): void
    {
        $this->notifyComingSoon();
    }

    public function getTranslationsProperty(): Collection
    {
        return AiGeneration::forField($this->recordType, $this->record->id, 'translate')
            ->latest()
            ->get();
    }

    // ============ Hero Image Generation (موقتاً stub — GenerateHeroImage/AiImageGeneration در گروه بعد) ============

    // آیا اصلاً یک ارائه‌دهنده‌ی تولید تصویرِ قابل‌استفاده تنظیم شده — دکمه‌ی «Generate Hero Image»
    // فقط وقتی این true است فعال می‌شود، وگرنه یک پیام راهنما به‌جایش نشان داده می‌شود
    public function getCanGenerateImagesProperty(): bool
    {
        return app(ProviderManager::class)->resolveImageProvider() !== null;
    }

    // بدونِ مدلِ AiImageGeneration یک مجموعه‌ی خالی برمی‌گردانیم تا بلید بدون ارجاع به کلاسِ گمشده رندر شود
    public function getHeroImageGenerationsProperty(): Collection
    {
        return collect();
    }

    public function generateHeroImage(): void
    {
        $this->notifyComingSoon();
    }

    public function cancelImageGeneration(int $id): void
    {
        $this->notifyComingSoon();
    }

    // ============ Cancellation ============

    // این سقفِ واقعیِ چیزی است که روی صف ممکن است — نمی‌شود یک تماس HTTP در حال اجرا را واقعاً
    // کشت. اگر هنوز queued است، هرگز اجرا نمی‌شود (چک‌پوینت اول در خودِ جاب). اگر processing است،
    // جاب هنوز تا انتهای تماس فعلی ادامه می‌دهد ولی نتیجه‌اش را نمی‌نویسد (چک‌پوینت دوم).
    public function cancelGeneration(int $id): void
    {
        $generation = AiGeneration::find($id);

        if (! $generation || ! $generation->isCancellable()) {
            return;
        }

        $generation->update(['status' => 'cancelled']);

        Notification::make()->success()->title('تولید لغو شد')->send();
    }

    // ============ History — همه‌ی تولیدهای این رکورد در همه‌ی فیلدها، نه فقط ۴ تای آخرِ هر فیلد ============

    public function getHistoryProperty(): Collection
    {
        return AiGeneration::forRecord($this->recordType, $this->record->id)
            ->latest()
            ->take(30)
            ->get();
    }

    private function queueGeneration(string $field, string $mode): void
    {
        // فیلدهای MEDIA_BACKED_FIELDS روی رکورد Article/Page ذخیره نمی‌شوند، روی Media متناظر —
        // پس مقدار فعلی هم باید از آنجا خوانده شود
        $inputSnapshot = in_array($field, ActionRegistry::MEDIA_BACKED_FIELDS, true)
            ? $this->mediaForRecord()?->getAttribute($field)
            : $this->record->getAttribute($field);

        // موقتاً: content_type را روی recordType ('Article'/'Page') ذخیره می‌کنیم — همان چیزی که همه‌ی
        // scopeهای خواندنِ این پنل (forField/forRecord/isPolling) با آن فیلتر می‌کنند. کلید morphMap
        // این پروژه lowercase است، پس getMorphClass() اینجا ناسازگاری خواندن/نوشتن می‌ساخت.
        $generation = AiGeneration::create([
            'user_id' => Auth::id(),
            'content_type' => $this->recordType,
            'content_id' => $this->record->id,
            'field' => $field,
            'mode' => $mode,
            'provider' => config('services.anthropic.driver', 'anthropic'),
            'status' => 'queued',
            'input_snapshot' => $inputSnapshot,
        ]);

        RunAiContentGeneration::dispatch($generation->id);
    }

    private function notifyQueued(string $what): void
    {
        Notification::make()
            ->success()
            ->title($what.' در صف قرار گرفت')
            ->body('این کار در پس‌زمینه اجرا می‌شود. صفحه در حین اجرا به‌صورت خودکار تازه می‌شود.')
            ->persistent()
            ->send();
    }

    private function notifyComingSoon(): void
    {
        Notification::make()
            ->info()
            ->title('این قابلیت در گروه بعد فعال می‌شود')
            ->send();
    }

    public function applyGeneration(int $id): void
    {
        $generation = AiGeneration::find($id);

        if (! $generation || ! $generation->canApply()) {
            return;
        }

        if (in_array($generation->field, ActionRegistry::MEDIA_BACKED_FIELDS, true) && ! $this->mediaForRecord()) {
            Notification::make()->danger()->title('برای این تصویر رکوردی در کتابخانه‌ی رسانه یافت نشد')->send();

            return;
        }

        app(GenerationApplier::class)->apply($generation, $this->record);

        Notification::make()->success()->title('روی «'.ActionRegistry::for($generation->field)['label'].'» اعمال شد')->send();
    }

    public function restoreGeneration(int $id): void
    {
        $generation = AiGeneration::find($id);

        if (! $generation || ! $generation->canRestore()) {
            return;
        }

        app(GenerationApplier::class)->restore($generation, $this->record);

        Notification::make()->success()->title('مقدار قبلیِ «'.ActionRegistry::for($generation->field)['label'].'» بازگردانده شد')->send();
    }

    // پیشنهادهای لینک داخلیِ هوش‌مصنوعی را به همان جدول/چرخه‌ی موجود (Internal Linking Center)
    // اضافه می‌کند — منطق نوشتن در App\Services\AiAssistant\GenerationApplier است.
    public function applyInternalLinkSuggestions(int $id): void
    {
        // مرکز لینک‌سازی داخلی (App\Models\InternalLinkSuggestion) هنوز منتقل نشده (موج ۵e).
        // بدونِ این گارد، GenerationApplier::applyInternalLinkSuggestions روی کلاسِ ناموجود fatal می‌داد.
        if (! class_exists(\App\Models\InternalLinkSuggestion::class)) {
            Notification::make()
                ->info()
                ->title('مرکز لینک‌سازی داخلی در گروه بعد فعال می‌شود')
                ->send();

            return;
        }

        $generation = AiGeneration::find($id);

        if (! $generation || ! $generation->canApply() || $generation->field !== 'internal_links') {
            return;
        }

        $count = app(GenerationApplier::class)->applyInternalLinkSuggestions($generation, $this->record);

        Notification::make()
            ->success()
            ->title($count.' پیشنهاد لینک داخلی افزوده شد')
            ->body('آن‌ها را در «مرکز لینک‌سازی داخلی» بررسی و تأیید کنید.')
            ->send();
    }

    // هر URL پیشنهادی هوش مصنوعی، قبل از نمایش، با همان الگوی SeoAuditService::checkUrls() بررسی
    // می‌شود که واقعاً بالا باشد (موقتاً: stub هیچ‌کدام را خراب نمی‌داند)
    public function getVerifiedExternalLinksProperty(): array
    {
        $generation = AiGeneration::forField($this->recordType, $this->record->id, 'external_links')->latest()->first();

        if (! $generation || ! $generation->canApply()) {
            return [];
        }

        $urls = collect($generation->result)->pluck('url')->filter()->all();

        if ($urls === []) {
            return [];
        }

        $checked = app(SeoAuditService::class)->checkUrls($urls);

        return collect($generation->result)->map(fn (array $item) => array_merge($item, [
            'broken' => $checked[$item['url']]['broken'] ?? false,
        ]))->all();
    }

    public function resolveTargetLabel(string $type, int $id): string
    {
        $model = $type === 'Article' ? Article::find($id) : PageModel::find($id);

        return $model ? $model->title : "#{$id} (یافت نشد)";
    }

    public function editUrl(): string
    {
        return $this->recordType === 'Article'
            ? ArticleResource::getUrl('edit', ['record' => $this->record->id])
            : PageResource::getUrl('edit', ['record' => $this->record->id]);
    }
}
