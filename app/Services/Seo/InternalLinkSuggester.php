<?php

namespace App\Services\Seo;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * پیشنهادگرِ لینکِ داخلی (موج ۵e) — کاملاً فقط-خواندنی. برای هر محتوای منتشرشده (target) دنبالِ
 * محتوای دیگری (source) می‌گردد که عنوانِ target را به‌صورتِ متنِ ساده در بدنه‌اش ذکر کرده اما هنوز
 * به آن لینک نداده است؛ آن‌گاه «افزودنِ لینکِ داخلی» را پیشنهاد می‌کند. هیچ بدنه‌ای را تغییر نمی‌دهد —
 * ادمین پیشنهاد را دستی در ویرایشگر اعمال می‌کند. پس صفر ریسک برای storefront/سئو (فقط سئو-مثبت).
 *
 * الگوریتم عمداً محافظه‌کار است تا نویز کم باشد:
 *  - فقط عنوان‌هایی که «به‌قدرِ کافی خاص» باشند (≥ MIN_TITLE_CHARS و ≥ ۲ کلمه) به‌عنوان لنگر در نظر
 *    گرفته می‌شوند تا عبارت‌های عمومی لینک‌سازی نکنند.
 *  - source اگر از قبل به slugِ target لینک داده باشد رد می‌شود (لینکِ تکراری پیشنهاد نمی‌شود).
 *  - source و target یکی نباشند.
 */
class InternalLinkSuggester
{
    /** حداقل طولِ عنوان تا به‌عنوان لنگرِ لینک در نظر گرفته شود (از عبارت‌های خیلی کوتاه/عمومی جلوگیری). */
    private const MIN_TITLE_CHARS = 10;

    /** حداقل تعداد کلمه در عنوان. */
    private const MIN_TITLE_WORDS = 2;

    public function __construct(private readonly SeoAuditService $audit) {}

    /**
     * فهرستِ پیشنهادهای لینکِ داخلی.
     *
     * @param  int  $limit  سقفِ تعداد پیشنهاد (برای نمایشِ صفحه‌بندی‌شده)
     * @return Collection<int, array{
     *     source_id:int, source_type:string, source_title:string, source_url:string,
     *     target_id:int, target_type:string, target_title:string, target_url:string,
     *     anchor:string, link_html:string
     * }>
     */
    public function suggestions(int $limit = 200): Collection
    {
        $items = $this->audit->collectContentItems();

        // targetهای واجدِ شرایط (عنوانِ به‌قدرِ کافی خاص).
        $targets = $items->filter(fn (array $it): bool => $this->isLinkableTitle($it['title']));

        $suggestions = collect();

        foreach ($items as $source) {
            $bodyPlain = $this->plainText($source['body']);

            if ($bodyPlain === '') {
                continue;
            }

            foreach ($targets as $target) {
                if ($source['id'] === $target['id'] && $source['type'] === $target['type']) {
                    continue; // خودش نباشد
                }

                $title = trim($target['title']);

                // آیا عنوانِ target به‌صورتِ متنِ ساده در بدنه آمده؟
                if (mb_stripos($bodyPlain, $title) === false) {
                    continue;
                }

                // آیا از قبل به این محتوا لینک شده؟ (حضورِ slug یا urlِ target در HTMLِ خام)
                if ($this->alreadyLinks($source['body'], $target)) {
                    continue;
                }

                $suggestions->push([
                    'source_id' => $source['id'],
                    'source_type' => $source['type'],
                    'source_title' => $source['title'],
                    'source_url' => $source['url'],
                    'target_id' => $target['id'],
                    'target_type' => $target['type'],
                    'target_title' => $target['title'],
                    'target_url' => $target['url'],
                    'anchor' => $title,
                    'link_html' => '<a href="'.$target['url'].'">'.$title.'</a>',
                ]);

                if ($suggestions->count() >= $limit) {
                    return $suggestions;
                }
            }
        }

        return $suggestions;
    }

    /**
     * محتوای «یتیم»: مقاله/صفحه‌های منتشرشده‌ای که هیچ محتوای دیگری در بدنه‌اش به آن‌ها لینک نداده.
     * تشخیصِ لینکِ ورودی بر اساسِ حضورِ مسیرِ یکتای مقصد (/article/slug یا /page/slug) در بدنه‌ی مبدأ
     * است — این مسیر عملاً فقط داخلِ href ظاهر می‌شود، پس سیگنالِ دقیقی از لینکِ واقعی است. محافظه‌کار:
     * اگر شک باشد، یتیم علامت نمی‌زند (کم‌گزارش، نه پرگزارش).
     *
     * @return Collection<int, array{id:int, type:string, title:string, url:string}>
     */
    public function orphans(): Collection
    {
        $items = $this->audit->collectContentItems();

        return $items
            ->reject(function (array $target) use ($items): bool {
                foreach ($items as $source) {
                    if ($source['id'] === $target['id'] && $source['type'] === $target['type']) {
                        continue; // خودارجاعی لینکِ ورودی حساب نمی‌شود
                    }
                    if (mb_stripos($source['body'], $target['url']) !== false) {
                        return true; // لینکِ ورودی دارد → یتیم نیست
                    }
                }

                return false;
            })
            ->map(fn (array $it): array => [
                'id' => $it['id'],
                'type' => $it['type'],
                'title' => $it['title'],
                'url' => $it['url'],
            ])
            ->values();
    }

    /**
     * لینک‌های داخلیِ شکسته: hrefهایی در بدنه‌ی محتوای منتشرشده که به /article/{slug} یا /page/{slug}
     * اشاره می‌کنند اما آن مقصد میانِ محتوای منتشرشده نیست (روی سایت ۴۰۴ می‌دهد چون storefront فقط
     * status=1 را سِرو می‌کند). اسلاگ‌ها با rawurldecode نرمال می‌شوند تا encode/decode تفاوتی ندهد.
     *
     * @return Collection<int, array{
     *     source_id:int, source_type:string, source_title:string, source_edit_type:string,
     *     target_type:string, target_slug:string, href:string
     * }>
     */
    public function brokenInternalLinks(): Collection
    {
        $items = $this->audit->collectContentItems();

        $valid = [
            'article' => $items->where('type', 'article')->mapWithKeys(fn (array $i) => [rawurldecode($i['slug']) => true])->all(),
            'page' => $items->where('type', 'page')->mapWithKeys(fn (array $i) => [rawurldecode($i['slug']) => true])->all(),
        ];

        $broken = collect();

        foreach ($items as $source) {
            if (! preg_match_all('/href\s*=\s*("|\')(.*?)\1/i', $source['body'], $hrefs)) {
                continue;
            }

            $seen = [];

            foreach ($hrefs[2] as $href) {
                if (! preg_match('~/(article|page)/([^/?#"\'\s]+)~u', $href, $m)) {
                    continue; // لینکِ داخلیِ مقاله/صفحه نیست
                }

                $targetType = $m[1];
                $slug = rawurldecode($m[2]);

                if (isset($valid[$targetType][$slug])) {
                    continue; // مقصدِ معتبرِ منتشرشده
                }

                $dedupKey = $targetType.'|'.$slug;
                if (isset($seen[$dedupKey])) {
                    continue;
                }
                $seen[$dedupKey] = true;

                $broken->push([
                    'source_id' => $source['id'],
                    'source_type' => $source['type'],
                    'source_title' => $source['title'],
                    'source_edit_type' => $source['type'],
                    'target_type' => $targetType,
                    'target_slug' => $slug,
                    'href' => $href,
                ]);
            }
        }

        return $broken->values();
    }

    private function isLinkableTitle(string $title): bool
    {
        $title = trim($title);

        return mb_strlen($title) >= self::MIN_TITLE_CHARS
            && count(preg_split('/\s+/u', $title, -1, PREG_SPLIT_NO_EMPTY)) >= self::MIN_TITLE_WORDS;
    }

    /**
     * آیا source از قبل به target لینک داده؟ حضورِ slug (در href یا هر جای HTML) کافی است — محافظه‌کار
     * تا پیشنهادِ تکراری داده نشود.
     */
    private function alreadyLinks(string $sourceBody, array $target): bool
    {
        $slug = $target['slug'];

        if ($slug !== '' && mb_stripos($sourceBody, $slug) !== false) {
            return true;
        }

        return mb_stripos($sourceBody, $target['url']) !== false;
    }

    /** حذفِ تگ‌های HTML و نرمال‌سازیِ فاصله‌ها برای جست‌وجوی متنِ ساده. */
    private function plainText(string $html): string
    {
        $text = strip_tags($html);
        $text = html_entity_decode($text, ENT_QUOTES | ENT_HTML5);

        return trim((string) preg_replace('/\s+/u', ' ', $text));
    }
}
