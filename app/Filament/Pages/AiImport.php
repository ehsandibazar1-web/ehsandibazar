<?php

namespace App\Filament\Pages;

use App\Filament\Resources\Articles\ArticleResource;
use App\Model\Article;
use App\Models\ImportLog;
use App\Services\Content\ContentDraftFactory;
use BackedEnum;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;
use UnitEnum;

/**
 * ایمپورتِ متمرکز و باثباتِ محتوا — پورتِ کاربردی (نه بایت‌به‌بایتِ) صفحه‌ی «AI Import» انگلیسی.
 *
 * موقتاً: ArticleImportServiceِ ۱۱۳۷ خطیِ انگلیسی (parseِ چندفرمتیِ XML/HTML/custom markers،
 * استخراجِ keyword، پیشنهادِ لینکِ داخلی، rollbackِ پیچیده) عمداً پورت نشده. به‌جایش این صفحه
 * ساده است: یا JSONِ هم‌شکلِ payloadِ Contract را می‌گیرد، یا فیلدهای ساختاریافته‌ی فرم را؛ سپس
 * یک payloadِ Contract می‌سازد، App\Services\Content\ContentDraftFactory::createArticleDraft را
 * صدا می‌زند و یک ردیفِ ImportLog می‌نویسد. تاریخچه‌ی سبکِ ایمپورت روی همین صفحه نگه داشته می‌شود.
 */
class AiImport extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSparkles;

    protected static string|UnitEnum|null $navigationGroup = 'AI Studio';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationLabel = 'AI Import';

    protected static ?string $title = 'ایمپورت محتوا با هوش مصنوعی';

    protected string $view = 'filament.pages.ai-import';

    public ?array $data = [];

    /** اطلاعاتِ آخرین ایمپورتِ موفق — برای نمایشِ کارتِ نتیجه در ویو. */
    public ?array $importedInfo = null;

    public function mount(): void
    {
        $this->form->fill([
            'locale' => 'fa',
            'status' => 'draft',
            'title' => '',
            'body' => '',
            'excerpt' => '',
            'tags' => '',
            'json' => '',
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('محتوای مقاله')
                    ->description('فیلدها را پر کنید یا در بخشِ پیشرفته یک JSONِ هم‌شکلِ payload بچسبانید. چیزی تا زدنِ «ایمپورت» ذخیره نمی‌شود. هر ایمپورت همیشه به‌صورتِ پیش‌نویس ساخته می‌شود مگر آنکه وضعیت را «منتشر شده» بگذارید.')
                    ->columns(2)
                    ->schema([
                        Select::make('locale')
                            ->label('زبان')
                            ->options([
                                'fa' => 'فارسی',
                                'en' => 'English',
                                'tr' => 'Türkçe',
                            ])
                            ->default('fa')
                            ->selectablePlaceholder(false),

                        Select::make('status')
                            ->label('وضعیت انتشار')
                            ->options([
                                'draft' => 'پیش‌نویس',
                                'published' => 'منتشر شده',
                            ])
                            ->default('draft')
                            ->selectablePlaceholder(false),

                        TextInput::make('title')
                            ->label('عنوان')
                            ->columnSpanFull(),

                        Textarea::make('body')
                            ->label('متن مقاله')
                            ->rows(12)
                            ->columnSpanFull(),

                        Textarea::make('excerpt')
                            ->label('خلاصه (اختیاری)')
                            ->rows(2)
                            ->columnSpanFull(),

                        TextInput::make('tags')
                            ->label('برچسب‌ها (اختیاری)')
                            ->helperText('با کامّا جدا کنید.')
                            ->columnSpanFull(),
                    ]),

                Section::make('پیشرفته: چسباندنِ JSON')
                    ->description('اگر این کادر پر باشد، بر فیلدهای بالا اولویت دارد. باید یک شیءِ JSON با کلیدهای Contract باشد (locale، title، body، excerpt?، faqs?، tags?، seo_title?، meta_description? و …).')
                    ->collapsed()
                    ->schema([
                        Textarea::make('json')
                            ->label('payload به‌صورت JSON')
                            ->rows(10),
                    ]),
            ])
            ->statePath('data');
    }

    public function runImport(): void
    {
        $state = $this->form->getState();
        $this->importedInfo = null;

        try {
            $payload = $this->buildPayload($state);
        } catch (Throwable $e) {
            Notification::make()->danger()->title('JSON نامعتبر است')->body($e->getMessage())->send();

            return;
        }

        if (blank($payload['title'] ?? null) || blank($payload['body'] ?? null)) {
            Notification::make()->danger()->title('عنوان و متن مقاله الزامی‌اند')->send();

            return;
        }

        try {
            $article = app(ContentDraftFactory::class)->createArticleDraft($payload);
        } catch (Throwable $e) {
            ImportLog::create([
                'user_id' => auth()->id(),
                'source' => 'panel',
                'format' => ! empty($state['json']) ? 'json' : 'fields',
                'status' => 'failed',
                'errors' => [$e->getMessage()],
                'locale' => $payload['locale'] ?? 'fa',
                'article_title' => $payload['title'] ?? null,
            ]);

            Notification::make()->danger()->title('ایمپورت ناموفق بود')->body($e->getMessage())->send();

            return;
        }

        ImportLog::create([
            'user_id' => auth()->id(),
            'source' => 'panel',
            'format' => ! empty($state['json']) ? 'json' : 'fields',
            'status' => 'imported',
            'article_id' => $article->id,
            'article_title' => $article->title,
            'locale' => $article->lang,
            'faq_count' => is_array($article->faqs) ? count($article->faqs) : 0,
            'image_count' => $article->image_path ? 1 : 0,
        ]);

        $this->importedInfo = [
            'title' => $article->title,
            'published' => (int) $article->status === 1,
            'edit_url' => ArticleResource::getUrl('edit', ['record' => $article->id]),
        ];

        $this->form->fill([
            'locale' => $state['locale'] ?? 'fa',
            'status' => 'draft',
            'title' => '',
            'body' => '',
            'excerpt' => '',
            'tags' => '',
            'json' => '',
        ]);

        Notification::make()->success()->title('مقاله ایمپورت شد: '.$article->title)->send();
    }

    /**
     * یک payloadِ شکل‌گرفته‌ی Contract می‌سازد — یا از JSONِ چسبانده‌شده (اولویت‌دار) یا از فیلدهای فرم.
     */
    private function buildPayload(array $state): array
    {
        // مسیرِ JSON: هرچه در payload باشد مستقیم به کارخانه می‌رود؛ فقط locale را در نبودش پر می‌کنیم.
        if (! empty($state['json'])) {
            $decoded = json_decode((string) $state['json'], true);

            if (! is_array($decoded)) {
                throw new \RuntimeException('ساختارِ JSON قابلِ خواندن نیست.');
            }

            $normalized = $this->normalizeImportPayload($decoded);
            $normalized['locale'] ??= $state['locale'] ?? 'fa';

            return $normalized;
        }

        // مسیرِ فیلدها.
        $payload = [
            'locale' => $state['locale'] ?? 'fa',
            'status' => $state['status'] ?? 'draft',
            'title' => trim((string) ($state['title'] ?? '')),
            'body' => (string) ($state['body'] ?? ''),
        ];

        if (filled($state['excerpt'] ?? null)) {
            $payload['excerpt'] = trim((string) $state['excerpt']);
        }

        if (filled($state['tags'] ?? null)) {
            $payload['tags'] = array_values(array_filter(array_map('trim', explode(',', (string) $state['tags']))));
        }

        return $payload;
    }

    /**
     * فرمتِ غنیِ خروجیِ سایت انگلیسی (یا هر نامِ مستعارِ رایج) را به payloadِ هم‌شکلِ Contract نگاشت
     * می‌کند: language→locale، content→body، publish_status→status، faq→faqs، seo/og تودرتو →
     * ستون‌های تخت، featured_image (URL) → دانلود و image_path. کلیدهای ناشناخته (internal_links،
     * external_links، keywords، provider، …) نادیده گرفته می‌شوند — ContentDraftFactory هم فقط
     * ستون‌های واقعی را می‌نویسد. مقادیرِ خالی حذف می‌شوند تا چیزی را بازننویسند.
     */
    private function normalizeImportPayload(array $raw): array
    {
        $seo = is_array($raw['seo'] ?? null) ? $raw['seo'] : [];
        $og = is_array($raw['og'] ?? null) ? $raw['og'] : [];

        $image = $raw['image_path'] ?? $raw['featured_image'] ?? null;
        if (filled($image) && Str::startsWith((string) $image, ['http://', 'https://'])) {
            $image = $this->downloadImage((string) $image); // null در صورتِ شکست
        }

        $faqs = $raw['faqs'] ?? $raw['faq'] ?? null;
        $tags = $raw['tags'] ?? null;

        $payload = [
            'locale' => $raw['locale'] ?? $raw['language'] ?? $raw['lang'] ?? null,
            'title' => $raw['title'] ?? null,
            'slug' => $raw['slug'] ?? null,
            'excerpt' => $raw['excerpt'] ?? null,
            'body' => $raw['body'] ?? $raw['content'] ?? null,
            'status' => $raw['status'] ?? $raw['publish_status'] ?? 'draft',
            'faqs' => is_array($faqs) ? $faqs : null,
            'tags' => is_array($tags) ? $tags : null,
            'seo_title' => $raw['seo_title'] ?? ($seo['title'] ?? null),
            'meta_description' => $raw['meta_description'] ?? ($seo['meta_description'] ?? null),
            'meta_keywords' => $raw['meta_keywords'] ?? null,
            'canonical_url' => $raw['canonical_url'] ?? null,
            'robots' => $raw['robots'] ?? null,
            'og_title' => $raw['og_title'] ?? ($og['title'] ?? null),
            'og_description' => $raw['og_description'] ?? ($og['description'] ?? null),
            'image_path' => $image,
            'image_alt' => $raw['image_alt'] ?? null,
            'author_name' => $raw['author_name'] ?? null,
            'reading_time' => $raw['reading_time'] ?? null,
            'published_at' => $raw['published_at'] ?? ($raw['publish_date'] ?? null),
            'translation_of' => filled($raw['translation_of'] ?? null) ? $raw['translation_of'] : null,
        ];

        // مقادیرِ null/'' حذف — تا کلیدِ خالی چیزی را بازننویسد و translation_of=''باعثِ خطای FK نشود.
        return array_filter($payload, fn ($v) => $v !== null && $v !== '');
    }

    /**
     * تصویرِ شاخصِ URL-دار را به دیسکِ public دانلود می‌کند و مسیرِ نسبی را برمی‌گرداند (یا null در
     * صورتِ شکست، تا ایمپورت به‌خاطرِ تصویر متوقف نشود). تولیدِ WebP/ثبت در کتابخانه‌ی رسانه بعداً.
     */
    private function downloadImage(string $url): ?string
    {
        try {
            $response = Http::timeout(20)->get($url);

            if (! $response->successful() || blank($response->body())) {
                return null;
            }

            $ext = pathinfo((string) parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION) ?: 'jpg';
            $path = 'articles/imported/'.Str::random(16).'.'.strtolower($ext);

            Storage::disk('public')->put($path, $response->body());

            return $path;
        } catch (Throwable $e) {
            return null;
        }
    }

    /** ۲۰ ایمپورتِ اخیر — برای جدولِ تاریخچه در ویو. */
    public function getRecentLogsProperty(): Collection
    {
        return ImportLog::with('user')->latest()->take(20)->get();
    }

    /**
     * بازگردانیِ یک ایمپورتِ موفق مستقیماً از جدولِ «واردات اخیر» — مقاله‌ی ساخته‌شده soft-delete
     * می‌شود و ردیفِ لاگ به‌عنوانِ بازگردانده‌شده علامت می‌خورد.
     *
     * موقتاً: rollbackِ کاملِ انگلیسی (بازگردانیِ رسانه‌ها، keywordها، لینک‌های داخلی) پورت نشده؛
     * چون این صفحه هیچ‌کدام را در زمانِ ایمپورت نمی‌سازد، حذفِ خودِ مقاله کافی است.
     */
    public function rollbackLog(int $logId): void
    {
        $log = ImportLog::find($logId);

        if (! $log || ! $log->canRollBack()) {
            Notification::make()->danger()->title('این ایمپورت قابلِ بازگردانی نیست')->send();

            return;
        }

        $article = Article::find($log->article_id);

        if ($article) {
            $article->delete();
        }

        $log->update([
            'rolled_back_at' => now(),
            'rolled_back_by' => auth()->id(),
        ]);

        Notification::make()->success()->title('ایمپورت بازگردانده شد')->send();
    }
}
