<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

/**
 * روی production، سایت با یک rewrite از public_html به main-app/public سرو می‌شود و APP_URL به
 * «/public» ختم می‌شود. فایل‌های ظاهریِ Filament (با ساختارِ درستِ تودرتو) در main-app/public/css|js
 * هستند و .htaccess مسیرِ /main-app/public/... را مستقیم سرو می‌کند — ولی asset() به‌طور پیش‌فرض
 * /css/filament/... می‌سازد که به پوشه‌ی اشتباهِ public_html می‌رود (۵۰۰).
 *
 * این میدل‌ور فقط برای درخواست‌های «پنل» (نه storefront) و فقط روی production (امضای «/public» در
 * APP_URL) ریشه‌ی asset را به .../main-app/public می‌برد تا استایل/JSِ پنل از همان فایل‌های commit‌شده
 * سرو شود. staging (docroot مستقیم، بدونِ /public) و لوکال دست‌نخورده می‌مانند و storefront هم چون
 * این میدل‌ور را اجرا نمی‌کند اصلاً تغییری نمی‌کند (خط قرمزِ سئو حفظ می‌شود).
 */
class UseFilamentAssetBase
{
    public function handle(Request $request, Closure $next)
    {
        $appUrl = rtrim((string) config('app.url'), '/');

        // فقط production که با ساختارِ دوپوشه‌ای سرو می‌شود این امضا را دارد.
        if (str_ends_with($appUrl, '/public')) {
            $base = substr($appUrl, 0, -strlen('/public')).'/main-app/public';
            URL::useAssetOrigin($base);
        }

        return $next($request);
    }
}
