# نقشه‌ی راه انتقال پنل AI Studio به سایت فارسی

> رویکرد: **همگرایی (Convergence)**، نه جایگزینی یک‌شبه.
> هدف: **یک CMS واحد** داخل سایت فارسی، هم‌معماری با سایت انگلیسی (trainwithehsan)،
> با حفظ کاملِ داده‌ی زنده، URLهای ایندکس‌شده‌ی گوگل و فروشگاه.
> اصل کاری: هر تغییر اول روی **استگینگ**، تیکه‌تیکه، تست‌شده، بعد **پروداکشن**.

---

## قانون طلایی این پروژه: دو نوع ماژول

| نشان | معنی | چرا |
|---|---|---|
| 🆕 **پورت مستقیم** | مدل/جدول جدید، عیناً از انگلیسی | معادل فارسی ندارد و **URL عمومی/داده‌ی زنده ندارد** → کپی امن |
| 🔧 **ارتقا در جا** | مدل فارسیِ موجود را تا سطح انگلیسی بالا می‌بریم | جدولش از ۲۰۱۸–۲۰۱۹ است، داده‌ی واقعی دارد و در **sitemap ایندکس شده** → جدول عوض نمی‌شود، فقط ستون/رابطه/UX اضافه می‌شود |

**سه ماژولِ حساس (فقط این‌ها ارتقا در جا):** `Article` · `Page` · `Tag/Tagable`
(چون فرانت‌اِند عمومی `/article/{slug}`, `/blog`, `/page/{slug}`, `/article/tag/{slug}`
مستقیماً از `App\Model\Article` و `App\Model\Page` رندر می‌شود و این URLها در sitemap.xml هستند.)

بقیه‌ی ماژول‌ها یا فقط ابزار ادمین‌اند یا معادل زنده‌ای ندارند → **پورت مستقیم**.

---

## وضعیت فعلی

### ✅ انجام‌شده (روی برنچ، آماده‌ی تست استگینگ)
- **گروه ۱ — زیرساخت هوش مصنوعی:** AiProviderConfigs, AiPrompts, AiTemplates, AiProfiles,
  AiUsageLogs, ApiTokens, AiActionRouting + بایندینگ AiProvider/Anthropic در `AppServiceProvider`.
- **گروه ۲ — ابزارهای مستقل از محتوا:** Brand Memory (+ activitylog + تاریخچه/بازگردانی),
  Workflow Stages.

---

## موج‌های پیش‌رو (به ترتیب اجرا: کم‌ریسک → پرریسک)

### موج ۳ — افزودنی‌های خالص، بدون تداخل، بدون URL عمومی  🆕
پایه‌ای‌اند و هیچ داده/URL زنده‌ای را لمس نمی‌کنند. امن‌ترین کار ممکن.

- [ ] **گروه ۳ — Knowledge Base (+ RAG):** `KnowledgeEntry`, `KnowledgeChunk`,
      `KnowledgeEntryAttachment` + Resource `KnowledgeEntries`. پایه‌ی کیفیت تولید محتوا.
      _(ستون‌های embedding از مهاجرت `add_embedding_columns_for_rag` که در گروه ۱ بخشی‌اش نگه داشته شد.)_
- [ ] **گروه ۴ — Activity Log Viewer:** صفحه‌ی `ActivityLogPage` — فقط یک نمایشگر روی
      activitylog که **همین حالا نصب است**. تقریباً بدون کار جانبی.
- [x] **گروه ۵ — Media Library:** ✅ روی استگینگ کار می‌کند. `Media`, `MediaFolder`,
      `MediaProcessor` (WebP/تامبنیل/ریسپانسیو با intervention/image v4)، `VideoMetadataService`،
      `MediaUsageScanner` (فعلاً خنثی تا موج ۴)، صفحه + trait + blade. زیر گروه «AI Studio».
      اکشنِ `storage-link` هم برای symlinkِ بدون‌شل اضافه شد. صفر تغییرِ vendor.

### موج ۴ — موتور تولید محتوا + نقطه‌ی همگرایی  🔧🆕
اینجا اولین «ارتقا در جا» رخ می‌دهد: خروجی هوش مصنوعی باید در **مقاله‌ی فارسیِ موجود** بنشیند.

- [ ] **گروه ۶ — ارتقای مدل Article (بدون تعویض جدول):** ستون‌ها/رابطه‌های لازمِ انگلیسی را
      به `App\Model\Article` اضافه کن (draft status, locale اختیاری, متادیتای AI, رابطه با
      WorkflowStage). جدول `articles` و URLهای `/article/{slug}` دست‌نخورده. Resource پنل مثل انگلیسی.
- [ ] **گروه ۷ — AI Content Assistant (موتور):** `ContentAssistantService`, `AiGeneration`,
      صفحه‌ی `AiContentAssistant`. خروجی → Article ارتقایافته‌ی گروه ۶.
      _پس از این: دکمه‌ی «Preview Prompt» در Brand Memory دوباره فعال می‌شود._

### موج ۵ — گردش‌کار محتوا (همه وابسته به Article + WorkflowStage)  🆕
- [ ] **گروه ۸ — Content Planner + ContentPlan** (کانبان). _پس از این: ستون «Cards» و
      گاردهای حذف در Workflow Stages برمی‌گردند._
- [ ] **گروه ۹ — Draft Queue · Editorial Calendar · AI Agent Dashboard.**
- [ ] **گروه ۱۰ — AI Import + Import History** (`ImportLog` / Resource `ImportLogs`).
- [ ] **گروه ۱۱ — Internal Linking Center** (`InternalLinkSuggestion`) — روی مقاله‌ها کار می‌کند.

### موج ۶ — Pages، Tags و تنظیمات (حساس به URL — ارتقا در جا)  🔧
- [ ] **گروه ۱۲ — ارتقای Page** (`App\Model\Page`, حفظ `/page/{slug}`).
- [ ] **گروه ۱۳ — ارتقای Tag/Tagable** (حفظ `/article/tag/{slug}`).
- [ ] **گروه ۱۴ — SEO Center** روی `App\Model\Seo` موجود (ارتقا، نه تعویض).
- [ ] **گروه ۱۵ — Menu Settings** روی `App\Model\Menu` موجود.
- [ ] **گروه ۱۶ — Newsletter** روی `App\Model\NewsLatters` موجود (+ `NewsletterSubscriber`).
- [ ] **گروه ۱۷ — Homepage / About / Footer Settings** (`SiteSetting`) — Homepage Builder.

---

## چیزهایی که باید موقع رسیدنِ گروهِ مربوطه «برگردانده» شوند
- ستون «Cards» (شمارش ContentPlan) + گاردهای حذف در `WorkflowStagesTable` و `EditWorkflowStage` → گروه ۸.
- دکمه‌ی «Preview Prompt» در `BrandMemory` (وابسته به `ContentAssistantService`) → گروه ۷.

## یادداشت‌های زیرساختی (ثابت برای همه‌ی گروه‌ها)
- استقرار بدون شل: `vendor/` در گیت؛ نصب composer با `--ignore-platform-reqs --no-scripts`
  و بعد `find vendor -mindepth 2 -name .git -exec rm -rf {} +` + حذف `.gitignore`های تودرتوی vendor.
- مهاجرت‌ها روی سرور از مسیر مرورگر: `/panel/manager/maintenance/migrate`.
- پاک‌سازی کش: `/panel/manager/maintenance/optimize`.
- مسیر پنل: `/adminpanel` (نه `/admin` — تداخل با پوشه‌ی فیزیکی).
- **پروداکشن:** قبل از سوییچ، مطمئن شو PHP 8.3 با `intl` از «Select PHP Version» فعال است.
- namespace: مدل‌های فروشگاهِ قدیمی در `App\Model` (مفرد)؛ مدل‌های پورت‌شده در `App\Models` (جمع)؛
  کاربر `App\User` است.
