<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * جدولِ رسانه (DAM) — نسخه‌ی یکجا و کاملِ همان اسکیمایی که سایت انگلیسی در چند مهاجرتِ افزایشی
 * ساخته بود (create + add_dam_fields + caption/description + duration). چون سایت فارسی این تاریخچه
 * را ندارد، همه در یک create می‌آید. مطابق «Schema هدف» در docs/CMS-CORE-CONTRACT.md.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('media')) {
            return;
        }

        Schema::create('media', function (Blueprint $table) {
            $table->id();
            $table->string('original_name');
            $table->string('alt_text')->nullable();
            $table->text('caption')->nullable();
            $table->text('description')->nullable();
            $table->string('disk')->default('public');
            // حذفِ پوشه، رسانه را حذف نمی‌کند — فقط به ریشه برمی‌گرداند (nullOnDelete)
            $table->foreignId('folder_id')->nullable()->constrained('media_folders')->nullOnDelete();
            $table->string('disk_path');       // مسیر ذخیره‌شده روی سرور
            $table->string('url');             // آدرس عمومی قابل‌استفاده
            $table->string('type');            // image | video | audio | document | other
            $table->string('mime_type')->nullable();
            $table->unsignedBigInteger('size')->nullable(); // بایت
            $table->unsignedInteger('width')->nullable();
            $table->unsignedInteger('height')->nullable();
            $table->unsignedInteger('duration_seconds')->nullable(); // ویدئو
            $table->string('webp_path')->nullable();
            $table->string('thumbnail_path')->nullable();
            $table->json('responsive_paths')->nullable(); // نگاشتِ عرض→مسیر برای srcset
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('media');
    }
};
