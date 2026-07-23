<?php

namespace App\Services\ArticleImport;

/**
 * تشخیص و تجزیه‌ی فرمت‌های ورودیِ ایمپورت — پورت از موتورِ کاملِ سایتِ انگلیسی (ArticleImportService)،
 * فقط بخشِ «پارس فرمت‌ها». پنج فرمت را می‌پذیرد و همه را به یک آرایه‌ی خامِ یکسان تبدیل می‌کند که
 * AiImport::normalizeImportPayload() سپس به payloadِ Contract نگاشت می‌کند:
 *   • JSON (پیش‌فرض)   • XML (‎<article>‎)   • HTML (سند یا قطعه، با کامنتِ متادیتای اختیاری)
 *   • Markdown (front matter و بخشِ ## FAQ)   • نشانه‌گذارِ سفارشی [[FIELD]]
 * خودکفاست — به هیچ مدل/سرویسِ غایبی وابسته نیست (فیلدهای keyword/internal_link که پارس می‌شوند اگر
 * در ادامه مصرف نشوند بی‌ضرر نادیده گرفته می‌شوند).
 */
class ImportFormatParser
{
    /**
     * @return array{data: array<string, mixed>, format: string, errors: array<int, string>}
     */
    public function parse(string $raw, ?string $forcedFormat = null): array
    {
        $format = $forcedFormat && $forcedFormat !== 'auto' ? $forcedFormat : $this->detectFormat($raw);

        [$data, $errors] = match ($format) {
            'json' => $this->parseJson($raw),
            'xml' => $this->parseXml($raw),
            'html' => $this->parseHtml($raw),
            'custom' => $this->parseCustomMarkers($raw),
            default => $this->parseMarkdown($raw),
        };

        return ['data' => $data, 'format' => $format, 'errors' => $errors];
    }

    public function detectFormat(string $raw): string
    {
        $trimmed = ltrim($raw);

        // باید پیش از JSON بیاید — [[FIELD]] هم با [ آغاز می‌شود.
        if (preg_match('/^\[\[[A-Z_]+\]\]/mu', $trimmed)) {
            return 'custom';
        }
        if (str_starts_with($trimmed, '{') || str_starts_with($trimmed, '[')) {
            return 'json';
        }
        if (str_starts_with($trimmed, '<?xml') || preg_match('/^<article[\s>]/i', $trimmed)) {
            return 'xml';
        }
        if (str_starts_with($trimmed, '<')) {
            return 'html';
        }

        return 'markdown';
    }

    /** @return array{0: array, 1: array<int,string>} */
    private function parseJson(string $raw): array
    {
        $decoded = json_decode($raw, true);

        if (! is_array($decoded)) {
            return [[], ['JSONِ نامعتبر: '.json_last_error_msg().'.']];
        }

        return [$decoded, []];
    }

    /** @return array{0: array, 1: array<int,string>} */
    private function parseMarkdown(string $raw): array
    {
        $meta = [];
        $body = $raw;

        if (preg_match('/\A---\s*\R(.*?)\R---\s*\R?(.*)\z/su', $raw, $m)) {
            [$meta, $errors] = $this->parseKeyValueBlock($m[1]);
            if ($errors !== []) {
                return [[], $errors];
            }
            $body = $m[2];
        }

        if (preg_match('/^##\s+FAQ\s*$/miu', $body, $mm, PREG_OFFSET_CAPTURE)) {
            $faqMd = substr($body, $mm[0][1] + strlen($mm[0][0]));
            $body = substr($body, 0, $mm[0][1]);

            $faqs = [];
            foreach (preg_split('/^###\s+/mu', trim($faqMd)) as $part) {
                $part = trim($part);
                if ($part === '') {
                    continue;
                }
                $pieces = preg_split('/\R/u',$part, 2);
                $faqs[] = ['question' => trim($pieces[0]), 'answer' => trim($pieces[1] ?? '')];
            }
            if ($faqs !== []) {
                $meta['faq'] = $faqs;
            }
        }

        $meta['content'] = trim($body);
        $meta['content_format'] = 'markdown';

        return [$meta, []];
    }

    /** @return array{0: array, 1: array<int,string>} */
    private function parseKeyValueBlock(string $block): array
    {
        $meta = [];
        $errors = [];

        foreach (preg_split('/\R/u',$block) as $line) {
            $line = trim($line);
            if ($line === '' || str_starts_with($line, '#')) {
                continue;
            }
            if (! str_contains($line, ':')) {
                $errors[] = 'خطِ متادیتا به شکلِ «field: value» نیست: "'.$line.'".';

                continue;
            }
            [$key, $value] = explode(':', $line, 2);
            data_set($meta, trim($key), trim(trim($value), '"\''));
        }

        return [$meta, $errors];
    }

    /** @return array{0: array, 1: array<int,string>} */
    private function parseHtml(string $raw): array
    {
        $meta = [];
        $body = $raw;

        if (preg_match('/\A\s*<!--\s*\R(.*?)\R\s*-->\s*\R?(.*)\z/su', $raw, $m)) {
            [$meta, $errors] = $this->parseKeyValueBlock($m[1]);
            if ($errors !== []) {
                return [[], $errors];
            }
            $body = $m[2];
        }

        if (! isset($meta['title']) && preg_match('/<title[^>]*>(.*?)<\/title>/isu', $raw, $m)) {
            $meta['title'] = trim(html_entity_decode(strip_tags($m[1])));
        }
        if (preg_match('/<meta\s+name=["\']description["\']\s+content=["\'](.*?)["\']/isu', $raw, $m)) {
            data_set($meta, 'seo.meta_description', trim(html_entity_decode($m[1])));
        }
        if (preg_match('/<meta\s+property=["\']og:title["\']\s+content=["\'](.*?)["\']/isu', $raw, $m)) {
            data_set($meta, 'og.title', trim(html_entity_decode($m[1])));
        }
        if (preg_match('/<meta\s+property=["\']og:description["\']\s+content=["\'](.*?)["\']/isu', $raw, $m)) {
            data_set($meta, 'og.description', trim(html_entity_decode($m[1])));
        }

        if (preg_match('/<body[^>]*>(.*)<\/body>/isu', $body, $m)) {
            $body = $m[1];
        }

        $meta['content'] = trim($body);
        $meta['content_format'] = 'html';

        return [$meta, []];
    }

    /** @return array{0: array, 1: array<int,string>} */
    private function parseXml(string $raw): array
    {
        libxml_use_internal_errors(true);
        $xml = simplexml_load_string($raw);

        if ($xml === false) {
            $messages = collect(libxml_get_errors())->map(fn ($e) => trim($e->message))->unique()->values()->all();
            libxml_clear_errors();

            return [[], $messages !== [] ? ['XMLِ نامعتبر: '.implode(' ', $messages)] : ['XMLِ نامعتبر.']];
        }

        $meta = [
            'language' => (string) ($xml->language ?? ''),
            'title' => (string) ($xml->title ?? ''),
            'slug' => (string) ($xml->slug ?? ''),
            'excerpt' => (string) ($xml->excerpt ?? ''),
            'content' => (string) ($xml->content ?? ''),
            'content_format' => (string) ($xml->content['format'] ?? 'html'),
            'category' => (string) ($xml->category ?? ''),
            'featured_image' => (string) ($xml->featured_image ?? ''),
            'image_alt' => (string) ($xml->image_alt ?? ''),
            'image_caption' => (string) ($xml->image_caption ?? ''),
            'publish_status' => (string) ($xml->status ?? ''),
            'publish_date' => (string) ($xml->publish_date ?? ''),
            'author' => (string) ($xml->author ?? ''),
            'reading_time' => (string) ($xml->reading_time ?? ''),
            'translation_of' => (string) ($xml->translation_of ?? ''),
            'provider' => (string) ($xml->provider ?? ''),
        ];

        if (isset($xml->tags)) {
            $meta['tags'] = collect($xml->xpath('tags/tag'))->map(fn ($t) => trim((string) $t))->filter()->values()->all();
        }

        if (isset($xml->faq)) {
            $meta['faq'] = collect($xml->xpath('faq/item'))->map(fn ($item) => [
                'question' => trim((string) ($item->question ?? '')),
                'answer' => trim((string) ($item->answer ?? '')),
            ])->all();
        }

        if (isset($xml->seo)) {
            $seo = [
                'title' => (string) ($xml->seo->title ?? ''),
                'meta_description' => (string) ($xml->seo->meta_description ?? ''),
            ];
            $meta['seo'] = $seo;
        }

        if (isset($xml->og)) {
            $meta['og'] = ['title' => (string) ($xml->og->title ?? ''), 'description' => (string) ($xml->og->description ?? '')];
        }

        // کلیدهای خالی حذف می‌شوند تا مثلِ «داده‌نشده» رفتار کنند.
        $meta = array_filter($meta, fn ($v) => $v !== '' && $v !== null && $v !== []);

        return [$meta, []];
    }

    /** @return array{0: array, 1: array<int,string>} */
    private function parseCustomMarkers(string $raw): array
    {
        preg_match_all('/^\[\[([A-Z_]+)\]\]\s*\R(.*?)(?=^\[\[[A-Z_]+\]\]|\z)/msu', $raw, $matches, PREG_SET_ORDER);

        if ($matches === []) {
            return [[], ['هیچ نشانه‌گذارِ [[FIELD]] معتبری پیدا نشد — هر فیلد را در دو براکت بگذارید، مثلاً [[TITLE]].']];
        }

        $meta = [];
        foreach ($matches as $match) {
            $key = strtolower($match[1]);
            $value = trim($match[2]);
            if ($value === '') {
                continue;
            }

            match ($key) {
                'faq' => $meta['faq'] = $this->parseQaBlock($value),
                'tags' => $meta['tags'] = array_values(array_filter(array_map('trim', explode(',', $value)))),
                'seo_title' => data_set($meta, 'seo.title', $value),
                'meta_description' => data_set($meta, 'seo.meta_description', $value),
                'og_title' => data_set($meta, 'og.title', $value),
                'og_description' => data_set($meta, 'og.description', $value),
                'body', 'content' => $meta['content'] = $value,
                default => $meta[$key] = $value,
            };
        }

        return [$meta, []];
    }

    /** @return array<int, array{question: string, answer: string}> */
    private function parseQaBlock(string $text): array
    {
        preg_match_all('/Q:\s*(.+?)\R+A:\s*(.+?)(?=\R+Q:|\z)/su', $text, $matches, PREG_SET_ORDER);

        return collect($matches)
            ->map(fn ($m) => ['question' => trim($m[1]), 'answer' => trim($m[2])])
            ->filter(fn (array $qa) => $qa['question'] !== '' && $qa['answer'] !== '')
            ->values()
            ->all();
    }
}
