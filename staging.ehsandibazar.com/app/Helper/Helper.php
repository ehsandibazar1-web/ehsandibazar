<?php
function persianNumberToEnglish($string)
{
    $newNumbers = range(0, 9);
    // 1. Persian HTML decimal
    $persianDecimal = array('&#1776;', '&#1777;', '&#1778;', '&#1779;', '&#1780;', '&#1781;', '&#1782;', '&#1783;', '&#1784;', '&#1785;');
    // 2. Arabic HTML decimal
    $arabicDecimal = array('&#1632;', '&#1633;', '&#1634;', '&#1635;', '&#1636;', '&#1637;', '&#1638;', '&#1639;', '&#1640;', '&#1641;');
    // 3. Arabic Numeric
    $arabic = array('٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩');
    // 4. Persian Numeric
    $persian = array('۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹');

    $string = str_replace($persianDecimal, $newNumbers, $string);
    $string = str_replace($arabicDecimal, $newNumbers, $string);
    $string = str_replace($arabic, $newNumbers, $string);
    return str_replace($persian, $newNumbers, $string);
}

function generateShortUrl($entity)
{
    $shortURLs = \AshAllenDesign\ShortURL\Model\ShortURL::findByDestinationURL($entity->path());
    if (isset($shortURLs[0]) && !empty($shortURLs[0])) {
        return $shortURLs;
    } else {
        $builder = new \AshAllenDesign\ShortURL\Classes\Builder();
        return $builder->destinationUrl($entity->path())->singleUse()->make();
    }
}

function showShortUrl($entity)
{
    $shortURLs = \AshAllenDesign\ShortURL\Model\ShortURL::findByDestinationURL($entity->path());
    if (isset($shortURLs[0]) && !empty($shortURLs[0])) {
        return $shortURLs[0]->default_short_url;
    } else {
        return false;
    }
}

function createMetaSite($model)
{
    if (isset($model->seo) && !empty($model->seo)){
        $meta = [
            empty($model->seo->title) ? $model->title : $model->seo->title => "setTitle",
            $model->seo->description => "setDescription",
            $model->seo->keyword => "setKeywords",
            $model->seo->canonical => "setCanonical",
        ];
        foreach ($meta as $key => $item){
            if (isset($key) && !empty($key)){
                \Artesaos\SEOTools\Facades\SEOMeta::{$item}($key);
            }
        }
    }
}
function fixImageDimensions(string $html): string
{
    return preg_replace_callback(
        '/<img([^>]*)>/i',
        function ($matches) {
            $attrs = $matches[1];

            $width  = null;
            $height = null;

            // از style نمایشی بخوان
            if (preg_match('/\bstyle\s*=\s*"([^"]*)"/', $attrs, $sm)) {
                if (preg_match('/width\s*:\s*(\d+)px/i', $sm[1], $wm) && (int)$wm[1] > 0) {
                    $width = (int) $wm[1];
                }
                if (preg_match('/height\s*:\s*(\d+)px/i', $sm[1], $hm) && (int)$hm[1] > 0) {
                    $height = (int) $hm[1];
                }
            }

            if ($width && $height) {
                // حذف width و height attribute با space قبل
                $attrs = preg_replace('/ width="[^"]*"/', '', $attrs);
                $attrs = preg_replace('/ height="[^"]*"/', '', $attrs);
                // اضافه کردن درست
                $attrs .= ' width="' . $width . '" height="' . $height . '"';
            }

            // CLS/LCP Fix: افزودن loading="lazy" در صورت نبود آن
            if (!preg_match('/\bloading\s*=\s*"/i', $attrs)) {
                $attrs .= ' loading="lazy"';
            }

            return '<img' . $attrs . '>';
        },
        $html
    );
}

/**
 * CLS Fix: ساخت فهرست مطالب (TOC) به صورت سمت سرور.
 * به جای اینکه جاوااسکریپت بعد از لود صفحه لیست را داخل DOM تزریق کند
 * (که باعث Layout Shift در #article-content می‌شد)، این تابع از قبل
 * id ها را به heading ها اضافه کرده و لیست TOC را آماده برمی‌گرداند.
 *
 * @param string $html محتوای HTML مقاله/صفحه
 * @param array  $tags تگ‌های heading که باید در TOC لحاظ شوند
 * @return array{html: string, list: string, count: int}
 */
function buildTableOfContents(string $html, array $tags = ['h2']): array
{
    $count = 0;
    $tocItems = '';
    $pattern = '/<(' . implode('|', $tags) . ')([^>]*)>(.*?)<\/\1>/is';

    $html = preg_replace_callback(
        $pattern,
        function ($matches) use (&$count, &$tocItems) {
            $tag = strtolower($matches[1]);
            $attrs = $matches[2];
            $innerHtml = $matches[3];

$text = html_entity_decode(
    trim(strip_tags($innerHtml)),
    ENT_QUOTES | ENT_HTML5,
    'UTF-8'
);

$text = preg_replace('/\x{00A0}/u', ' ', $text);

            // heading خالی (بدون متن) را نادیده بگیر
            if ($text === '') {
                return $matches[0];
            }

            // اگر id از قبل تعریف شده بود همان را استفاده کن، وگرنه اضافه کن
            if (preg_match('/\bid\s*=\s*"([^"]*)"/i', $attrs, $idMatch)) {
                $id = $idMatch[1];
            } else {
                $id = 'sec-' . $count;
                $attrs .= ' id="' . $id . '"';
            }

            $indentAttr = $tag === 'h3' ? ' style="padding-right:14px"' : '';
            $tocItems .= '<li' . $indentAttr . '><a href="#' . $id . '">'
                . htmlspecialchars($text, ENT_QUOTES, 'UTF-8') . '</a></li>';

            $count++;

            return '<' . $tag . $attrs . '>' . $innerHtml . '</' . $tag . '>';
        },
        $html
    );

    return [
        'html'  => $html,
        'list'  => $count >= 2 ? $tocItems : '',
        'count' => $count,
    ];
}
