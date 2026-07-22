<?php

namespace App\Cms\Contracts;

use Illuminate\Database\Eloquent\Model;

/**
 * قراردادِ موتورِ تولیدِ محتوا (docs/CMS-CORE-CONTRACT.md، لایه ۵). امضاها با پیاده‌سازیِ واقعی
 * (App\Services\AiAssistant\ContentAssistantService) هم‌راستا شده‌اند — منبعِ حقیقت خودِ موتور است.
 * موتور حافظه‌ی برند (BrandMemoryService) را در system prompt می‌گنجاند و هرگز خودش روی رکورد
 * نمی‌نویسد (نوشتن فقط با Apply دستیِ ادمین از طریقِ GenerationApplier).
 */
interface ContentAssistant
{
    /**
     * تولید/بهبودِ یک فیلد روی یک رکورد (Article/Page) در یک حالتِ مشخص.
     *
     * @param  array<string, mixed>  $options
     * @return array{result: mixed, warnings: array<int, string>}
     */
    public function generate(Model $record, string $field, string $mode, array $options = []): array;

    /** همان system promptی که برای یک فیلد/حالت/زبان فرستاده می‌شود (برای Preview Prompt). */
    public function previewSystemPrompt(string $field, string $mode, string $locale = 'en'): string;
}
