<?php

namespace App\Services;

use App\Model\AiSetting;
use Illuminate\Support\Facades\Http;
use RuntimeException;

/**
 * تولید مقاله‌ی فارسیِ سئوشده با استفاده از Claude (Anthropic Messages API).
 *
 * از اتصال مستقیم HTTP (کلاینت Http لاراول) استفاده می‌کنیم تا وابستگی اضافه‌ای
 * به vendor اضافه نشود — این سایت روی هاست بدون‌ترمینال دیپلوی می‌شود و هر
 * پکیج جدید یعنی حجم بیشترِ vendor برای commit.
 */
class AiArticleGenerator
{
    private const ENDPOINT = 'https://api.anthropic.com/v1/messages';

    private const API_VERSION = '2023-06-01';

    /**
     * @return array{title:string, slug:string, meta_description:string, body:string, faq:array}
     */
    public function generate(string $topic): array
    {
        $topic = trim($topic);
        if ($topic === '') {
            throw new RuntimeException('موضوع مقاله را وارد کنید.');
        }

        $setting = AiSetting::current();
        $apiKey = (string) $setting->api_key;
        if ($apiKey === '') {
            throw new RuntimeException('کلید API هوش مصنوعی تنظیم نشده است. ابتدا آن را در همین صفحه ذخیره کنید.');
        }

        $model = $setting->model ?: 'claude-opus-4-8';

        $response = Http::withHeaders([
            'x-api-key' => $apiKey,
            'anthropic-version' => self::API_VERSION,
            'content-type' => 'application/json',
        ])->timeout(120)->post(self::ENDPOINT, [
            'model' => $model,
            'max_tokens' => 8000,
            'system' => $this->systemPrompt(),
            'messages' => [
                ['role' => 'user', 'content' => "موضوع مقاله: {$topic}"],
            ],
        ]);

        if ($response->failed()) {
            $msg = $response->json('error.message') ?? $response->body();
            throw new RuntimeException('خطا از سرویس هوش مصنوعی: ' . $msg);
        }

        $text = $response->json('content.0.text');
        if (! is_string($text) || $text === '') {
            throw new RuntimeException('پاسخ خالی از سرویس هوش مصنوعی دریافت شد.');
        }

        return $this->parse($text);
    }

    private function systemPrompt(): string
    {
        return <<<'PROMPT'
شما یک نویسنده‌ی حرفه‌ای محتوای فارسی و متخصص سئو هستید. بر اساس موضوعی که کاربر می‌دهد،
یک مقاله‌ی کامل، اصیل، روان و بهینه‌شده برای موتورهای جستجو به زبان فارسی بنویسید.

خروجی را **فقط و فقط** به‌صورت یک شیء JSON معتبر با کلیدهای زیر برگردانید. هیچ متنی خارج از
JSON ننویسید و از بلوک ```json استفاده نکنید:

{
  "title": "عنوان جذاب و سئوشده‌ی مقاله (فارسی)",
  "slug": "نامک-فارسی-با-خط-تیره-بدون-فاصله",
  "meta_description": "توضیحات متای حدوداً ۱۵۰ کاراکتری برای گوگل (فارسی)",
  "body": "متن کامل مقاله به‌صورت HTML: از تگ‌های <h2>، <h3>، <p>، <ul>، <li>، <strong> استفاده کنید. عنوان اصلی (h1) را داخل body نگذارید. حدود ۷۰۰ تا ۱۲۰۰ کلمه.",
  "faq": [
    {"question": "سوال متداول ۱", "answer": "پاسخ کوتاه"},
    {"question": "سوال متداول ۲", "answer": "پاسخ کوتاه"}
  ]
}

نکات مهم:
- محتوا باید کاملاً فارسی، طبیعی و بدون لحن رباتیک باشد.
- «slug» باید فارسی، با کلمات جداشده با خط تیره (-) و بدون فاصله یا کاراکتر خاص باشد.
- «body» باید HTML معتبر باشد، با تیتربندی مناسب برای سئو.
- بخش «faq» بین ۳ تا ۵ سوال متداول مرتبط داشته باشد.
PROMPT;
    }

    /**
     * استخراج و decode کردن JSON از پاسخ مدل (با تحملِ بلوک markdown یا متن اضافه).
     *
     * @return array{title:string, slug:string, meta_description:string, body:string, faq:array}
     */
    private function parse(string $text): array
    {
        $clean = trim($text);

        // حذف بلوک ```json ... ``` در صورت وجود.
        $clean = preg_replace('/^```(?:json)?/i', '', $clean);
        $clean = preg_replace('/```$/', '', trim($clean));

        // فقط از اولین { تا آخرین } را نگه می‌داریم.
        $start = strpos($clean, '{');
        $end = strrpos($clean, '}');
        if ($start !== false && $end !== false && $end > $start) {
            $clean = substr($clean, $start, $end - $start + 1);
        }

        $data = json_decode(trim($clean), true);
        if (! is_array($data)) {
            throw new RuntimeException('پاسخ هوش مصنوعی قابل پردازش نبود. لطفاً دوباره تلاش کنید.');
        }

        $faq = [];
        if (! empty($data['faq']) && is_array($data['faq'])) {
            foreach ($data['faq'] as $item) {
                if (! empty($item['question'])) {
                    $faq[] = [
                        'question' => (string) $item['question'],
                        'answer' => (string) ($item['answer'] ?? ''),
                    ];
                }
            }
        }

        return [
            'title' => (string) ($data['title'] ?? ''),
            'slug' => (string) ($data['slug'] ?? ''),
            'meta_description' => (string) ($data['meta_description'] ?? ''),
            'body' => (string) ($data['body'] ?? ''),
            'faq' => $faq,
        ];
    }
}
