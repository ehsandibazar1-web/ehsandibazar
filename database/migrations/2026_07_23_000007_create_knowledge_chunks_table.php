<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // یک قطعه‌ی قابل‌بازیابیِ متن + بردار embedding آن. توجه (نسخه‌ی فارسی): چون مسیرِ embedding
    // فعلاً stub است این جدول در عمل خالی می‌ماند — ساختارش اکنون ساخته می‌شود تا وقتی embedding
    // واقعی آمد آماده باشد (forward-compatible).
    public function up(): void
    {
        Schema::create('knowledge_chunks', function (Blueprint $table) {
            $table->id();
            // منبع این قطعه — یک KnowledgeEntry یا یک KnowledgeEntryAttachment
            $table->string('chunkable_type', 50);
            $table->unsignedBigInteger('chunkable_id');
            // KnowledgeEntry مالکِ نهایی — برای فیلتر/join سریع؛ denormalized عمدی
            $table->foreignId('knowledge_entry_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('chunk_index');
            $table->longText('text');
            $table->unsignedInteger('char_count');
            $table->json('embedding');
            $table->string('embedding_model');
            $table->unsignedInteger('embedding_dims');
            $table->string('locale', 5)->nullable();
            $table->timestamps();

            $table->unique(['chunkable_type', 'chunkable_id', 'chunk_index'], 'knowledge_chunk_unique');
            $table->index(['knowledge_entry_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('knowledge_chunks');
    }
};
