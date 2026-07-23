<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // پیوست‌های PDF/سند/URL یک ورودی دانش — فقط ذخیره‌ی ساده‌ی فایل روی دیسک (بدون پردازش تصویر).
    // ستون‌های استخراج (source_type/extraction_status/...) از همان ابتدا اینجا هستند؛ در نسخه‌ی
    // انگلیسی این‌ها یک migrationِ جداگانه بودند ولی چون این یک create تازه است، تا شده‌اند.
    // فعلاً استخراج/embedding stub است، پس این ستون‌ها روی مقادیر پیش‌فرض می‌مانند.
    public function up(): void
    {
        Schema::create('knowledge_entry_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('knowledge_entry_id')->constrained()->cascadeOnDelete();
            $table->string('source_type', 10)->default('file'); // file | url
            $table->string('source_url')->nullable();
            $table->string('disk_path');
            $table->string('original_filename');
            $table->string('mime_type')->nullable();
            $table->unsignedInteger('size')->nullable();
            $table->string('extraction_status', 20)->default('pending'); // pending | extracted | failed
            $table->longText('extracted_text')->nullable();
            $table->timestamp('extracted_at')->nullable();
            $table->text('extraction_error')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('knowledge_entry_attachments');
    }
};
