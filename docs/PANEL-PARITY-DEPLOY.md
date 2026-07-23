# پنلِ Filament — نقشه‌ی پاریتی و چک‌لیستِ استقرار

سندِ ثبتِ آنچه در پورتِ پنلِ ادمین از سایتِ انگلیسی (trainwithehsan) به سایتِ فارسی
(ehsandibazar) انجام شد، و مراحلِ امنِ استقرار روی production. هدف: خط قرمزِ سئو حفظ شود.

## ۱) نقشه‌ی پاریتیِ منو (انگلیسی → فارسی)

| گروه | آیتم | وضعیت |
|---|---|---|
| بالا | Dashboard (کارت‌های آمار) | ✅ |
| بالا | Activity Log · Internal Linking · Media Library | ✅ |
| بالا | **Menu Settings** · **SEO Center** · **System Maintenance** | ✅ نو |
| بالا | Articles · Newsletter · Pages | ✅ |
| AI Studio | AI Import · Draft Queue · **Import History** · AI Templates · Prompt Library · Brand Memory · AI Profiles · AI Providers · API Tokens · AI Routing · AI Usage Logs · **AI Agent** | ✅ |
| Content Planner | **Planner** · Content Plans · Workflow Stages · **Tags** | ✅ نو |
| بالا | **About / Footer / Homepage Settings** | ✅ نو — روی انبارِ مستقلِ `site_settings`؛ هنوز به قالبِ سایت وصل نیست (صفر ریسک) |
| Knowledge Base | **Knowledge Entries** | ✅ نو — CRUD کامل؛ indexingِ برداری stub است (RAG موکول) |

پاریتیِ منو **کامل** است. دو مورد بالا عمداً «staged»اند: صفحاتِ Settings دیتای خود را ذخیره می‌کنند ولی storefront هنوز آن‌ها را نمی‌خواند (اتصال یک قدمِ جدا و gate‌شده)؛ Knowledge Entries فایل/ورودی را ذخیره می‌کند ولی embedding انجام نمی‌دهد.

### تفاوت‌های عمدیِ تطبیقی (نه نقص)
- **AI Agent**: نسخه‌ی فارسی **فقط-خواندنی** است (بدونِ auto-fix که مقاله‌ی زنده را تغییر می‌دهد) — خط قرمزِ سئو.
- **Content Planner**: اعلان‌های وابسته به `NotificationPreference` حذف شد؛ تاریخچه‌ی مراحل حفظ شد.
- **زمان‌بندیِ گروهیِ مقاله**: پورت نشد چون `PublishDueArticles` (cron) در فارسی نیست.
- **زبان‌ها**: fa/en (گزینه‌ی ترکیِ سایتِ انگلیسی حذف شد).
- **اسکیمای زنده**: `status` بولین (۱ منتشر)، ستونِ `lang`، `viewCount`؛ نگاشتِ مورفیِ فعال با aliasهای کوتاه (`article`/`page`).

## ۲) جدول‌های جدید (نیازمندِ migration)

- Content Planner: `content_plans` · `content_tasks` · `content_plan_stage_transitions` (`2026_07_23_000001..000003`)
- Knowledge Base: `knowledge_entries` · `knowledge_entry_attachments` · `knowledge_chunks` · `ai_generation_knowledge_entry` (`2026_07_23_000005..000008`)
- Settings: `site_settings` (`2026_07_23_000009`)

بقیه‌ی کارها بدونِ جدولِ جدیدند (روی مدل‌های موجود). همه‌ی migrationها با «اجرای Migration» در System Maintenance اجرا می‌شوند.

## ۳) استقرار روی استگینگ (تست)

1. cPanel Git استگینگ → **Pull** برنچِ `claude/website-code-github-upload-xn3z2n`.
2. `/adminpanel/system-maintenance` → **«پاک‌سازی و بهینه‌سازی»**.
3. همان‌جا → **«اجرای Migration»** (۳ جدولِ content_plan* ساخته می‌شود).
4. تست: یک **Content Plan** بساز؛ **AI Agent / SEO Center / Import History** را باز کن؛ یک مقاله را **تکثیر** کن.
5. **گیتِ سئو:** `/panel/manager/maintenance/seo-check?nocache=1` → باید **GREEN** بماند.
6. یک‌بار **«پشتیبان‌گیری اکنون»** را بزن (تأییدِ سرویسِ بکاپ).

## ۴) استقرار روی production (وقتی استگینگ سالم بود)

> ترتیب مهم است. قبل از هر چیز بکاپ.

1. **بکاپِ دیتابیس** (از همین صفحه‌ی System Maintenance → «پشتیبان‌گیری اکنون» + «دانلود») و یادداشتِ commit hashِ فعلی برای بازگشت.
2. مطمئن شو production روی **PHP 8.3** با اکستنشنِ **intl** است (پنل نیاز دارد).
3. cPanel Git production → **Pull** همان برنچ.
4. `/adminpanel/system-maintenance` → **«پاک‌سازی و بهینه‌سازی»** → **«انتشارِ فایل‌های طراحی»**.
5. → **«اجرای Migration»**.
6. **گیتِ سئو** را اجرا کن؛ اگر GREEN نبود، فوراً بررسی/بازگشت.
7. یک مقاله‌ی قدیمی و یک صفحه‌ی قدیمی را باز کن؛ مطمئن شو متای سئو **مثلِ قبل** است (محتوای موجود نباید تغییر کند).
8. کلیدِ Anthropic را در پنل (AI Providers / API Tokens) اضافه کن تا موتورِ تولید فعال شود.

## ۵) اصولِ ایمنی که رعایت شد

- **همگراییِ storefront (موج ۴e)**: متای سئو با ستون‌های canonicalِ جدید، با fallback به `Seo`ِ legacy و عنوان — برای محتوای موجود **بایت‌به‌بایت رفتارِ قبلی**.
- هیچ ابزارِ پنلی محتوای زنده را **خودکار** تغییر نمی‌دهد؛ اعمالِ لینکِ داخلی فقط با پیش‌نمایش و تأییدِ ادمین.
- MorphMapِ جمع (`app/Cms/MorphMap.php`) **ثبت نشد** (repoint به `App\Models\*`ِ ناموجود سایت را می‌شکست) — همگرایی از قبل با aliasهای مشترک برقرار است.
