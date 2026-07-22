<?php

namespace App\Services\Seo;

/**
 * اعمالِ امنِ یک لینکِ داخلی در بدنه‌ی HTMLِ مقاله/صفحه (موج ۵e). عمداً بدنه را با DOMDocument
 * بازپارس/بازنویسی نمی‌کند (که می‌تواند تگ‌ها/انتیتی‌ها/فاصله‌ها را به‌شکلِ ناخواسته تغییر دهد و
 * محتوای زنده را خراب کند — خط قرمزِ سئو). به‌جایش بدنه را به توکن‌های «تگ» و «متن» می‌شکند و فقط
 * «اولین» رخدادِ عبارتِ لنگر را که در یک قطعه‌ی متنِ ساده (خارج از a/script/style و خارج از خودِ تگ‌ها)
 * است با نسخه‌ی لینک‌دار جایگزین می‌کند. بقیه‌ی بایت‌های بدنه بایت‌به‌بایت دست‌نخورده می‌ماند.
 *
 * اگر عبارت پیدا نشود (مثلاً بین چند تگ شکسته، داخلِ لینکِ دیگر، یا فقط در attribute)، null برمی‌گرداند
 * تا فراخواننده به کاربر بگوید «دستی اعمال کن» — هرگز حدس نمی‌زند و هرگز چیزی را کورکورانه تغییر نمی‌دهد.
 */
class InternalLinkApplier
{
    /**
     * بدنه‌ی جدید با یک لینکِ داخلیِ اضافه‌شده، یا null اگر امن قابل‌اعمال نبود.
     */
    public function buildLinkedBody(string $body, string $anchor, string $targetUrl): ?string
    {
        $anchor = trim($anchor);

        if ($anchor === '' || $body === '') {
            return null;
        }

        // توکن‌سازی: تگ‌ها (شاملِ کامنت‌ها) به‌عنوان جداکننده حفظ می‌شوند؛ بقیه متن است.
        $parts = preg_split('/(<!--.*?-->|<[^>]+>)/us', $body, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);

        if ($parts === false) {
            return null;
        }

        $skipDepth = 0; // داخلِ a/script/style
        $result = '';
        $done = false;

        foreach ($parts as $part) {
            if ($done) {
                $result .= $part;

                continue;
            }

            // آیا این توکن یک تگ/کامنت است؟
            if ($part !== '' && $part[0] === '<') {
                if (preg_match('/^<(a|script|style)[\s>]/i', $part)) {
                    $skipDepth++;
                } elseif (preg_match('/^<\/(a|script|style)\s*>/i', $part)) {
                    $skipDepth = max(0, $skipDepth - 1);
                }
                $result .= $part;

                continue;
            }

            // قطعه‌ی متن. اگر داخلِ a/script/style هستیم دست نمی‌زنیم.
            if ($skipDepth > 0) {
                $result .= $part;

                continue;
            }

            $pos = mb_stripos($part, $anchor);
            if ($pos === false) {
                $result .= $part;

                continue;
            }

            $len = mb_strlen($anchor);
            $before = mb_substr($part, 0, $pos);
            $match = mb_substr($part, $pos, $len); // حفظِ حالتِ نگارشیِ اصلی
            $after = mb_substr($part, $pos + $len);

            $result .= $before
                .'<a href="'.htmlspecialchars($targetUrl, ENT_QUOTES).'">'.$match.'</a>'
                .$after;
            $done = true;
        }

        return $done ? $result : null;
    }

    /**
     * پیش‌نمایشِ قبل/بعد (قطعه‌ی کوتاهِ متن حولِ لنگر) بدونِ ذخیره‌سازی. null اگر قابل‌اعمال نبود.
     *
     * @return array{new_body:string, preview_before:string, preview_after:string}|null
     */
    public function plan(string $body, string $anchor, string $targetUrl): ?array
    {
        $newBody = $this->buildLinkedBody($body, $anchor, $targetUrl);

        if ($newBody === null) {
            return null;
        }

        return [
            'new_body' => $newBody,
            'preview_before' => $this->excerptAround($body, $anchor, false, $targetUrl),
            'preview_after' => $this->excerptAround($newBody, $anchor, true, $targetUrl),
        ];
    }

    /**
     * قطعه‌ای از متنِ ساده حولِ اولین رخدادِ لنگر، برای نمایشِ پیش‌نمایش. در حالتِ «بعد»، لنگر را
     * به‌صورتِ لینک نمایش می‌دهد. خروجی برای نمایشِ امن escape شده است (به‌جز تگِ لینکِ عمدی).
     */
    private function excerptAround(string $html, string $anchor, bool $linked, string $targetUrl): string
    {
        $plain = trim((string) preg_replace('/\s+/u', ' ', html_entity_decode(strip_tags($html), ENT_QUOTES | ENT_HTML5)));
        $pos = mb_stripos($plain, $anchor);

        if ($pos === false) {
            return '';
        }

        $len = mb_strlen($anchor);
        $ctx = 60;
        $start = max(0, $pos - $ctx);
        $before = ($start > 0 ? '… ' : '').mb_substr($plain, $start, $pos - $start);
        $match = mb_substr($plain, $pos, $len);
        $afterStart = $pos + $len;
        $after = mb_substr($plain, $afterStart, $ctx).(mb_strlen($plain) > $afterStart + $ctx ? ' …' : '');

        $matchHtml = $linked
            ? '<a href="'.htmlspecialchars($targetUrl, ENT_QUOTES).'" class="text-primary-600 dark:text-primary-400 underline">'.htmlspecialchars($match).'</a>'
            : '<mark class="bg-warning-200 dark:bg-warning-500/30">'.htmlspecialchars($match).'</mark>';

        return htmlspecialchars($before).$matchHtml.htmlspecialchars($after);
    }
}
