<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // سه ستون کوچکِ افزودنی برای فعال‌سازی RAG — بدون تغییر جدول‌های موجود دیگر:
    // (۱) کدام مدلِ این ارائه‌دهنده برای embedding استفاده شود (فقط OpenAI/Gemini واقعاً API
    //     embedding دارند — Anthropic/Grok/DeepSeek این ستون را خالی می‌گذارند)
    // (۲) کدام ارائه‌دهنده‌ی پیکربندی‌شده الان فعالانه برای embedding استفاده می‌شود
    // (۳) نتیجه‌ی بازیابی معنایی (قطعه‌ها/امتیاز/منبع) که برای این تولید استفاده شد — برای نمایش
    //     «Retrieved chunks / Confidence score / Sources used» در UI، بدون دست‌زدن به pivot
    //     موجود knowledge_entry_ids که هنوز دست‌نخورده کار می‌کند
    public function up(): void
    {
        Schema::table('ai_provider_configs', function (Blueprint $table) {
            $table->string('embedding_model')->nullable()->after('default_model');
        });

        Schema::table('ai_provider_settings', function (Blueprint $table) {
            $table->foreignId('embedding_provider_config_id')->nullable()->after('fallback_provider_config_id')
                ->constrained('ai_provider_configs')->nullOnDelete();
        });

        // نکته‌ی انتقال: ستون ai_generations.retrieved_chunks حذف شد — جدول ai_generations
        // در گروه RAG است و هنوز منتقل نشده. وقتی آن گروه بیاید، این ستون همان‌جا اضافه می‌شود.
    }

    public function down(): void
    {
        Schema::table('ai_provider_settings', function (Blueprint $table) {
            $table->dropConstrainedForeignId('embedding_provider_config_id');
        });

        Schema::table('ai_provider_configs', function (Blueprint $table) {
            $table->dropColumn('embedding_model');
        });
    }
};
