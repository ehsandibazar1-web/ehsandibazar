<?php

namespace App\Services\Content;

use App\Model\Article;
use App\Model\Page;
use App\Model\Tag;
use App\User;
use App\Utility\Level;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * تنها لایه‌ی نگاشتِ Contract → اسکیمای زنده‌ی فارسی. هر مسیرِ ساختِ محتوا (ایمپورت، ترجمه،
 * چت، …) از همین‌جا عبور می‌کند؛ قواعدِ نگاشت فقط اینجا زندگی می‌کنند. وقتی همگراییِ کاملِ
 * ستون‌ها (locale/status رشته‌ای، Keyword، Tagِ همگرا) برسد، فقط همین فایل عوض می‌شود.
 *
 * payload شکلِ Contract دارد (کلیدها: locale, translation_of, title, slug?, excerpt?, body,
 * faqs?, category?, seo_title?, meta_description?, og_title?, og_description?, canonical_url?,
 * robots?, meta_keywords?(page), image_path?, image_alt?, author_name?(article),
 * reading_time?(article), status?, tags?, keywords?).
 *
 * قواعدِ کلیدی:
 *  - locale → ستونِ زنده‌ی `lang` (پیش‌فرض 'fa'). ستونِ `locale` وجود ندارد و هرگز نوشته نمی‌شود.
 *  - status → بولین: منتشر فقط اگر صراحتاً published/1/true باشد؛ در غیر این‌صورت ۰ (پیش‌نویس).
 *    ایمپورت/ترجمه پیش‌نویس می‌سازند، پس نبودِ status یعنی ۰.
 *  - slug → اگر نبود از روی title؛ یکتا در همان `lang`.
 *  - user_id → auth()->id() و در نبودش نخستین کاربرِ ادمین.
 *  - faqs → مستقیم (مدل به array کست می‌کند).
 *  - tags (نام‌ها) → پس از ساخت، از طریق رابطه‌ی موجودِ tags() به‌صورت find-or-create وصل می‌شوند.
 */
class ContentDraftFactory
{
    /**
     * ساختِ پیش‌نویسِ Article از payloadِ شکل‌گرفته‌ی Contract.
     */
    public function createArticleDraft(array $payload): Article
    {
        $lang = $this->resolveLang($payload);

        // فقط ستون‌هایی که واقعاً روی جدولِ articles وجود دارند نوشته می‌شوند — نگاشتِ صریح
        // (نه نوشتنِ کورکورانه‌ی همه‌ی کلیدها) تا کلیدهایی مثل category/keywords که ستون نیستند
        // باعث خطای «Unknown column» نشوند.
        $attributes = $this->baseAttributes($payload, $lang, Article::class);

        $attributes += $this->pick($payload, [
            'excerpt',
            'author_name',
            'reading_time',
        ]);

        $attributes += $this->sharedMetaAttributes($payload);

        // faqs مستقیم پاس داده می‌شود؛ مدل خودش به array کست می‌کند.
        if (array_key_exists('faqs', $payload) && is_array($payload['faqs'])) {
            $attributes['faqs'] = $payload['faqs'];
        }

        $article = Article::create($attributes);

        $this->attachTags($article, $payload, $lang);

        // موقتاً: category در سایتِ فارسی یک رابطه‌ی morphToMany (نه ستون) با مدلِ خاصِ خودش است
        // و هنوز بخشی از همگراییِ Contract نیست — کلیدِ category پذیرفته ولی نادیده گرفته می‌شود.

        // TODO(5f/6): keywords — مدلِ App\Models\Keyword هنوز پورت نشده؛ کلید پذیرفته و نادیده گرفته می‌شود.

        return $article;
    }

    /** فیلدهایی که در چرخه‌ی «خروجی → ویرایشِ AI → ورودی» قابلِ به‌روزرسانی‌اند (فقط محتوا/سئو). */
    public const ROUNDTRIP_UPDATABLE = [
        'title', 'body', 'excerpt',
        'seo_title', 'meta_description', 'canonical_url', 'robots',
        'og_title', 'og_description',
        'image_alt', 'author_name', 'reading_time',
    ];

    /**
     * به‌روزرسانیِ یک مقاله‌ی موجود از payload — فقط فیلدهای محتوا/سئو. خطوطِ قرمز که هرگز از فایل
     * عوض نمی‌شوند: slug (URL)، status/published_at (وضعیتِ انتشار)، user_id، lang، translation_of،
     * viewCount. اگر $apply=false باشد چیزی ذخیره نمی‌شود (برای پیش‌نمایش). خروجی:
     *   ['changes' => [field => ['old'=>..,'new'=>..]], 'slug_ignored' => bool, 'incoming_slug' => ?string]
     *
     * @return array{changes: array<string, array{old: mixed, new: mixed}>, slug_ignored: bool, incoming_slug: ?string}
     */
    public function updateArticleFromPayload(Article $article, array $payload, bool $apply = true): array
    {
        $changes = [];
        $dirty = [];

        foreach (self::ROUNDTRIP_UPDATABLE as $field) {
            if (! array_key_exists($field, $payload) || $payload[$field] === null) {
                continue;
            }
            $new = $payload[$field];
            $old = $article->getAttribute($field);
            if ((string) $old !== (string) $new) {
                $changes[$field] = ['old' => $old, 'new' => $new];
                $dirty[$field] = $new;
            }
        }

        // faqs (آرایه) — مقایسه‌ی ساختاری با FAQِ «مؤثرِ» فعلی (اول ستونِ legacyِ faq، بعد faqs) —
        // همان اولویتی که storefront دارد، تا برای مقاله‌های قدیمی درست مقایسه شود.
        if (array_key_exists('faqs', $payload) && is_array($payload['faqs'])) {
            $oldFaqs = (is_array($article->faq) && $article->faq !== [])
                ? $article->faq
                : (is_array($article->faqs) ? $article->faqs : []);
            if (json_encode($oldFaqs, JSON_UNESCAPED_UNICODE) !== json_encode($payload['faqs'], JSON_UNESCAPED_UNICODE)) {
                $changes['faqs'] = ['old' => count($oldFaqs).' مورد', 'new' => count($payload['faqs']).' مورد'];
                $dirty['faqs'] = $payload['faqs'];
                // ستونِ قدیمیِ faq را خالی می‌کنیم تا storefront (faq ?: faqs) نسخه‌ی جدید را نشان دهد.
                if (is_array($article->faq) && $article->faq !== []) {
                    $dirty['faq'] = [];
                }
            }
        }

        // خطِ قرمزِ URL: slug هرگز از فایل تغییر نمی‌کند — فقط تشخیص می‌دهیم که AI عوضش کرده یا نه.
        $incomingSlug = $payload['slug'] ?? null;
        $slugIgnored = filled($incomingSlug) && (string) $incomingSlug !== (string) $article->slug;

        if ($apply && $dirty !== []) {
            // فقط $dirty نوشته می‌شود؛ slug/status/user_id/lang در آن نیستند پس دست‌نخورده می‌مانند.
            $article->update($dirty);

            // تگ‌ها (اگر در فایل آمده) دوباره همگام می‌شوند.
            if (isset($payload['tags']) && is_array($payload['tags'])) {
                $this->attachTags($article, $payload, $article->lang);
            }
        }

        return [
            'changes' => $changes,
            'slug_ignored' => $slugIgnored,
            'incoming_slug' => $incomingSlug,
        ];
    }

    /**
     * ساختِ پیش‌نویسِ Page از payloadِ شکل‌گرفته‌ی Contract.
     */
    public function createPageDraft(array $payload): Page
    {
        $lang = $this->resolveLang($payload);

        $attributes = $this->baseAttributes($payload, $lang, Page::class);

        // meta_keywords فقط روی جدولِ pages وجود دارد (نه articles).
        $attributes += $this->pick($payload, [
            'meta_keywords',
        ]);

        $attributes += $this->sharedMetaAttributes($payload);

        if (array_key_exists('faqs', $payload) && is_array($payload['faqs'])) {
            $attributes['faqs'] = $payload['faqs'];
        }

        $page = Page::create($attributes);

        $this->attachTags($page, $payload, $lang);

        // موقتاً: category — همان توضیحِ createArticleDraft.
        // TODO(5f/6): keywords — همان توضیحِ createArticleDraft.

        return $page;
    }

    /**
     * ستون‌های پایه‌ی مشترکِ هر دو مدل: زبان، وضعیت، عنوان، اسلاگ، بدنه، مالک، رابطه‌ی ترجمه.
     *
     * @param  class-string<Model>  $modelClass
     */
    private function baseAttributes(array $payload, string $lang, string $modelClass): array
    {
        $title = (string) ($payload['title'] ?? '');

        $attributes = [
            'lang' => $lang,
            'status' => $this->resolveStatus($payload['status'] ?? null),
            'title' => $title,
            'body' => $payload['body'] ?? null,
            'slug' => $this->uniqueSlug($modelClass, $payload['slug'] ?? null, $title, $lang),
            'user_id' => $this->resolveUserId(),
        ];

        if (array_key_exists('translation_of', $payload) && $payload['translation_of'] !== null) {
            $attributes['translation_of'] = $payload['translation_of'];
        }

        return $attributes;
    }

    /**
     * ستون‌های متادیتای سئو/سوشالِ مشترکِ هر دو مدل که همگی روی جدول‌ها وجود دارند.
     */
    private function sharedMetaAttributes(array $payload): array
    {
        return $this->pick($payload, [
            'seo_title',
            'meta_description',
            'canonical_url',
            'robots',
            'og_title',
            'og_description',
            'image_path',
            'image_alt',
            'published_at',
        ]);
    }

    /**
     * فقط کلیدهایی را برمی‌دارد که در payload حاضر و غیرِnull‌اند — تا مقدارِ خالی چیزی را بازننویسد.
     */
    private function pick(array $payload, array $keys): array
    {
        $result = [];

        foreach ($keys as $key) {
            if (array_key_exists($key, $payload) && $payload[$key] !== null) {
                $result[$key] = $payload[$key];
            }
        }

        return $result;
    }

    /**
     * locale → lang (پیش‌فرض 'fa').
     */
    private function resolveLang(array $payload): string
    {
        $locale = $payload['locale'] ?? null;

        return $locale !== null && $locale !== '' ? (string) $locale : 'fa';
    }

    /**
     * نگاشتِ status به بولینِ زنده: منتشر فقط برای مقادیرِ صریحِ published/1/true.
     */
    private function resolveStatus(mixed $status): int
    {
        return in_array($status, ['published', '1', 1, true], true) ? 1 : 0;
    }

    /**
     * مالکِ رکورد: کاربرِ واردشده، و در نبودش نخستین کاربرِ ادمین/سوپرادمین (و در نهایت نخستین کاربر).
     */
    private function resolveUserId(): ?int
    {
        if ($id = auth()->id()) {
            return $id;
        }

        $adminId = User::query()
            ->whereIn('level', [Level::SUPER_ADMIN, Level::ADMIN])
            ->orderBy('id')
            ->value('id');

        return $adminId ?? User::query()->orderBy('id')->value('id');
    }

    /**
     * اسلاگِ یکتا در همان زبان. اگر اسلاگ داده نشده باشد از title ساخته می‌شود؛ در تصادم -xxxx.
     *
     * @param  class-string<Model>  $modelClass
     */
    private function uniqueSlug(string $modelClass, ?string $slug, string $title, string $lang): string
    {
        $base = $slug !== null && trim($slug) !== '' ? Str::slug($slug) : Str::slug($title);

        // Str::slug روی متنِ فارسی می‌تواند رشته‌ی خالی بدهد — در آن صورت یک پایه‌ی تصادفیِ امن.
        if ($base === '') {
            $base = Str::lower(Str::random(8));
        }

        $candidate = $base;

        while ($modelClass::where('lang', $lang)->where('slug', $candidate)->exists()) {
            $candidate = $base.'-'.Str::lower(Str::random(4));
        }

        return $candidate;
    }

    /**
     * برچسب‌ها (نام‌ها) را از طریقِ رابطه‌ی موجودِ tags() وصل می‌کند — find-or-create به‌صورتِ
     * App\Model\Tag بر اساسِ title، با status=1 و lang همان رکورد. استفاده از رابطه یعنی
     * سازگاری با MorphMapِ آینده حفظ می‌شود.
     */
    private function attachTags(Model $record, array $payload, string $lang): void
    {
        $tags = $payload['tags'] ?? null;

        if (! is_array($tags) || $tags === []) {
            return;
        }

        $ids = [];

        foreach ($tags as $name) {
            $name = trim((string) $name);

            if ($name === '') {
                continue;
            }

            // Sluggable روی مدلِ Tag خودش slug را از روی title می‌سازد.
            $tag = Tag::firstOrCreate(
                ['title' => $name, 'lang' => $lang],
                ['status' => 1],
            );

            $ids[] = $tag->id;
        }

        if ($ids !== []) {
            $record->tags()->syncWithoutDetaching($ids);
        }
    }
}
