<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // یک واحد دانش ساخت‌یافته درباره‌ی برند/کسب‌وکار — قبل از تولید محتوا بازیابی و به پرامپت
    // اضافه می‌شود (نگاه کنید به App\Services\KnowledgeBase\KnowledgeBaseService). توجه: مسیرِ
    // ایندکسِ برداری (RAG) فعلاً stub است و این جدول فقط برای مدیریتِ ادمین ساخته می‌شود.
    public function up(): void
    {
        Schema::create('knowledge_entries', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            // رشته‌ی آزاد با datalist از دسته‌های پیشنهادی در UI
            $table->string('category', 100);
            // fa/en — این محتوا مستقیماً وارد تولید محتوای واقعی سایت می‌شود
            $table->string('locale', 5)->default('fa');
            $table->longText('content');
            $table->string('source')->nullable();
            $table->string('status', 20)->default('active');
            $table->string('priority', 20)->default('medium');
            $table->boolean('is_pinned')->default(false);
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();

            $table->index(['locale', 'status']);
            $table->index('category');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('knowledge_entries');
    }
};
