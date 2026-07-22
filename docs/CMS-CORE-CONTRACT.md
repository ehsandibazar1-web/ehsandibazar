# CMS Core Contract — v1

قرارداد مشترک معماری CMS بین دو پروژه‌ی مستقل:
**ehsandibazar (فارسی)** و **trainwithehsan (انگلیسی)**.

هدف: هر قابلیتِ جدیدِ CMS که برای یک پروژه ساخته می‌شود، با **کمترین تغییر** به دیگری منتقل شود.

---

## اصل بنیادی (چرا قرارداد در سطح interface است، نه ستونِ دیتابیس)

دو پروژه اسکیمای legacy واگرا دارند (فارسی: `App\Model` مفرد، تک‌زبانه، `status` عددی؛
انگلیسی: `App\Models` جمع، چندزبانه، `status` رشته‌ای). اگر قرارداد را روی «ستون‌های یکسان»
ببندیم، مجبور به نابودی داده‌ی زنده‌ی فارسی می‌شویم.

بنابراین **منبع حقیقتِ قرارداد = interface + trait + قراردادهای نام‌گذاری**، نه ستون خام.
- کدِ **قابل‌پورت** = فایل‌های `Contracts/*` و `Concerns/*` (traitها) که **عیناً** بین دو repo کپی می‌شوند.
- هر جا ستون فیزیکی فرق کند: یا accessor/mutator پل می‌زند، یا مهاجرتِ «ارتقای در جا» ستونِ هم‌نام اضافه می‌کند (بدون drop/rename).
- **هر قابلیت جدید علیه interface نوشته می‌شود، نه علیه مدلِ یک پروژه** ⇒ پورت = کپیِ trait/service.

---

## لایه ۱ — Interfaceها (قلب قرارداد)

مسیر یکسان در هر دو پروژه: `app/Cms/Contracts/`

```php
// هر محتوای قابل‌انتشار (Article, Page, ...) این‌ها را با هم دارد:
interface CmsContent extends Sluggable, Localizable, Publishable, Taggable, SeoOptimizable, HasFeaturedMedia {}

interface Sluggable {
    public function getSlug(): string;
    public static function makeSlug(string $title, ?string $locale = null): string;
}

interface Localizable {
    public function getLocale(): string;                 // 'en' | 'tr' | 'fa'
    public function translation();                       // BelongsTo self
    public function translations();                      // HasMany self
}

interface Publishable {
    public function isPublished(): bool;
    public function scopePublished($query);
    public function getPublishedAt(): ?\DateTimeInterface;
    // status ∈ {'draft','scheduled','published','archived'}
}

interface Taggable {
    public function tags(): \Illuminate\Database\Eloquent\Relations\MorphToMany;
}

interface SeoOptimizable {
    public function keywords(): \Illuminate\Database\Eloquent\Relations\MorphMany;
    public function getSeoTitle(): ?string;
    public function getMetaDescription(): ?string;
    public function getCanonicalUrl(): ?string;          // null ⇒ خودِ path()
    public function getRobots(): ?string;                // null ⇒ index,follow
}

interface HasFeaturedMedia {
    public function getImagePath(): ?string;
    public function getImageAlt(): ?string;
    public function getOptimizedImageUrlAttribute(): ?string;   // WebP از Media، fallback به خام
}
```

---

## لایه ۲ — Traitهای مشترک (کدِ قابل‌پورت — عیناً کپی بین دو repo)

مسیر یکسان: `app/Cms/Concerns/`

| trait | interface | مسئولیت |
|---|---|---|
| `HasSlug` | Sluggable | ساخت خودکار slug از عنوان، یکتا per locale |
| `HasLocale` | Localizable | روابط `translation()/translations()` روی `translation_of` |
| `Publishable` | Publishable | `scopePublished` (published یا scheduled سررسیده)، `scopeLocale` |
| `HasContentTags` | Taggable | `tags()` = morphToMany روی pivot `taggables` |
| `HasSeoMeta` | SeoOptimizable | `keywords()` = morphMany، getterهای سئو با fallback |
| `HasFeaturedMedia` | HasFeaturedMedia | `getOptimizedImageUrlAttribute` = `Media::forRecord($this)?->webp_url` |
| `LogsContentActivity` | — | تنظیمات استاندارد spatie/activitylog (logOnlyDirty، useLogName) |

> این هفت فایل **قلبِ پورت‌پذیری‌اند**. فیچر جدید معمولاً یعنی «یک trait جدید اینجا» که بعد در هر دو پروژه `use` می‌شود.

---

## لایه ۳ — Schema هدف (canonical columns)

جدول‌های مشترک باید این ستون‌ها را داشته باشند. روی فارسی با **ارتقای در جا** (فقط `ADD COLUMN`،
هرگز drop/rename) به جدول‌های موجودِ ۲۰۱۸ اضافه می‌شوند.

### `articles` / `pages`
```
id
locale            string  index         # 'fa' برای فارسی، 'en'/'tr' برای انگلیسی
translation_of    fk self  nullable      # لینک نسخه‌های هم‌زبان
title             string
slug              string  (unique per locale)
excerpt           text    nullable       # فقط articles
body              longText
category          string  nullable       # فقط articles
faqs              json    nullable
seo_title         string  nullable
meta_description  text    nullable
meta_keywords     string  nullable       # فقط pages
canonical_url     string  nullable
robots            string  nullable
og_title          string  nullable
og_description    text    nullable
image_path        string  nullable
image_alt         string  nullable
author_name       string  nullable       # فقط articles
reading_time      uint    nullable       # فقط articles
views             uint    default 0      # فقط articles
status            string  default 'draft'
published_at      timestamp nullable
hero_image_prompt / thumbnail_image_prompt / og_image_prompt / social_image_prompt  text nullable  # متادیتای AI
timestamps
```
> فارسی ستون‌های legacy خودش (مثل `status` عددی قدیم) را نگه می‌دارد؛ اگر تعارض معنایی بود،
> accessor پل می‌زند یا ستونِ canonicalِ جدید مرجع می‌شود و legacy همگام نگه داشته می‌شود.

### `tags` + pivot `taggables`
```
tags:      id, name, slug(unique), color, timestamps
taggables: tag_id, taggable_type, taggable_id   (morph)
```

### `keywords` (هدفِ سئو، جدا از tags)
```
keywords:  id, keyword, keywordable_type, keywordable_id (morph), timestamps
# locale از رکورد والد به ارث می‌رسد — ستون locale جدا ندارد (یک منبع حقیقت)
```

### `media` + `media_folders`
```
media:         id, original_name, disk, disk_path, url, type, mime_type, size,
               folder_id fk nullable, alt_text, caption, description,
               width, height, duration_seconds,
               webp_path, thumbnail_path, responsive_paths(json), timestamps
media_folders: id, name, parent_id fk self nullable, timestamps
```
> **قانون اتصال Media:** رکورد Media با **تطبیق `disk_path`** به Article/Page وصل می‌شود، نه FK.
> برای فارسی، رابطه را از ابتدا **polymorphic-آماده** طراحی کن (`mediable_type/mediable_id` اختیاری)
> تا بعداً بتواند مدیای محصولاتِ فروشگاه را هم جذب کند، بدون تغییر قرارداد.

### `site_settings`
```
site_settings: id, key(unique), value(longText), group, timestamps
# API: SiteSetting::get / getJson / set($key,$value,$group)
# Homepage/About/Footer/Menu همگی ردیف‌های همین جدول‌اند، فقط group فرق دارد.
```

---

## لایه ۴ — قراردادهای نام‌گذاری (ثابت و غیرقابل‌مذاکره)

اگر این‌ها بین دو پروژه یکی نباشند، پورت می‌شکند:

| مورد | استاندارد |
|---|---|
| نام روابط | `tags()`, `keywords()`, `contentPlan()`, `translation()`, `translations()` |
| pivot برچسب | جدول `taggables`، ستون‌های `taggable_type/taggable_id` |
| morph کلیدواژه | `keywordable_type/keywordable_id` |
| morph پلن محتوا | `contentable_type/contentable_id` |
| مقادیر status | `'draft' \| 'scheduled' \| 'published' \| 'archived'` |
| مقادیر locale | `'en' \| 'tr' \| 'fa'` |
| slug | `Str::slug`، یکتا per locale |
| اتصال media | با `disk_path`، نه FK |
| settings | `key/value/group` + `get/getJson/set` |

### ⭐ مهم‌ترین قاعده: نقشه‌ی morph ثابت (`Relation::enforceMorphMap`)

ستون‌های چندریختی نامِ کلاس را ذخیره می‌کنند. فارسی `App\Model\Article` (مفرد) و انگلیسی
`App\Models\Article` (جمع) است. بدون نقشه‌ی morph، **دادهٔ pivot بین دو دیتابیس قابل‌انتقال نیست**.
هر دو پروژه باید در `AppServiceProvider::boot()` این نقشه را با **aliasهای رشته‌ای ثابت** ثبت کنند:

```php
Relation::enforceMorphMap([
    'article'         => \App\{Model|Models}\Article::class,
    'page'            => \App\{Model|Models}\Page::class,
    'tag'             => \App\{Model|Models}\Tag::class,
    'knowledge_entry' => \App\Models\KnowledgeEntry::class,
    'content_plan'    => \App\Models\ContentPlan::class,
]);
```
> نتیجه‌ی جانبی مهم: با وجود این نقشه، **اختلافِ namespace دیگر مانع پورت نیست** — پس انتقال
> `Article/Page/Tag` فارسی به `App\Models` (جمع) صرفاً زیبایی‌شناختی می‌شود، نه الزامِ فنی.

---

## لایه ۵ — Service Contractها

تا موتور AI و ابزارها یک API پایدار صدا بزنند (نه پیاده‌سازیِ خاصِ یک پروژه). مسیر: `app/Cms/Contracts/`

```php
interface MediaLibrary {
    public function forRecord(Model $record): ?Media;          // تصویر شاخصِ یک رکورد
    public function optimizedUrl(?string $diskPath, ?int $maxWidth = null): ?string;
    public function store(UploadedFile $file, ?int $folderId = null): Media;   // + WebP/thumbnail/responsive
}

interface SeoService {
    public function metaFor(CmsContent $content): array;       // آرایه‌ی آمادهٔ <head>
    public function keywordScore(CmsContent $content): int;
}

interface ContentAssistant {                                   // موتور تولید محتوا
    public function generate(string $field, string $mode, array $context): string;
    public function previewSystemPrompt(string $field, string $mode, string $locale): string;
}

interface AiProvider { /* از قبل پورت‌شده — AnthropicProvider | NullProvider */ }
```

هر پروژه پیاده‌سازیِ خودش را در container به این interfaceها bind می‌کند؛ کدِ فیچرها فقط
interface را می‌بیند ⇒ همان فیچر بدون تغییر روی هر دو اجرا می‌شود.

---

## لایه ۶ — نسخه‌بندی و انضباط همگام‌سازی

1. این سند = **Contract v1**. کپیِ یکسانِ آن در هر دو repo (`docs/CMS-CORE-CONTRACT.md`).
2. هر تغییرِ شکننده در interfaceها ⇒ **bump نسخه** + یک ردیف در Changelog پایین + اعمال در هر دو repo.
3. جریانِ ساخت قابلیت جدید:
   `interface/trait را در Contract به‌روزرسانی کن → در پروژه‌ی مبدأ پیاده کن و تست کن →
    همان فایل‌های Concerns/Contracts را به پروژه‌ی مقصد کپی کن → مهاجرتِ ارتقای‌در‌جا برای ستون‌های جدید`.
4. **تستِ قرارداد** (در هر دو repo یکسان): تستی که مطمئن شود هر مدلِ `CmsContent` تمام متدهای
   interface را دارد و morphMap کامل است — تا drift زودتر از production گیر بیفتد.

---

## وضعیت پیاده‌سازی (اسکلتِ کد)

اسکلتِ Core در `app/Cms/` ساخته شد (همه پیوریِ کد، صفر ریسکِ DB، با ۷ تستِ قرارداد در
`tests/Unit/CmsCoreContractTest.php`):

- `app/Cms/Enums/` → `ContentStatus`, `Locale`
- `app/Cms/Contracts/` → `Sluggable`, `Localizable`, `Publishable`, `Taggable`, `SeoOptimizable`,
  `HasFeaturedMedia`, `CmsContent` (ترکیب) + سرویس‌ها: `MediaLibrary`, `SeoService`, `ContentAssistant`
- `app/Cms/Concerns/` → `HasSlug`, `HasLocale`, `HasPublishing`, `HasContentTags`, `HasSeoMeta`,
  `ProvidesFeaturedMedia`, `LogsContentActivity`
- `app/Cms/MorphMap.php` → منبعِ حقیقتِ aliasها

> نگاشتِ interface↔trait: Sluggable↔HasSlug · Localizable↔HasLocale · Publishable↔HasPublishing ·
> Taggable↔HasContentTags · SeoOptimizable↔HasSeoMeta · HasFeaturedMedia↔ProvidesFeaturedMedia.

⚠️ **MorphMap هنوز register نشده.** فعال‌سازیِ `Relation::morphMap` روی دادهٔ زنده‌ی موجود بدونِ
backfill، لوکاپ‌های چندریختیِ فعلی را می‌شکند (taggables قدیمیِ فروشگاه + activity_log). فعال‌سازی
در موج ۴ همراه با یک مهاجرتِ backfill انجام می‌شود. تا آن زمان `MorphMap::map()` فقط مرجع است.

## Changelog
- **v1 (تعریف اولیه):** Article, Page, Tag, Keyword(SEO), Media/MediaFolder, SiteSetting +
  ۷ trait مشترک + نقشه‌ی morph ثابت + ۴ service contract.
- **v1.1 (اسکلتِ کد):** `app/Cms/` ساخته شد (Enums + Contracts + Concerns + MorphMap) + تستِ قرارداد.
  فعال‌سازیِ MorphMap به موج ۴ (بعد از backfill) موکول شد.
