<?php

namespace App\Services\ArticleImport;

use App\Model\Article;
use App\Services\Content\ContentDraftFactory;

/**
 * خروجی‌گرفتن از یک مقاله به فرمتِ قابلِ‌ویرایشِ AI (همان فرمت‌هایی که AI Import می‌خواند) با یک
 * شناسه‌ی مخفیِ `id` تا موقعِ برگشت، همان مقاله آپدیت شود — نه درافتِ جدید. یک `_content_hash` هم
 * جاسازی می‌شود تا اگر مقاله بینِ خروجی و برگشت تغییر کرده باشد، تعارض تشخیص داده شود.
 * slug فقط برای مرجع نوشته می‌شود و موقعِ ورودِ دوباره «قفل» است (خطِ قرمزِ URL).
 */
class ArticleRoundtripExporter
{
    /** @return array{filename: string, mime: string, content: string} */
    public function export(Article $article, string $format = 'json'): array
    {
        $format = in_array($format, ['json', 'markdown'], true) ? $format : 'json';
        $slugSafe = preg_replace('/[^A-Za-z0-9_-]+/u', '-', (string) $article->slug) ?: (string) $article->id;

        return [
            'filename' => "article-{$article->id}-{$slugSafe}.".($format === 'markdown' ? 'md' : 'json'),
            'mime' => $format === 'markdown' ? 'text/markdown' : 'application/json',
            'content' => $format === 'markdown' ? $this->toMarkdown($article) : $this->toJson($article),
        ];
    }

    /** هشِ محتوای فعلیِ مقاله روی فیلدهای قابلِ‌به‌روزرسانی — برای تشخیصِ تعارض هنگامِ ورودِ دوباره. */
    public static function contentHash(Article $article): string
    {
        $data = [];
        foreach (ContentDraftFactory::ROUNDTRIP_UPDATABLE as $field) {
            $data[$field] = (string) $article->getAttribute($field);
        }
        $data['faqs'] = json_encode($article->faqs ?? [], JSON_UNESCAPED_UNICODE);

        return substr(hash('sha256', json_encode($data, JSON_UNESCAPED_UNICODE)), 0, 16);
    }

    /** FAQِ «مؤثر»: اول ستونِ legacyِ faq (سایتِ قدیمی)، بعد ستونِ جدیدِ faqs — همان اولویتی که
     * storefront دارد (`$article->faq ?: $article->faqs`). بدونِ این، مقاله‌هایی که FAQشان در ستونِ
     * قدیمی است، در اکسپورت خالی می‌آمدند. */
    private function effectiveFaqs(Article $article): array
    {
        if (is_array($article->faq) && $article->faq !== []) {
            return $article->faq;
        }

        return is_array($article->faqs) ? $article->faqs : [];
    }

    /** برچسب‌های مقاله (نام‌ها) — برای دیده‌شدن و ویرایش در چرخه. */
    private function tagNames(Article $article): array
    {
        return $article->tags->pluck('title')->filter()->values()->all();
    }

    /**
     * سئوی «مؤثر» را با همان fallbackِ storefront برمی‌گرداند: اول ستون‌های canonicalِ جدید، بعد
     * رابطه‌ی legacyِ Seo (title/description/keyword)، بعد عنوان. مقاله‌های قدیمی seo_title/meta خالی
     * دارند ولی سئوشان در رابطه‌ی Seo است؛ بدونِ این، اکسپورت خالی می‌آمد.
     *
     * @return array{seo_title: string, meta_description: ?string, og_title: string, og_description: ?string, keyword: ?string}
     */
    private function effectiveSeo(Article $article): array
    {
        $seo = \App\Model\Seo::where('seoable_id', $article->id)
            ->where('seoable_type', 'article')
            ->first();

        $seoTitle = filled($article->seo_title) ? $article->seo_title : ($seo->title ?? $article->title);
        $metaDescription = filled($article->meta_description) ? $article->meta_description : ($seo->description ?? null);

        return [
            'seo_title' => (string) $seoTitle,
            'meta_description' => $metaDescription !== null ? (string) $metaDescription : null,
            'og_title' => filled($article->og_title) ? (string) $article->og_title : (string) $seoTitle,
            'og_description' => filled($article->og_description) ? (string) $article->og_description : ($metaDescription !== null ? (string) $metaDescription : null),
            'keyword' => $seo->keyword ?? null,
        ];
    }

    private function toJson(Article $article): string
    {
        $seo = $this->effectiveSeo($article);

        $payload = [
            'id' => $article->id,
            '_note' => 'این فایل برای ویرایشِ همین مقاله است. مقدارِ id را تغییر ندهید. slug قفل است (تغییرش نادیده گرفته می‌شود).',
            '_slug_locked' => $article->slug,
            '_content_hash' => self::contentHash($article),
            'lang' => $article->lang,
            'title' => $article->title,
            'excerpt' => $article->excerpt,
            'body' => $article->body,
            'seo_title' => $seo['seo_title'],
            'meta_description' => $seo['meta_description'],
            'meta_keywords' => $seo['keyword'],
            'canonical_url' => $article->canonical_url,
            'robots' => $article->robots,
            'og_title' => $seo['og_title'],
            'og_description' => $seo['og_description'],
            'image_alt' => $article->image_alt,
            'author_name' => $article->author_name,
            'reading_time' => $article->reading_time,
            'tags' => $this->tagNames($article),
            'faqs' => $this->effectiveFaqs($article),
        ];

        return json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    private function toMarkdown(Article $article): string
    {
        $seo = $this->effectiveSeo($article);

        $fm = [
            'id' => $article->id,
            'slug' => $article->slug.'   # قفل — تغییر ندهید',
            '_content_hash' => self::contentHash($article),
            'lang' => $article->lang,
            'title' => $article->title,
            'excerpt' => (string) $article->excerpt,
            'seo_title' => $seo['seo_title'],
            'meta_description' => (string) $seo['meta_description'],
            'meta_keywords' => (string) $seo['keyword'],
            'og_title' => $seo['og_title'],
            'og_description' => (string) $seo['og_description'],
            'image_alt' => (string) $article->image_alt,
            'author_name' => (string) $article->author_name,
            'reading_time' => (string) $article->reading_time,
            'tags' => implode(', ', $this->tagNames($article)),
        ];

        $lines = ['---'];
        foreach ($fm as $k => $v) {
            $v = str_replace(["\r", "\n"], ' ', (string) $v);
            $lines[] = "{$k}: {$v}";
        }
        $lines[] = '---';
        $lines[] = '';
        $lines[] = (string) $article->body;

        // FAQ به شکلی که parserِ Markdown می‌فهمد (## FAQ سپس ### پرسش / پاسخ). از FAQِ مؤثر (قدیمی یا جدید).
        $faqsForMd = $this->effectiveFaqs($article);
        if ($faqsForMd !== []) {
            $lines[] = '';
            $lines[] = '## FAQ';
            foreach ($faqsForMd as $faq) {
                $q = is_array($faq) ? ($faq['question'] ?? '') : '';
                $a = is_array($faq) ? ($faq['answer'] ?? '') : '';
                if ($q === '') {
                    continue;
                }
                $lines[] = '### '.$q;
                $lines[] = $a;
            }
        }

        return implode("\n", $lines);
    }
}
