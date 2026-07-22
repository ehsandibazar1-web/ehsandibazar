# نقشه‌ی موج ۵ — موتورِ تولیدِ محتوا (AI Content Assistant) و ادامه

> قلبِ سایت انگلیسی: تولیدِ محتوا با هوش مصنوعی، با استفاده از حافظه‌ی برند. اولین جایی که هوش
> مصنوعی واقعاً **محتوا می‌نویسد**. کاملاً ادمین‌محور و افزودنی — خروجی به‌صورتِ **پیش‌نویس** در
> جدولِ articles نوشته می‌شود و تا وقتی ادمین «منتشر شده» را روشن نکند، به storefront/sitemap نمی‌رود
> (خط قرمزِ SEO امن).

---

## سرآغازِ خوب: نیمی از کار از قبل آماده است

از گروه ۱، زیرساختِ ارائه‌دهنده‌ها **کاملاً منتقل شده**:
`ActionRegistry`, `ProviderManager`, همه‌ی Providerها (Anthropic/OpenAI/Gemini/DeepSeek/Grok/...),
Contractها, `ProviderCredentials`, config `services.anthropic`, و بایندینگِ `AiProvider` در `AppServiceProvider`.

پس فقط **لایه‌ی موتور + رابطِ کاربری + مدل** مانده.

## دو پیش‌نیازِ مهم (قبل از اینکه واقعاً تولید کند)

1. **کلیدِ API از داخلِ پنل:** برخلافِ فرضِ اولیه، کلید در `.env` لازم نیست — `ProviderManager`
   کلیدها را از جدولِ `ai_provider_configs` می‌خواند که با Resourceِ **AI Provider Configs** (از گروه ۱،
   قبلاً منتقل‌شده) در پنل مدیریت می‌شود. پس ادمین کلیدِ Anthropic را همان‌جا اضافه می‌کند. **هزینه:**
   هر تولید، مصرفِ API دارد (پولی). اول روی استگینگ تست می‌کنیم.
2. **RAG/Knowledge Base موکول است** (تصمیمِ قبلیِ خودت: فعلاً بی‌خیالِ RAG). موتور به
   `KnowledgeBaseService` وابسته است؛ آن را **خنثی** می‌کنیم (`relevantKnowledgeFor` → []). تولید
   با حافظه‌ی برند کار می‌کند، فقط بازیابیِ دانشِ معنایی ندارد. وقتی embedding اضافه شد، فعال می‌شود.

---

## زیرمرحله‌ها

### ۵a — لایه‌ی موتور (بدون UI)
پورت + تطبیقِ namespace (Article/Page: `App\Models`→`App\Model`) + خنثی‌سازیِ KB:
- `App\Services\AiAssistant\ContentAssistantService` (۵۴۱ خط — مغزِ کار: ساختِ system prompt با
  حافظه‌ی برند، فراخوانیِ provider، پارسِ خروجی).
- `DiffService`, `GenerationApplier`, `ContentReviewService`.
- مدلِ `App\Models\AiGeneration` + مهاجرتِ `ai_generations` (ثبتِ تولیدها برای بازبینی/بازگردانی).
- پیاده‌سازیِ Contractِ `App\Cms\Contracts\ContentAssistant` توسطِ ContentAssistantService و
  bind در container (تا با CMS Core هم‌راستا باشد).
→ بدونِ رابط؛ فقط تستِ boot. صفر تغییرِ storefront.

### ۵b — رابطِ دستیار (رویِ پنلِ مقاله/صفحه)  ← بردِ اصلیِ کاربر
- `App\Livewire\AiAssistantPanel` (۵۰۵ خط) + بلید (۵۵۸ خط) + صفحه‌ی `AiContentAssistant`.
- تعبیه در `EditArticle`/`EditPage` (کشوی کناری) — انتخابِ فیلد (عنوان/متن/خلاصه/سئو/...) + حالت
  (نوشتن/بهبود/کوتاه‌کردن/ترجمه/...) → تولید با حافظه‌ی برند → نمایشِ diff → «اعمال».
- **فعال‌سازیِ دوباره‌ی دکمه‌ی «Preview Prompt» در Brand Memory** (که در گروه ۲ موقتاً غیرفعال شد).
- نیاز به کلیدِ API. تست روی یک مقاله‌ی آزمایشی روی استگینگ.

### ۵c — Content Planner + گردش‌کار
`ContentPlan` مدل + صفحه‌ی کانبانِ `ContentPlanner` + `DraftQueue` + `EditorialCalendar` +
`AiAgentDashboard`. **برگرداندنِ ستونِ «Cards» و گاردهای حذف در Workflow Stages** (که در گروه ۲ حذف شد).

### ۵d — AI Import
واردکردنِ محتوای بیرونی (URL/متن) و تبدیلش به مقاله‌ی پیش‌نویس (`ImportLog` + صفحه‌ی `AiImport`).

### ۵e — Internal Linking Center
`InternalLinkSuggestion` — پیشنهادِ لینکِ داخلی بینِ مقاله‌ها بر پایه‌ی کلیدواژه‌ها.

### ۵f — همگراییِ کاملِ Tags + MorphMap  (کارِ موکول‌شده‌ی موج ۴)
ارتقای جدولِ tags (title→name، +color)، مدلِ `App\Models\Tag` همگرا، **backfillِ `taggables` و
`activity_log`** (نام‌کلاسِ کامل → alias)، سپس `App\Cms\MorphMap::register()` در `AppServiceProvider`.
دروازه‌ی SEO با تمرکزِ ویژه روی `/article/tag/{slug}`. **حساس‌ترین قدم — جدا و با احتیاط.**
پس از این: `Article`/`Page` می‌توانند `CmsContent`ِ کامل را implement کنند.

---

## ترتیبِ پیشنهادی و منطقش
اول **۵a → ۵b** (موتور + دستیار = خواسته‌ی اصلیِ تو). بعد بسته به اولویت: ۵c (گردش‌کار) یا مستقیم
۵f (تمیزکاریِ tags). ۵d/۵e تکمیلی‌اند.

## خط قرمزِ SEO در موج ۵
موتور فقط در پنل کار می‌کند و **پیش‌نویس** می‌سازد (status=0). هیچ چیزی خودکار منتشر نمی‌شود. مثل
موج ۴، هر زیرمرحله با `seo-check` سبز تأیید می‌شود. تنها ریسکِ واقعی در ۵f (tags) است.

## هم‌راستایی با CMS Core (بدونِ بدهیِ فنی)
- ContentAssistantService، Contractِ `App\Cms\Contracts\ContentAssistant` را implement می‌کند.
- موتور روی interfaceِ `AiProvider` کار می‌کند (نه provider خاص) → تعویضِ ارائه‌دهنده بدونِ تغییرِ موتور.
- خروجی روی ستون‌های canonicalِ موج ۴ نوشته می‌شود.
