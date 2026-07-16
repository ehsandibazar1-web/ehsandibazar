# احسان دیبازار (ehsandibazar)

فروشگاه اینترنتی مبتنی بر **Laravel 7** و **PHP 7.1+** به همراه پنل مدیریت، درگاه پرداخت، پیامک، سیستم تخفیف و آزمون آنلاین.

## تکنولوژی‌ها

- **فریم‌ورک:** Laravel 7.x
- **زبان:** PHP ^7.1.3
- **پایگاه داده:** MySQL
- **رابط کاربری:** Laravel UI + Blade

### پکیج‌های اصلی

| قابلیت | پکیج |
|--------|------|
| پرداخت آنلاین | `shetabit/payment` |
| پیامک | `ipecompany/smsirlaravel` |
| تاریخ شمسی | `hekmatinasser/verta` |
| خروجی اکسل | `maatwebsite/excel` |
| تولید PDF | `carlos-meneses/laravel-mpdf`, `spatie/pdf-to-image` |
| مدیریت فایل | `unisharp/laravel-filemanager` |
| نقشه سایت (SEO) | `laravelium/sitemap`, `artesaos/seotools` |
| کد QR | `werneckbh/laravel-qr-code` |
| کپچا | `anhskohbo/no-captcha` |
| جداول داده | `yajra/laravel-datatables-oracle` |
| بهینه‌سازی تصویر | `spatie/laravel-image-optimizer` |

## راه‌اندازی

```bash
# ۱. نصب وابستگی‌ها
composer install

# ۲. ساخت فایل تنظیمات
cp .env.example .env
php artisan key:generate

# ۳. تنظیم اطلاعات پایگاه داده در فایل .env
#    DB_DATABASE، DB_USERNAME، DB_PASSWORD

# ۴. اجرای مهاجرت‌ها و داده‌های اولیه
php artisan migrate --seed

# ۵. ساخت لینک نمادین برای فایل‌های عمومی
php artisan storage:link

# ۶. نصب و بیلد فایل‌های فرانت‌اند
npm install
npm run dev

# ۷. اجرای پروژه
php artisan serve
```

## ساختار پروژه

- `app/` — کنترلرها، مدل‌ها، رویدادها و منطق اصلی
- `config/` — فایل‌های تنظیمات
- `database/` — مهاجرت‌ها، seederها و factoryها
- `resources/` — فایل‌های Blade، CSS و JS
- `routes/` — مسیرهای وب و API
- `public/` — فایل‌های عمومی و نقطه ورود
- `staging.ehsandibazar.com/` — نسخه استیجینگ سایت

## نکات

- فایل‌های ویدیویی حجیم (`*.mp4`) و پوشه‌های `vendor`, `node_modules` و `storage` عمومی از طریق `.gitignore` از مخزن کنار گذاشته شده‌اند.
- برای اجرای صحیح، پس از `composer install` پوشه‌ی `vendor/` بازسازی می‌شود.

## مجوز

این پروژه بر پایه فریم‌ورک Laravel با مجوز [MIT](https://opensource.org/licenses/MIT) توسعه یافته است.
