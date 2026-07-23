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

    private function toJson(Article $article): string
    {
        $payload = [
            'id' => $article->id,
            '_note' => 'این فایل برای ویرایشِ همین مقاله است. مقدارِ id را تغییر ندهید. slug قفل است (تغییرش نادیده گرفته می‌شود).',
            '_slug_locked' => $article->slug,
            '_content_hash' => self::contentHash($article),
            'lang' => $article->lang,
            'title' => $article->title,
            'excerpt' => $article->excerpt,
            'body' => $article->body,
            'seo_title' => $article->seo_title,
            'meta_description' => $article->meta_description,
            'canonical_url' => $article->canonical_url,
            'robots' => $article->robots,
            'og_title' => $article->og_title,
            'og_description' => $article->og_description,
            'image_alt' => $article->image_alt,
            'author_name' => $article->author_name,
            'reading_time' => $article->reading_time,
            'faqs' => is_array($article->faqs) ? $article->faqs : [],
        ];

        return json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    private function toMarkdown(Article $article): string
    {
        $fm = [
            'id' => $article->id,
            'slug' => $article->slug.'   # قفل — تغییر ندهید',
            '_content_hash' => self::contentHash($article),
            'lang' => $article->lang,
            'title' => $article->title,
            'excerpt' => (string) $article->excerpt,
            'seo_title' => (string) $article->seo_title,
            'meta_description' => (string) $article->meta_description,
            'og_title' => (string) $article->og_title,
            'og_description' => (string) $article->og_description,
            'image_alt' => (string) $article->image_alt,
            'author_name' => (string) $article->author_name,
            'reading_time' => (string) $article->reading_time,
        ];

        $lines = ['---'];
        foreach ($fm as $k => $v) {
            $v = str_replace(["\r", "\n"], ' ', (string) $v);
            $lines[] = "{$k}: {$v}";
        }
        $lines[] = '---';
        $lines[] = '';
        $lines[] = (string) $article->body;

        // FAQ به شکلی که parserِ Markdown می‌فهمد (## FAQ سپس ### پرسش / پاسخ).
        if (is_array($article->faqs) && $article->faqs !== []) {
            $lines[] = '';
            $lines[] = '## FAQ';
            foreach ($article->faqs as $faq) {
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
