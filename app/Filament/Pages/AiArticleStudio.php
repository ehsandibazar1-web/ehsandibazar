<?php

namespace App\Filament\Pages;

use App\Model\AiSetting;
use App\Model\Article;
use App\Services\AiArticleGenerator;
use BackedEnum;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Auth;
use Throwable;
use UnitEnum;

/**
 * دستیار تولید مقاله با هوش مصنوعی.
 *
 * این صفحه خودش یک کامپوننت Livewire است؛ فرم‌ها و دکمه‌ها مستقیماً با
 * پراپرتی‌های عمومی (wire:model) و متدها (wire:click) در ویوی Blade کار می‌کنند.
 * عمداً از DSL فرم Filament استفاده نشده تا ساده و کم‌ریسک بماند.
 */
class AiArticleStudio extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSparkles;

    protected static string|UnitEnum|null $navigationGroup = 'هوش مصنوعی';

    protected static ?string $navigationLabel = 'دستیار مقاله';

    protected static ?string $title = 'دستیار تولید مقاله با هوش مصنوعی';

    protected string $view = 'filament.pages.ai-article-studio';

    // --- تنظیمات ---
    public string $apiKey = '';

    public string $model = 'claude-opus-4-8';

    // --- ورودی تولید ---
    public string $topic = '';

    // --- خروجی تولیدشده ---
    public bool $hasResult = false;

    public string $genTitle = '';

    public string $genSlug = '';

    public string $genMeta = '';

    public string $genBody = '';

    /** @var array<int, array{question:string, answer:string}> */
    public array $genFaq = [];

    public function mount(): void
    {
        $setting = AiSetting::current();
        $this->apiKey = (string) $setting->api_key;
        $this->model = $setting->model ?: 'claude-opus-4-8';
    }

    /**
     * گزینه‌های مدل هوش مصنوعی برای منوی کشویی.
     *
     * @return array<string, string>
     */
    public function modelOptions(): array
    {
        return [
            'claude-opus-4-8' => 'Claude Opus 4.8 — بهترین کیفیت (گران‌تر)',
            'claude-sonnet-5' => 'Claude Sonnet 5 — متعادل و ارزان‌تر',
            'claude-haiku-4-5' => 'Claude Haiku 4.5 — سریع و ارزان',
        ];
    }

    public function saveSettings(): void
    {
        $setting = AiSetting::current();
        $setting->provider = 'anthropic';
        $setting->model = $this->model ?: 'claude-opus-4-8';
        // فقط اگر مقداری وارد شده باشد کلید را به‌روزرسانی می‌کنیم.
        if (trim($this->apiKey) !== '') {
            $setting->api_key = trim($this->apiKey);
        }
        $setting->save();

        Notification::make()
            ->title('تنظیمات ذخیره شد')
            ->success()
            ->send();
    }

    public function generate(): void
    {
        if (trim($this->topic) === '') {
            Notification::make()
                ->title('موضوع مقاله را وارد کنید')
                ->warning()
                ->send();

            return;
        }

        try {
            $result = app(AiArticleGenerator::class)->generate($this->topic);
        } catch (Throwable $e) {
            Notification::make()
                ->title('تولید مقاله ناموفق بود')
                ->body($e->getMessage())
                ->danger()
                ->send();

            return;
        }

        $this->genTitle = $result['title'];
        $this->genSlug = $result['slug'];
        $this->genMeta = $result['meta_description'];
        $this->genBody = $result['body'];
        $this->genFaq = $result['faq'];
        $this->hasResult = true;

        Notification::make()
            ->title('مقاله تولید شد')
            ->body('می‌توانید متن را بازبینی و ویرایش کنید، سپس به‌عنوان پیش‌نویس ذخیره کنید.')
            ->success()
            ->send();
    }

    public function saveDraft(): void
    {
        if (! $this->hasResult || trim($this->genTitle) === '' || trim($this->genBody) === '') {
            Notification::make()
                ->title('چیزی برای ذخیره نیست')
                ->warning()
                ->send();

            return;
        }

        $slug = $this->makeUniqueSlug($this->genSlug !== '' ? $this->genSlug : $this->genTitle);

        $words = count(preg_split('/\s+/', trim(strip_tags($this->genBody))) ?: []);
        $minutes = max(1, (int) ceil($words / 200));

        $article = new Article();
        $article->user_id = Auth::id();
        $article->title = $this->genTitle;
        $article->slug = $slug;
        $article->body = $this->genBody;
        $article->lang = 'fa';
        $article->status = 0; // پیش‌نویس (منتشرنشده)
        $article->study_time = (string) $minutes;
        $article->faq = $this->genFaq;
        $article->extra_meta = $this->genMeta;
        $article->save();

        Notification::make()
            ->title('پیش‌نویس ذخیره شد ✅')
            ->body('مقاله «' . $this->genTitle . '» به‌عنوان پیش‌نویس ذخیره شد. برای افزودن تصویر، دسته‌بندی و انتشار، به بخش «مقالات» پنل بروید.')
            ->success()
            ->send();

        // پاک‌سازی برای تولید بعدی.
        $this->reset(['topic', 'hasResult', 'genTitle', 'genSlug', 'genMeta', 'genBody', 'genFaq']);
    }

    /**
     * ساخت نامک یکتا؛ اگر تکراری بود یک شماره به انتهایش اضافه می‌کند.
     */
    private function makeUniqueSlug(string $raw): string
    {
        $base = trim($raw);
        // فاصله‌ها/زیرخط به خط تیره، حذف کاراکترهای خطرناک برای URL.
        $base = preg_replace('/[\s_]+/u', '-', $base);
        $base = preg_replace('/[\/\\\\?#&"\'<>]+/u', '', (string) $base);
        $base = trim((string) $base, '-');
        if ($base === '') {
            $base = 'مقاله-' . time();
        }

        $slug = $base;
        $i = 2;
        while (Article::withTrashed()->where('slug', $slug)->exists()) {
            $slug = $base . '-' . $i;
            $i++;
        }

        return $slug;
    }
}
