# نقشه‌ی موج ۴ — ارتقای Article / Page / Tag (ارتقای در جا)

> اولین باری که به **محتوای زنده** دست می‌زنیم. اصلِ حاکم: **storefront بایت‌به‌بایت دست‌نخورده
> می‌ماند**؛ فقط پنلِ ادمین همگرا می‌شود و روی همان جدولِ زنده می‌نویسد. هر زیرمرحله جداگانه با
> دروازه‌ی SEO تأیید می‌شود. طبق docs/SEO-RED-LINE.md و docs/CMS-CORE-CONTRACT.md.

---

## واقعیتِ فعلی (از روی کد، نه فرض)

**جدول `articles`** (۲۰۱۸): `user_id`, `title`, `slug`, `lang`(پیش‌فرض fa), `body`, `extra_meta`,
`status`(**بولین** default 0), `study_time`, softDeletes, timestamps.
**جدول `pages`**: همان شکل.

**storefront** (`app/Http/Controllers/Site/SiteController.php`):
- `article($slug)`: `Article::with(['category','image','user'])->where('slug',$slug)->where('status',1)->first()`
- در sitemap: `where('status', 1)` + `where('lang','fa')` برای pages.
- URLهای ایندکس‌شده: `/article/{slug}`, `/blog`, `/category-article/{slug}`, `/article/tag/{slug}`, `/page/{slug}`.

**مدلِ `App\Model\Article`** traitها: `SoftDeletes, HasImage, HasComment, HasTag, Rateable, HasCategory, HasSeo`.
- تصویر: `HasImage` (جدولِ polymorphicِ images) — نه `image_path`.
- سئو: `HasSeo` → `morphOne(Seo, 'seoable')` + `extra_meta` — نه ستون‌های سئو روی مقاله.
- برچسب: `HasTag` → جدول‌های `tags`(title/status/lang) + `taggables` قدیمی.

**سه ناسازگاری با Contract که باید «پل» بخورند (نه جایگزین):**
1. `status` بولین ↔ Contract مقدارِ رشته‌ای می‌خواهد. **storefront به `status=1` وابسته است.**
2. `lang` ↔ Contract `locale` می‌خواهد.
3. سئو در جدولِ جدا (`seo`) ↔ Contract ستون‌های سئو روی مقاله.

---

## اصلِ کلیدیِ موج ۴: «پل بزن، جایگزین نکن»

مدلِ `Article`، interfaceِ `CmsContent` را **پیاده می‌کند**، ولی هرجا ستونِ فیزیکی فرق دارد،
**accessor/bridge** می‌نویسیم که همان ستونِ legacy را بخواند (طبق «اصلِ بنیادی» Contract):

- `isPublished()` / `scopePublished()` → روی همان `status` بولینِ زنده (`where('status', 1)`) — **نه** enum رشته‌ای.
  یعنی traitِ `HasPublishing`ِ Core را روی این مدل **use نمی‌کنیم**؛ به‌جایش یک پیاده‌سازیِ سفارشیِ پل‌زننده.
- `getLocale()` → `$this->lang`.
- ستون‌های سئوی canonical **اضافه** می‌شوند، ولی **رندرِ <head>ی storefront همچنان از `HasSeo` می‌خواند** —
  تا وقتی در مرحله‌ی جدا (و اختیاریِ) بعدی با ۳۰۱/تستِ کامل repoint شود. صفر تغییرِ خروجیِ فعلی.

نتیجه: پنلِ جدیدِ Article ظاهر و تجربه‌ی سایت انگلیسی را دارد، روی همان جدول می‌نویسد، ولی
storefront و sitemap و خروجیِ سئو **هیچ تغییری نمی‌کنند**.

---

## زیرمرحله‌ها (هرکدام: کد → Pull/optimize/migrate روی استگینگ → **دروازه‌ی SEO سبز** → بعدی)

### ۴a — ستون‌های canonicalِ افزودنی  (صفر تغییرِ رفتار)
فقط `ADD COLUMN` روی `articles` و `pages` (nullable، بدون تغییرِ ستون‌های موجود):
`translation_of`, `excerpt`, `faqs`(json), `seo_title`, `meta_description`, `canonical_url`, `robots`,
`og_title`, `og_description`, `image_path`, `image_alt`, `author_name`, `reading_time`(اگر لازم؛ یا نگاشت از study_time),
`views`, `published_at`, و promptهای تصویرِ AI.
→ هیچ کدی این‌ها را هنوز نمی‌خواند. **دروازه‌ی SEO باید ۱۰۰٪ بی‌تغییر بماند.**

### ۴b — مدلِ همگرای Article + Resource پنل
- `App\Model\Article` را با traitهای Core مجهز کن: `HasLocale`(پل به lang), `HasSeoMeta`,
  `ProvidesFeaturedMedia`, `LogsContentActivity` + پیاده‌سازیِ سفارشیِ `Publishable`(پل به status بولین).
  `implements CmsContent`. **بدونِ حذفِ هیچ traitِ فعلی** (HasImage/HasSeo/HasTag/HasCategory می‌مانند).
- `App\Filament\Resources\Articles\*` مثل انگلیسی (فرم/جدول/صفحات) — ادمین‌محور، روی همان جدول.
- Media Library را به فیلدِ تصویرِ شاخص وصل کن (`image_path` جدید؛ HasImage قدیمی هم می‌ماند).
- storefront **دست‌نخورده**. → دروازه‌ی SEO.

### ۴c — همگراییِ Tags  (حساس‌ترین: `/article/tag/{slug}`)
- جدولِ `tags` را ارتقا بده (افزودنِ `color`؛ نگاشتِ `title`↔`name` با accessor یا افزودنِ `name`).
- `App\Models\Tag` همگرا (name/color) بساز که روی همان جدولِ زنده کار کند.
- **backfillِ `taggables`**: `taggable_type`های `App\Model\Article` → aliasِ `'article'`.
- سپس `App\Cms\MorphMap::register()` را در `AppServiceProvider::boot()` فعال کن.
- `/article/tag/{slug}` باید بایت‌به‌بایت همان بماند. → دروازه‌ی SEO (با تمرکزِ ویژه روی URLهای tag).

### ۴d — Page  (تکرارِ ۴a+۴b برای pages، حفظِ `/page/{slug}`)

### ۴e — (اختیاری، آخر) repointِ storefront به ستون‌های canonical
فقط اگر خواستیم سئو را از ستون‌های جدید سرو کنیم — با ۳۰۱ در صورتِ نیاز و دروازه‌ی سخت. **پیش‌فرض: انجام نمی‌شود** مگر تصمیمِ صریح.

---

## چه چیزهایی در موج ۴ انجام **نمی‌شود** (تا موج‌های بعد)
- موتورِ تولیدِ محتوا (فقط interfaceش آماده است).
- Knowledge Base و Embeddings/RAG.
- زمان‌بندیِ scheduled/archived (فعلاً published/draft با همان بولین؛ `published_at` فقط ذخیره/نمایش).
- repointِ storefront (۴e) مگر تصمیمِ جدا.

## معیارِ عبورِ هر زیرمرحله
`seo-check` سبز (صفر 🔴) + هر مقاله/صفحه/tagِ منتشرشده هنوز ۲۰۰ و در sitemap + خروجیِ <head> بی‌تغییر.
