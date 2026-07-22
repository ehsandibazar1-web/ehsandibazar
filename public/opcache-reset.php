<?php

/**
 * ریست OPcache روی هاستِ بدون‌شل، بعد از تغییرِ فایل‌های *موجود* (مثل routes/admin.php).
 *
 * چرا لازم است: وقتی opcache.validate_timestamps=0 باشد، PHP نسخه‌ی کامپایل‌شده‌ی قدیمیِ
 * یک فایلِ ویرایش‌شده را نگه می‌دارد و تغییر را نمی‌بیند (فایل‌های «جدید» این مشکل را ندارند).
 * این اسکریپت خودش یک فایلِ جداست، پس گرفتارِ همان کشِ کهنه نمی‌شود و می‌تواند کش را پاک کند.
 *
 * استفاده (بعد از هر Pull که فایلِ موجودی را عوض کرده):
 *   https://staging.ehsandibazar.com/opcache-reset.php?token=oc_7f3a9b21e5c4
 *
 * توکن فقط جلوی ریستِ تصادفی/مکرر توسط دیگران را می‌گیرد؛ این اسکریپت هیچ داده‌ای را تغییر نمی‌دهد.
 */

const OPCACHE_RESET_TOKEN = 'oc_7f3a9b21e5c4';

header('Content-Type: text/plain; charset=utf-8');

if (($_GET['token'] ?? '') !== OPCACHE_RESET_TOKEN) {
    http_response_code(403);
    exit('forbidden — token نامعتبر است.');
}

if (! function_exists('opcache_reset')) {
    exit('OPcache روی این سرور فعال نیست (نیازی به ریست نبود).');
}

$ok = opcache_reset();

// opcache_reset ممکن است در همان درخواست کامل اعمال نشود؛ status را هم گزارش می‌دهیم.
$status = function_exists('opcache_get_status') ? @opcache_get_status(false) : null;
$cached = is_array($status) && isset($status['opcache_statistics']['num_cached_scripts'])
    ? $status['opcache_statistics']['num_cached_scripts']
    : 'نامشخص';

echo $ok ? "OPcache reset: OK\n" : "OPcache reset: انجام نشد (شاید غیرفعال)\n";
echo "اسکریپت‌های کش‌شده (بعد از ریست): {$cached}\n";
echo "حالا دوباره به /panel/manager/maintenance/seo-check برو.\n";
