# 📘 دفترچه‌ی انتقال سایت اصلی به Laravel 12

> این سند مرجع کامل عملیات انتقال `ehsandibazar.com` از کد قدیمی (Laravel 7) به کد جدید
> (Laravel 12 / PHP 8.3) است. staging از قبل با موفقیت با همین کد بالا آمده و تست شده.
>
> **اصل طلایی: سایت قدیمی هیچ‌وقت پاک نمی‌شود — فقط ترافیک به پوشه‌ی جدید هدایت می‌شود.
> رول‌بک در هر لحظه چندثانیه‌ای است.**

---

## فاز ۱ — آماده‌سازی (بدون هیچ ریسکی؛ هر زمان قابل انجام)

### ۱.۱ بکاپ تازه از دیتابیس اصلی
- phpMyAdmin → انتخاب دیتابیس اصلی سایت → تب **Export** → Quick / SQL → **Go**
- فایل را جای امن نگه دار.

### ۱.۲ کلون جدید برای production
- cPanel → **Git Version Control** → **Create**
- Clone a Repository: روشن
- Clone URL: `https://TOKEN@github.com/ehsandibazar1-web/ehsandibazar.git`
- Repository Path: `public_html/main-app`
- بعد از ساخت: **Manage** → Checked-Out Branch را به
  `claude/website-code-github-upload-xn3z2n` تغییر بده → **Update**
- تأیید: HEAD Commit باید مربوط به Laravel 12 باشد.

### ۱.۳ ~~کپی پوشه‌های asset~~ — لازم نیست! ✅
برای production هیچ کپی‌ای لازم نیست: بلوک سوییچ (فاز ۲) طوری نوشته شده که
پوشه‌های asset (`site_themes`، `users_theme`، `admin`، `upload`، `storage`،
`public`، `css`، `js`، `general`، `vendor`) از **همان جای فعلی‌شان در
`public_html`** سرو شوند. صفر مصرف دیسک اضافه، صفر ریسک جاافتادن فایل.

### ۱.۴ فایل `.env` production
- File Manager → `public_html` → فایل `.env` فعلی سایت را **Copy** → مقصد: `/public_html/main-app`
- سپس `main-app/.env` را **Edit** کن و فقط این سه خط را چک/اصلاح کن (بقیه دست نخورد):
  ```
  APP_ENV=production
  APP_DEBUG=false
  APP_URL=https://ehsandibazar.com
  ```
- APP_KEY و اطلاعات دیتابیس و درگاه همان قبلی می‌مانند (نشست کاربران حفظ می‌شود).

### ۱.۵ فایل‌های سئویی فیزیکی
در `public_html` این فایل‌ها را پیدا کن و هر کدام بود به `/public_html/main-app/public` کپی کن:
- `robots.txt`
- `sitemap.xml` (اگر فایل فیزیکی بود — نسخه‌ی داینامیک هم در کد هست)
- `google*.html` (فایل تأیید Search Console — **خیلی مهم**)
- `ads.txt`
- `favicon.ico` (اگر سفارشی بود)

### ۱.۶ تست خاموش (اختیاری ولی توصیه‌شده)
- ساب‌دامنه‌ی `test.ehsandibazar.com` (که هاست ساخته) را موقتاً به
  `public_html/main-app/public` وصل کن و فقط صفحه‌ی اصلی/یک محصول را چک کن.
- ⚠️ چون `APP_ENV=production` است این ساب‌دامنه noindex ندارد — بعد از چند دقیقه تست،
  docroot ساب‌دامنه را به جای قبلی برگردان.
- توجه: این تست به **دیتابیس اصلی** وصل است — چیزی ثبت/خرید نکن.

---

## فاز ۲ — شب سوییچ (ساعت کم‌ترافیک، مثلاً ۱۲ شب به بعد)

### ۲.۱ بکاپ لحظه‌ای
- File Manager → `public_html/.htaccess` → Copy → همان‌جا با نام `.htaccess-old-backup`
- (اختیاری: یک Export سریع دیگر از دیتابیس)

### ۲.۲ کلید سوییچ 🔑
`public_html/.htaccess` را Edit کن و **این بلوک را بعد از دو بلوک ریدایرکت www/HTTPS**
(حدوداً بعد از خط ۲۵ فایل فعلی، قبل از `<IfModule mod_rewrite.c>` بعدی) اضافه کن:

```apache
# ---- NEW SITE (Laravel 12): route main-domain traffic into main-app/public ----
# Asset folders are excluded and keep serving from their current locations
# in public_html (no duplication needed).
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteCond %{HTTP_HOST} ^(www\.)?ehsandibazar\.com$ [NC]
    RewriteCond %{REQUEST_URI} !^/main-app/public/
    RewriteCond %{REQUEST_URI} !^/(site_themes|users_theme|admin|upload|storage|public|css|js|general|vendor|site_theme)/
    RewriteCond %{REQUEST_URI} !^/(google[a-z0-9]*\.html|ads\.txt|favicon\.ico)$
    RewriteRule ^(.*)$ main-app/public/$1 [L]
</IfModule>
# ---- END NEW SITE ----
```

> نکته: با این نسخه، فایل تأیید Search Console و ads.txt و favicon هم از جای
> فعلی‌شان سرو می‌شوند — کار ۱.۵ هم عملاً حذف می‌شود (فقط مطمئن شو در
> public_html هستند).

ذخیره کن. از این لحظه سایت اصلی از کد جدید سرو می‌شود.

### ۲.۳ نسخه‌ی PHP
- MultiPHP Manager → تیک `ehsandibazar.com` → PHP **8.3 (ea-php83)** → Apply
- (این کار خط AddHandler انتهای .htaccess را خودکار به ea-php83 به‌روز می‌کند.)

### ۲.۴ چک‌لیست فوری بعد از سوییچ (۵ دقیقه)
| تست | انتظار |
|---|---|
| `https://ehsandibazar.com` | صفحه‌ی اصلی کامل با قالب |
| یک صفحه‌ی محصول | باز شود، قیمت درست |
| `https://ehsandibazar.com/robots.txt` | **Disallow خالی** (یعنی باز) + خط Sitemap |
| `https://ehsandibazar.com/sitemap.xml` | XML با لیست URLها |
| ورود با اکانت واقعی | موفق |
| افزودن به سبد + رفتن تا درگاه | صفحه‌ی درگاه زرین‌پال باز شود |
| یک خرید کوچک واقعی | پرداخت و ثبت سفارش و پیامک |
| `view-source:` صفحه‌ی اصلی | تگ canonical و JSON-LD موجود |

### ۲.۵ اگر مشکلی بود — رول‌بک ⏪ (چند ثانیه)
1. Edit `public_html/.htaccess` → بلوک `---- NEW SITE ----` را کامل حذف کن → ذخیره
2. MultiPHP → دامنه‌ی اصلی → برگردان به **PHP 8.2 (ea-php82)**
3. سایت قدیمی برمی‌گردد. فایل‌ها و دیتابیس دست‌نخورده‌اند.

---

## فاز ۳ — بعد از سوییچ موفق

- ۲۴–۴۸ ساعت Google Search Console را زیر نظر بگیر (Coverage / خطاهای جدید).
  کاری لازم نیست بکنی — URLها عوض نشده‌اند.
- staging را نگه دار (برای تغییرات بعدی: اول staging، بعد main).
- گردش کار آپدیت از این به بعد:
  1. تغییر کد (با Claude) → push به GitHub
  2. cPanel → Git → مخزن staging → Update from Remote → تست روی staging
  3. cPanel → Git → مخزن main-app → Update from Remote → لایو
- بعد از چند هفته‌ی پایدار، می‌توان فایل‌های سایت قدیمی داخل `public_html` را
  (به‌جز `main-app`، `staging-app`، پوشه‌های asset و فایل‌های سئویی) پاک‌سازی کرد —
  **عجله‌ای نیست.**

---

## 🔧 مرجع سریع

| چیز | مقدار |
|---|---|
| شاخه‌ی دیپلوی | `claude/website-code-github-upload-xn3z2n` |
| کد staging | `public_html/staging-app` (docroot ساب‌دامنه: `staging-app/public`) |
| کد production | `public_html/main-app` (سرو از طریق rewrite در `.htaccess`) |
| دیتابیس staging | `ehsandib_staging` |
| بکاپ .htaccess قدیمی | `deploy/production-htaccess-ORIGINAL-BACKUP.txt` (در همین مخزن) |
| PHP قدیم / جدید | 8.2 → 8.3 |
