<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // ثبت اینکه کدام ورودی‌های دانش برای یک اجرای مشخصِ تولید هوش مصنوعی استفاده شدند — یک pivot
    // ساده. جدولِ ai_generations در نسخه‌ی فارسی موجود است (نگاه کنید به
    // 2026_07_16_000014_create_ai_generations_table)، پس این pivot نگه داشته می‌شود.
    //
    // نامِ صریح و کوتاه برای unique index از همان ابتدا استفاده می‌شود (نامِ خودکارِ Laravel از
    // سقفِ ۶۴ کاراکتریِ MySQL عبور می‌کند) — پس نیازی به migrationِ fix-up جداگانه نیست.
    public function up(): void
    {
        Schema::create('ai_generation_knowledge_entry', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ai_generation_id')->constrained()->cascadeOnDelete();
            $table->foreignId('knowledge_entry_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['ai_generation_id', 'knowledge_entry_id'], 'ai_gen_knowledge_entry_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_generation_knowledge_entry');
    }
};
