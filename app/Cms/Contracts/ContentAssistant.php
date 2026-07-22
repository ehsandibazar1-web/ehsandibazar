<?php

namespace App\Cms\Contracts;

/**
 * قراردادِ موتورِ تولیدِ محتوا (docs/CMS-CORE-CONTRACT.md، لایه ۵). پیاده‌سازی در موج ۴ می‌آید و
 * حافظه‌ی برند (App\Services\BrandMemory\BrandMemoryService) را در system prompt می‌گنجاند.
 */
interface ContentAssistant
{
    /**
     * تولیدِ یک فیلدِ محتوا در یک حالتِ مشخص، با زمینه‌ی دلخواه.
     *
     * @param  array<string, mixed>  $context
     */
    public function generate(string $field, string $mode, array $context): string;

    /** همان system promptی که واقعاً به ارائه‌دهنده فرستاده می‌شود (برای Preview Prompt). */
    public function previewSystemPrompt(string $field, string $mode, string $locale): string;
}
